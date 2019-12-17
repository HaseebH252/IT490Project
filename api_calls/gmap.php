<?php
//include ("attom.php") no longer neede school.php already includes attom.php
//include ("school.php");
//takes long/lat from attom api to make map
function receiveMap($zipcode){
	//assigning variables to lat and long
	//schools
	$schools=receiveSchool($zipcode);
	$slat1=$schools[0]['latitude'];
	$slon1=$schools[0]['longitude'];
	$slat2=$schools[1]['latitude'];
        $slon2=$schools[1]['longitude'];
        $slat3=$schools[2]['latitude'];
        $slon3=$schools[2]['longitude'];
        $slat4=$schools[3]['latitude'];
        $slon4=$schools[3]['longitude'];
	$saddr1="&markers=color:blue%7Clabel:1%7C".$slat1.','.$slon1;
        $saddr2="&markers=color:blue%7Clabel:2%7C".$slat2.','.$slon2;
        $saddr3="&markers=color:blue%7Clabel:3%7C".$slat3.','.$slon3;
	$saddr4="&markers=color:blue%7Clabel:4%7C".$slat4.','.$slon4;
	$saddrTotal=$saddr1.$saddr2.$saddr3.$saddr4;
      	//houses
	$address=receiveCurl($zipcode);
	$lat1=$address[0]['latitude'];
	$lon1=$address[0]['longitude'];
	$lat2=$address[1]['latitude'];
	$lon2=$address[1]['longitude'];
	$lat3=$address[2]['latitude'];
	$lon3=$address[2]['longitude'];
	$lat4=$address[3]['latitude'];
	$lon4=$address[3]['longitude'];
	$lat5=$address[4]['latitude'];
	$lon5=$address[4]['longitude'];
	//formatting for google maps url
	$addr1="&markers=label:1%7C".$lat1.",".$lon1;
	$addr2="&markers=label:2%7C".$lat2.",".$lon2;
	$addr3="&markers=label:3%7C".$lat3.",".$lon3;
	$addr4="&markers=label:4%7C".$lat4.",".$lon4;
	$addr5="&markers=label:5%7C".$lat5.",".$lon5;
	$api_key='AIzaSyB4hMVNgMIg8-mXh2gbO5BggD39n0Y0c4I';
	//url code that combines att addresses
	$url ="https://maps.googleapis.com/maps/api/staticmap?size=640x640".$saddrTotal.$addr1.$addr2.$addr3.$addr4.$addr5."&key=".$api_key;
	//downloading url to png locally
	$dest='googleMap.png';
	$download=file_put_contents($dest, file_get_contents($url));
	//encoding to send to rmq *incomplete*
	$image=file_get_contents("googleMap.png");
	$data=base64_encode($image);
	$json=json_encode($data);
	return $json;
}
//testing
//receiveMap("07109");
?>
