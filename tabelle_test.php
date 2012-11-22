<html>
	<head>
		<title>Quality Egg</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="quality_egg.css" />
	</head>
	<!--siehe Cosm Tutorial-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<script src="http://d23cj0cdvyoxg0.cloudfront.net/cosmjs-1.0.0.min.js"></script>
	<!--Bibliothek für den Mouseover-Effekt: http://www.bosrup.com/web/overlib/-->
	<script type="text/javascript" src="overlib421/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	<body id="Hintergrund">

	    <!--Hier werden momentan die Werte eingetragen. Die jeweilige Stelle auf dem Bildschirm ist mit id="myelementX" referenziert-->
		<div>Humidity:
		<span id="myelement"></span>
		</div>
		<div>Humidity history1:
		<span id="myelement1"></span>
		</div>
		<div>Humidity history2:
		<span id="myelement2"></span>
		</div>
		<div>Humidity history3:
		<span id="myelement3"></span>
		</div>
		<div>Humidity history4:
		<span id="myelement4"></span>
		</div>

		<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
		
		<!-- <form...nötig, weil mit dem Submit-Button etwas gepostet wird. tabelle_test.php gibt an, wo das PHP-Script ist, in dem Fall im selben Dokument, also tabelle_test.php-->
		<form method="post" action="tabelle_test.php">
			<h1 id="header1"> Tabelle </h1>

			<!--Dropdown-Liste für die Parameter-->
				<div align="center">
					<select name="mydropdown">
						<option value="Temperature">Temperatur</option>
						<option value="Humidity">Luftfeuchtigkeit</option>
						<option value="CO">Kohlenmonoxid</option>
						<option value="O3">Ozon</option>
						<option value="NO2">Stickstoffdioxid</option>
					</select>
				</div>

		<!--Mouse-Over-Effekt für das Fragezeichen: http://www.bosrup.com/web/overlib/?Documentation-->		
		<div align="center">
			<a href='javascript:void(0);' onmouseover="return overlib('Informationen <br> bla <br> Blup',STICKY,MOUSEOFF,1500,CAPTION,'Parameter',BGCOLOR,'blue',FGCOLOR,'White',TEXTFONT, 'Arial');" onmouseout="return nd();">?</a>
		</div>
		<br>
		<!--Radio-Buttons für die Auswahl des Tages-->
		<table align="center">
		<tr>
				<td><p>Tag6<input type="radio" name="radio" value="tag6"/></p></td>
				<td><p>Tag5<input type="radio" name="radio" value="tag5"/></p></td>
				<td><p>Tag4<input type="radio" name="radio" value="tag4"/></p></td>
				<td><p>Tag3<input type="radio" name="radio" value="tag3"/></p></td>
				<td><p>Tag2<input type="radio" name="radio" value="tag2"/></p></td>
				<td><p>Tag1<input type="radio" name="radio" value="tag1"/></p></td>
				<td><p>Heute<input type="radio" name="radio" value="heute"/></p></td>
				<td><p><input type="submit" name="submit" value="Übernehmen" /></p></td>
		</tr>
		</table><br>
		</form>

		<?php

		$timestampStart="";
		$timestampEnd="";
		$parameter="";

		//Nach dem Drücken des Submit- ("Übernehmen ") Buttons wird im Folgenden abgefragt, welcher Tag und welcher Parameter gewählt wurde
		if(isset($_POST['submit'])){
	
		//Abfrage des Tages
		$auswahlTag=$_REQUEST['radio'];
		//Abfrage des Parameters
		$auswahlParameter=$_REQUEST['mydropdown'];
		
		//global $parameter;
		
		switch ($auswahlParameter)
		{
		case "Temperature":
			$parameter="temperature";
			break;

		case "Humidity":
			$parameter="humidity";
			break;

		case "CO":
			$parameter="CO";
			break;

		case "O3":
			$parameter="O3";
			break;
		case "NO2":
			$parameter="NO2";
			break;	
		}	
		
		$tag=0;
		switch ($auswahlTag)
		{
		case "heute":
			$tag=0;
			echo AktuellesDatum();
			echo Zeitraum($tag);
			break;
		case "tag1":
			$tag=1;
			echo AktuellesDatum();
			echo Zeitraum($tag);
			break;
		case "tag2":
			$tag=2;
			echo AktuellesDatum();
			echo Zeitraum($tag);
			break;
		case "tag3":
			$tag=3;
			echo AktuellesDatum();
			echo Zeitraum($tag);
			break;
		case "tag4":
			$tag=4;
			echo AktuellesDatum();
			echo Zeitraum($tag);
			break;
		case "tag5":
			$tag=5;
			echo AktuellesDatum();
			echo Zeitraum($tag);
			break;
		case "tag6";
			$tag=6;
			echo AktuellesDatum();
			echo Zeitraum($tag);

		default: echo "Keine Auswahl";

		}
		}

		//Alternativ date("c") => 2012-11-19T21:30:45+01:00
		function AktuellesDatum(){

			$year=date("y");
			$month=date("m");
			if($month<10){
			$month="0".$month;}

			$day=date("d");
			if($day<10){
			$day="0".$day;}

			$hours=date("H");
			if($hours<10){
			$hours="0".$hours;}

			$minutes=date("i");
			//if($minutes<10){
			//$minutes="0".$minutes;}

			$seconds=date("s");
			//if($seconds<10){
			//$seconds="0".$seconds;}

			$timestamp="20".$year."-".$month."-".$day."T".$hours.":".$minutes.":".$seconds.".000Z";
			echo $timestamp;
			}

		function Zeitraum($tage){

			$year=date("y");
			$month=date("m");
			//if($month<10){
			//$month="0".$month;}

			$day=date("d")-$tage;
			//if($day<10){
			//$day="0".$day;}

			global $timestampStart, $timestampEnd;

			$timestampStart="20".$year."-".$month."-".$day;
			//IM Moment nicht nötig, aber vielleicht später?: $timestampEnd="20".$year."-".$month."-".$day."T23:59:59.999Z";

			//Zeilenumbrüche in php sucken einfach.
			?></br><?php echo "Startdatum:	". $timestampStart."\n";?></br><?php
			echo "Enddatum:	".$timestampEnd;		  ?></br><?php

		}

		?>


		<script language="JavaScript" type="text/JavaScript">

		var Datum="<?php echo $timestampStart ?>";
		var Parameter="<?php echo $parameter ?>";
		var laengen=new Array();
		
		
		var Tag_Teil1=Datum+"T00:00:00.000Z";
			Tag_Teil2=Datum+"T05:59:59.999Z";
			Tag_Teil3=Datum+"T11:59:59.999Z";
			Tag_Teil4=Datum+"T17:59:59.999Z";
			Tag_Teil5=Datum+"T23:59:59.999Z";

		Tag_Teile= new Array(Tag_Teil1,Tag_Teil2,Tag_Teil3,Tag_Teil4,Tag_Teil5);

		for(var i=0; i<5; i++){
		allValues(Tag_Teile[i], Tag_Teile[i+1],Parameter, String(i+1));

		}

		function allValues(Teil1, Teil2,parameter, element){
		$(document).ready(function($) {

		//2012-11-11T14:20:47.745828Z	2012-11-20T00:00:00.000Z und	2012-11-20T23:59:59.999Z
		function Operation(){
		this.start=Teil1;
		this.end=Teil2;
		this.interval=0;
		this.limit=1000;
		}

		cosm.setKey( "QgvMiPLj6wDkY3k2JSPpD3-rMAuSAKxLbVlLMVhxTURMOD0g" );

		// Replace with your own values
		var feedID        = 75759,          // Feed ID
		datastreamID  = parameter; 	  // Datastream ID

		selector      = "#myelement";   // Your element on the page
		selector1      = "#myelement1";   // Your element on the page
		selector2      = "#myelement2";
		selector3      = "#myelement3";
		selector4      = "#myelement4";
		var  options         = new Operation();

		// Get datastream data from Cosm
		cosm.datastream.get (feedID, datastreamID, function ( datastream ) {

        $(selector).html( datastream["current_value"] );


        cosm.datastream.subscribe( feedID, datastreamID, function ( event , datastream_updated ) {

		$(selector).html( datastream_updated["current_value"] );
        });

		});

		cosm.datastream.history(feedID, datastreamID,options,function ( datastream) {
		$(selector+element).html(parameter + "	" +datastream["datapoints"][datastream["datapoints"].length-1].at+"      value:  "+datastream["datapoints"][datastream["datapoints"].length-1].value);
		laengen.push(datastream["datapoints"].length);		
		
		//alert(datastream["datapoints"][datastream["datapoints"].length-1].at);
		//var date=datastream["datapoints"][datastream["datapoints"].length-1].at;
		//var value= datastream["datapoints"][datastream["datapoints"].length-1].value;
		
		});
		
		});
	

}		function zeilenAdd(zahl){
			
		
			gesamtzahlen=zahl;
		
		}
		//createTable(1000);
	
		
		function createTable(Zeilen){
			for(var i=0; i<Zeilen; i++){
			document.write("<center><table border='2' width='50%'>");
			document.write("<tr>");
			document.write("<td>" + "Tabelleninhalt" + "</td>");
			document.write("</tr>");
			document.write("</table></center>");}
		}
		alert(laengen.length);
		
		</script>

		<!--<div align="center"><input type="Button" value="Aktualisieren" /></div><br>-->

		<a href="index.html" id="Startseite">Startseite</a>
		<a href="Karte.html" id="kartenlink">Zur Karte</a>
	</body>
</html>