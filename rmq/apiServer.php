<?php

//error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

include '../api_calls/attom.php';
include '../api_calls/crime.php';
include '../api_calls/yelp.php';
include '../api_calls/flood.php';
include '../api_calls/google-county.php';
include '../api_calls/gmap.php';
include '../api_calls/school.php';

/*include 'attom.php';
include 'crime.php';
include 'yelp.php';
include 'flood.php';
include 'google-county.php';
include 'gmap.php';*/
include 'rmq.php';

//DMZ QUEUE
$queueName = 'dmz_queue';

//CONNECT TO RMQ
$connection = new AMQPStreamConnection($rmq_host, $rmq_port, $rmq_username, $rmq_password);
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);


//function to process API requests
function processMessage($message){

    //receive zipcode from rmq message
    $message = $message['zipcode'];
    echo "Processing message...\n";
    $zip = $message;

    //array,returning all api data back in array. each array item is all info from each api
    $returnApiData= array();

    echo "Calling attom API...\n";
    $returnApiData['attom'] = receiveCurl($zip);

    echo "Calling Yelp API...\n";
    $returnApiData['yelp'] = getYelp($returnApiData['attom']);

    echo "Calling Flood API...\n";
    $returnApiData['flood'] = getFlood($returnApiData['attom']);

    echo "Calling Google County...\n";
    $state_county = receiveCurlGoogleCounty($zip);

    echo "Calling Crime API...\n";
    $returnApiData['crime'] = getCrime($state_county['state'],$state_county['county']);

    echo "Calling Google Api and Creating map...\n";
    $returnApiData['map'] = receiveMap($zip);

    //returns message to callback function to send back to rmq
    return $returnApiData;
}

//TESTING
//$test['zipcode'] = '07601';
//$returnedDataForTest = processMessage($test);
//echo print_r($returnedDataForTest);
//echo "\n";


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
