<?php
function get_ids($alle){
$temp2=explode("https://api.cosm.com/v2/feeds/",$alle);
$ids=array();
$count2=count($temp2);
for($i=0;$i<$count2;$i++){
if(strpos($temp2[$i],'.json')!==false){ 
	$temp=explode(".json",$temp2[$i]);
	array_push($ids, $temp[0]);
	}
}
return $ids;
}

function get_json($ids){
$pachubes=array();
global $pachube;
$count3=count($ids);
for($i=0;$i<$count3;$i++){
$feed = $ids[$i];;
$pachub=$pachube->getFeed("json", $feed);
array_push($pachubes, $pachub);
}
//echo "<code>" . $pachubes . "</code><br/>";
return $pachubes;
}

function get_lat($json_fulltext){

$lat=array();

$count4=count($json_fulltext);
for($i=0;$i<$count4;$i++){
	if(strpos($json_fulltext[$i],'"lat":')!==false){
		$temp1=explode('"lat":',$json_fulltext[$i]);			
		array_push($lat, substr($temp1[1],0,16));	
			}
	
	else{
		array_push($lat, -1);
	}
}	return $lat;
}

function get_lon($json_fulltext){

$lon=array();
$count5=count($json_fulltext);
for($i=0;$i<$count5;$i++){
	if(strpos($json_fulltext[$i],'"lon":')!==false){
		$temp1=explode('"lon":',$json_fulltext[$i]);
			if(strpos($temp1[1],',"domain"')!==false){ 
				$temp=explode(',"domain"',$temp1[1]);
				array_push($lon, substr($temp[0],0,16));
				}
	}			
			
	
	else{
		array_push($lon, -1);
	}
}	return $lon;
}

function get_timestamp($json_fulltext){

$timestamp=array();

$count6=count($json_fulltext);
for($i=0;$i<$count6;$i++){
	if(strpos($json_fulltext[$i],'"at":"')!==false){
		$temp1=explode('"at":"',$json_fulltext[$i]);
			if(strpos($temp1[1],'","max_value"')!==false){ 
				$temp=explode('","max_value"',$temp1[1]);
				array_push($timestamp, $temp[0]);
				}
			else{
				array_push($timestamp, -1);
			}	
	}			
			
	
	else{
		array_push($timestamp, -1);
	}
}	return $timestamp;
}

function get_NO2($json_fulltext){

$no2_values=array();

for($i=0;$i<count($json_fulltext);$i++){
	if(strpos($json_fulltext[$i],'"id":"O3"')!==false){
		$temp1=explode('"id":"O3"',$json_fulltext[$i]);
			$count7=count($temp1);
				if(strpos($temp1[0],'"current_value":"')!==false){ 
					$temp=explode('"current_value":"',$temp1[0]);
					$temp0=explode('","at"',$temp[count($temp)-2]);
					array_push($no2_values, $temp0[0]);
					}
				else{
					array_push($no2_values, -1);
				}	
	}			
	else{
		array_push($no2_values, -1);
	}
}	return $no2_values;
}

function get_O3($json_fulltext){

$o3_values=array();

for($i=0;$i<count($json_fulltext);$i++){
	if(strpos($json_fulltext[$i],'"id":"O3"')!==false){
		$temp1=explode('"id":"O3"',$json_fulltext[$i]);
			$count7=count($temp1);
				if(strpos($temp1[0],'"current_value":"')!==false){ 
					$temp=explode('"current_value":"',$temp1[0]);
					$temp0=explode('","at"',$temp[count($temp)-1]);
					array_push($o3_values, $temp0[0]);
					}
				else{
					array_push($o3_values, -1);
				}	
	}			
	else{
		array_push($o3_values, -1);
	}
}	return $o3_values;
}

function get_Humidity($json_fulltext){

$humidity_values=array();

for($i=0;$i<count($json_fulltext);$i++){
	if(strpos($json_fulltext[$i],'"id":"O3"')!==false){
		$temp1=explode('"id":"O3"',$json_fulltext[$i]);
			$count7=count($temp1);
				if(strpos($temp1[0],'"current_value":"')!==false){ 
					$temp=explode('"current_value":"',$temp1[0]);
					$temp0=explode('","at"',$temp[count($temp)-3]);
					array_push($humidity_values, $temp0[0]);
					}
				else{
					array_push($humidity_values, -1);
				}	
	}			
	else{
		array_push($humidity_values, -1);
	}
}	return $humidity_values;
}

function get_CO($json_fulltext){

$co_values=array();

for($i=0;$i<count($json_fulltext);$i++){
	if(strpos($json_fulltext[$i],'"id":"O3"')!==false){
		$temp1=explode('"id":"O3"',$json_fulltext[$i]);
			$count7=count($temp1);
				if(strpos($temp1[0],'"current_value":"')!==false){ 
					$temp=explode('"current_value":"',$temp1[0]);
					$temp0=explode('","at"',$temp[count($temp)-4]);
					array_push($co_values, $temp0[0]);
					}
				else{
					array_push($co_values, -90);
				}	
	}			
	else{
		array_push($co_values, -90);
	}
}	return $co_values;
}

function get_Temperature($json_fulltext){

$o3_values=array();

for($i=0;$i<count($json_fulltext);$i++){
	if(strpos($json_fulltext[$i],'"id":"temperature"')!==false){
		$temp1=explode('"id":"temperature"',$json_fulltext[$i]);
			$count7=count($temp1);
				if(strpos($temp1[0],'"current_value":"')!==false){ 
					$temp=explode('"current_value":"',$temp1[0]);
					$temp0=explode('","at"',$temp[count($temp)-1]);
					array_push($o3_values, $temp0[0]);
					}
				else{
					array_push($o3_values, -90);
				}	
	}			
	else{
		array_push($o3_values, -90);
	}
}	return $o3_values;
}
?>