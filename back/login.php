
<?php


require('rmq/authClient.php');

$email = $_POST['email'];
$userpass = $_POST['pass'];

$hash=password_hash($userpass, PASSWORD_DEFAULT);

$authenticate = new RabbitMQAuthClient();

$response = $authenticate->auth($email,$hash);



if($response == false){
	echo "Login Failed";
	$_SESSION['message'] = "Invalid email or password, try again!";
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
	$date = date_create();
	file_put_contents('logs/user.log', "[".date_format($date, 'm-d-Y H:i:s')."] "."Account with email: ".$email." logged in.".PHP_EOL, FILE_APPEND);
	header("location: main.php");
}
 




?>
