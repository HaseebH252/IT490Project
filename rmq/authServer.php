<?php

//PATH SHOULD BE CHANGE BASED ON MACHINE THAT RUNS THE CODE
require_once __DIR__ . '/vendor/autoload.php';
include ('rmq.php');
include ('../back/db.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


//CONNECTING TO RMQ AND QUEUE
$connection = new AMQPStreamConnection($host, $port, $username, $password);
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);
echo " [*] RMQ Authentication Listener Started:\n";



$callback = function ($msg) use ($sql_pass, $sql_db, $sql_user, $sql_host) {

	// Create connection
	$conn = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db);
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
	$s= "select * from accounts where email='$email' AND password = '$pass'";
	echo "<br> SQL is $s<br><br>";
	($t = mysqli_query ( $sql_db,  $s   ) )  or die ( mysqli_error ($sql_db) );
	$num = mysqli_num_rows($t);
	echo "<br>There are $num rows<br>";

	//TEST QUERY RESULTS

	while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) )
	{
		$hash = $r['pass'];
		echo "<br>Hashed Pass is $hash<br>";
		if (password_verify($pass, $hash))
		{
			echo "<br>Password Valid!<br>";
			return true;
		}
		else
		{
			echo "<br>Password Not Valid<br>";
			return false;
		}
	}


	//RETURN MESSAGE TO THE CLIENT
	//$msgBack = new AMQPMessage ("messageBack", array('correlations_id' =>$msg->get("correlation_id")));
	//$msg->delivery_info['channel']->basic_publish($msgBack,'',$msg->get('reply_to'));
	//$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);


		
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
