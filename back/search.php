
<?php

session_start();
require('../rmq/apiClient.php');

//$zip = $_POST['zip'];
$zip = '07601';

$api_call = new RabbitMQAPIClient();

$response = array();


$response = $api_call->search($zip);

//echo gettype($response) . "\n \n";

$response = json_decode($response, true);


//print_r($response);


$_SESSION["attom-api"]= $response["attom"];
$_SESSION["crime-api"]= $response["crime"];
$_SESSION["flood-api"]= $response["flood"];
$_SESSION["yelp-api"]= $response["yelp"];
$_SESSION["map-api"]= $response["map"];


if( isset($response) AND !empty($_SESSION['attom-api']) ):
header( "location: ../result.php" );
endif;


/*print_r($_SESSION["attom-api"]);
print_r($_SESSION["crime-api"]);
print_r($_SESSION["flood-api"]);
print_r($_SESSION["yelp-api"]);*/




?>
