<!DOCTYPE html>
<!--This script contains our map based upon OpenStreetMap. Official values from LANUV, AG Klimatologie, Popups containing values, 
linkages to the help-site, the table and the diagram.  -->

<html lang="de">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8">
  <title>Air Quality Egg Muenster</title>
  <link rel="shortcut icon" href="bilder/egg1.png" type="image/x-icon" />
  <meta name="description" content="Luftqualitätsdaten Münster">
  <meta name="keywords" content="Air Quality Egg">
  <meta name="viewport" content="target-densitydpi=480px, width=1000px, user-scalable=yes">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"><!--Order IE to use the best available compatibility-Microsoft Standard-->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="overlib421/overlib.js"></script>
  <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.4/leaflet.css">
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="banner">
        <div id="Willkommen">
          <h1>Semvis Air</h1>
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
          <a href="Impressum.html">Impressum und Kontakt</a>
        </li>
      </ul>
    </div><!--close menubar-->

    <div id="site_content">
      <div class="sidebar_container">
        <div class="sidebar">
          <div class="sidebar_item">
            <h2>Auswahl</h2>
            <p><b>Ausgewählte AQEs:</b></p>
            <p>Egg 1:<input type="text" name="Egg1" readonly id="1" style="margin-left:5px"></p>
            <div class="delete" onclick="loeschen(1);">
              löschen
            </div><!--close delete-button-->
            <p>Egg 2:<input type="text" name="Egg2" readonly id="2" style="margin-left:5px"></p>
            <div class="delete" onclick="loeschen(2);">
              löschen
            </div><!--close delete-button-->
            <p>Egg 3:<input type="text" name="Egg3" readonly id="3" style="margin-left:5px"></p>
            <div class="delete" onclick="loeschen(3);">
              löschen
            </div><!--close delete-button-->
			<input type="button" name="Tabelle" value="Tabelle" id="4" onclick="linkgenerieren(4)">
			<input type="button" name="Diagramm" value="Diagramm" id="5" onclick="linkgenerieren(5)">

            <span style="margin-left: 225px"><a class="tooltip" href="#">?<span class="classic">Erläuterungen zum Umgang mit
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
                                                      }       
                                              ?></span>
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
                                                      } 
                                              ?></span>
            </div><br>

            <div>
              Feinstaub PM10: <span id="myelement3"></span> [µg/m³] <span style="margin-left: 60px"><a class="tooltip" href="#">?<span class="classic">
			  Erläuterungen zu den offiziellen Werten erhalten Sie unter 'Hilfe'</span></a></span>
            </div>
          </div><!--close-sidebar_item-->
        </div><!--close-sidebar-->	
      </div><!--close-sidebar_container-->
	  
	  <script src="http://cdn.leafletjs.com/leaflet-0.4/leaflet.js"></script> 
	  <?php 
                                                
                //Parses the LANUV-page to get the official values for NO2, O3 and pm10
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
						var all_eggs = new Array();
						var selected_eggs = ["","",""];
						
                        //following code with the help of the Cosm library and Leaflet.


                        selector      = "#myelement";
                        selector2     = "#myelement2";
                        selector3     = "#myelement3";
                        
                        //copy of arrays from php to javascript.
                        var O3= "<?php echo $test_O3[count($test_O3)-1] ?>";
                        var NO2="<?php echo $test_NO2[count($test_NO2)-1] ?>";
                        var pm10="<?php echo $test_pm10[count($test_pm10)-1] ?>";
                        
                        $(selector).html(O3);
                        $(selector2).html(NO2);
                        $(selector3).html(pm10);
                        
                        //Leaflet
                        var icon = L.icon({
                        iconUrl: 'bilder/egg1.png',

                        iconSize:     [32, 32], // size of the icon
                        //shadowSize:   [50, 64], // size of the shadow
                        iconAnchor:   [16, 16], // point of the icon which will correspond to marker's location
                        //shadowAnchor: [4, 62],  // the same for the shadow
                        popupAnchor:  [0, -12] // point from which the popup should open relative to the iconAnchor
                        });
                        
                    
                        //Leaflet
                        var map = L.map('map').setView([51.963572, 7.613813], 13);
                                        L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
                                        maxZoom: 18,
                                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap<\/a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA<\/a>, Imagery ?<a href="http://cloudmade.com">CloudMade<\/a>'
                                        }).addTo(map);
                                        
                                        //Not important
                                        function onMapClick(e) {
                                                        popup
                                                        /*.setLatLng(e.latlng)
                                                        .setContent("You clicked the map at " + e.latlng.toString())
                                                        .openOn(map); */ 
                                                        } 


                                        map.on('click', onMapClick);
                                        
        <?php 
        include('pg_value_functions.php');
                                                
                                                //Connection to our database
                                                $connection="host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW";
                                                pg_connect($connection);
                                         
                                                //Getting all the Ids
                                                $ids=pg_query('select id from "CosmSensor";');
                                                $rows = pg_num_rows($ids);
                                                
                                                //$id_coord is an array and will be filled with the help of the loop thereunder.
                                                $id_coord=array();
                                                
                                                //Fills id_coord with IDs, latitude and longitude.
                                                for ($i=0; $i<$rows; $i++) 
                                                {
                                                        $row = pg_fetch_row($ids, $i);
                                                        $coordinates=pg_query('select latitude,longitude from "CosmSensor" where id='.$row[0].';');
                                                        $coord = pg_fetch_row($coordinates);
                                                        array_push($id_coord, $row[0]);
                                                        array_push($id_coord, $coord[0]);
                                                        array_push($id_coord, $coord[1]);
                                                }
                                                
                                                //standard values for IDs without coordinates, in the database with the value -1.
                                                $standard_latitude=51.963059;
                                                $standard_longitude=7.627009;
                                                for($i=1;$i<count($id_coord)-1; $i+=3){
                                                        if($id_coord[$i]==-1)
                                                                {
                                                                $id_coord[$i]=$standard_latitude;
                                                                }
                                                        if($id_coord[$i+1]==-1)
                                                                {$id_coord[$i+1]=$standard_longitude;
                                                                $standard_longitude=$standard_longitude+0.004;
                                                                }
                                                        }
                                                
                                                //$array_connect will contain a string so it ist easy to overgive it to javascript              
                                                $array_connect='';
                                                for ($i=0; $i<count($id_coord); $i++) 
                                                {       
                                                        $array_connect=$array_connect.','.$id_coord[$i];
                                                }
                                                $array_connect=substr($array_connect,1);
                                                
                                                /*
                                                $result=pg_query("select * from sensor_measureddata_join where id=75759 and date= (select max(date)from \"MeasuredData\" where \"sensorId\"=75759);");
                                                $data = pg_fetch_object($result);
                                                $timestamp=$data->date;
                                                $date=substr($timestamp,0,10);
                                                $sub=explode("-",$date);
                                                $date="  ".$sub[2]."-".$sub[1]."-".$sub[0];
                                                $time=substr($timestamp,10,9);*/
                                                 
                                                //The following line retrieves all values from every parameter for every ID, $all_values is an array. See pg_value_functions.
                                                $all_values=get_values(get_id_coord());
                                                $anzahl=count($all_values);
        ?>                                                                      

                                                //$all_values is an array. In every slot is a string with the values for one parameter. The following lines split these
                                                //strings so they can be used for the popups.
                                                var split_names="<?php echo $all_values[0] ?>".split(",");
                                                split_dates="<?php echo $all_values[1] ?>".split(",");
                                                split_times="<?php echo $all_values[2] ?>".split(",");
                                                split_temperatures="<?php echo $all_values[3] ?>".split(",");
                                                split_ozones="<?php echo $all_values[4] ?>".split(",");
                                                split_no2s="<?php echo $all_values[5] ?>".split(",");
                                                split_humiditys="<?php echo $all_values[6] ?>".split(",");
                                                split_carbon_monoxide="<?php echo $all_values[7] ?>".split(",");
                                                
                                                //Same as above.
                                                var id_coord="<?php echo $array_connect ?>";
                                                id_coord=id_coord.split(",");
                                                
                                                //The following loop creates a marker and binds a popup for every ID. 
												var egg;
												for(var i=0;i<split_names.length;i++) {
													var j = i*3;
													egg = new teg(id_coord[j],split_names[i],id_coord[j+1],id_coord[j+2]);
													var marker = L.marker([egg.x_coordinate,egg.y_coordinate], {icon: icon}).addTo(map);
													marker.bindPopup(" <table class=\"tabelle\" style=\"width:200\"><tr ><td style=\"text-decoration:underline;font-weight:bold;\"><em>Name:<\/em><\/td><td>"+egg.name+"<\/td><\/tr><tr> <td ><em>Datum:<\/em><\/td>  <td>"+split_dates[i]+"<\/td> <\/tr>  <tr> <td ><em> Uhrzeit:<\/em><\/td> <td>"+split_times[i]+" CET<\/td> <\/tr> <tr> <td> <em>Temperatur<\/em><\/td> <td>"+split_temperatures[i]+" °C<\/td> <\/tr> <tr><td ><em> Luftfeuchtigkeit:<\/em><\/td> <td>"+split_humiditys[i]+" %<\/td> <\/tr> <tr> <td > <em>Kohlenmonoxid:<\/em><\/td> <td>"+Math.round(split_carbon_monoxide[i]*28.01/27)+" mg/m³<\/td> <\/tr> <tr><td ><em>Ozon:<\/em><\/td> <td>"+Math.round(split_ozones[i]*48.01/27)+" mg/m³<\/td> <\/tr> <tr><td > <em>Stickstoffdioxid:<\/em><\/td><td>"+Math.round(split_no2s[i]*46.01/27)+" mg/m³<\/td> <\/tr> <\/table> <br> <button onClick='auswahlfenster(\""+i+"\")'> Zur Auswahl hinzufügen <\/button>");
													all_eggs[i] = egg;
												}
												
						//creates a link for the table/diagram and commited the id's of the selected eggs with the URL						
						function linkgenerieren (id) {
						if (id == "4")
						{
						var newURL         = "tabelle.html?";
						}
						else
						{
						var newURL = "Diagramm.html?";
						}
						for (var i = 0; i < 3; i++) {
							if(selected_eggs[i] != "") {
								newURL = newURL + "id"+(i+1)+"="+selected_eggs[i].id+"&";
							}
						}
						newURL = newURL.substring(0,newURL.length-1);
						location.href = newURL;
						}
						
						
						//deletes the egg name in the selectionwindow and selected eggs array
						function loeschen (id) {
						document.getElementById(id).value="";
						selected_eggs[id-1]="";
						}
						
						//puts egg name in the selectionwindow; if the selectionwindow is full an alert applies
                        function auswahlfenster (en) {
							var addedAt = addEgg(en);
							if(addedAt > 0) {
								var myEgg = all_eggs[en];
								var name = myEgg.name;
								document.getElementById(addedAt).value=name;
							} else {
								alert ('Die Auswahlfelder sind voll!');
							}                          
                        }

						//initialize an egg with id, name and coordinates
						function teg(id, name,x_coordinate, y_coordinate) {
							this.id = id;
							this.name = name;
							this.x_coordinate = x_coordinate;
							this.y_coordinate = y_coordinate;
						}
						
						//add the egg to the selected eggs with his position in the selectionwindow
						function addEgg(en) {
							var myegg = all_eggs[en];
							var position = 0;
							for(var i = 0; i < 3; i++) {
								if(selected_eggs[i] == "") {
									selected_eggs[i] = myegg;
									position = i+1;
									break;
								}
							}
							return position;
						}
        </script> <!--Mit dem Befehl unten öffnet sich ein ganz neues Fenster!
                        <a href="Hilfe.html" target="_blank" onClick="ganzneuWindow = window.open('Hilfe.html', '500', 'resizable=no,toolbar=no,scrollbars=yes,width=70,height=60,dependent'); ganzneuWindow.focus(); return false">????</a>
                      -->
        </div><!--map-->
    </div><!--site_content-->
  </div><!---main -->
</body>
</html>
