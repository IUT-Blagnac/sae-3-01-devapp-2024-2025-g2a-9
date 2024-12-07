package application.view;

import application.control.CapteursController;
import application.model.SensorData;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.NumberAxis;
import javafx.scene.control.Button;
import javafx.scene.control.MenuItem;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TabPane;
import javafx.scene.control.Tab;
import javafx.scene.control.ScrollPane;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.chart.XYChart;

import java.io.Reader;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

public class CapteursViewController {

    // Contrôleur de Dialogue associé à CapteursController
    private CapteursController cDialogController;

    // Fenêtre physique où est la scène contenant le fichier XML contrôlé par this
    private Stage cStage;

    // Eléments FXML
    @FXML
    private TabPane tabPane;

    @FXML
    private Tab realTimeTab;

    @FXML
    private TableView<SensorData> realTimeTable;

    @FXML
    private TableColumn<SensorData, String> typeColumn;

    @FXML
    private TableColumn<SensorData, Double> dataColumn;

    @FXML
    private TableColumn<SensorData, String> roomColumn;

    @FXML
    private Tab historyTab;

    @FXML
    private ScrollPane historyScrollPane;

    @FXML
    private MenuItem quitMenuItem;

    @FXML
    private MenuItem configMenuItem;

    @FXML
    private MenuItem helpMenuItem;

    @FXML
    private Button showAllDataButton;

    @FXML
    private LineChart<Number, Number> temperatureChart;

    @FXML
    private LineChart<Number, Number> humidityChart;

    private ScheduledExecutorService executorService;

    private ObservableList<SensorData> sensorDataList = FXCollections.observableArrayList();

    /**
     * Initialise le contexte pour la fenêtre.
     *
     * @param _cStage Fenêtre actuelle.
     * @param _p      Contrôleur parent.
     */
    public void initContext(Stage _cStage, CapteursController _p) {
        this.cDialogController = _p;
        this.cStage = _cStage;
        this.configure();
        realTimeTable.setItems(sensorDataList);
        startDataUpdate();
    }

    /**
     * Configure les actions et événements de la fenêtre.
     */
    private void configure() {
        this.cStage.setOnCloseRequest(this::closeWindow);
    }

    private void startDataUpdate() {
        executorService = Executors.newSingleThreadScheduledExecutor();
        executorService.scheduleAtFixedRate(() -> {
            Platform.runLater(this::updateData);
        }, 0, 5, TimeUnit.SECONDS); // Mise à jour toutes les 5 secondes
    }

    private void updateData() {
        try {
            Path path = Paths.get("../donnees.json");
            if (!Files.exists(path)) {
                System.out.println("Le fichier donnees.json n'existe pas.");
                return;
            }

            Reader reader = Files.newBufferedReader(path);
            JsonArray dataArray = JsonParser.parseReader(reader).getAsJsonArray();
            reader.close();

            if (dataArray.size() == 0) {
                System.out.println("Le fichier donnees.json est vide.");
                return;
            }

            sensorDataList.clear();

            for (int i = 0; i < dataArray.size(); i++) {
                JsonObject data = dataArray.get(i).getAsJsonObject();

                String room = data.has("room") ? data.get("room").getAsString() : "Inconnu";
                String type = "Température";
                double value = data.get("temperature").getAsJsonArray().get(0).getAsDouble();

                sensorDataList.add(new SensorData(room, type, value));

                type = "Humidité";
                value = data.get("humidity").getAsJsonArray().get(0).getAsDouble();

                sensorDataList.add(new SensorData(room, type, value));
            }

        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des données: " + e.getMessage());
            e.printStackTrace();
        }
    }

    private void showAllData() {
        try {
            Path path = Paths.get("../donnees.json");
            if (!Files.exists(path)) {
                System.out.println("Le fichier donnees.json n'existe pas.");
                return;
            }

            Reader reader = Files.newBufferedReader(path);
            JsonArray dataArray = JsonParser.parseReader(reader).getAsJsonArray();
            reader.close();

            if (dataArray.size() == 0) {
                System.out.println("Le fichier donnees.json est vide.");
                return;
            }

            temperatureChart.getData().clear();
            humidityChart.getData().clear();

            Map<String, XYChart.Series<Number, Number>> tempSeriesMap = new HashMap<>();
            Map<String, XYChart.Series<Number, Number>> humiditySeriesMap = new HashMap<>();

            for (int i = 0; i < dataArray.size(); i++) {
                JsonObject data = dataArray.get(i).getAsJsonObject();

                String room = data.has("room") ? data.get("room").getAsString() : "Inconnu";
                double temperature = data.get("temperature").getAsJsonArray().get(0).getAsDouble();
                double humidity = data.get("humidity").getAsJsonArray().get(0).getAsDouble();

                XYChart.Series<Number, Number> tempSeries = tempSeriesMap.computeIfAbsent(room, k -> {
                    XYChart.Series<Number, Number> series = new XYChart.Series<>();
                    series.setName(k);
                    temperatureChart.getData().add(series);
                    return series;
                });

                XYChart.Series<Number, Number> humiditySeries = humiditySeriesMap.computeIfAbsent(room, k -> {
                    XYChart.Series<Number, Number> series = new XYChart.Series<>();
                    series.setName(k);
                    humidityChart.getData().add(series);
                    return series;
                });

                tempSeries.getData().add(new XYChart.Data<>(i, temperature));
                humiditySeries.getData().add(new XYChart.Data<>(i, humidity));
            }

        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des données: " + e.getMessage());
            e.printStackTrace();
        }
    }

    @FXML
    private void initialize() {
        roomColumn.setCellValueFactory(new PropertyValueFactory<>("room"));
        typeColumn.setCellValueFactory(new PropertyValueFactory<>("type"));
        dataColumn.setCellValueFactory(new PropertyValueFactory<>("value"));
        showAllDataButton.setOnAction(event -> showAllData());
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog() {
        this.cStage.showAndWait();
    }

    /**
     * Gère la fermeture de la fenêtre.
     *
     * @param e Événement de fermeture.
     * @return null
     */
    private Object closeWindow(WindowEvent e) {
        this.doQuitter();
        return null;
    }

    @FXML
	private void doConfig() {
	}
    @FXML
	private void doAide() {
	}
    @FXML
	private void doQuitter() {
		this.cStage.close();
	}
}