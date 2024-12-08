package application.model;

/**
 * Classe pour gérer les données des capteurs.
 * @autor Thomas
 */
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

    public void setRoom(String room) {
        this.room = room;
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type;
    }

    public double getValue() {
        return value;
    }

    public void setValue(double value) {
        this.value = value;
    }
}