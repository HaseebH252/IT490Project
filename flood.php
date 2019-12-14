<?php

//First function, Currently receives only an address that is url encoded for testing purposes
//Makes call to api, receives a json string
//string then decoded into array
function receiveCurlFlood($address){
    $curl = curl_init();

    //curl code from tutorial
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.nationalflooddata.com/data?searchtype=addresscoord&getloma=false&address=".$address,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "x-api-key: SVooBJzM5k4JOmp7cdk4D275z3GzeOvB8xfMyIkW",
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


    // need to declare this as a string because of . in name
    $flood='flood.s_fld_haz_ar';

    //setting variables for needed info
    $flood_risk= $response->result->$flood[0]->zone_subty;
    $flood_zone= $response->result->$flood[0]->fld_zone;

    //echo $flood_risk."\n".$flood_zone;

    $result_flood['flood-risk'] = $flood_risk;
    $result_flood['flood-zone'] = $flood_zone;


    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $response."\n";
        return $result_flood;
    }
}
function getFlood($attomAddresses){

$address= $attomAddresses;

$result = array();
foreach ($address as $key=>$street) {
    $single_addr=rawurlencode($street['street']);
    $result[]= receiveCurlFlood($single_addr);
}
//print_r($result);
return $result;
}
$address = "323 Dr M.L.K. Jr. Blvd, Newark, NJ 07102";
$address = rawurlencode($address);
receiveCurlYelp($address);
?>
