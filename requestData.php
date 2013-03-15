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

$sql_select1 = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId1' AND date BETWEEN '$timeBegin' AND '$timeEnd'";
$result1 = pg_query($dbconn, $sql_select1);

$sql_select2 = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId2' AND date BETWEEN '$timeBegin' AND '$timeEnd'";
$result2 = pg_query($dbconn, $sql_select2);

$sql_select3 = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId3' AND date BETWEEN '$timeBegin' AND '$timeEnd'";
$result3 = pg_query($dbconn, $sql_select3);


$i = 0;
while($row1 = pg_fetch_array($result1))
    {
    echo "egg1[".$i."] = " .$row1[$selectedPara] .";";	
	$i++;
	} 
	
$j = 0;
while($row2 = pg_fetch_array($result2))
    {
    echo "egg2[".$j."] = " .$row2[$selectedPara] .";";	
	$j++;
	} 
	
$k = 0;
while($row3 = pg_fetch_array($result3))
    {
    echo "egg3[".$k."] = " .$row3[$selectedPara] .";";	
	$k++;
	} 
	
pg_close($dbconn);

?>
