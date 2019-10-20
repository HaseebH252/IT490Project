<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
include __DIR__.'/attom.php';

//DECLARE RMQ CONNECT VARIABLES
$hostip = 'localhost';
$port = '5672';
$rmqLogin = 'guest1';
$rmqPass = 'guest1';

//DMZ QUEUE
$queueName = 'dmz_queue';

//CONNECT TO RMQ
$connection = new AMQPStreamConnection($hostip, $port, $rmqLogin, $rmqPass);
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);


//function to process API requests
function processMessage($message){
    echo "Processing message...\n";
    //$zip= $message['zip'];
    $zip = $message;

    //array,returning all api data back in array. each array item is all info from each api
    $returnApiData= array();

    //attom api data
    //extract data from api, and add it to array
    $returnApiData['attom'] = receiveCurl($zip);

    //here will be calls for other API functions that will be added to $returnApiData array

    //returns message to callback function to send back to rmq
    return $returnApiData;
}

//TESTING
//call function to get attom api info, and example of how to access data inside this nested array.
$returnedDataForTest = processMessage('07108');

//tests by zipcode and echoes entire array with nested arrays inside. Demonstrates structure of array
echo "Prints array. Take a look to understand the array structure\n";
echo print_r($returnedDataForTest);
echo "\n";

echo "Prints street as one line. Demonstrates how to access individual elements inside nested array\n";
echo $returnedDataForTest['attom'][0]['street']."\n";
echo $returnedDataForTest['attom'][1]['street']."\n";

//END OF TESTING CODE

//first process of received RMQ message
//Not invoked unless there is RMQ message
$callback = function ($receivedArray){

    //decodes received json array
    $array = json_decode($receivedArray->body, true);
    echo "Frond-end request received\n";

    //debug
    //echo print_r($array)."\n";
    echo $receivedArray->get('correlation_id')."\n";
    echo $receivedArray->get('reply_to')."\n";

    //required function is called and decoded array is passed to it
    //function result is encoded json and sent back via unique queue with unique correlation_id
    //function call is inside json_encode()
    //This callback function won't work until we decide what exactly we sending from front end
    //must be changed based on POST from front end
    $msg = new AMQPMessage((json_encode(processMessage($array))), array('correlation_id' =>$receivedArray->get('correlation_id')));
    $receivedArray->delivery_info['channel']->basic_publish($msg,'',$receivedArray->get('reply_to'));
};

//consumes messages from declared above RMQ queue to receive a request
$channel->basic_consume($queueName, '', false, true, false, false, $callback);
while ($channel->is_consuming()) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>