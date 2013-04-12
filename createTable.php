<?php

//Create connection
$dbconn = pg_connect("host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW");

$date = $_GET["date"];
$month = $_GET["month"];
$year = $_GET["year"];

$time = $year . '-' . $month . '-' . $date;

$timestamp = array();

for ($i=0, $hour=0; $hour<24; $hour++){
	for ($min=0; $min<6; $min++, $i++){
		$timestamp[$i] = $hour .":". $min . "0";
		}
	}

for ($j=0; $j<44; $j++){
	$timestamp[$j] = $time . " " . $timestamp[$j];
	}

$selectedpara = $_GET["selectedPara"];
$sensorId1 = $_GET["id1"];
$sensorId2 = $_GET["id2"];
$sensorId3 = $_GET["id3"];

for ($i=0; $i<44; $i++){
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

echo "table[0] = '<table width=250 border=1><tr><th>Uhrzeit</th><th>AQE $sensorId1</th><th>AQE $sensorId2</th><th>AQE $sensorId3</th></tr>';";
echo 'table[1] = "';
for ($l=0; $l<44; $l++)
    {
	echo "<tr>";
	echo "<td>" .$timestamp[$l] ."</td>"; 
	echo "<td>" .$values1[$l] ."</td>"; 
	echo "<td>" .$values2[$l] ."</td>"; 
	echo "<td>" .$values3[$l] ."</td>"; 
	echo "</tr>";
	} 
echo '</table>";';
	
pg_close($dbconn);

//url zum testen:
//http://localhost/Semvis-Air/createTable.php?selectedPara=temperature&id1=75842&id2=75759&id3=75842&date=08&month=04&year=2013



?>
