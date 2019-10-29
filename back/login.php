
<?php


require('rmq/authClient.php');

$email = $_POST['email'];
$password = $_POST['password'];


$authenticate = new RabbitMQAuthClient();

$response = $authenticate->auth($email,$password);



if($response == "false"){
	echo "Login Failed";
	$_SESSION['message'] = "Invalid email or password; Tried logging in with email: ".$email;
	header("location: error.php");
}
else{

	echo "Login Successful";

	$_SESSION['active'] = $response;


	$type ='authenticate';
	$message ="Account with email: ".$email." logged in.".PHP_EOL;
	$logging = $authenticate->log($type,$message);

	file_put_contents('logs/user.txt', "[".date_format($date, 'm-d-Y H:i:s')."] "."Account with email: ".$email." logged in.".PHP_EOL, FILE_APPEND);
	header("location: main.php");
}
 




?>
