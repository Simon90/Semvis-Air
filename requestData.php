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

$selectedPara = $_GET["selectedPara"];
$sensorId1 = $_GET["sensorId1"];
$sensorId2 = $_GET["sensorId2"];
$sensorId3 = $_GET["sensorId3"];

$bool = $selectedPara . '_validated';

$sql_select1 = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId1' AND date BETWEEN '$timeBegin' AND '$timeEnd' ORDER BY date";
$result1 = pg_query($dbconn, $sql_select1);

$sql_select2 = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId2' AND date BETWEEN '$timeBegin' AND '$timeEnd' ORDER BY date";
$result2 = pg_query($dbconn, $sql_select2);

$sql_select3 = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId3' AND date BETWEEN '$timeBegin' AND '$timeEnd' ORDER BY date";
$result3 = pg_query($dbconn, $sql_select3);


$i = 0;
while($row1 = pg_fetch_array($result1))
    {
    echo "egg1[".$i."] = " .$row1[$selectedPara] .";";
	if ($row1[$bool] == 't') {echo "bool1[".$i."] = 1;";}
	else {echo "bool1[".$i."] = 0;";}
	$i++;
	} 
	
$j = 0;
while($row2 = pg_fetch_array($result2))
    {
    echo "egg2[".$j."] = " .$row2[$selectedPara] .";";	
	if ($row2[$bool] == 't') {echo "bool2[".$j."] = 1;";}
	else {echo "bool2[".$j."] = 0;";}
	$j++;
	} 
	
$k = 0;
while($row3 = pg_fetch_array($result3))
    {
    echo "egg3[".$k."] = " .$row3[$selectedPara] .";";
	if ($row3[$bool] == 't') {echo "bool3[".$k."] = 1;";}
	else {echo "bool3[".$k."] = 0;";}	
	$k++;
	} 
	
pg_close($dbconn);

?>
