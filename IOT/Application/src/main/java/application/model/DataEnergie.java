package application.model;

import java.util.Date;

public class DataEnergie {
    private final String type = "Energie";
    private Date date;
    private double value;

    public DataEnergie(Date _date, double _double){
        date = _date;
        value = _double;
    }
    public String getType() {
        return type;
    }

    public Date getDate() {
        return date;
    }

    public void setDate(Date date) {
        this.date = date;
    }

    public double getValue() {
        return value;
    }

    public void setValue(double value) {
        this.value = value;
    }
}
