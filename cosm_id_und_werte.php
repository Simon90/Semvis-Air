<?php


include('PachubeAPI.php');
include('cosm_id_und_werte_f.php');
$pachube = new PachubeAPI("QgvMiPLj6wDkY3k2JSPpD3-rMAuSAKxLbVlLMVhxTURMOD0g");

$alle=$pachube->getFeedsList("json", 0, 100000, "summary", "munster","munster");

$ids=get_ids($alle);
$json=get_json($ids);
$lat=get_lat($json);
$lon=get_lon($json);
$timestamp=get_timestamp($json);
$NO2=get_NO2($json);
$temperature=get_Temperature($json);
$CO=get_CO($json);
$humidity=get_Humidity($json);
$O3=get_O3($json);
//$values=get_values($json);
//echo count($values);
$count2=count($O3);
for($i=0;$i<$count2;$i++){
	echo $ids[$i].' ';
	echo $lat[$i].' ';
	echo $lon[$i].' ';
	echo $timestamp[$i].' ';
	echo $temperature[$i].' ';
	echo $humidity[$i].' ';
	echo $NO2[$i].' ';
	echo $CO[$i].' ';
	echo $O3[$i]."</br>";
	}


$count1=count($ids);
for($i=0;$i<$count1;$i++){
	echo $json[$i];
//	echo $timestamp[$i]."</br>";	
  //  echo $ids[$i].' ';
	//echo $lat[$i].' ';	
	//echo $lon[$i]."</br>";
	}
unset($alle);

/*
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
			if(strpos($temp1[1],',"lon"')!==false){ 
				$temp=explode(',"lon"',$temp1[1]);
				array_push($lat, substr($temp[0],0,16));
				}
			else if(strpos($temp1[1],'"lon"')!==false){
				$temp=explode('"lon"',$temp1[1]);
				array_push($lat, substr($temp[0],0,16));
	}			
			}
	
	else{
		array_push($lat, 'Kein Wert');
	}
}	return $lat;
}

function get_lon($json_fulltext){

$lon=array();
$count5=count($json_fulltext);
for($i=0;$i<$count5;$i++){
	if(strpos($json_fulltext[$i],'"lon":')!==false){
		$temp1=explode('"lon":',$json_fulltext[$i]);
			if(strpos($temp1[1],'},"version"')!==false){ 
				$temp=explode('},"version"',$temp1[1]);
				array_push($lon, substr($temp[0],0,16));
				}
	}			
			
	
	else{
		array_push($lon, 'Kein Wert');
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
				array_push($timestamp, 'Kein Wert');
			}	
	}			
			
	
	else{
		array_push($timestamp, 'Kein Wert');
	}
}	return $timestamp;
}

function get_values($json_fulltext){

$values=array();

for($i=0;$i<count($json_fulltext);$i++){
	if(strpos($json_fulltext[$i],'"current_value":"')!==false){
		$temp1=explode('"current_value":"',$json_fulltext[$i]);
			for($j=1;$j<count($temp1);$i++)
				if(strpos($temp1[$j],'","at"')!==false){ 
					$temp=explode('","at"',$temp1[$j]);
					array_push($values, $temp[0]);
					}
				else{
					array_push($values, 'Kein Wert');
				}	
	}			
	else{
		array_push($values, 'Kein Wert');
	}
}	return $values;
}*/
?>