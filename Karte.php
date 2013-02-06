﻿<!DOCTYPE html>

<html lang="de">
<head>

  <title>Air Quality Egg Muenster</title>
  <meta name="description" content="Luftqualität Muenster">
  <meta name="keywords" content="Air Quality Egg">
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=9">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="js/jquery.min.js">
</script>
  <script type="text/javascript" src="js/image_slide.js">
</script>
  <script type="text/javascript" src="overlib421/overlib.js">
</script>
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css">
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="banner">
        <div id="Willkommen">
          <h1>SeMVIS Air</h1>

          <h6>System zur Erfassung,Modellierung und Visualisierung von Luftqualitätsdaten</h6>
        </div><!--close Willkommen-->
      </div><!--close banner-->
    </div><!--close header-->

    <div id="menubar">
      <ul id="menu">
        <li>
          <a href="index.html">Startseite</a>
        </li>

        <li class="current">
          <a href="Karte.php">Karte</a>
        </li>

        <li>
          <a href="Hilfe.html">Hilfe</a>
        </li>

        <li>
          <a href="impressum.html">Impressum und Kontakt</a>
        </li>
      </ul>
    </div><!--close menubar-->

    <div id="site_content">
      <div class="sidebar_container">
        <div class="sidebar">
          <div class="sidebar_item">
            <h2>Auswahl</h2>

            <p>Ausgewählte AQEs:</p>

            <p>Nr.1:<input type="text" name="Egg1" readonly id="1" style="margin-left:5px"></p>

            <div class="delete" onclick="document.getElementById(1).value=&quot;&quot;">
              löschen
            </div>

            <p>Nr.2:<input type="text" name="Egg2" readonly id="2" style="margin-left:5px"></p>

            <div class="delete" onclick="document.getElementById(2).value=&quot;&quot;">
              löschen
            </div>

            <p>Nr.3:<input type="text" name="Egg3" readonly id="3" style="margin-left:5px"></p>

            <div class="delete" onclick="document.getElementById(3).value=&quot;&quot;">
              löschen
            </div>

            <form method="link" action="tabelle.html">
              <input type="submit" value="Tabelle">
            </form>

            <form method="link" action="Diagramm.html">
              <input type="submit" value="Diagramm">
            </form><span style="margin-left: 225px"><a class="tooltip" href="#">?<span class="classic">Erläuterungen zum Umgang mit
            der Auswahlfunktion erhalten Sie unter 'Hilfe'</span></a></span>
          </div><!--close sidebar_item-->
        </div><!--close sidebar-->

        <div class="sidebar">
          <div class="sidebar_item">
            <h2>Offizielle Werte</h2>

            <div>
              Ozon (O3): <span id="myelement"></span> [µg/m³]
            </div><br>

            <div>
              Stickstoffdioxid (NO2): <span id="myelement2"></span> [µg/m³]
            </div><br>

            <div>
              Relative Luftfeuchtigkeit: <span><?php
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
                                                                    }       ?></span>
            </div><br>

            <div>
              Lufttemperatur: <span><?php $host = "http://www.uni-muenster.de/Klima/wetter/wetter.php";
                                                                    $filestring = file_get_contents($host);
                                                                    $startpos = 0;
                                                                    while($pos = strpos($filestring, "<td class=", $startpos))
                                                                    {
                                                                    $string1 = substr($filestring, $pos, strpos($filestring, "</td>", $pos + 1) - $pos);
                                                                    if(stristr($string1, '°C')) {
                                                                    echo $string1."</br>";
                                                                    }
                                                                    $startpos = $pos + 1;
                                                                    } ?></span>
            </div><br>

            <div>
              Feinstaub PM10: <span id="myelement3"></span> [µg/m³] <span style="margin-left: 60px"><a class="tooltip" href=
              "#">?<span class="classic">Erläuterungen zu den offiziellen Werten erhalten Sie unter
              'Hilfe'</span></a></span>
            </div>
          </div>
        </div>
      </div><script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js">
</script> <?php 
                                          
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
                                                                              
                                                                              function getpm10($quelltext){
                                          $text=$quelltext;
                                          $startpos_pm10=0;
                                          $werte_pm10='';
                                          while($position_pm10 = strpos($text, '<td class="mw_pm10">', $startpos_pm10)){
                                          $pos_pm10=strpos($text,'<td class="mw_pm10">',$startpos_pm10)+20;
                                          $zeile_pm10=substr($text,$pos_pm10,strpos($text,'</td>',$pos_pm10+1)-$pos_pm10);
                                          $zeile_pm10=trim($zeile_pm10,' ');
                                          if(strlen($zeile_pm10)>0 && strlen($zeile_pm10)<5){
                                          
                                          $werte_pm10=$werte_pm10.','.$zeile_pm10;
                                          }               
                                          $startpos_pm10 = $pos_pm10 + 1;             
                                          }
                                          $werte_pm10=substr($werte_pm10,1,strlen($werte_pm10)-1);
                                          $werte_pm10=explode(',',$werte_pm10);
                                          
                                          return $werte_pm10;
                                          }
                                          
                                          
                                          $test_O3=getO3($seitenquelltext);
                                          $test_NO2=getNo2($seitenquelltext);
                                                                              $test_pm10=getpm10($seitenquelltext);
                                          
                                          
                                          for($j=0;$j<count($test_O3);$j+=1){
                                                          //echo 'O3: '.$test_O3[$j]."</br>";
                                                  }       
                                          for($j=0;$j<count($test_NO2);$j+=1){
                                                          //echo 'NO2: '.$test_NO2[$j]."</br>";
                                                  }
                                                                              for($j=0;$j<count($test_pm10);$j+=1){
                                                          //echo 'pm10: '.$test_pm10[$j]."</br>";
                                                  }
                                          
                                          
                                          /*Bei Bedarf die Werte in Integer konvertieren
                                          for($i=0;$i<count($werte);$i++){
                                                  $werte[$i]=(int)$werte[$i];
                                          }
                                          echo gettype($werte[0]);*/
                                          
                                          ?>

      <div id="map">
        <script>
                        


                        selector      = "#myelement";
                        selector2      = "#myelement2";
                                                selector3       = "#myelement3";
                        var O3= "<?php echo $test_O3[count($test_O3)-1] ?>";
                        var NO2="<?php echo $test_NO2[count($test_NO2)-1] ?>";
                                                var pm10="<?php echo $test_pm10[count($test_pm10)-1] ?>";
                        
                        $(selector).html(O3);
                        $(selector2).html(NO2);
                                                $(selector3).html(pm10);
                        
                        var icon = L.icon({
                        iconUrl: 'bilder/egg1.png',

                        iconSize:     [32, 32], // size of the icon
                        //shadowSize:   [50, 64], // size of the shadow
                        iconAnchor:   [16, 16], // point of the icon which will correspond to marker's location
                        //shadowAnchor: [4, 62],  // the same for the shadow
                        popupAnchor:  [0, -12] // point from which the popup should open relative to the iconAnchor
                        });
                        
                        //puts egg value(s) in the selectionwindow; if the selectionwindow is full an alert applies
                        function auswahlfenster () {
                        if (document.getElementById(1).value=="")
                        {
                        document.getElementById(1).value='EGG';
                        }
                                else 
                                        {
                                                if (document.getElementById(2).value=="")
                                                {
                                                document.getElementById(2).value='EGG';
                                                }
                                                        else 
                                                                {
                                                                        if (document.getElementById(3).value=="")
                                                                        {
                                                                        document.getElementById(3).value='EGG';
                                                                        }
                                                                                else
                                                                                        {
                                                                                        alert ('Die Auswahlfelder sind voll!');
                                                                                        }
                                                                }
                                                }               
                                                                                }
                        
                        var map = L.map('map').setView([51.963572, 7.613813], 13);
                                L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
                                maxZoom: 18,
                                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap<\/a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA<\/a>, Imagery ?<a href="http://cloudmade.com">CloudMade<\/a>'
                                }).addTo(map);
                                
                                function onMapClick(e) {
                                        popup
                                        /*.setLatLng(e.latlng)
                                        .setContent("You clicked the map at " + e.latlng.toString())
                                        .openOn(map); */ 
                                        } 


                                map.on('click', onMapClick);
                        
                                var marker = L.marker([51.963572, 7.613813], {icon: icon}).addTo(map);
                                                                //creates a popup
                                                                <?php $connection="host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW";
                                 pg_connect($connection);
                                                                 $result=pg_query("select * from sensor_measureddata_join where id=75759 and date= (select max(date)from \"MeasuredData\" where \"sensorId\"=75759);");
                                 $data = pg_fetch_object($result);
                                                                 $timestamp=$data->date;
                                                                 $date=substr($timestamp,0,10);
                                                                 $sub=explode("-",$date);
                                                                 $date="  ".$sub[2]."-".$sub[1]."-".$sub[0];
                                                                 $time=substr($timestamp,10,9);
                                                                 
                                        
                                                                ?>
                                                                marker.bindPopup(" <table class=\"tabelle\" style=\"width:200\"><tr ><td style=\"text-decoration:underline;font-weight:bold;\"><em>Name:<\/em><\/td><td><?php print($data->name); ?><\/td><\/tr><tr> <td ><em>Datum:<\/em><\/td>  <td><?php print($date); ?><\/td> <\/tr>  <tr> <td ><em> Uhrzeit:<\/em><\/td> <td><?php print($time); ?><\/td> <\/tr> <tr> <td> <em>Temperatur<\/em><\/td> <td><?php print($data->temperaturC."°C"); ?>  <\/td> <\/tr> <tr><td ><em> Luftfeuchtigkeit:<\/em><\/td> <td><?php print($data->humidity); ?><\/td> <\/tr> <tr> <td > <em>Kohlenmonoxid:<\/em><\/td> <td><?php print($data->CO); ?><\/td> <\/tr> <tr><td ><em>Ozon:<\/em><\/td> <td><?php print($data->ozon); ?><\/td> <\/tr> <tr><td > <em>Stickstoffdioxid:<\/em><\/td><td><?php print($data->NO2); ?> <\/td> <\/tr> <\/table> <br> <button onClick='auswahlfenster()'> Zur Auswahl hinzufügen <\/button>");
                                                                
                                
        </script> <!--Mit dem Befehl unten öffnet sich ein ganz neues Fenster!
                        <a href="Hilfe.html" target="_blank" onClick="ganzneuWindow = window.open('Hilfe.html', '500', 'resizable=no,toolbar=no,scrollbars=yes,width=70,height=60,dependent'); ganzneuWindow.focus(); return false">????</a>
                      -->
      </div><!--map-->
    </div><!--site_content-->
  </div><!---main -->
</body>
</html>
