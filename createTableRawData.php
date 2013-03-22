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

$sensorId1 = $_GET["sensorId1"];

$sql_select = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId1' AND date BETWEEN '$timeBegin' AND '$timeEnd'";

$result = pg_query($dbconn, $sql_select);

echo 'Rohdaten des Air Quality Eggs ' . $sensorId1;

echo "<table width=250 border=1>";
echo "<tr>";
echo "<th>" ."Datum"."</th>";
echo "<th>" ."Temperatur"."</th>";
echo "<th>" ."Ozon"."</th>";
echo "<th>" ."Kohlenstoffmonoxid"."</th>";
echo "<th>" ."Stickstoffdioxid"."</th>";
echo "<th>" ."Luftfeuchtigkeit"."</th>";
echo "</tr>";
while($row = pg_fetch_array($result))
    {
	echo "<tr>";
    echo "<td>" .$row["date"] ."</td>";	
    echo "<td>" .$row["temperaturC"] ."</td>";
	echo "<td>" .$row["ozon"] ."</td>";
	echo "<td>" .$row["CO"] ."</td>";
	echo "<td>" .$row["NO2"] ."</td>";
	echo "<td>" .$row["humidity"] ."</td>";
    echo "</tr>";
	} 
echo "</table>";
	
pg_close($dbconn);

//url zum testen:
//http://localhost/Semvis-Air/createTableRawData.php?sensorId1=75842&dateBegin=19&monthBegin=3&yearBegin=2013&dateEnd=20&monthEnd=3&yearEnd=2013

?>
