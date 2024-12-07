package application.view;

import application.control.CapteursController;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import javafx.application.Platform;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.control.MenuItem;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TabPane;
import javafx.scene.control.Tab;
import javafx.scene.control.ScrollPane;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;
import javafx.scene.control.cell.PropertyValueFactory;

import java.io.Reader;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
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

        // Action quitter
        quitMenuItem.setOnAction(event -> this.cStage.close());

        // Action configuration
        configMenuItem.setOnAction(event -> {
            // Ajouter une logique de configuration si nécessaire
            System.out.println("Configuration action triggered.");
        });

        // Action aide
        helpMenuItem.setOnAction(event -> {
            // Ajouter une logique d'aide si nécessaire
            System.out.println("Help action triggered.");
        });
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

    public static class SensorData {
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

    @FXML
    private void initialize() {
        roomColumn.setCellValueFactory(new PropertyValueFactory<>("room"));
        typeColumn.setCellValueFactory(new PropertyValueFactory<>("type"));
        dataColumn.setCellValueFactory(new PropertyValueFactory<>("value"));
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
        this.doCancel();
        return null;
    }

    /**
     * Actions effectuées lors de l'annulation.
     */
    private void doCancel() {
        System.out.println("Window close request intercepted. Cancelling close.");
    }

    // Ajoutez ici des méthodes supplémentaires pour manipuler vos éléments de l'interface utilisateur, si nécessaire.
}