<?php

//receives each found property and retrieves only required fields

function splitObject($property){
    //$street = $response->property[0]->address->oneLine."\n";
    $street = $property->address->oneLine;
    $latitude = $property->location->latitude;
    $longitude = $property->location->longitude;

    //adds all required variables into associative array
    $info = array('street' => $street,'latitude' => $latitude,'longitude' => $longitude);
    return $info;
}

//First function, Currently receives only zipcode for testing purposes
//Makes call to api, receives a json string
//string then decoded into array
function receiveCurl($zip){
    $curl = curl_init();
    //$postalCode = $message;
    $postalCode = $zip;

    //pagesize regulates how many search results we get back from api
    $pagesize= "4";

    //curl code from tutorial
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.gateway.attomdata.com/propertyapi/v1.0.0/property/address?postalcode=".$postalCode."&page=1&pagesize=".$pagesize."",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "apikey: d85d17849905c3125d52318e0898e80b",
        ),
    ));
    //this variable receives data returned from api
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    //json string is decoded
    $response = json_decode($response);

    //declaring array that will be returned from this function
    //this array will be nested in the array that will be returned to front end
    $valuesArray = array();

    //loops through each property from api call
    //this array will be nested
    //it will contain arrays for each found property, but only with data that we actually require
    //calls function splitObject()
    foreach ($response->property as $row){
        $valuesArray[] = splitObject($row);
    }

    //debug
    //echo print_r($valuesArray);
    //get address as a line
    //$street = $response->property[0]->address->oneLine."\n";
    //echo print_r($street);
    //echo gettype($response)."\n";
    //echo print_r($response)."\n";

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $response."\n";
        //return $valuesArray;
    }
    return $valuesArray;
}
//TO TEST THIS CODE
//receiveCurl('07108');
?>