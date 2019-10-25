<?php
require_once  '/home/po42/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//DECLARE
$hostip = 'localhost';
$port = '5672';
$rmqLogin = 'guest1';
$rmqPass = 'guest1';
$queueName = 'hello';

//CONNECTION TO RMQ AND QUEUE
$connection = new AMQPStreamConnection($hostip,$port, $rmqLogin, $rmqPass);
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);

//GET EMAIL AND PASS FROM FORM
$email = $_POST['email'];
$userpass = $_POST['pass'];

//TEST
echo $email."\n";
echo $userpass."\n";

//DECLARING ARRAY TO SEND FOR AUTH
$request = array();
$request['email'] = $email;
$request['pass'] = $userpass;


//ENCODING ARRAY TO JSON AND PUTTING INTO A RMQ MESSAGE
$msg = new AMQPMessage(json_encode($request));

//$msg = new AMQPMessage(json_encode($request, array('reply_to' => $queueName)));

//PUBLISHING MESSAGE TO RMQ QUEUE
$channel->basic_publish($msg, '', $queueName);

//TEST
echo "$email";
echo "$userpass";

//CHOSE CHANNEL
$channel->close();
$connection->close();
?>

