Create Database "CosmDaten" ;

/* 
table CosmSensor:
In this table the different AirQualityEggs with their attributes (id,name, position(latitude,longitude)) are stored.
*/
create table "CosmSensor"   (
id  integer primary key,
name varchar (20) /* not null*/,
longitude float  not null,
latitude float not null
);

/*
table MeasuredData:
In this table the measuring data of the AirQualityEggs are stored. All data of all AirQualityEggs are stored here.
The primary key are the variables: "date" and "sensorId". With these two every data can be definitely identified.
*/
create table "MeasuredData"(
"date"  timestamp ,
"sensorId" integer  references "CosmSensor" on delete cascade on update cascade,
"temperaturC" double precision default -99,
"temperaturC_validated" boolean,
ozon double precision  default -1,
ozon_validated boolean,
"NO2" double precision default -1,
"NO2_validated" boolean,
humidity double precision default -1,
humidity_validated boolean,
"CO" double precision default -1,
"CO_validated" boolean,
primary key ("date", "sensorId")
);
/* 
This table saves the 10 values before the first value to vadidate.
*/
CREATE TABLE "previusValues"
(
  id integer NOT NULL,
  date timestamp without time zone,
  value double precision,
  CONSTRAINT "primary_Key" PRIMARY KEY (id)
);
/*
view sensor_MeasuredData_join:
This view joins the two tables CosmSensor and MeasuredData.
*/ 
create view sensor_MeasuredData_join (id,name,latitude,longitude,"date", "temperaturC","temperaturC_validated",ozon,ozon_validated,"NO2", "NO2_validated",humidity,humidity_validated,"CO","CO_validated") as
select c.id,c.name,c.latitude,c.longitude,m."date", m."temperaturC",m."temperaturC_validated",m.ozon,m.ozon_validated,m."NO2", m."NO2_validated",m.humidity,m.humidity_validated,m."CO",m."CO_validated"  from "CosmSensor"  c inner join "MeasuredData"m on (c.id= m."sensorId");

/*
rule value_exists:
This rule prevent insertion into the columns "_validated" if the belonging column with the measured data has only the default value.

example: temperaturC= -99, temperaturC_validated=false (bzw. true) ----- not allowed
*/ 
create rule "value_exists" as on  insert to "MeasuredData" 
where ("temperaturC" =-99 and new."temperaturC_validated" is not null) or (ozon =-1 and new.ozon_validated is not null)
or( "NO2" =-1 and NEW."NO2_validated" is not null) or (humidity =-1 and new.humidity_validated is not null)
or ("CO" =-1 and "CO_validated" is not null)
do instead nothing;
/*to give the rights to the user*/
grant select, insert, delete, update on "CosmSensor" to "geosoft2";
grant select, insert, delete, update on "MeasuredData" to "geosoft2";
grant select, insert, delete, update on sensor_MeasuredData_join to "geosoft2";
grant connect on database "CosmDaten" to "geosoft2";
GRANT SELECT, UPDATE, INSERT, DELETE ON TABLE "previusValues" TO "geosoft2";

