<?php

//PATH SHOULD BE CHANGE BASED ON MACHINE THAT RUNS THE CODE
require_once  '/home/po42/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//DECLARE
$hostip = 'localhost';
$port = '5672';
$rmqLogin = 'guest1';
$rmqPass = 'guest1';
$queueName = 'hello';

//CONNECTING TO RMQ AND QUEUE
$connection = new AMQPStreamConnection($hostip, $port, $rmqLogin, $rmqPass);
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);
echo " [*] Waiting for messages. To exit press CTRL+C\n";



$callback = function ($msg) {

	//DB INFO
	$servername = "localhost";
	$username = "root";
	$password = "password";
	$db = "testing";

	// Create connection
	$conn = new mysqli($servername, $username, $password,$db);
	// Check connection
	if ($conn->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
}

	//DECLARE VARIABLES FOR EMAIL AND PASSWORD
	$email='';
	$pass='';

	//DECODE RECEIVED MESSAGE FROM JSON INTO PHP ARRAY
	$request = json_decode($msg->body,true);

	//READ CONTENTS OF RECEIVED ARRAY
	if (is_array($request)){
		$email = $request['email'];
		$pass =  $request['pass'];
		//echo $email." message\n";
		//echo $pass." message\n";
	}

	//PERFORM QUERY ON DB
	$sql = "SELECT * FROM accdb WHERE accEmail='$email' AND accPass = '$pass'";
	$result = $conn->query($sql);


	//RETURN MESSAGE TO THE CLIENT
	//$msgBack = new AMQPMessage ("messageBack", array('correlations_id' =>$msg->get("correlation_id")));
	//$msg->delivery_info['channel']->basic_publish($msgBack,'',$msg->get('reply_to'));
	//$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);

	//TEST QUERY RESULTS
	foreach ($result as $row){
		if ($email == $row["accEmail"] && $pass == $row['accPass']){
			echo "User exists\n";
		}
		if ($row['accEmail'] == '' && $row['accPass']==''){
			echo "Wrong info\n";
		}
		
		//echo $row["accEmail\n"];
		//echo $row["accPass\n"];

	}

};

//CONSUMING MESSAGES
$channel->basic_consume($queueName, '', false, true, false, false, $callback);
while ($channel->is_consuming()) {
    $channel->wait();
}
$channel->close();
$connection->close();
?>
