package application.control;

import application.model.SensorData;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.ComboBox;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;

import java.io.Reader;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.time.*;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.*;


/**
 * Contrôleur pour le graphique de données en temps réel.
 * @author Thomas
 */
public class GraphController {

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

    private ScheduledExecutorService executorService;

    private ObservableList<SensorData> sensorDataList = FXCollections.observableArrayList();

    private Map<String, XYChart.Series<Number, Number>> tempSeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> humiditySeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> co2SeriesMap = new HashMap<>();
    private Map<String, XYChart.Series<Number, Number>> pressureSeriesMap = new HashMap<>();

    private Instant startTimeInstant = Instant.now();

    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
    private static final int MAX_POINTS = 8; // Nombre maximum de points par série


    /**
     * Initialise le contrôleur dès que le fichier FXML est chargé.
     * 
     * @author Thomas
     */
    @FXML
    public void initialize() {
        roomColumn.setCellValueFactory(new PropertyValueFactory<>("room"));
        typeColumn.setCellValueFactory(new PropertyValueFactory<>("type"));
        dataColumn.setCellValueFactory(new PropertyValueFactory<>("value"));

        temperatureChart.getXAxis().setLabel("Temps (s)");
        temperatureChart.getYAxis().setLabel("Température (°C)");

        humidityChart.getXAxis().setLabel("Temps (s)");
        humidityChart.getYAxis().setLabel("Humidité (%)");

        co2Chart.getXAxis().setLabel("Temps (s)");
        co2Chart.getYAxis().setLabel("CO₂ (ppm)");

        pressureChart.getXAxis().setLabel("Temps (s)");
        pressureChart.getYAxis().setLabel("Pression (hPa)");

        individualTemperatureChart.getXAxis().setLabel("Temps (s)");
        individualTemperatureChart.getYAxis().setLabel("Température (°C)");

        individualHumidityChart.getXAxis().setLabel("Temps (s)");
        individualHumidityChart.getYAxis().setLabel("Humidité (%)");

        roomSelector.setOnAction(event -> updateIndividualRoomData());

        startDataUpdate();
    }


    /**
     * Démarre la mise à jour périodique des données des capteurs.
     * Les données sont mises à jour toutes les 5 secondes.
     * 
     * @author Thomas
     */
    private void startDataUpdate() {
        executorService = Executors.newSingleThreadScheduledExecutor();
        executorService.scheduleAtFixedRate(() -> {
            Platform.runLater(this::updateData);
        }, 0, 5, TimeUnit.SECONDS); // Mise à jour toutes les 5 secondes
    }


    /**
     * Met à jour les données des capteurs en les lisant depuis le fichier donnees.json.
     * Les données sont ajoutées à la liste des données des capteurs et les graphiques sont mis à jour.
     * 
     * @author Thomas
     */
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

            // Définir l'heure actuelle et le seuil de deux heures (au dela on ignore les données)
            LocalDateTime now = LocalDateTime.now(ZoneId.systemDefault());
            LocalDateTime cutoff = now.minusHours(2);

            sensorDataList.clear();
            tempSeriesMap.clear();
            humiditySeriesMap.clear();
            co2SeriesMap.clear();
            pressureSeriesMap.clear();
            // On efface les autres séries

            for (int i = 0; i < dataArray.size(); i++) {
                JsonObject data = dataArray.get(i).getAsJsonObject();

                // Parser la date de l'enregistrement
                String dateStr = data.has("date") ? data.get("date").getAsString() : null;
                if (dateStr == null) {
                    System.out.println("Aucune date trouvée dans l'enregistrement.");
                    continue;
                }

                LocalDateTime dataTime;
                try {
                    dataTime = LocalDateTime.parse(dateStr, DATE_FORMATTER);
                } catch (DateTimeParseException e) {
                    System.out.println("Format de date invalide: " + dateStr);
                    continue;
                }

                // verif si la donnée est dans les deux dernières heures
                if (dataTime.isBefore(cutoff)) {
                    continue; // Ignore les données plus anciennes
                }

                // Calculer le temps écoulé depuis le début en secondes
                long elapsedSeconds = Duration.between(startTimeInstant, dataTime.atZone(ZoneId.systemDefault()).toInstant()).getSeconds();

                String room = data.has("room") ? data.get("room").getAsString() : "Inconnu";

                // Mise à jour des données pour chaque type
                if (data.has("temperature")) {
                    double temperature = extractValue(data.get("temperature"));
                    if (!Double.isNaN(temperature)) {
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
                        sensorDataList.add(new SensorData(room, "Température", temperature));
                    }
                }

                if (data.has("humidity")) {
                    double humidity = extractValue(data.get("humidity"));
                    if (!Double.isNaN(humidity)) {
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
                        sensorDataList.add(new SensorData(room, "Humidité", humidity));
                    }
                }

                if (data.has("co2")) {
                    double co2 = extractValue(data.get("co2"));
                    if (!Double.isNaN(co2)) {
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
                        sensorDataList.add(new SensorData(room, "CO₂", co2));
                    }
                }

                if (data.has("pressure")) {
                    double pressure = extractValue(data.get("pressure"));
                    if (!Double.isNaN(pressure)) {
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
                        sensorDataList.add(new SensorData(room, "Pression", pressure));
                    }
                }
            }

            // on nettoie les données plus anciennes que 2h
            nettoyerDonnees(cutoff);

            // MAJ des salles dans le combobox
            Platform.runLater(() -> {
                roomSelector.getItems().clear();
                roomSelector.getItems().addAll(tempSeriesMap.keySet());
            });

        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des données: " + e.getMessage());
            e.printStackTrace();
        }
    }


    /**
     * Extrait une valeur numérique d'un élément JSON.
     * 
     * @param jsonElement l'élément JSON dont la valeur doit être extraite
     * @return la valeur numérique extraite, ou Double.NaN si l'extraction échoue
     * 
     * @author Thomas
     */
    private double extractValue(JsonElement jsonElement) {
        if (jsonElement.isJsonArray()) {
            JsonArray array = jsonElement.getAsJsonArray();
            if (array.size() > 0 && array.get(0).isJsonPrimitive()) {
                return array.get(0).getAsDouble();
            }
        } else if (jsonElement.isJsonPrimitive()) {
            return jsonElement.getAsDouble();
        }
        return Double.NaN;
    }


    /**
     * Met à jour les graphiques individuels pour une salle sélectionnée.
     * Les données sont récupérées à partir des séries de données existantes et ajoutées aux graphiques individuels.
     * 
     * @author Thomas
     */
    private void updateIndividualRoomData() {
        String selectedRoom = roomSelector.getSelectionModel().getSelectedItem();
        if (selectedRoom == null) {
            return;
        }

        XYChart.Series<Number, Number> tempSeriesOriginal = tempSeriesMap.get(selectedRoom);
        if (tempSeriesOriginal != null) {
            individualTemperatureChart.getData().clear();
            individualTemperatureChart.getData().add(tempSeriesOriginal);
        }

        XYChart.Series<Number, Number> humiditySeriesOriginal = humiditySeriesMap.get(selectedRoom);
        if (humiditySeriesOriginal != null) {
            individualHumidityChart.getData().clear();
            individualHumidityChart.getData().add(humiditySeriesOriginal);
        }
    }


    /**
     * Nettoie les séries de données en supprimant les points de données plus anciens que la date limite spécifiée.
     * 
     * @param cutoff la date limite pour la suppression des points de données
     * 
     * @author Thomas
     */
    private void nettoyerDonnees(LocalDateTime cutoff) {
        nettoyerSerie(tempSeriesMap, cutoff);
        nettoyerSerie(humiditySeriesMap, cutoff);
        nettoyerSerie(co2SeriesMap, cutoff);
        nettoyerSerie(pressureSeriesMap, cutoff);
    }


    /**
     * Nettoie les séries de données en supprimant les points de données plus anciens que la date limite spécifiée.
     * 
     * @param seriesMap la map contenant les séries de données à nettoyer
     * @param cutoff la date limite pour la suppression des points de données
     * 
     * @author Thomas
     */
    private void nettoyerSerie(Map<String, XYChart.Series<Number, Number>> seriesMap, LocalDateTime cutoff) {
        for (Map.Entry<String, XYChart.Series<Number, Number>> entry : seriesMap.entrySet()) {
            XYChart.Series<Number, Number> series = entry.getValue();
            series.getData().removeIf(data -> {
                long elapsedSeconds = data.getXValue().longValue();
                Instant dataInstant = startTimeInstant.plusSeconds(elapsedSeconds);
                LocalDateTime dataTime = LocalDateTime.ofInstant(dataInstant, ZoneId.systemDefault());
                return dataTime.isBefore(cutoff);
            });
        }
    }

    /**
     * Arrête le ScheduledExecutorService lors de la fermeture de l'application. (mise à jour des données)
     * 
     * @author Thomas
     */
        public void shutdown() {
        if (executorService != null) {
            executorService.shutdownNow();
        }
    }
}