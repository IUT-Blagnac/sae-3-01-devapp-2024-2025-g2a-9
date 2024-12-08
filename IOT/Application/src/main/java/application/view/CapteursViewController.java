package application.view;

import application.control.CapteursController;
import application.model.SensorData;
import application.model.Seuil;
import application.tools.ConfigIni;
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
import java.io.IOException;
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


    //graphes collectifs
    @FXML
    private LineChart<Number, Number> temperatureChart;

    @FXML
    private LineChart<Number, Number> humidityChart;

    @FXML
    private LineChart<Number, Number> co2Chart;

    @FXML
    private LineChart<Number, Number> pressureChart;

    @FXML
    private LineChart<Number, Number> luminosityChart;
    @FXML
    private LineChart<Number, Number> activityChart;
    @FXML
    private LineChart<Number, Number> tvocChart;
    @FXML
    private LineChart<Number, Number> infraredChart;
    @FXML
    private LineChart<Number, Number> infraredVisibleChart;


    //graphes individuels
    @FXML
    private LineChart<Number, Number> individualTemperatureChart;

    @FXML
    private LineChart<Number, Number> individualHumidityChart;

    @FXML
    private LineChart<Number, Number> individualLuminosityChart;
    @FXML
    private LineChart<Number, Number> individualActivityChart;
    @FXML
    private LineChart<Number, Number> individualTvocChart;
    @FXML
    private LineChart<Number, Number> individualInfraredChart;
    @FXML
    private LineChart<Number, Number> individualInfraredVisibleChart;

    @FXML
    private Text colorLegendText;

    @FXML
    private HBox colorLegendContainer;

    @FXML
    private ComboBox<String> roomComboBox;


    //seuils
    private Seuil seuilTemperature;
    private Seuil seuilHumidite;
    private Seuil seuilCO2;
    private Seuil seuilPression;
    private Seuil seuilLuminosite;
    private Seuil seuilActivite;
    private Seuil seuiltvoc;
    private Seuil seuilInfrarouge;
    private Seuil seuilInfrarougeVisible;

    private ScheduledExecutorService executorService;

    private ObservableList<SensorData> sensorDataList = FXCollections.observableArrayList();

    private Map<String, XYChart.Series<Number, Number>> tempSeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> humiditySeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> co2SeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> pressureSeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> luminositySeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> activitySeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> tvocSeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> infraredSeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> infraredVisibleSeriesMap = new HashMap<>();

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
    
        luminosityChart.getXAxis().setLabel("Temps (s)");
        luminosityChart.getYAxis().setLabel("Luminosité (lux)");
        luminosityChart.setLegendVisible(true);
    
        activityChart.getXAxis().setLabel("Temps (s)");
        activityChart.getYAxis().setLabel("Activité");
        activityChart.setLegendVisible(true);
    
        tvocChart.getXAxis().setLabel("Temps (s)");
        tvocChart.getYAxis().setLabel("TVOC (ppb)");
        tvocChart.setLegendVisible(true);
    
        infraredChart.getXAxis().setLabel("Temps (s)");
        infraredChart.getYAxis().setLabel("Infrarouge");
        infraredChart.setLegendVisible(true);
    
        infraredVisibleChart.getXAxis().setLabel("Temps (s)");
        infraredVisibleChart.getYAxis().setLabel("Infrarouge Visible");
        infraredVisibleChart.setLegendVisible(true);
    
        individualTemperatureChart.getXAxis().setLabel("Temps (s)");
        individualTemperatureChart.getYAxis().setLabel("Température (°C)");
    
        individualHumidityChart.getXAxis().setLabel("Temps (s)");
        individualHumidityChart.getYAxis().setLabel("Humidité (%)");
    
        individualLuminosityChart.getXAxis().setLabel("Temps (s)");
        individualLuminosityChart.getYAxis().setLabel("Luminosité (lux)");
    
        individualActivityChart.getXAxis().setLabel("Temps (s)");
        individualActivityChart.getYAxis().setLabel("Activité");
    
        individualTvocChart.getXAxis().setLabel("Temps (s)");
        individualTvocChart.getYAxis().setLabel("TVOC (ppb)");
    
        individualInfraredChart.getXAxis().setLabel("Temps (s)");
        individualInfraredChart.getYAxis().setLabel("Infrarouge");
    
        individualInfraredVisibleChart.getXAxis().setLabel("Temps (s)");
        individualInfraredVisibleChart.getYAxis().setLabel("Infrarouge Visible");
    
        roomComboBox.setOnAction(event -> updateIndividualRoomData());
    
        updateColorLegend();
        loadSeuils();
    }

    private void loadSeuils() {
        try {
            ConfigIni configIni = new ConfigIni();
            configIni.loadConfig("../config.ini");

            seuilTemperature = new Seuil("Température", configIni.getConfigValue("seuils_capteur", "temperature"));
            seuilHumidite = new Seuil("Humidité", configIni.getConfigValue("seuils_capteur", "humidity"));
            seuilCO2 = new Seuil("CO₂", configIni.getConfigValue("seuils_capteur", "co2"));
            seuilPression = new Seuil("Pression", configIni.getConfigValue("seuils_capteur", "pressure"));
            seuilLuminosite = new Seuil("Luminosité", configIni.getConfigValue("seuils_capteur", "illumination"));
            seuilActivite = new Seuil("Activité", configIni.getConfigValue("seuils_capteur", "activity"));
            seuiltvoc = new Seuil("TVOC", configIni.getConfigValue("seuils_capteur", "tvoc"));
            seuilInfrarouge = new Seuil("Infrarouge", configIni.getConfigValue("seuils_capteur", "infrared"));
            seuilInfrarougeVisible = new Seuil("Infrarouge Visible", configIni.getConfigValue("seuils_capteur", "infrared_and_visible"));

        } catch (IOException e) {
            System.err.println("Erreur lors du chargement du fichier config.ini: " + e.getMessage());
            e.printStackTrace();
        }
    }

    public void initContext(Stage _cStage, CapteursController _p) {
        this.cDialogController = _p;
        this.cStage = _cStage;
        this.configure();
        realTimeTable.setItems(sensorDataList);
                realTimeTable.setRowFactory(tv -> new TableRow<SensorData>() {
            @Override
            protected void updateItem(SensorData item, boolean empty) {
                super.updateItem(item, empty);
                if (item == null || empty) {
                    setStyle("");
                } else {
                    boolean exceedsThreshold = false;
                    double value = item.getValue();
                    String type = item.getType();
        
                    if ("Température".equals(type) && value > Double.parseDouble(seuilTemperature.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("Humidité".equals(type) && value > Double.parseDouble(seuilHumidite.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("CO₂".equals(type) && value > Double.parseDouble(seuilCO2.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("Pression".equals(type) && value > Double.parseDouble(seuilPression.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("Luminosité".equals(type) && value > Double.parseDouble(seuilLuminosite.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("Activité".equals(type) && value > Double.parseDouble(seuilActivite.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("TVOC".equals(type) && value > Double.parseDouble(seuiltvoc.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("Infrarouge".equals(type) && value > Double.parseDouble(seuilInfrarouge.getValeur())) {
                        exceedsThreshold = true;
                    } else if ("Infrarouge Visible".equals(type) && value > Double.parseDouble(seuilInfrarougeVisible.getValeur())) {
                        exceedsThreshold = true;
                    }
        
                    if (exceedsThreshold) {
                        setStyle("-fx-background-color: red;");
                    } else {
                        setStyle("");
                    }
                }
            }
        });
        startDataUpdate();
        ObservableList<String> rooms = FXCollections.observableArrayList(
            "E210", "B103", "E207", "E101", "E100", "C006", "hall-amphi", "E102", "E103", "B110", 
            "hall-entrée-principale", "B106", "B001", "E004", "E106", "C004", "Foyer-personnels", 
            "B202", "Local-velo", "B201", "B109", "C001", "B002", "Salle-conseil", "B105", 
            "Foyer-etudiants-entrée", "B111", "B234", "E006", "B113", "E209", "E003", "B217", 
            "B112", "C002", "E001", "C102", "E007", "B203", "E208", "amphi1"
        );
        roomComboBox.setItems(rooms);
        updateRoomComboBox();
    }

    private void configure() {
        this.cStage.setOnCloseRequest(this::closeWindow);
    }

    private void startDataUpdate() {
        executorService = Executors.newSingleThreadScheduledExecutor();
        executorService.scheduleAtFixedRate(() -> {
            Platform.runLater(() -> {
                updateData();
                updateRoomComboBox();
            });
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
        String selectedRoom = roomComboBox.getSelectionModel().getSelectedItem();
        if (selectedRoom == null) {
            return;
        }
    
        individualTemperatureChart.getData().clear();
        individualHumidityChart.getData().clear();
        individualLuminosityChart.getData().clear();
        individualActivityChart.getData().clear();
        individualTvocChart.getData().clear();
        individualInfraredChart.getData().clear();
        individualInfraredVisibleChart.getData().clear();
    
        XYChart.Series<Number, Number> tempSeriesOriginal = tempSeriesMap.get(selectedRoom);
        if (tempSeriesOriginal != null) {
            XYChart.Series<Number, Number> tempSeriesCopy = new XYChart.Series<>();
            tempSeriesCopy.setName(tempSeriesOriginal.getName());
            for (XYChart.Data<Number, Number> dataPoint : tempSeriesOriginal.getData()) {
                tempSeriesCopy.getData().add(new XYChart.Data<>(dataPoint.getXValue(), dataPoint.getYValue()));
            }
            individualTemperatureChart.getData().add(tempSeriesCopy);
        }
    
        XYChart.Series<Number, Number> humiditySeriesOriginal = humiditySeriesMap.get(selectedRoom);
        if (humiditySeriesOriginal != null) {
            XYChart.Series<Number, Number> humiditySeriesCopy = new XYChart.Series<>();
            humiditySeriesCopy.setName(humiditySeriesOriginal.getName());
            for (XYChart.Data<Number, Number> dataPoint : humiditySeriesOriginal.getData()) {
                humiditySeriesCopy.getData().add(new XYChart.Data<>(dataPoint.getXValue(), dataPoint.getYValue()));
            }
            individualHumidityChart.getData().add(humiditySeriesCopy);
        }
    
        XYChart.Series<Number, Number> luminositySeriesOriginal = luminositySeriesMap.get(selectedRoom);
        if (luminositySeriesOriginal != null) {
            XYChart.Series<Number, Number> luminositySeriesCopy = new XYChart.Series<>();
            luminositySeriesCopy.setName(luminositySeriesOriginal.getName());
            for (XYChart.Data<Number, Number> dataPoint : luminositySeriesOriginal.getData()) {
                luminositySeriesCopy.getData().add(new XYChart.Data<>(dataPoint.getXValue(), dataPoint.getYValue()));
            }
            individualLuminosityChart.getData().add(luminositySeriesCopy);
        }
    
        XYChart.Series<Number, Number> activitySeriesOriginal = activitySeriesMap.get(selectedRoom);
        if (activitySeriesOriginal != null) {
            XYChart.Series<Number, Number> activitySeriesCopy = new XYChart.Series<>();
            activitySeriesCopy.setName(activitySeriesOriginal.getName());
            for (XYChart.Data<Number, Number> dataPoint : activitySeriesOriginal.getData()) {
                activitySeriesCopy.getData().add(new XYChart.Data<>(dataPoint.getXValue(), dataPoint.getYValue()));
            }
            individualActivityChart.getData().add(activitySeriesCopy);
        }
    
        XYChart.Series<Number, Number> tvocSeriesOriginal = tvocSeriesMap.get(selectedRoom);
        if (tvocSeriesOriginal != null) {
            XYChart.Series<Number, Number> tvocSeriesCopy = new XYChart.Series<>();
            tvocSeriesCopy.setName(tvocSeriesOriginal.getName());
            for (XYChart.Data<Number, Number> dataPoint : tvocSeriesOriginal.getData()) {
                tvocSeriesCopy.getData().add(new XYChart.Data<>(dataPoint.getXValue(), dataPoint.getYValue()));
            }
            individualTvocChart.getData().add(tvocSeriesCopy);
        }
    
        XYChart.Series<Number, Number> infraredSeriesOriginal = infraredSeriesMap.get(selectedRoom);
        if (infraredSeriesOriginal != null) {
            XYChart.Series<Number, Number> infraredSeriesCopy = new XYChart.Series<>();
            infraredSeriesCopy.setName(infraredSeriesOriginal.getName());
            for (XYChart.Data<Number, Number> dataPoint : infraredSeriesOriginal.getData()) {
                infraredSeriesCopy.getData().add(new XYChart.Data<>(dataPoint.getXValue(), dataPoint.getYValue()));
            }
            individualInfraredChart.getData().add(infraredSeriesCopy);
        }
    
        XYChart.Series<Number, Number> infraredVisibleSeriesOriginal = infraredVisibleSeriesMap.get(selectedRoom);
        if (infraredVisibleSeriesOriginal != null) {
            XYChart.Series<Number, Number> infraredVisibleSeriesCopy = new XYChart.Series<>();
            infraredVisibleSeriesCopy.setName(infraredVisibleSeriesOriginal.getName());
            for (XYChart.Data<Number, Number> dataPoint : infraredVisibleSeriesOriginal.getData()) {
                infraredVisibleSeriesCopy.getData().add(new XYChart.Data<>(dataPoint.getXValue(), dataPoint.getYValue()));
            }
            individualInfraredVisibleChart.getData().add(infraredVisibleSeriesCopy);
        }
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
    
                if (data.has("temperature") && data.get("temperature").isJsonArray()) {
                    double temperature = data.get("temperature").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> tempSeries = tempSeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        temperatureChart.getData().add(series);
                        return series;
                    });
                    tempSeries.getData().add(new XYChart.Data<>(elapsedSeconds, temperature));
                    if (tempSeries.getData().size() > MAX_POINTS) {
                        tempSeries.getData().remove(0);
                    }
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
                    if (humiditySeries.getData().size() > MAX_POINTS) {
                        humiditySeries.getData().remove(0);
                    }
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
                    if (co2Series.getData().size() > MAX_POINTS) {
                        co2Series.getData().remove(0);
                    }
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
                    if (pressureSeries.getData().size() > MAX_POINTS) {
                        pressureSeries.getData().remove(0);
                    }
                }
    
                if (data.has("luminosity") && data.get("luminosity").isJsonArray()) {
                    double luminosity = data.get("luminosity").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> luminositySeries = luminositySeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        luminosityChart.getData().add(series);
                        return series;
                    });
                    luminositySeries.getData().add(new XYChart.Data<>(elapsedSeconds, luminosity));
                    if (luminositySeries.getData().size() > MAX_POINTS) {
                        luminositySeries.getData().remove(0);
                    }
                }
    
                if (data.has("activity") && data.get("activity").isJsonArray()) {
                    double activity = data.get("activity").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> activitySeries = activitySeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        activityChart.getData().add(series);
                        return series;
                    });
                    activitySeries.getData().add(new XYChart.Data<>(elapsedSeconds, activity));
                    if (activitySeries.getData().size() > MAX_POINTS) {
                        activitySeries.getData().remove(0);
                    }
                }
    
                if (data.has("tvoc") && data.get("tvoc").isJsonArray()) {
                    double tvoc = data.get("tvoc").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> tvocSeries = tvocSeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        tvocChart.getData().add(series);
                        return series;
                    });
                    tvocSeries.getData().add(new XYChart.Data<>(elapsedSeconds, tvoc));
                    if (tvocSeries.getData().size() > MAX_POINTS) {
                        tvocSeries.getData().remove(0);
                    }
                }
    
                if (data.has("infrared") && data.get("infrared").isJsonArray()) {
                    double infrared = data.get("infrared").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> infraredSeries = infraredSeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        infraredChart.getData().add(series);
                        return series;
                    });
                    infraredSeries.getData().add(new XYChart.Data<>(elapsedSeconds, infrared));
                    if (infraredSeries.getData().size() > MAX_POINTS) {
                        infraredSeries.getData().remove(0);
                    }
                }
    
                if (data.has("infrared_visible") && data.get("infrared_visible").isJsonArray()) {
                    double infraredVisible = data.get("infrared_visible").getAsJsonArray().get(0).getAsDouble();
                    XYChart.Series<Number, Number> infraredVisibleSeries = infraredVisibleSeriesMap.computeIfAbsent(room, k -> {
                        XYChart.Series<Number, Number> series = new XYChart.Series<>();
                        series.setName(k);
                        infraredVisibleChart.getData().add(series);
                        return series;
                    });
                    infraredVisibleSeries.getData().add(new XYChart.Data<>(elapsedSeconds, infraredVisible));
                    if (infraredVisibleSeries.getData().size() > MAX_POINTS) {
                        infraredVisibleSeries.getData().remove(0);
                    }
                }
            }
    
            nettoyerDonnees(cutoff);
    
        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des graphiques: " + e.getMessage());
            e.printStackTrace();
        }
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

    private void updateRoomComboBox() {
        try {
            Path path = Paths.get("../donnees.json");
            if (!Files.exists(path)) {
                System.out.println("Le fichier donnees.json n'existe pas.");
                return;
            }

            Reader reader = Files.newBufferedReader(path);
            JsonArray dataArray = JsonParser.parseReader(reader).getAsJsonArray();
            reader.close();

            ObservableList<String> rooms = FXCollections.observableArrayList();
            for (JsonElement element : dataArray) {
                JsonObject data = element.getAsJsonObject();
                if (data.has("room")) {
                    String room = data.get("room").getAsString();
                    if (!rooms.contains(room)) {
                        rooms.add(room);
                    }
                }
            }
            roomComboBox.setItems(rooms);

        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des salles: " + e.getMessage());
            e.printStackTrace();
        }
    }
}