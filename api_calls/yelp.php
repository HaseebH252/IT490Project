<?php

//include ('attom.php');

//First function, Currently receives only an address that is url encoded for testing purposes
//Makes call to api, receives a json string
//string then decoded into array
function receiveCurlYelp($url_params){
    $curl = curl_init();

    $api_key='nYvxxj3DpPZLL1mwiFMmBekDaG9rIB7LbgoOo9I1Mlq9MYzVGGcJS5OGkZ5JG-fKiwh4M58WIkPIN8pNi2ldoRd1IEFDXjHzHS23586HMvDGc4wO1CyGbniEVh6pXXYx';

    $url ="https://api.yelp.com/v3/businesses/search?" . http_build_query($url_params);

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
            "authorization: Bearer " . $api_key,
            "cache-control: no-cache",

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

    for ($x = 0; $x <$url_params['limit']; $x++){
        $name[$x] = $response->businesses[$x]->name;
        $location[$x] = $response->businesses[$x]->location->display_address;
    }

    $result_yelp['res_name'] =$name;
    $result_yelp['location'] = $location;

    //print($response->businesses[0]->name);


    // need to declare this as a string because of . in name



    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $response."\n";
        return $result_yelp;
    }
}

function getYelp($attomAddresses){

//TO TEST THIS CODE
    $url_params = array();

    $location = $attomAddresses;
    $term = 'dinner';
    $limit = '3';

    $result = array();
    foreach ($location as $key => $street) {
        $url_params['term'] = $term;
        $url_params['location'] = $street['street'];
        $url_params['limit'] = $limit;

        //print_r($url_params);
        for ($x = 0; $x < 3; $x++) {
            $result[$x] = receiveCurlYelp($url_params);
        }
    }
    return $result;
    //print_r($result);
}
?>