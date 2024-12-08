package application.view;

import application.control.CapteursController;
import application.model.SensorData;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.*;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.layout.HBox;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Text;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.io.Reader;
import java.time.*;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

public class CapteursViewController {

    // Contrôleur de Dialogue associé à CapteursController
    private CapteursController cDialogController;

    // Fenêtre physique où est la scène contenant le fichier XML contrôlé par this
    private Stage cStage;

    // Éléments FXML
    @FXML
    private TabPane tabPane;

    @FXML
    private TableView<SensorData> realTimeTable;

    @FXML
    private TableColumn<SensorData, String> roomColumn;

    @FXML
    private TableColumn<SensorData, String> typeColumn;

    @FXML
    private TableColumn<SensorData, Double> dataColumn;

    @FXML
    private LineChart<Number, Number> temperatureChart;

    @FXML
    private LineChart<Number, Number> humidityChart;

    @FXML
    private LineChart<Number, Number> co2Chart;

    @FXML
    private LineChart<Number, Number> pressureChart;

    @FXML
    private ComboBox<String> roomSelector;

    @FXML
    private LineChart<Number, Number> individualTemperatureChart;

    @FXML
    private LineChart<Number, Number> individualHumidityChart;

    @FXML
    private Text colorLegendText;

    @FXML
    private HBox colorLegendContainer;

    private ScheduledExecutorService executorService;

    private ObservableList<SensorData> sensorDataList = FXCollections.observableArrayList();

    private Map<String, XYChart.Series<Number, Number>> tempSeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> humiditySeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> co2SeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> pressureSeriesMap = new HashMap<>();
    // Ajoutez d'autres maps pour chaque type de donnée

    private Instant startTimeInstant = Instant.now();

    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
    private static final int MAX_POINTS = 8; // Nombre maximum de points visibles dans le graphique

    @FXML
    private void initialize() {
        roomColumn.setCellValueFactory(new PropertyValueFactory<>("room"));
        typeColumn.setCellValueFactory(new PropertyValueFactory<>("type"));
        dataColumn.setCellValueFactory(new PropertyValueFactory<>("value"));

        temperatureChart.getXAxis().setLabel("Temps (s)");
        temperatureChart.getYAxis().setLabel("Température (°C)");
        temperatureChart.setLegendVisible(true);

        humidityChart.getXAxis().setLabel("Temps (s)");
        humidityChart.getYAxis().setLabel("Humidité (%)");
        humidityChart.setLegendVisible(true);

        co2Chart.getXAxis().setLabel("Temps (s)");
        co2Chart.getYAxis().setLabel("CO₂ (ppm)");
        co2Chart.setLegendVisible(true);

        pressureChart.getXAxis().setLabel("Temps (s)");
        pressureChart.getYAxis().setLabel("Pression (hPa)");
        pressureChart.setLegendVisible(true);

        // Initialisez les autres graphiques de la même manière

        individualTemperatureChart.getXAxis().setLabel("Temps (s)");
        individualTemperatureChart.getYAxis().setLabel("Température (°C)");

        individualHumidityChart.getXAxis().setLabel("Temps (s)");
        individualHumidityChart.getYAxis().setLabel("Humidité (%)");

        roomSelector.setOnAction(event -> updateIndividualRoomData());

        updateColorLegend();
    }

    public void initContext(Stage _cStage, CapteursController _p) {
        this.cDialogController = _p;
        this.cStage = _cStage;
        this.configure();
        realTimeTable.setItems(sensorDataList);
        startDataUpdate();
    }

    private void configure() {
        this.cStage.setOnCloseRequest(this::closeWindow);
    }

    private void startDataUpdate() {
        executorService = Executors.newSingleThreadScheduledExecutor();
        executorService.scheduleAtFixedRate(() -> {
            Platform.runLater(this::updateData);
        }, 0, 10, TimeUnit.SECONDS); // Mise à jour toutes les 10 secondes
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
    
                if (data.has("temperature") && data.get("temperature").isJsonArray()) {
                    String type = "Température";
                    double value = data.get("temperature").getAsJsonArray().get(0).getAsDouble();
                    sensorDataList.add(new SensorData(room, type, value));
                }
    
                if (data.has("humidity") && data.get("humidity").isJsonArray()) {
                    String type = "Humidité";
                    double value = data.get("humidity").getAsJsonArray().get(0).getAsDouble();
                    sensorDataList.add(new SensorData(room, type, value));
                }
    
                if (data.has("co2") && data.get("co2").isJsonArray()) {
                    String type = "CO₂";
                    double value = data.get("co2").getAsJsonArray().get(0).getAsDouble();
                    sensorDataList.add(new SensorData(room, type, value));
                }
    
                if (data.has("pressure") && data.get("pressure").isJsonArray()) {
                    String type = "Pression";
                    double value = data.get("pressure").getAsJsonArray().get(0).getAsDouble();
                    sensorDataList.add(new SensorData(room, type, value));
                }
            }
    
        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des données: " + e.getMessage());
            e.printStackTrace();
        }
        updateCharts();
        updateColorLegend();
    }

    

    private void updateIndividualRoomData() {
        String selectedRoom = roomSelector.getSelectionModel().getSelectedItem();
        if (selectedRoom == null) {
            return;
        }

        individualTemperatureChart.getData().clear();
        individualHumidityChart.getData().clear();

        XYChart.Series<Number, Number> tempSeries = tempSeriesMap.get(selectedRoom);
        if (tempSeries != null) {
            individualTemperatureChart.getData().add(tempSeries);
        }

        XYChart.Series<Number, Number> humiditySeries = humiditySeriesMap.get(selectedRoom);
        if (humiditySeries != null) {
            individualHumidityChart.getData().add(humiditySeries);
        }

        // Ajoutez le code pour afficher les données individuelles des autres types si nécessaire
    }


    public void displayDialog() {
        this.cStage.showAndWait();
    }

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


    private void updateCharts() {
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
    
            LocalDateTime now = LocalDateTime.now(ZoneId.systemDefault());
            LocalDateTime cutoff = now.minusHours(2);
    
            for (JsonElement element : dataArray) {
                JsonObject data = element.getAsJsonObject();
    
                if (!data.has("room")) continue;
    
                String room = data.get("room").getAsString();
    
                String dateStr = data.has("date") ? data.get("date").getAsString() : null;
                if (dateStr == null) continue;
    
                LocalDateTime dataTime;
                try {
                    dataTime = LocalDateTime.parse(dateStr, DATE_FORMATTER);
                } catch (DateTimeParseException e) {
                    continue;
                }
    
                if (dataTime.isBefore(cutoff)) continue;
    
                long elapsedSeconds = Duration.between(startTimeInstant, dataTime.atZone(ZoneId.systemDefault()).toInstant()).getSeconds();
    
                // Mise à jour des séries pour chaque type de capteur
                if (data.has("temperature") && data.get("temperature").isJsonArray()) {
                    double temperature = data.get("temperature").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> tempSeries = tempSeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        temperatureChart.getData().add(series);
                        return series;
                    });
                    tempSeries.getData().add(new XYChart.Data<>(elapsedSeconds, temperature));
                }
    
                if (data.has("humidity") && data.get("humidity").isJsonArray()) {
                    double humidity = data.get("humidity").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> humiditySeries = humiditySeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        humidityChart.getData().add(series);
                        return series;
                    });
                    humiditySeries.getData().add(new XYChart.Data<>(elapsedSeconds, humidity));
                }
    
                if (data.has("co2") && data.get("co2").isJsonArray()) {
                    double co2 = data.get("co2").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> co2Series = co2SeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        co2Chart.getData().add(series);
                        return series;
                    });
                    co2Series.getData().add(new XYChart.Data<>(elapsedSeconds, co2));
                }
    
                if (data.has("pressure") && data.get("pressure").isJsonArray()) {
                    double pressure = data.get("pressure").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> pressureSeries = pressureSeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        pressureChart.getData().add(series);
                        return series;
                    });
                    pressureSeries.getData().add(new XYChart.Data<>(elapsedSeconds, pressure));
                }
            }
    
            // Nettoyer les données plus anciennes que le cutoff
            nettoyerDonnees(cutoff);
    
        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des graphiques: " + e.getMessage());
            e.printStackTrace();
        }
        updateColorLegend();
    }

    private void nettoyerDonnees(LocalDateTime cutoff) {
        nettoyerSerie(tempSeriesMap, cutoff);
        nettoyerSerie(humiditySeriesMap, cutoff);
        nettoyerSerie(co2SeriesMap, cutoff);
        nettoyerSerie(pressureSeriesMap, cutoff);
    }
    
    private void nettoyerSerie(Map<String, XYChart.Series<Number, Number>> seriesMap, LocalDateTime cutoff) {
        for (XYChart.Series<Number, Number> series : seriesMap.values()) {
            series.getData().removeIf(data -> {
                long elapsedSeconds = data.getXValue().longValue();
                Instant dataInstant = startTimeInstant.plusSeconds(elapsedSeconds);
                LocalDateTime dataTime = LocalDateTime.ofInstant(dataInstant, ZoneId.systemDefault());
                return dataTime.isBefore(cutoff);
            });
        }
    }

    private void updateColorLegend() {
        colorLegendContainer.getChildren().clear();
        for (String room : tempSeriesMap.keySet()) {
            XYChart.Series<Number, Number> series = tempSeriesMap.get(room);
            if (series != null && series.getNode() != null) {
                String color = extractColorFromSeries(series);
                Rectangle colorBox = new Rectangle(10, 10, Color.web(color));
                Text roomText = new Text(room);
                HBox legendItem = new HBox(5, colorBox, roomText);
                colorLegendContainer.getChildren().add(legendItem);
            }
        }
    }

    private String extractColorFromSeries(XYChart.Series<Number, Number> series) {
        // Obtenir la classe CSS par défaut de la série
        String defaultColorStyleClass = series.getNode().getStyleClass().stream()
                .filter(style -> style.startsWith("default-color"))
                .findFirst()
                .orElse("");

        // Définir les couleurs par défaut utilisées par JavaFX
        Map<String, String> defaultColorMap = new HashMap<>();
        defaultColorMap.put("default-color0", "#f3622d");
        defaultColorMap.put("default-color1", "#fba71b");
        defaultColorMap.put("default-color2", "#57b757");
        defaultColorMap.put("default-color3", "#41a9c9");
        defaultColorMap.put("default-color4", "#888888");
        defaultColorMap.put("default-color5", "#a2ab58");
        defaultColorMap.put("default-color6", "#3264c8");
        defaultColorMap.put("default-color7", "#994499");
        defaultColorMap.put("default-color8", "#a2b9d5");
        defaultColorMap.put("default-color9", "#ff8e6c");

        return defaultColorMap.getOrDefault(defaultColorStyleClass, "couleur inconnue");
    }
}