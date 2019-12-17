<?php
require_once __DIR__ . '/vendor/autoload.php';
include ('rmq.php');
//require('../back/db.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$queueName = 'authentication';

//CONNECT TO RMQ
$connection = new AMQPStreamConnection($rmq_host, $rmq_port, $rmq_username, $rmq_password);
$channel = $connection->channel();
$channel->queue_declare($queueName, false, false, false, false);


//FUNCTION TO CHECK WHETHER USER EXISTS IN DB
function auth($receivedEmail,$receivedPass){
    echo "auth() engaged";
    //DB INFO
    $sql_host = "localhost";
    $sql_user = "test";
    $sql_pass = "Today123$";
    $sql_db = "Users";
    $conn = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db);
    if ($conn->connect_error) {
        $backup_host = "192.168.1.110";
        $sql_user = "test";
        $sql_pass = "Today123$";
        $sql_db = "Users";
        echo "\n Master DB is down, connecting to backup....\n";
        $conn = new mysqli($backup_host,$sql_user,$sql_pass,$sql_db);

    }
    // Check connection
    echo "SQL Connected";


    //DECLARE VARIABLES FOR EMAIL AND PASSWORD
    $email=$receivedEmail;
    $pass=$receivedPass;

    //PERFORM QUERY ON DB
    $sql = "SELECT * FROM accounts WHERE Email='$email'";

    ($t = mysqli_query ( $conn,  $sql   ) )  or die ( mysqli_error ($conn) );
    $num = mysqli_num_rows($t);

    echo "QUERY PERFORMED \n Found $num of rows \n\n";

    // Check hash


    //DECIDE WHAT TO RETURN FROM THIS FUNCTION
    if ($num == 0){
        echo "not found";
        return false;
    }else {
        //Check Hash
        while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) )
        {
            $hash = $r['Pass'];
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


    $sql_host = "localhost";
    $sql_user = "test";
    $sql_pass = "Today123$";
    $sql_db = "Users";
    $conn = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db);
    if ($conn->connect_error) {
        $backup_host = "192.168.1.110";
        $sql_user = "test";
        $sql_pass = "Today123$";
        $sql_db = "Users";
        echo "\n Master DB is down, connecting to backup....\n";
        $conn = new mysqli($backup_host,$sql_user,$sql_pass,$sql_db);

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
        $conn->query($sql);
        //USER CREATED
        echo "TRUE RETURNED\n";
        return true;
    }
}
//========================================================================================
//========================================================================================

//Add Log to DB
function logging($receivedType,$receivedMessage){
    echo "log() engaged";
    //DB INFO
    $sql_host = "localhost";
    $sql_user = "test";
    $sql_pass = "Today123$";
    $sql_db = "Users";
    $conn = new mysqli($sql_host,$sql_user,$sql_pass,$sql_db);
    if ($conn->connect_error) {
        $backup_host = "192.168.1.110";
        $sql_user = "test";
        $sql_pass = "Today123$";
        $sql_db = "Users";
        echo "\n Master DB is down, connecting to backup....\n";
        $conn = new mysqli($backup_host,$sql_user,$sql_pass,$sql_db);

    }

    //DECLARE VARIABLES FOR Log type AND log message
    $type=$receivedType;
    $message=$receivedMessage;

    //PERFORM QUERY ON DB
    $sql = "INSERT INTO logs (date,type,message) VALUES (NOW(), '$type','$message')";

    $conn->query($sql);
    //Log CREATED
    echo "TRUE RETURNED\n";
    return true;

}


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
        case "Log":
            return logging($message['type'],$message['message']);
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
