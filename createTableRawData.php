<?php

//Create connection
$dbconn = pg_connect("host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW");

$date = $_GET["date"];
$month = $_GET["month"];
$year = $_GET["year"];

$timeBegin = $year . '-' . $month . '-' . $date;

$timeEnd = $year . '-' . $month . '-' . $date . ' 23:59:59';
$sensorId1 = $_GET["sensorId1"];

$sql_select = "SELECT * FROM \"MeasuredData\" WHERE \"sensorId\" = '$sensorId1' AND date BETWEEN '$timeBegin' AND '$timeEnd' ORDER BY date";

$result = pg_query($dbconn, $sql_select);

echo "name1 = " . $sensorId1 . ";";

echo "table[0] = '<table width=250 border=1><tr><th>Datum</th><th>Temperatur</th><th>Ozon</th><th>Kohlenstoffmonoxid</th><th>Stickstoffdioxid</th><th>Luftfeuchtigkeit</th></tr>';";


echo 'table[1] = "';
while($row = pg_fetch_array($result))
    {
	echo "<tr>";
    echo "<td>" .$row["date"] ."</td>";	
	if ($row["temperature_validated"] == 't'){
		echo "<td><span style='color:red'>" .$row["temperature"] ."</span></td>";
		}
		else {echo "<td>" .$row["temperature"] ."</td>";}
	if ($row["ozon_validated"] == 't'){
		echo "<td><span style='color:red'>" .$row["ozon"] ."</span></td>";
		}
		else {echo "<td>" .$row["ozon"] ."</td>";}
	if ($row["co_validated"] == 't'){
		echo "<td><span style='color:red'>" .$row["co"] ."</span></td>";
		}
		else {echo "<td>" .$row["co"] ."</td>";}
	if ($row["no2_validated"] == 't'){
		echo "<td><span style='color:red'>" .$row["no2"] ."</span></td>";
		}
		else {echo "<td>" .$row["no2"] ."</td>";}
	if ($row["humidity_validated"] == 't'){
		echo "<td><span style='color:red'>" .$row["humidity"] ."</span></td>";
		}
		else {echo "<td>" .$row["humidity"] ."</td>";}
    echo "</tr>";
	}	
echo '</table>";';
	
pg_close($dbconn);

//url zum testen:
//http://localhost/Semvis-Air/createTableRawData.php?sensorId1=75842&dateBegin=09&monthBegin=4&yearBegin=2013

?>
