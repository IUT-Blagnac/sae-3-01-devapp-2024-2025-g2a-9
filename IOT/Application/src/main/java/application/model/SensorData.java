package application.model;

public class SensorData {
    private String room;
    private String type;
    private double value;

    public SensorData(String room, String type, double value) {
        this.room = room;
        this.type = type;
        this.value = value;
    }

    public String getRoom() {
        return room;
    }

    public String getType() {
        return type;
    }

    public double getValue() {
        return value;
    }
}