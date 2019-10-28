<?php

//Makes call to api, receives a json string
//string then decoded into array
function receiveCurlGoogleCounty($zipcode){
    $curl = curl_init();

    $api_key='AIzaSyB4hMVNgMIg8-mXh2gbO5BggD39n0Y0c4I';

    $url ="https://maps.googleapis.com/maps/api/geocode/json?address=".$zipcode."&key=".$api_key;

    //curl code from tutorial
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(


        ),
    ));
    //this variable receives data returned from api
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    //json string is decoded
    $response = json_decode($response);

    //print entire json array
    //print_r($response);

    //Get state Abbreviation
    $results_county['state'] = $response->results[0]->address_components[3]->short_name;

    //Get county but cut sting so it only says Essex rather than Essex County.
    $county_string = substr($response->results[0]->address_components[2]->short_name, 0,
        strpos($response->results[0]->address_components[2]->short_name,
            "County"));

    //Put into return array
    $results_county['county'] = trim($county_string," ");




    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $response."\n";
        return $results_county;
    }
}
//TO TEST THIS CODE
//$zipcode = 07103;
//receiveCurlGoogleCounty($zipcode);
?>