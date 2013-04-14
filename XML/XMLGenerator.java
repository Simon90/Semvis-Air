package XML;

import java.io.File;
import java.io.OutputStream;
import java.io.StringWriter;
import java.math.BigDecimal;
import java.math.BigInteger;
import java.net.ContentHandler;
import java.net.MalformedURLException;
import java.net.URI;
import java.net.URL;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.List;

import javax.jws.WebMethod;
import javax.jws.WebService;
import javax.jws.soap.SOAPBinding;
import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.PathParam;
import javax.ws.rs.Produces;
import javax.ws.rs.core.MediaType;
import javax.xml.XMLConstants;
import javax.xml.bind.JAXB;
import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import javax.xml.datatype.DatatypeConfigurationException;
import javax.xml.datatype.DatatypeConstants;
import javax.xml.datatype.DatatypeFactory;
import javax.xml.datatype.XMLGregorianCalendar;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.Result;
import javax.xml.validation.Schema;
import javax.xml.validation.SchemaFactory;

import org.w3c.dom.Document;
import org.xml.sax.SAXException;

/**
 * This class generates xml files from the data of the measurements.
 * 
 * @author Jana
 * 
 */

@Path("/XML")
public class XMLGenerator {
	public static void main(String[] args) throws JAXBException, MalformedURLException, SAXException, DatatypeConfigurationException {
		
		XMLGenerator xml=new XMLGenerator();
		String s=xml.parseToXML(75842, "humidity","2013-04-12 0:0:0","2013-04-12 24:0:0");
	System.out.println(s);
	}
	/**
	 * This method parses the data of a measurement (humidity,temperature,...)
	 * to an xml file.
	 * 
	 * @param sensorId
	 *            id of the air quality egg
	 * @param measurementtype
	 *            type of the measurement (humidity, temperature, ozon, co, no2)
	 * @param start
	 *            time of the earliest measurement
	 * @param end
	 *            time of the latest measurement
	 */
	@GET
	@Path("/{sensorId}/{measurementtype}/{start}/{end}")
	@Produces(MediaType.TEXT_XML)
	public String parseToXML(@PathParam("sensorId") int sensorId,
			@PathParam("measurementtype") String measurementtype,
			@PathParam("start") String start, @PathParam("end") String end) {
		// In this paragraph the objects are initialized and already known
		// variables are defined.
		String string = null;
		MeasurementTable table = new MeasurementTable();
		Measurements measurements = new Measurements();
		List measurementList = measurements.getMeasurement();
		table.setId(BigInteger.valueOf(sensorId));
		table.setMeasurementtype(measurementtype);
		table.setStart(getXMLGregorianCalendar(start));
		table.setEnd(getXMLGregorianCalendar(end));
		table.setUnit(setUnit(measurementtype));

		String url = "jdbc:postgresql://giv-geosoft2c.uni-muenster.de/CosmDaten";
		try {
			Class.forName("org.postgresql.Driver");
			Connection conn = DriverManager.getConnection(url, "geosoft2",
					"DZLwwxbW");
			Statement st = conn.createStatement();
			// The additional information about the sensor are got from the
			// database.
			ResultSet r = st
					.executeQuery("select * from \"CosmSensor\" where id="
							+ sensorId);
			if (r.next()) {
				String name = r.getString("name");
				table.setName(name);
				BigDecimal latitude = BigDecimal.valueOf(r.getDouble("latitude"));
				table.setLatitude(latitude);
				BigDecimal longitude = BigDecimal
						.valueOf(r.getDouble("longitude"));
				table.setLongitude(longitude);

			}
			// Here all the measurements between the start and the end date are
			// selected from the database
			// and saved in the object "measurementList".
			r = st.executeQuery("select date," + measurementtype + ","
					+ measurementtype
					+ "_validated from \"MeasuredData\" where \"sensorId\"="
					+ sensorId + "and date<'" + end + "' and date>'" + start
					+ "' order by date;");
			while (r.next()) {
				Measurement measurement = new Measurement();
				XMLGregorianCalendar date = getXMLGregorianCalendar(r
						.getString("date"));
				measurement.setTimestamp(date);
				BigDecimal data = BigDecimal.valueOf(r.getDouble(2));
				measurement.setData(data);
				measurement.setOutlier(r.getBoolean(3));
				measurementList.add(measurement);
			}

			table.setMeasurements(measurements);
			conn.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		// Now all the objects are translated into a XML schema.
		// It will be validated with the file measurements.xsd and saved as a
		// String.
		try {
			JAXBContext context = JAXBContext.newInstance("XML");
			Marshaller marshaller = context.createMarshaller();
			SchemaFactory schemaFactory = SchemaFactory
					.newInstance(XMLConstants.W3C_XML_SCHEMA_NS_URI);
			//Schema schema = schemaFactory.newSchema((getClass()
			//		.getResource("/measurements.xsd")));
			//marshaller.setSchema(schema);
			marshaller.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, true);
			StringWriter w = new StringWriter();
			marshaller.marshal(table, w);
			string = w.toString();
		} catch (JAXBException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} //catch (SAXException e) {
			// TODO Auto-generated catch block
		//	e.printStackTrace();
		//}
		return string;
	}

	/**
	 * This method converts the timestamp of the measurements into the data type
	 * "XMLGregorianCalendar"
	 * 
	 * @param dateString
	 *            timestamp of the measurement
	 * @return converted timestamp
	 */
	private static XMLGregorianCalendar getXMLGregorianCalendar(
			String dateString) {
		GregorianCalendar calendar = new GregorianCalendar();
		XMLGregorianCalendar xmlcalendar = null;
		// Splits the input date into day, month, year,hours and minutes.
		String[] split = dateString.split(" ");
		String[] date = split[0].split("-");
		String[] time = split[1].split(":");
		// The values are adopted by the new data type.
		calendar.set(Integer.parseInt(date[0]), Integer.parseInt(date[1])-1,
				Integer.parseInt(date[2]), Integer.parseInt(time[0]),
				Integer.parseInt(time[1]));
		try {
			xmlcalendar = DatatypeFactory.newInstance()
					.newXMLGregorianCalendar(calendar);

		} catch (DatatypeConfigurationException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return xmlcalendar;
	}

	/**
	 * This method get the measurement type and returns the corresponding unit
	 * of it.
	 * 
	 * @param measurementtype
	 *            : expected is the String humidity|ozon|no2|co|temperature
	 * @return The unit of the measurement type are returned. If the input is
	 *         not one of the expected values, then it returns null.
	 */
	private static String setUnit(String measurementtype) {
		if (measurementtype.equals("humidity")) {
			return "%";
		}
		if (measurementtype.equals("ozon")) {
			return "ppb";
		}
		if (measurementtype.equals("no2")) {
			return "ppb";
		}
		if (measurementtype.equals("temperature")) {
			return "°C";
		}
		if (measurementtype.equals("co")) {
			return "ppb";
		}
		return null;
	}
}
