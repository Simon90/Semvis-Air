package Validierung;

import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.Properties;

/**
 * This class provides methods which get or change the data from the database.
 * 
 * @author Jana
 * 
 */
public class Database {

	/**
	 * This method creates a list of all measurements which are needed to validate the measurements of one air quality
	 * egg.
	 * 
	 * @param conn connection to the database
	 * @param sensorid id of the air quality egg
	 * @param measured measured type of data (for example: humidity, CO, NO2, ozon, temperature)
	 * @param ws window size for validation
	 * @return a list of all measurements needed for the validation
	 */
	public static ArrayList<Measurement> getWerte(Connection conn, int sensorid, String measured, int ws) {
		ArrayList<Measurement> list = new ArrayList<Measurement>();
		String firsttimestamp;

		try {
			String date;
			Statement st = conn.createStatement();
			// selects the oldest measured data
			ResultSet r = st.executeQuery("select date from  \"MeasuredData\" where \"sensorId\"=" + sensorid
					+ " order by date;");
			for (int i = 1; i <= (ws / 2); i++) {
				r.next();
			}
			// because the first (ws/2) values can't be validated they will
			// always be null. They should not be
			// considered. The search should start after them. If there are less
			// than ws/2 data in the database the value
			// is set to a default value.
			if (r.next() == true) {
				date = "'" + r.getString(1) + "'";
			} else {
				date = "'2010-02-22 15:30:39.861065'";
			}
			// searches the oldest data set which has has not been validated yet
			// and saves it in "firsttimestamp".
			r = st.executeQuery("select min(date) from  \"MeasuredData\" where \"sensorId\"=" + sensorid
					+ " and date>=" + date + " and \"" + measured + "_validated\" is null ;");
			if (r.next()) {
				firsttimestamp = r.getString(1);
				if (firsttimestamp != null) {
					firsttimestamp = "'" + firsttimestamp + "'";
					// deletes all values in the table "previusValues". After
					// that the sequence from 1 to (ws/2) will be
					// insert into the column "id"
					st.execute("delete from \"previusValues\"");
					for (int j = 1; j <= (ws / 2); j++) {
						st.execute("insert into \"previusValues\" (id)values (" + j + ");");
					}
					// saves the (ws/2) values which are measured before the
					// data to validate
					last30Values(conn, measured, firsttimestamp, sensorid, 1, ws);
					// gets the values of the table and saves them in the list
					r = st.executeQuery("select * from \"previusValues\" where date is not null order by id  desc;");
					while (r.next()) {
						Measurement m = new Measurement(r.getString("date"), r.getDouble("value"), measured);
						list.add(m);
					}
					// gets all values which are later measured than the
					// "firstvalue" and saves them in the list
					r = st.executeQuery("select date, \"" + measured + "\" from  \"MeasuredData\" where \"sensorId\"="
							+ sensorid + " and date>=" + firsttimestamp + " order by date;");
					while (r.next()) {
						Measurement m = new Measurement(r.getString("date"), r.getDouble(2), measured);
						list.add(m);
					}
				}
			}

		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return list;

	}

	/**
	 * This method saves (ws/2) measured data before the first value to validate.
	 * 
	 * @param conn connection of the database
	 * @param measured type of data (for example: humidity, CO, NO2, ozon,temperature)
	 * @param timestamp date of the measurement
	 * @param sensorid id of the air quality egg
	 * @param i value to count for the recursive function. Always use 0!
	 * @throws SQLException
	 */
	private static void last30Values(Connection conn, String measured, String timestamp, int sensorid, int i, int ws)
			throws SQLException {
		Statement st = conn.createStatement();

		if (i <= (ws / 2)) {
			st = conn.createStatement();
			// gets the latest measurement before the actual measurement and
			// saves it in the table "previusValues"
			st.execute("with p AS(select max(date)as datum from \"MeasuredData\" where \"sensorId\"=" + sensorid
					+ " and date <" + timestamp + ")update \"previusValues\" set value= (select \"" + measured
					+ "\" from \"MeasuredData\", p where \"sensorId\"=" + sensorid
					+ " and  date=p.datum), date=p.datum from p where id=" + i + ";");
			// Selects the latest date before the actual date. This is the value
			// for the recursive function.
			ResultSet r = st.executeQuery("select date from \"MeasuredData\" where \"sensorId\"=" + sensorid
					+ " and date= (select max(date) from \"MeasuredData\" where \"sensorId\"=" + sensorid
					+ " and date <" + timestamp + ");");
			if (r.next()) {
				timestamp = "'" + r.getString(1) + "'";
			}
			i++;
			last30Values(conn, measured, timestamp, sensorid, i, ws);

		}
	}

	/**
	 * This method deletes all data from the table which are older than 7 days.
	 */
	public static void deleteOldData() {
		Connection conn;
		try {
			String url = "jdbc:postgresql://giv-geosoft2c.uni-muenster.de/CosmDaten";
			conn = DriverManager.getConnection(url, "geosoft2", "DZLwwxbW");
			Statement st = conn.createStatement();
			st.execute("delete from \"MeasuredData\" where date<(Current_Timestamp- interval'7 days');");
			conn.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

}
