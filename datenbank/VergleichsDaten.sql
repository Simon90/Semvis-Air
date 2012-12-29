
create database "Vergleichsdaten";

/*
table WetterDienst:
This table is analog to the table "CosmSensor" and stores also the attributes( id,name,position).
Additionaly in the column "sensoren" there is stored the information which sensors for which measurements this source has.
In the column "validated" there can be stored the information if the measurement data are validated.
More information about the source can be stored in the column "beschreibung".
In the column "bereitgestelltVon" there is stored name of the provider of the measurement data.
*/

create table "WetterDienst" (
id integer primary key,
name char (40) not null,
longitude float not null,
latitude float not null,
sensoren varchar (200) not null,
validated boolean,
bereitgestelltVon varchar(50),
beschreibung varchar (300)
);

/*
more tables:
The following tables all have the same structure. They contain the date of the measurement, the measurement data, the information
if the measurement could be an outlier and the primary key values of the table "WetterDienst".
*/
create table temperatur (
"date" timestamp,
"temperaturC" float  not null,
"temperaturC_validated" boolean ,
"wetterDienstId" integer references "WetterDienst" on delete cascade on update cascade,
primary key ("date", "wetterDienstId")
);
create table ozon (
"date" timestamp,
"ozon" float not null,
"ozon_validated" boolean ,
"wetterDienstId" integer references "WetterDienst" on delete cascade on update cascade,
primary key ("date", "wetterDienstId")
);

create table humidity (
"date" timestamp,
"humidity" float not null,
"humidity_validated" boolean ,
"wetterDienstId" integer references "WetterDienst" on delete cascade on update cascade,
primary key ("date", "wetterDienstId")
);

create table no2(
"date" timestamp,
"NO2" float not null,
"NO2_validated" boolean ,
"wetterDienstId" integer references "WetterDienst" on delete cascade on update cascade,
primary key ("date", "wetterDienstId")
);
create table co (
"date" timestamp,
"CO" float not null,
"CO_validated" boolean ,
"wetterDienstId" integer references "WetterDienst" on delete cascade on update cascade,
primary key ("date", "wetterDienstId")
);
/*to give the rights to the user*/
grant select, insert, delete, update on "WetterDienst" to "geosoft2";
grant select, insert, delete, update on "temperatur" to "geosoft2";
grant select, insert, delete, update on "ozon" to "geosoft2";
grant select, insert, delete, update on "humidity" to "geosoft2";
grant select, insert, delete, update on "no2" to "geosoft2";
grant select, insert, delete, update on "co" to "geosoft2";
grant connect on database "Vergleichsdaten" to "geosoft2";

/* 
------------------------------------------------------------
views: they have to be customized for the different measurement sources.

*/

create view "lanuv_MessDaten" as

with sensor_temperatur ( id, "date", "temperaturC", "temperaturC_validated")as
(select w.id, t."date",t."temperaturC",t."temperaturC_validated" from ("WetterDienst" w inner join temperatur t on w.id=t."wetterDienstId")
where w.id=1),

sensor_ozon  ( id, "date", "ozon", "ozon_validated")as
(select w.id, o."date",o."ozon",o."ozon_validated"  from ("WetterDienst" w inner join ozon o on w.id=o."wetterDienstId")
where w.id=1),

sensor_no2 ( id, "date", "NO2", "NO2_validated")as
(select w.id, n."date",n."NO2",n."NO2_validated" from ("WetterDienst" w inner join no2 n on w.id=n."wetterDienstId")
where w.id=1),

 sensor_co ( id, "date", "CO", "CO_validated")as
(select w.id, c."date",c."CO",c."CO_validated"  from ("WetterDienst" w inner join  co c on w.id=c."wetterDienstId")
where w.id=1),

 sensor_humidity ( id, "date", "humidity", "humidity_validated") as
(select w.id, h."date",h."humidity",h."humidity_validated" from ("WetterDienst" w inner join humidity h on w.id=h."wetterDienstId")
where w.id=1)
select * from ((( sensor_temperatur natural inner join sensor_ozon) natural inner join sensor_humidity) natural inner join sensor_no2) natural inner join sensor_co;

grant select, insert, delete, update on "lanuv_MessDaten" to "geosoft2";

