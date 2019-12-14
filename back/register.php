
<?php


require ('rmq/authClient.php');
$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$email = $_POST['email'];
$password = $_POST['password'];

$hash=password_hash($password, PASSWORD_DEFAULT);



$authenticate = new RabbitMQAuthClient();

$response = $authenticate->reg($first_name,$last_name,$email,$hash);

//$response = json_decode($test);
//

if ($response == "false"){
	echo "Account with email already exists";
	$_SESSION['message'] = 'User with this email already exists!';
	header("location: error.php");
}
else{
	echo "Account Created";
	$userinfo = json_decode($response, true);
	$_SESSION['email'] = $userinfo['email'];
	$_SESSION['first_name'] = $userinfo['first_name'];
	$_SESSION['last_name'] = $userinfo['last_name'];
	$_SESSION['active'] = 0;
	$_SESSION['logged_in'] = true;
	$date = date_create();
	file_put_contents('logs/reg.log', "[".date_format($date, 'm-d-Y H:i:s')."] "."Account with email: ".$email." successfully registered.".PHP_EOL, FILE_APPEND);
	header("location: main.php");
}




?>
