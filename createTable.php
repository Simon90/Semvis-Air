<?php

//Create connection
$dbconn = pg_connect("host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW");

$dateBegin = $_GET["dateBegin"];
$monthBegin = $_GET["monthBegin"];
$yearBegin = $_GET["yearBegin"];

$timeBegin = $yearBegin . '-' . $monthBegin . '-' . $dateBegin;

$dateEnd = $_GET["dateEnd"];
$monthEnd = $_GET["monthEnd"];
$yearEnd = $_GET["yearEnd"];

$timeEnd = $yearEnd . '-' . $monthEnd . '-' . $dateEnd;

$timestamp = array();

for ($i=0, $hour=0; $hour<24; $hour++){
  for ($min=0; $min<6; $min++, $i++){
		$timestamp[$i] = $hour .":". $min . "0";
		}
	}

for ($j=0; $j<144; $j++){
	$timestamp[$j] = $timeBegin . " " . $timestamp[$j];
	}

$selectedpara = $_GET["selectedPara"];
$sensorId1 = $_GET["sensorId1"];
$sensorId2 = $_GET["sensorId2"];
$sensorId3 = $_GET["sensorId3"];

for ($i=0; $i<144; $i++){
	$sql_select1 = "SELECT $selectedpara FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId1' AND date > '$timestamp[$i]' ORDER BY date";
	$result1 = pg_query($dbconn, $sql_select1);
	$values1[$i] = pg_fetch_result($result1, 0, 0);
	
	$sql_select2 = "SELECT $selectedpara FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId2' AND date > '$timestamp[$i]' ORDER BY date";
	$result2 = pg_query($dbconn, $sql_select2);
	$values2[$i] = pg_fetch_result($result2, 0, 0);
	
	$sql_select3 = "SELECT $selectedpara FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId3' AND date > '$timestamp[$i]' ORDER BY date";
	$result3 = pg_query($dbconn, $sql_select3);
	$values3[$i] = pg_fetch_result($result3, 0, 0);	
	}


echo "<table width=250 border=1>";
echo "<tr><th>Uhrzeit</th><th>AQE " .$sensorId1 ."</th><th>AQE " .$sensorId2 ."</th><th>AQE " .$sensorId3 ."</th></tr>";

for ($l=0; $l<144; $l++)
    {
	echo "<tr>";
	echo "<td>" .$timestamp[$l] ."</td>"; 
	echo "<td>" .$values1[$l] ."</td>"; 
	echo "<td>" .$values2[$l] ."</td>"; 
	echo "<td>" .$values3[$l] ."</td>"; 
	echo "</tr>";
	} 
echo "</table>";
	
pg_close($dbconn);

//url zum testen:
//http://localhost/Semvis-Air/createTable.php?selectedPara=temperature&sensorId1=75842&sensorId2=75759&sensorId3=75842&dateBegin=05&monthBegin=04&yearBegin=2013&dateEnd=06&monthEnd=04&yearEnd=2013

?>
