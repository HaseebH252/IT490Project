<?php

 //$api = 'iiHnOKfno2Mgkt5AynpvPpUQTEyxE77jo1RU8PIv';
 //$api1 = 'UXGblncZDk0jehKCvU7bicRIfW8IlfAFZoHurYUI';

//this function gets all ORI numbers in state and returns only ORI from required county
function getPD($allPD,$county){

    $eachPD='';
    if ($allPD->county_name == $county){
        $eachPD = $allPD->ori;
        return $eachPD;
    }else {
        return false;
    }
}

//this function calls api for each ORI and returns object with all crimes in this ORI
function getCrimesByOri($ori){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.usa.gov/crime/fbi/sapi/api/summarized/agencies/".$ori."/offenses/2018/2018?API_KEY=UXGblncZDk0jehKCvU7bicRIfW8IlfAFZoHurYUI",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $response = json_decode($response);
    return $response;
}

//Performs API call for all ORIs based on state format = 'NJ'
function getAllOri($state) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.usa.gov/crime/fbi/sapi/api/agencies/byStateAbbr/raw/".$state."?API_KEY=UXGblncZDk0jehKCvU7bicRIfW8IlfAFZoHurYUI",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET"
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    return $response;
}

//main function
function getCrime($state,$county){

//declare array for all ORIs
$allORI = array();

//Array with crimes by type for whole county | returns from this file
$crimes = array();
$crimes['aggravated-assault'] = 0;
$crimes['arson'] = 0;
$crimes['burglary'] = 0;
$crimes['homicide'] = 0;
$crimes['human-trafficing'] = 0;
$crimes['larceny'] = 0;
$crimes['motor-vehicle-theft'] = 0;
$crimes['property-crime'] = 0;
$crimes['rape'] = 0;
$crimes['rape-legacy'] = 0;
$crimes['robbery'] = 0;
$crimes['violent-crime'] = 0;

//format county to upper case for search
$county = strtoupper($county);

//calls getALLOri to get required ORI numbers
$response = getAllOri($state);
$response = json_decode($response);

//getting rid of state property in returned object
$response = $response->$state;

//loops through array of all Police Departments and returns only PDs from required county
foreach ($response as $pd){
    $flag = true;
    if (getPD($pd,$county) != false){
        $allORI[] = getPD($pd,$county);
    }
}

//$allORI[0]= 'WA0310400';

//loops through each ori in $allORI[] array
foreach ($allORI as $ori){

    //get crimes by each ori (not county)
    $getCrimesByCounty = getCrimesByOri($ori);

    foreach ($getCrimesByCounty as $pd){
        $crimeByDepartment = array();
        if (is_array($pd)){
            foreach ($pd as $obj){
                $offense = $obj->offense;
                $actual =  $obj->actual;
                $crimeByDepartment[$offense] = $actual;
            }
        }else{
            //echo "Pagination\n";
        }
        foreach ($crimeByDepartment as $typeCrime=>$value){
            $crimes[$typeCrime] +=$value;
        }
    }
}

//debug
//echo $county."\n";
//echo print_r($allORI)."\n";
//echo print_r($crimes)."\n";
return $crimes;

}
//calls main function to test
//will be called from dmzReceiver.php
//getCrime("NJ","bergen");
?>