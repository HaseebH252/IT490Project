
<?php


require ('RMQClient.php');
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$userpass = $_POST['pass'];


echo $email;
echo $userpass;

$authenticate = new RabbitMQRpcClient();

$response = $authenticate->reg($email,$userpass,$fname,$lname);

//$response = json_decode($test);
//

echo "<br><br><br>";
echo $response;
echo gettype($response);




if ($response == 'false') {
	echo "<br> You already have an account!!!!!!!!! <br>";
}
else{
	echo "User Successfully Created!!!!";
}

 
 




?>
