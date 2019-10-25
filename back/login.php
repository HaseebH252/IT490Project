
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
	$userinfo = json_decode($response, true);
	$_SESSION['logged_in'] = true;
	$_SESSION['email'] = $userinfo['email'];
	$_SESSION['first_name'] = $userinfo['first_name'];
	$_SESSION['last_name'] = $userinfo['last_name'];
	$_SESSION['active'] = $userinfo['active'];


	$type ='authenticate';
	$message ="Account with email: ".$email." logged in.".PHP_EOL;
	$logging = $authenticate->log($type,$message);

	//file_put_contents('logs/user.txt', "[".date_format($date, 'm-d-Y H:i:s')."] "."Account with email: ".$email." logged in.".PHP_EOL, FILE_APPEND);
	header("location: main.php");
}
 




?>
