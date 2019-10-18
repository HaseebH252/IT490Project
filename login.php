
<?php


require ('RMQClient.php');

$email = $_POST['email'];
$userpass = $_POST['pass'];


echo $email;
echo $userpass;

$authenticate = new RabbitMQRpcClient();

$response = $authenticate->auth($email,$userpass);

//$response = json_decode($test);
//

echo "<br><br><br>";
echo $response;
echo gettype($response);




if ($response == 'false') {
	echo "Login Failed";
}
else{
	echo "Login Sucessful";
}

 
 




?>
