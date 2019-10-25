
<?php


require('rmq/apiClient.php');

$zip = $_POST['zip'];


$api_call = new RabbitMQAPIClient();

$response = $api_call->search($zip);



if($response == "false"){
    echo "Login Failed";
    header("location: result.php");
}




?>














