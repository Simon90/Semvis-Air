<?php
//This script retrieves the values from Cosm with the help of PachubeAPI and cosm_id_and_values_f.php. 
//Former ist from https://github.com/cosm/cosm-js, the latter contains self-defined functions.

//functions from Cosm
include('PachubeAPI.php');
//self-defined functions to get the values
include('cosm_id_und_werte_f.php');

$pachube = new PachubeAPI("QgvMiPLj6wDkY3k2JSPpD3-rMAuSAKxLbVlLMVhxTURMOD0g");

//$alle contains all meta-information about all feeds with the tag 'munster' (ID,Title, see http://cosm.com/docs/v2/feed/list.html for further information) . 
$alle=$pachube->getFeedsList("json", 0, 100000, "summary", "munster","munster");

//array: contains all IDs with the tag 'munster'.
$ids=get_ids($alle);
//echo count($ids);
//array: contains the values for every ID in JSON format.
$json=get_json($ids);

//The following two arrays contain the coordinates. The values below are self-explaining.
$lat=get_lat($json);
$lon=get_lon($json);
$timestamp=get_timestamp($json);
$NO2=get_NO2($json);
$temperature=get_Temperature($json);
$CO=get_CO($json);
$humidity=get_Humidity($json);
$O3=get_O3($json);
$names=get_Name($json);

for($i=0;$i<count($temperature);$i++){
echo $temperature[$i]."T"."</br>";
echo $humidity[$i]."H"."</br>";
}
//$values=get_values($json);
//echo count($values);

//$count2 initialisation before the loop for a better performance. Echo-lines are not important, just for implementation. 
$count2=count($ids);
echo $count2."</br>";
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
	
	//pgSQL
	$sensorid=pg_escape_string($ids[$i]);
	$latitude=pg_escape_string( $lat[$i]);
	$longitude=pg_escape_string($lon[$i]);
    $date= pg_escape_string($timestamp[$i]);
	$date="'$date'";
	$date=str_replace("T"," ",$date);
	$date=str_replace("Z","",$date);
	$temperature= pg_escape_string($temperature[$i]);
	$humidity= pg_escape_string($humidity[$i]);
	$no2= pg_escape_string($NO2[$i]);
	$co=pg_escape_string( $CO[$i]);
	$ozon=pg_escape_string( $O3[$i]);
	$name=pg_escape_string( $names[$i]);
//$name="'sensor'";
//Connection to PostgreSQL. Enter appropriate values, otherwise it will not work.
$connection="host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW";
pg_connect($connection);
$result=pg_query("Select id from \"CosmSensor\" where id=$sensorid");

$array=pg_fetch_array($result);
if(strlen($array[0])==0){
$result=pg_query("Insert into \"CosmSensor\" (id,name,latitude,longitude) values ($sensorid, $name,$latitude,$longitude);");
	}
if($date!="'-1'"){	
$re=pg_query("Select \"sensorId\" from \"MeasuredData\" where \"sensorId\"=$sensorid and \"date\"=$date");
$r=pg_fetch_array($re);

if(is_bool($r)){
$query="Insert into \"MeasuredData\"" ;
$query.="(\"date\",\"sensorId\",\"temperature\",ozon,\"no2\",humidity,\"co\") values ($date,$sensorid,$temperature,$ozon,$no2,$humidity,$co)";
$connection="host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW";
pg_connect($connection);
$result=pg_query($query);

}
}	
	
	
	
	
	
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