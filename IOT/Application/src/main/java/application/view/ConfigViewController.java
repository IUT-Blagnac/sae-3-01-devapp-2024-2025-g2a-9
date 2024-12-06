package application.view;

import application.control.ConfigController;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.stage.Stage;

import java.util.ArrayList;
import java.util.List;

/**
 * Contrôleur de la vue pour la configuration.
 */
public class ConfigViewController {

    private ConfigController configController;
    private Stage stage;

    // Éléments de l'interface utilisateur (liés via FXML)
    @FXML
    private TextField mqttServerField, outputFrequenceField;
    @FXML
    private CheckBox capteursCheckBox, solaredgeCheckBox;
    @FXML
    private CheckBox temperatureCheckBox, humidityCheckBox, activityCheckBox, co2CheckBox, tvocCheckBox, illuminationCheckBox, infraredCheckBox, infraredVisibleCheckBox, pressureCheckBox;
    @FXML
    private TableView<ConfigController.Seuil> seuilsTableView;
    @FXML
    private TableColumn<ConfigController.Seuil, String> seuilNomColumn, seuilValeurColumn;
    @FXML
    private ListView<String> roomsListView;
    @FXML
    private Button saveButton;

    public void initContext(Stage stage, ConfigController configController) {
        this.stage = stage;
        this.configController = configController;
        this.configure();
    }

    private void configure() {
        // Utilisez les listes du ConfigController
        seuilsTableView.setItems(configController.getSeuilsList());
        roomsListView.setItems(configController.getRoomsList());

        // Charger les données depuis le contrôleur principal
        loadDataFromController();
    }

    /**
     * Méthode appelée automatiquement après le chargement du fichier FXML.
     */
    @FXML
    public void initialize() {
        // Initialisation des colonnes de la table des seuils
        seuilNomColumn.setCellValueFactory(cellData -> cellData.getValue().nomProperty());
        seuilValeurColumn.setCellValueFactory(cellData -> cellData.getValue().valeurProperty());

        // Gestion de l'état des cases à cocher des capteurs
        capteursCheckBox.selectedProperty().addListener((obs, oldVal, newVal) -> handleCapteursCheckBox());
    }

    /**
     * Charge les données depuis le contrôleur principal.
     */
    private void loadDataFromController() {
        // Charger les configurations depuis configController
        configController.loadConfig();

        mqttServerField.setText(configController.getMqttServer());
        outputFrequenceField.setText(configController.getOutputFrequence());

        capteursCheckBox.setSelected(configController.isCapteursSelected());
        solaredgeCheckBox.setSelected(configController.isSolaredgeSelected());

        // Charger les variables capteur sélectionnées
        temperatureCheckBox.setSelected(configController.isTemperatureSelected());
        humidityCheckBox.setSelected(configController.isHumiditySelected());
        activityCheckBox.setSelected(configController.isActivitySelected());
        co2CheckBox.setSelected(configController.isCo2Selected());
        tvocCheckBox.setSelected(configController.isTvocSelected());
        illuminationCheckBox.setSelected(configController.isIlluminationSelected());
        infraredCheckBox.setSelected(configController.isInfraredSelected());
        infraredVisibleCheckBox.setSelected(configController.isInfraredVisibleSelected());
        pressureCheckBox.setSelected(configController.isPressureSelected());

        // Charger les seuils et les salles
        seuilsTableView.setItems(configController.getSeuilsList());
        roomsListView.setItems(configController.getRoomsList());

        // Gérer l'état des cases à cocher
        handleCapteursCheckBox();
    }

    /**
     * Gère l'activation ou la désactivation des cases à cocher des capteurs.
     */
    @FXML
    private void handleCapteursCheckBox() {
        boolean isSelected = capteursCheckBox.isSelected();
        temperatureCheckBox.setDisable(!isSelected);
        humidityCheckBox.setDisable(!isSelected);
        activityCheckBox.setDisable(!isSelected);
        co2CheckBox.setDisable(!isSelected);
        tvocCheckBox.setDisable(!isSelected);
        illuminationCheckBox.setDisable(!isSelected);
        infraredCheckBox.setDisable(!isSelected);
        infraredVisibleCheckBox.setDisable(!isSelected);
        pressureCheckBox.setDisable(!isSelected);
    }

    /**
     * Enregistre la configuration en utilisant le contrôleur principal.
     */
    @FXML
    private void saveConfig() {
        // Mettre à jour les valeurs dans le contrôleur principal
        String mqttServer = mqttServerField.getText();
        String outputFrequence = outputFrequenceField.getText();
        boolean capteursSelected = capteursCheckBox.isSelected();
        boolean solaredgeSelected = solaredgeCheckBox.isSelected();

        List<String> variablesCapteur = new ArrayList<>();
        if (temperatureCheckBox.isSelected()) variablesCapteur.add("temperature");
        if (humidityCheckBox.isSelected()) variablesCapteur.add("humidity");
        if (activityCheckBox.isSelected()) variablesCapteur.add("activity");
        if (co2CheckBox.isSelected()) variablesCapteur.add("co2");
        if (tvocCheckBox.isSelected()) variablesCapteur.add("tvoc");
        if (illuminationCheckBox.isSelected()) variablesCapteur.add("illumination");
        if (infraredCheckBox.isSelected()) variablesCapteur.add("infrared");
        if (infraredVisibleCheckBox.isSelected()) variablesCapteur.add("infrared_and_visible");
        if (pressureCheckBox.isSelected()) variablesCapteur.add("pressure");

        // Appeler la méthode saveConfig sans passer les listes localement
        configController.saveConfig(mqttServer, outputFrequence, capteursSelected, solaredgeSelected, variablesCapteur);

        // Afficher une confirmation si nécessaire
        Alert alert = new Alert(Alert.AlertType.INFORMATION);
        alert.setTitle("Succès");
        alert.setHeaderText(null);
        alert.setContentText("La configuration a été sauvegardée avec succès.");
        alert.showAndWait();
    }

    // Méthodes pour gérer les seuils
    @FXML
    private void ajouterSeuil() {
        configController.ajouterSeuil();
    }

    @FXML
    private void modifierSeuil() {
        ConfigController.Seuil selectedSeuil = seuilsTableView.getSelectionModel().getSelectedItem();
        if (selectedSeuil != null) {
            configController.modifierSeuil(selectedSeuil);
            seuilsTableView.refresh();
        }
    }

    @FXML
    private void supprimerSeuil() {
        ConfigController.Seuil selectedSeuil = seuilsTableView.getSelectionModel().getSelectedItem();
        if (selectedSeuil != null) {
            configController.getSeuilsList().remove(selectedSeuil);
        }
    }

    // Méthodes pour gérer les salles
    @FXML
    private void ajouterSalle() {
        configController.ajouterSalle();
    }

    @FXML
    private void supprimerSalle() {
        String selectedSalle = roomsListView.getSelectionModel().getSelectedItem();
        if (selectedSalle != null) {
            configController.getRoomsList().remove(selectedSalle);
        }
    }
}