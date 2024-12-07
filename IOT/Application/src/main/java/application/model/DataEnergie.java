package application.model;

public class DataEnergie {
    private final String type = "Energie";
    private String date;
    private double value;

    public DataEnergie(String _date, double _double){
        date = _date;
        value = _double;
    }
    public String getType() {
        return type;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public double getValue() {
        return value;
    }

    public void setValue(double value) {
        this.value = value;
    }
}
