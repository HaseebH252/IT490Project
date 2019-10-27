<?php

//receives each found property and retrieves only required fields
function splitObject($property){

    $street = $property->address->oneLine;
    $latitude = $property->location->latitude;
    $longitude = $property->location->longitude;
    $lineOne = $property->address->line1;
    $lineTwo = $property->address->line2;

    //adds all required variables into associative array
    $info = array('street' => $street,'latitude' => $latitude,'longitude' => $longitude,'line1' => $lineOne,'line2'=>$lineTwo);
    return $info;
}

//performs api call
function attomApiCall($postalCode,$pagesize){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.gateway.attomdata.com/propertyapi/v1.0.0/property/address?postalcode=".$postalCode."&propertytype=APARTMENT&page=1&pagesize=".$pagesize."",
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

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {

    }

    $response = json_decode($response);
    return $response;
}


//Main Function for attom.php, receives Zipcode as parameter
function receiveCurl($zip){

    //declaring array that will be returned from this function, will return to from this function
    $addresses = array();
    $postalCode = $zip;

    //pagesize regulates how many search results we get back from api
    $pagesize= "5";

    //calls attom API call function to retrieve Addresses as one line, coordinates, and addresses in two line format (needed for attomBasicProfile())
    $justAddresses = attomApiCall($postalCode,$pagesize);

    //loops through each property from api call, calls function splitObject()
    foreach ($justAddresses->property as $row){
        $addresses[] = splitObject($row);
    }

    //debug
    echo print_r($addresses);
    //get address as a line
    //$street = $response->property[0]->address->oneLine."\n";
    //echo print_r($street);
    //echo gettype($response)."\n";
    //echo print_r($response)."\n";

    return $addresses;
}
//TO TEST THIS CODE
//receiveCurl('07108');
?>