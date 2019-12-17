<?php
//include ("attom.php");

function splitObj($prop){

    $name = $prop->School->InstitutionName;
    $latArr = $prop->School->geocodinglatitude;
    $lonArr = $prop->School->geocodinglongitude;
    $street = $prop->School->locationaddress;
    $city = $prop->School->locationcity;
    $state= $prop->School->stateabbrev;

    //adds all required variables into associative array
    $info = array('name' => $name,'latitude' => $latArr,'longitude' => $lonArr,'street' => $street,'street'=>$street,'city'=>$city,'state'=>$state);
    return $info;
}

//performs api call for schools within certain distance
function attomSchoolCall($latitude,$longitude,$radius){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.gateway.attomdata.com/propertyapi/v1.0.0/school/snapshot?latitude=".$latitude."&longitude=".$longitude."&radius=".$radius."",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "apikey: 7dd51036a4157ae0626c45efb07f7f8a",
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

function receiveSchool($zipSchool){
        $addy=receiveCurl($zipSchool);
        $lat=$addy[0]['latitude'];
        $lon=$addy[0]['longitude'];
        $radius = 5;
	
	$justSchools = null;
	$justSchools = attomSchoolCall($lat,$lon,$radius);
	
	foreach ($justSchools->school as $rows){
		$var[] = splitobj($rows);
	}

	return $var;
}
//code tester
//print_r(receiveSchool('07109'));
?>
