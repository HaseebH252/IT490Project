<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//========================================================================================
//DECLARE
$hostip = 'localhost';
$port = '5672';
$rmqLogin = 'guest1';
$rmqPass = 'guest1';
$queueName = 'rpc_queue';

//CONNECT TO RMQ
$connection = new AMQPStreamConnection($hostip, $port, $rmqLogin, $rmqPass);
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);

//========================================================================================

//========================================================================================
//FUNCTION TO CHECK WHETHER USER EXISTS IN DB
function auth($receivedEmail,$receivedPass){
    echo "auth() engaged";
    //DB INFO
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $db = "it490";

    // Create connection
    $conn = new mysqli($servername, $username, $password,$db);
    // Check connection
    echo "SQL Connected";
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //DECLARE VARIABLES FOR EMAIL AND PASSWORD
    $email=$receivedEmail;
    $pass=$receivedPass;

    //PERFORM QUERY ON DB
    $sql = "SELECT * FROM accounts WHERE Email='$email' AND Pass = '$pass'";
    $result = $conn->query($sql);
    $num = mysqli_num_rows($result);
    echo "QUERY PERFORMED";

    //DECIDE WHAT TO RETURN FROM THIS FUNCTION
    if ($num == 0){
        return false;
    }else {
        echo "TRUE RETURNED";
        return true;
    }

/*
        //TEST QUERY RESULTS
        foreach ($result as $row){
            if ($email == $row["accEmail"] && $pass == $row['accPass']){
                echo "User exists\n";
                break;
            }else{
                continue;
            }
        }
*/

}
//========================================================================================

//========================================================================================
//FUNCTION THAT REGISTERS THE USER
function reg($receivedFname,$receivedLname,$receivedEmail,$receivedPass){
    echo "reg() engaged";
    echo $receivedFname;
    echo $receivedLname;
    echo $receivedEmail;
    echo $receivedPass;
    //DB INFO
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $db = "it490";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $db);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //PERFORM QUERY ON DB TO CHECK FOR ACCOUNT
    $sql = "SELECT * FROM accounts WHERE Email='$receivedEmail' ";
    //$sql = "SELECT * FROM accounts WHERE Email='1@gmail.com' ";
    //$result = $conn->query($sql);
    $result = mysqli_query($conn,$sql);

    echo "query performed\n";
    $num = mysqli_num_rows($result);
    echo "\n $num \n";
    if ( $num <> 0 ) {
        //USER EXISTS
        echo "account exists";
        return false;
    } else {
        //USER DOESN'T EXIST. PROCEED
        $sql = "INSERT INTO accounts (Fname, LName, Email, Pass) VALUES ('$receivedFname', '$receivedLname', '$receivedEmail', '$receivedPass')";
        //$sql = "INSERT INTO accounts (Fname, LName, Email, Pass) VALUES ('jane', 'dope', 'asdasd@yahoo.com', '1234568')";
        $ror =$conn->query($sql);
        //USER CREATED
        echo "TRUE RETURNED\n";
        return true;
    }
}
//========================================================================================


// CALL IT FROM INSIDE OF CALLBACK TO PROCEED WITH AUTH/REG
function processMessage($message){
    echo "processMessage() engaged";
    //change based on received array and type of auth/reg flag
    switch ($message['Type']) {
        //change
        case "Login":
            return auth($message['email'],$message['pass']);
            break;
        //change
        case "Register":
            return reg($message['firstname'],$message['lastname'],$message['email'],$message['pass']);
            break;
    }
}

//REACTS TO INITIAL MESSAGE FROM RMQ
//========================================================================================
$callback = function ($receivedArray){
    $array = json_decode($receivedArray->body, true);
    echo "Message Received, Processing";
    echo print_r($array);
    echo $receivedArray->get('correlation_id');
    echo $receivedArray->get('reply_to');
    //DECIDE WHETHER TO ENCODE OR NOT BASED ON WHAT IS RETURNED FROM FUNCTIONS
    $msg = new AMQPMessage((json_encode(processMessage($array))), array('correlation_id' =>$receivedArray->get('correlation_id')));
    $receivedArray->delivery_info['channel']->basic_publish($msg,'',$receivedArray->get('reply_to'));
    //$receivedArray->delivery_info['channel']->basic_ack($receivedArray->delivery_info['delivery_tag']);
};
//========================================================================================


//========================================================================================
$channel->basic_consume($queueName, '', false, true, false, false, $callback);
while ($channel->is_consuming()) {
    $channel->wait();
}
$channel->close();
$connection->close();
//========================================================================================
?>