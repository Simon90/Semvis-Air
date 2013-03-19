package Validierung;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;

/**
 * This is the main class for the validation.
 * 
 * @author Jana
 * 
 */
public class Validation {

	/**
	 * This method starts the validation
	 */
	public static void validate() {

		String url = "jdbc:postgresql://giv-geosoft2c.uni-muenster.de/CosmDaten";
		Connection conn;
		try {
			conn = DriverManager.getConnection(url, "geosoft2", "DZLwwxbW");
			Statement st = conn.createStatement();
			// selects all ids of the air quality eggs
			ResultSet r = st.executeQuery("select id from \"CosmSensor\";");
			// for all air quality eggs (ids) the different measured data are validated
			while (r.next()) {
				ArrayList list = Database.getWerte(conn, r.getInt(1), "humidity", 60);
				if (list.size() != 0) {
					RunningWindow.validate(conn, list, 100, 20);
				}
				list = Database.getWerte(conn, r.getInt(1), "temperaturC", 60);
				if (list.size() != 0) {
					RunningWindow.validate(conn, list, 60, 2);
				}
				list = Database.getWerte(conn, r.getInt(1), "ozon", 60);
				if (list.size() != 0) {
					RunningWindow.validate(conn, list, 60, 2);
				}
				list = Database.getWerte(conn, r.getInt(1), "NO2", 60);
				if (list.size() != 0) {
					RunningWindow.validate(conn, list, 60, 2);
				}
				list = Database.getWerte(conn, r.getInt(1), "CO", 60);
				if (list.size() != 0) {
					RunningWindow.validate(conn, list, 60, 2);
				}
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

	/**
	 * only for testing now
	 * 
	 * @param args
	 */
	public static void main(String[] args) {
		// TODO Auto-generated method stub
		Database.deleteOldData();
		Validation.validate();
	}

}
