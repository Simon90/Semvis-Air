<?php

$sensorId1 = $_GET["id1"];
$sensorId2 = $_GET["id2"];
$sensorId3 = $_GET["id3"];

$dbconn2 = pg_connect("host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW");

$selectname1 = "SELECT name FROM \"CosmSensor\" WHERE id = $sensorId1";
$resultname1 = pg_query($dbconn2, $selectname1);
$name1 = pg_fetch_result($resultname1, 0, 0);
echo ' name1 = "'.$name1.'"; ';

$selectname2 = "SELECT name FROM \"CosmSensor\" WHERE id = $sensorId2";
$resultname2 = pg_query($dbconn2, $selectname2);
$name2 = pg_fetch_result($resultname2, 0, 0);
echo ' name2 = "'.$name2.'"; ';

$selectname3 = "SELECT name FROM \"CosmSensor\" WHERE id = $sensorId3";
$resultname3 = pg_query($dbconn2, $selectname3);
$name3 = pg_fetch_result($resultname3, 0, 0);
echo ' name3 = "'.$name3.'"; ';

pg_close($dbconn2);

?>
