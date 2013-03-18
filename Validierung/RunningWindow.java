package Validierung;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Arrays;

/**
 * @author Jana Klemm, Simon Schoemaker
 */

/**
 * Class to detect outlier with the help of a list full with values. The used
 * method is running median with a windowsize of 61, which represents the data
 * intervall of an hour.
 */

public class RunningWindow {

	public static void validate(Connection conn, ArrayList list, int ws,double sigma) {
		RunningWindow w = new RunningWindow();

		double[] result = w.runMed(list, ws);
		if (result != null) {
			for (int j = 0; j < result.length; j++) {
				// System.out.append(",result"+j+":"+result[j]);
			}
			w.berechneAusreiﬂer(conn, list, result, ws, sigma);
		}
	}

	/**
	 * method to calculate the smoothed median for each value with the help of the windowsize
	 * @param list
	 * @param ws windowsize
	 * @return
	 */
	
	private double[] runMed(ArrayList list, int ws) {
		int aktuell = (ws / 2);
		int letzter = list.size() - (ws / 2);
		if (letzter > 0) {
			double[] result = new double[list.size() - ws];

			for (int i = 0; i < result.length; i++) {
				if (aktuell < letzter) {
					ArrayList werteIntervall = copyOf(list, aktuell
							- (int) (ws / 2), aktuell + (int) ((ws) / 2));
					result[i] = med(werteIntervall);
					aktuell++;
				}

			}
			return result;
		}
		return null;

	}

	// method, which creates from a part of an existing array a new array
	private ArrayList copyOf(ArrayList array, int a, int b) {
		ArrayList newarray = new ArrayList();
		int j = a;
		for (int i = 0; i < (b - a) + 1; i++) {
			newarray.add(array.get(i));
			j++;
		}
		return newarray;
	}

	/**
	 * method, which sorts the list and returns the middle of the sorted list, the median
	 * @param list
	 * @return
	 */
	
	private double med(ArrayList list) {
		double[] werteIntervall = new double[list.size()];
		Measurement measurement;
		for (int i = 0; i < list.size(); i++) {
			measurement = (Measurement) list.get(i);
			werteIntervall[i] = measurement.getData();
		}
		Arrays.sort(werteIntervall);
		int mitte = (int) (werteIntervall.length / 2);
		return werteIntervall[mitte];
	}

	/**
	 * method to calculate the p-quantil of the list
	 * @param list
	 * @param p p-quantil
	 * @return
	 */
	
	private double quantil(ArrayList list, double p) {
		double[] n = new double[list.size()];
		Measurement measurement;
		for (int i = 0; i < list.size(); i++) {
			measurement = (Measurement) list.get(i);
			n[i] = measurement.getData();
		}
		Arrays.sort(n);
		for (int i = 0; i < n.length; i++)
			;
		// System.out.println(n[i]);
		double q;
		double b = n.length * p;
		// System.out.println("b:"+(b-(int)b));

		//determines, if b is an integer and calculates the quartil (q) of the list
		if ((b - (int) b) == 0) {
			q = 0.5 * (n[(int) b - 1] + n[(int) (b)]);
		} else {
			q = n[(int) b];
		}
		return q;
	}

	/**
	 * method to calculate the interquartile range (IQR)
	 * @param n
	 * @return
	 */
	
	private double berechneIR(ArrayList n) {
		return Math.abs(quantil(n, 0.75) - quantil(n, 0.25));

	}

	/**
	 * method, which detect if the value is an outlier ((< 1.4*ir) || (>1.4*ir))
	 * @param conn
	 * @param list
	 * @param runmed
	 * @param ws windowsize
	 */
	
	private void berechneAusreiﬂer(Connection conn, ArrayList list,
			double[] runmed, int ws,double sigma) {
		int k = (ws / 2);
		int index = 0;
		int indexWerte = k;
		try {
			Statement st = conn.createStatement();
			while (index < (list.size() - ws)) {
				ArrayList window = this.copyOf(list, (indexWerte - k),
						(indexWerte + k));
				double ir = berechneIR(window);
				// System.out.println("ir:"+ir+"  window[2]:"+window[2]);
				Measurement measurement = (Measurement) list.get(indexWerte);
				if (measurement.getData() < (runmed[index] - (sigma * ir))
						|| measurement.getData() > (runmed[index] + (sigma * ir))) {
					System.out.println("index:" + indexWerte + " wert:"
							+ measurement.getData());
					st.execute("update \"MeasuredData\" set "
							+ measurement.getMeasured()
							+ "_validated=true where date= '"
							+ measurement.getTimestamp() + "';");
				} else {
					st.execute("update \"MeasuredData\" set "
							+ measurement.getMeasured()
							+ "_validated=false where date= '"
							+ measurement.getTimestamp() + "';");
				}
				indexWerte++;
				index++;
			}
		}
		// exception
		catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}
}
