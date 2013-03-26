<?php 
//This script contains functions to get the values from our database, for example for the popups.

//get_id_coord() returns an array including all the IDs and coordinates (latitude, longitude). 
//Not Every ID has coordinates. In those cases they got standard values (*). Longitude increases with every call by 0.004 Degrees. Thus they are not on the same place but in a row.
			function get_id_coord(){
			
			//connection to our database.
			$connection="host=giv-geosoft2c.uni-muenster.de port=5432 dbname=CosmDaten user=geosoft2 password=DZLwwxbW";
			pg_connect($connection);
		 
			
			$ids=pg_query('select id from "CosmSensor";');
			$rows = pg_num_rows($ids);
			$id_coord=array();

			$standard_latitude=51.963059;//(*)
			$standard_longitude=7.627009;//(*)
			
			for ($i=0; $i<$rows; $i++) 
			{
				$row = pg_fetch_row($ids, $i);
				$coordinates=pg_query('select latitude,longitude from "CosmSensor" where id='.$row[0].';');
				$coord = pg_fetch_row($coordinates);
				array_push($id_coord, $row[0]);
				array_push($id_coord, $coord[0]);
				array_push($id_coord, $coord[1]);
			}
			
			//As mentioned on the top, not every ID has coordinates. In the database, those entrys get a '-1' as a value for longitude or latitude.
			//Longitude increases with every call by 0.004 Degrees. 
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
			/*$array_connect='';
			for ($i=0; $i<count($id_coord); $i++) 
			{	
				$array_connect=$array_connect.','.$id_coord[$i];
			}
			$array_connect=substr($array_connect,1);*/
			
			//$id_coord contains three values for every ID: First slot: The ID itself, second slot: Latitude, third slot: Longitude; and so on...
			return $id_coord; 
			}
					
//get_values(...) needs an array with IDs, here $id_coord with an ID in every third slot.
//This function returns an array containing comma-connected values. Every slot is an own parameter and contains all the values for every ID.					
			function get_values($id_coord){

			//Initialisation of arrays for every parameter.
			$names=array();
			$dates=array();
			$times=array();
			$temperatures=array();
			$ozones=array();
			$no2s=array();
			$humiditys=array();
			$carbon_monoxide=array();
						
				for ($i=0; $i<count($id_coord); $i+=3) 
				{		
					//The following query calls up all the values for one ID. The loop increases by 3 because in every third slot we have an ID	
					$result=pg_query("select * from sensor_measureddata_join where id=".$id_coord[$i]." and date= (select max(date)from \"MeasuredData\" where \"sensorId\"=".$id_coord[$i].");");
					
					$data = pg_fetch_row($result);	
					
					//Some eggs are defect or inactive from the beginning. Those eggs do not have usefull values and only a single slot. 
					//In these cases we replace them by '-'		
					if(count($data)==1){
					array_push($names, "-");
					array_push($dates, "-");
					array_push($times, "-");
					array_push($temperatures, "-");
					array_push($ozones, "-");
					array_push($no2s, "-");
					array_push($humiditys, "-");
					array_push($carbon_monoxide, "-");		
					}
					//The query delivers an array 'data[]' containing all the values. The following lines fill the arrays with values.
					else
					{
					array_push($names, $data[1]);
					$timestamp=$data[4];
					$date=substr($timestamp,0,10);
					$sub=explode("-",$date);
					$date="  ".$sub[2]."-".$sub[1]."-".$sub[0];
					$time=substr($timestamp,10,9);
					array_push($dates, $date);
					array_push($times, $time);
					array_push($temperatures, $data[5]);
					array_push($ozones, $data[7]);
					array_push($no2s, $data[9]);
					array_push($humiditys, $data[11]);
					array_push($carbon_monoxide, $data[13]);
					}
					/*for($i=0;$i<count($data);$i++){
						echo $data[$i].'</br>';
						}*/
				}	
					/*What the following lines do:
					The problem here is the transfer to javascript. Solution: Connect the values in the arrays above with a comma as a separator. 
					Thus we get one string for every parameter.	In our case we get 8 big strings, each representing one parameter. This makes it easy to overgive 
					these values to javascript, for example: var test="<?php echo $testcase[i] ?>";. Then it can be splitted again in Javascript: 
					split_test="<?php echo $test[i] ?>".split(",");					
					*/
					$names_connect='';
					$dates_connect='';
					$times_connect='';
					$temperatures_connect='';
					$ozones_connect='';
					$no2s_connect='';
					$humiditys_connect='';
					$carbon_monoxide_connect='';
					
					$id_coord=get_id_coord();
					for($i=0;$i<count($id_coord)/3;$i++){
					$names_connect=$names_connect.','.$names[$i];
					$dates_connect=$dates_connect.','.$dates[$i];
					$times_connect=$times_connect.','.$times[$i];
					$temperatures_connect=$temperatures_connect.','.$temperatures[$i];
					$ozones_connect=$ozones_connect.','.$ozones[$i];
					$no2s_connect=$no2s_connect.','.$no2s[$i];
					$humiditys_connect=$humiditys_connect.','.$humiditys[$i];
					$carbon_monoxide_connect=$carbon_monoxide_connect.','.$carbon_monoxide[$i];							
					}
					
					//delete the first sign, here a comma.
					$names_connect=substr($names_connect,1);
					$dates_connect=substr($dates_connect,1);
					$times_connect=substr($times_connect,1);
					$temperatures_connect=substr($temperatures_connect,1);
					$ozones_connect=substr($ozones_connect,1);
					$no2s_connect=substr($no2s_connect,1);
					$humiditys_connect=substr($humiditys_connect,1);
					$carbon_monoxide_connect=substr($carbon_monoxide_connect,1);
					//echo $names_connect;
					
					//$all_values is an array containing a string for every parameter as mentioned above.
					$all_values=array();
					array_push($all_values, $names_connect);
					array_push($all_values, $dates_connect);
					array_push($all_values, $times_connect);
					array_push($all_values, $temperatures_connect);
					array_push($all_values, $ozones_connect);
					array_push($all_values, $no2s_connect);
					array_push($all_values, $humiditys_connect);
					array_push($all_values, $carbon_monoxide_connect);
					return $all_values;
					}
					
					$dies=get_id_coord();
				    $test=get_values($dies);
					echo $test[0].'</br>';
					echo $test[1].'</br>';
					echo $test[2].'</br>';
					echo $test[3].'</br>';
					echo $test[4].'</br>';
					echo $test[5].'</br>';
					echo $test[6].'</br>';							
?>