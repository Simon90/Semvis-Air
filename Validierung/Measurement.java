package Validierung;
/**
 * This class saves measurement data. The objects of this class are added in the ArrayList. This list saves the 
 * measurements that are used for the validation. 
 * @author Jana
 *
 */
class Measurement{
	String timestamp;
	double data;
	String measured;
	
	Measurement(String timestamp, double data,String measured){
		this.timestamp=timestamp;
		this.data=data;
		this.measured=measured;
	}
	public String getMeasured() {
		return measured;
	}
	public void setMeasured(String measured) {
		this.measured = measured;
	}
	public String getTimestamp() {
		return timestamp;
	}
	public void setTimestamp(String timestamp) {
		this.timestamp = timestamp;
	}
	public double getData() {
		return data;
	}
	public void setData(double data) {
		this.data = data;
	}
	
}
