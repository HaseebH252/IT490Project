
<?php


//require('rmq/apiClient.php');

$zip = $_POST['zip'];


/*$api_call = new RabbitMQAPIClient();

$response = $api_call->search($zip);

print_r($response);*/



//image from dmz
$image = file_get_contents("../images/map.jpg");
//convert image to base64 then to json file
$data = base64_encode($image);
$json = json_encode($data);

//SEND TO RMQ
/////////////////////

//then decode

$return_json = json_decode($json);
$return_data = base64_decode($return_json);
//recreate image on front end
file_put_contents("../images/decode.jpg",$return_data);




/*
$_SESSION["attom-api"]= $response["attom-api"];
$_SESSION["crime-api"]= $response["crime-api"];
$_SESSION["flood-api"]= $response["flood-api"];
$_SESSION["yelp-api"]= $response["yelp-api"];

*/




?>














