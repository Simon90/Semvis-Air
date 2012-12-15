﻿<html>
	<head>
  	<title>Air Quality Egg Muenster</title>
  	<meta name="description" content="Luftqualität Muenster" />
  	<meta name="keywords" content="Air Quality Egg" />
  	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
  	<meta http-equiv="X-UA-Compatible" content="IE=9" />
  	<link rel="stylesheet" type="text/css" href="css/style.css" />
  	<script type="text/javascript" src="js/jquery.min.js"></script>
  	<script type="text/javascript" src="js/image_slide.js"></script>
	</head>

	<script type="text/javascript" src="overlib421/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css" />
	<body>

<div id="main">
    <div id="header">
	  <div id="banner">
	    <div id="Willkommen">
	      <h1>Semvis Air</h1>
	    </div><!--close Willkommen-->
	  </div><!--close banner-->
    </div><!--close header-->

	<div id="menubar">
      <ul id="menu">
        <li><a href="index.html">Startseite</a></li>
        <li class="current"><a href="Karte.php">Karte</a></li>
        <li><a href="Hilfe.html">Hilfe</a></li>
        <li><a href="impressum.html">Impressum und Kontakt</a></li>
      </ul>
    </div><!--close menubar-->	
    
	<div id="site_content">		
	<div class="sidebar_container">       
		<div class="sidebar">
          <div class="sidebar_item">
            <h2>Willkommen</h2>
            <p>Willkommen auf unserer Webseite zum "Air Quality Egg".Ihr duerft euch gerne umsehen!</p>
          </div><!--close sidebar_item--> 
        </div><!--close sidebar-->     		
		
<div class="sidebar">  
 <div class="sidebar_item">
            <h2>Auswahl</h2> 
       <div id="Auswahlfelder">
		<p>Ausgewählte AQEs:</p>
		<p>Egg1<input type="text" name="Egg1" /> </p>
		<p>Egg2<input type="text" name="Egg2" /> </p>
		<p>Egg3<input type="text" name="Egg3" /> </p>
		<FORM METHOD="LINK" ACTION="tabelle.html">
		<INPUT TYPE="submit" VALUE="Tabelle">
		</FORM>
		<FORM METHOD="LINK" ACTION="Diagramm.html">
		<Input Type="submit" VALUE="Diagramm">
		</FORM>
		</div> 
   </div><!--close sidebar_item-->
  </div><!--close sidebar-->		 

  <div id="official_values">
	<div class="sidebar">  
		<div class="sidebar_item">		
			<h2>Offizielle Werte</h2> 
				<div>O3:	   
					<span id="myelement"></span>
				</div>
				<div>NO2:	
					<span id="myelement2"></span>
				</div>
				<div>Relative Luftfeuchtigkeit:
					<span id=""></span>
				</div>
				<div>Lufttemperatur:
					<span id=""></span>
				</div>
			</div>
		</div>	
   </div><!--close sidebar_item-->
  <!--close sidebar_item-->
  
	
	<script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"></script>
		
		<div id="map"></div>
		
		<?php
		$host = "http://www.uni-muenster.de/Klima/wetter/wetter.php";
		$filestring = file_get_contents($host);
		$startpos = 0;
		while($pos = strpos($filestring, "<td class=", $startpos))
		{
		$string = substr($filestring, $pos, strpos($filestring, "</td>", $pos + 1) - $pos);
		if(stristr($string, '%')) {
       		 echo $string."</br>";
		}
		$startpos = $pos + 1;
		
		}
		
		$host = "http://www.uni-muenster.de/Klima/wetter/wetter.php";
		$filestring = file_get_contents($host);
		$startpos = 0;
		while($pos = strpos($filestring, "<td class=", $startpos))
		{
		$string1 = substr($filestring, $pos, strpos($filestring, "</td>", $pos + 1) - $pos);
		if(stristr($string1, '°C')) {
        	echo $string1."</br>";
		}
		$startpos = $pos + 1;
		
		}
		
		$url="http://www.lanuv.nrw.de/luft/temes/heut/MSGE.htm#jetzt";
		$seitenquelltext=file_get_contents($url);
		
		function getNO2($quelltext){
		$text=$quelltext;
		$startpos=0;
		$werte='';
		while($position = strpos($text, '<td class="mw_standard">', $startpos)){
		$pos=strpos($text,'<td class="mw_standard">',$startpos)+24;
		$zeile=substr($text,$pos,strpos($text,'</td>',$pos+1)-$pos);
		$zeile=trim($zeile,' ');
		if(strlen($zeile)>0 && strlen($zeile)<5){
		
		$werte=$werte.','.$zeile;
		}		
		$startpos = $pos + 1; 		
		}
		$werte=substr($werte,1,strlen($werte)-1);
		$werte=explode(',',$werte);
		$no2_werte=array();
		
		$i=0;
		for($j=1;$j<count($werte);$j+=2){
		
			$no2_werte[$i]=$werte[$j];
			$i++;
			}
		return $no2_werte;	
		}
		
		
		function getO3($quelltext){
		$text=$quelltext;
		$startpos_O3=0;
		$werte_O3='';
		while($position_O3 = strpos($text, '<td class="mw_ozon">', $startpos_O3)){
		$pos_O3=strpos($text,'<td class="mw_ozon">',$startpos_O3)+20;
		$zeile_O3=substr($text,$pos_O3,strpos($text,'</td>',$pos_O3+1)-$pos_O3);
		$zeile_O3=trim($zeile_O3,' ');
		if(strlen($zeile_O3)>0 && strlen($zeile_O3)<5){
		
		$werte_O3=$werte_O3.','.$zeile_O3;
		}		
		$startpos_O3 = $pos_O3 + 1; 		
		}
		$werte_O3=substr($werte_O3,1,strlen($werte_O3)-1);
		$werte_O3=explode(',',$werte_O3);
		
		return $werte_O3;
		}
		
		
		$test_O3=getO3($seitenquelltext);
		$test_NO2=getNo2($seitenquelltext);
		
		
		for($j=0;$j<count($test_O3);$j+=1){
				//echo 'O3: '.$test_O3[$j]."</br>";
			}	
		for($j=0;$j<count($test_NO2);$j+=1){
				//echo 'NO2: '.$test_NO2[$j]."</br>";
			}
		
		
		/*Bei Bedarf die Werte in Integer konvertieren
		for($i=0;$i<count($werte);$i++){
			$werte[$i]=(int)$werte[$i];
		}
		echo gettype($werte[0]);*/
		
		?>
			<script>			

			selector      = "#myelement";
			selector2      = "#myelement2";
			var O3= "<?php echo $test_O3[count($test_O3)-1] ?>";
			var NO2="<?php echo $test_NO2[count($test_NO2)-1] ?>";
			
			$(selector).html(O3);
			$(selector2).html(NO2);
			
			var icon = L.icon({
			iconUrl: 'bilder/egg.png',

			iconSize:     [32, 32], // size of the icon
			//shadowSize:   [50, 64], // size of the shadow
			iconAnchor:   [16, 16], // point of the icon which will correspond to marker's location
			//shadowAnchor: [4, 62],  // the same for the shadow
			popupAnchor:  [0, -12] // point from which the popup should open relative to the iconAnchor
			});
			
			
			
			
			var map = L.map('map').setView([51.963572, 7.613813], 13);
		  		L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery ?<a href="http://cloudmade.com">CloudMade</a>'
				}).addTo(map);
				
				function onMapClick(e) {
					popup
					/*.setLatLng(e.latlng)
					.setContent("You clicked the map at " + e.latlng.toString())
					.openOn(map);*/
					}

				map.on('click', onMapClick);
			
				var marker = L.marker([51.963572, 7.613813], {icon: icon}).addTo(map);
				
				marker.bindPopup("Die Messdaten von AQE Name <br>Temperatur: <a href='somewhere.html' title='Beschreibung des jeweiligen Parameters'>?</a> <br>Luftfeuchtigkeit: <a href='somewhere.html' title='Your pop-up text here'>?</a> <br> Kohlenmonoxid: <a href='somewhere.html' title='Your pop-up text here'>?</a> <br> Ozon: <a href='somewhere.html' title='Your pop-up text here'>?</a> <br> Stickstoffdioxid: <a href='somewhere.html' title='Your pop-up text here'>?</a> <br> <input type='Button' value='Zur Auswahl hinzufügen' />");
				
				
		  </script>
			
			<!--Mit dem Befehl unten öffnet sich ein ganz neues Fenster!
			<a href="Hilfe.html" target="_blank" onClick="ganzneuWindow = window.open('Hilfe.html', '500', 'resizable=no,toolbar=no,scrollbars=yes,width=70,height=60,dependent'); ganzneuWindow.focus(); return false">????</a>
			-->


		</body>
</html>		
