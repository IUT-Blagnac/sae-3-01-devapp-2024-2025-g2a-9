package com.example.controller;

import java.io.IOException;
import java.util.*;

import com.example.model.ConfigIni;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.control.*;
import javafx.scene.control.cell.PropertyValueFactory;

public class ConfigController {

    @FXML
    private TextField mqttServerField, outputFrequenceField;
    @FXML
    private CheckBox capteursCheckBox, solaredgeCheckBox;
    @FXML
    private CheckBox temperatureCheckBox, humidityCheckBox, activityCheckBox, co2CheckBox, tvocCheckBox, illuminationCheckBox, infraredCheckBox, infraredVisibleCheckBox, pressureCheckBox;
    @FXML
    private TableView<Seuil> seuilsTableView;
    @FXML
    private TableColumn<Seuil, String> seuilNomColumn, seuilValeurColumn;
    @FXML
    private ListView<String> roomsListView;

    private ObservableList<Seuil> seuilsList = FXCollections.observableArrayList();
    private ObservableList<String> roomsList = FXCollections.observableArrayList();

    private ConfigIni configIni = new ConfigIni();

    @FXML
    public void initialize() {
        try {
            System.out.println("Emplacement actuel : " + System.getProperty("user.dir"));
            String configFilePath = "IOT/config.ini";

            configIni.loadConfig(configFilePath);

            // Section MQTT
            mqttServerField.setText(configIni.getConfigValue("MQTT", "server"));
            mqttServerField.setDisable(true);

            // Sélection des topics
            String topics = configIni.getConfigValue("MQTT", "topics");
            if (topics != null) {
                capteursCheckBox.setSelected(topics.contains("AM107/#"));
                solaredgeCheckBox.setSelected(topics.contains("solaredge/#"));
            }

            // Sélection des valeurs à récupérer
            String variablesCapteur = configIni.getConfigValue("variables", "variable_capteur");
            if (variablesCapteur != null) {
                temperatureCheckBox.setSelected(variablesCapteur.contains("temperature"));
                humidityCheckBox.setSelected(variablesCapteur.contains("humidity"));
                activityCheckBox.setSelected(variablesCapteur.contains("activity"));
                co2CheckBox.setSelected(variablesCapteur.contains("co2"));
                tvocCheckBox.setSelected(variablesCapteur.contains("tvoc"));
                illuminationCheckBox.setSelected(variablesCapteur.contains("illumination"));
                infraredCheckBox.setSelected(variablesCapteur.contains("infrared"));
                infraredVisibleCheckBox.setSelected(variablesCapteur.contains("infrared_and_visible"));
                pressureCheckBox.setSelected(variablesCapteur.contains("pressure"));
            }

            // Gérer l'état des CheckBox des valeurs à récupérer
            handleCapteursCheckBox();

            // Seuils Capteur
            seuilNomColumn.setCellValueFactory(new PropertyValueFactory<>("nom"));
            seuilValeurColumn.setCellValueFactory(new PropertyValueFactory<>("valeur"));
            Map<String, String> seuilsCapteur = configIni.getSectionConfig("seuils_capteur");
            seuilsCapteur.forEach((key, value) -> seuilsList.add(new Seuil(key, value)));
            seuilsTableView.setItems(seuilsList);

            // Salles
            String rooms = configIni.getConfigValue("salles", "rooms");
            if (rooms != null && !rooms.isEmpty()) {
                roomsList.addAll(Arrays.asList(rooms.split(",")));
            }
            roomsListView.setItems(roomsList);

            // Fréquence
            outputFrequenceField.setText(configIni.getConfigValue("OUTPUT", "frequence"));

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

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

    @FXML
    private void saveConfig() {
        try {
            String configFilePath = "IOT/config.ini"; // Utilisez le même chemin qu'au chargement

            // Section MQTT
            configIni.setConfigValue("MQTT", "server", mqttServerField.getText());

            // Sélection des topics
            List<String> topics = new ArrayList<>();
            if (capteursCheckBox.isSelected()) {
                topics.add("AM107/by-room/#");
            }
            if (solaredgeCheckBox.isSelected()) {
                topics.add("solaredge/#");
            }
            configIni.setConfigValue("MQTT", "topics", String.join(",", topics));

            // Sélection des valeurs à récupérer
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
            configIni.setConfigValue("variables", "variable_capteur", String.join(",", variablesCapteur));

            // Seuils Capteur
            Map<String, String> seuilsCapteur = new HashMap<>();
            for (Seuil seuil : seuilsList) {
                seuilsCapteur.put(seuil.getNom(), seuil.getValeur());
            }
            configIni.setSectionConfig("seuils_capteur", seuilsCapteur);

            // Salles
            String rooms = String.join(",", roomsList);
            configIni.setConfigValue("salles", "rooms", rooms);

            // Fréquence
            configIni.setConfigValue("OUTPUT", "frequence", outputFrequenceField.getText());

            // Sauvegarde
            configIni.saveConfig(configFilePath);

            // Confirmation
            Alert alert = new Alert(Alert.AlertType.INFORMATION);
            alert.setTitle("Succès");
            alert.setHeaderText(null);
            alert.setContentText("La configuration a été sauvegardée avec succès.");
            alert.showAndWait();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    @FXML
    private void ajouterSeuil() {
        TextInputDialog dialog = new TextInputDialog();
        dialog.setTitle("Ajouter un seuil");
        dialog.setHeaderText(null);
        dialog.setContentText("Entrez le nom du seuil:");
        Optional<String> result = dialog.showAndWait();
        result.ifPresent(nom -> {
            TextInputDialog valeurDialog = new TextInputDialog();
            valeurDialog.setTitle("Valeur du seuil");
            valeurDialog.setHeaderText(null);
            valeurDialog.setContentText("Entrez la valeur du seuil:");
            Optional<String> valeurResult = valeurDialog.showAndWait();
            valeurResult.ifPresent(valeur -> seuilsList.add(new Seuil(nom, valeur)));
        });
    }

    @FXML
    private void modifierSeuil() {
        Seuil selectedSeuil = seuilsTableView.getSelectionModel().getSelectedItem();
        if (selectedSeuil != null) {
            TextInputDialog dialog = new TextInputDialog(selectedSeuil.getValeur());
            dialog.setTitle("Modifier le seuil");
            dialog.setHeaderText(null);
            dialog.setContentText("Nouvelle valeur pour " + selectedSeuil.getNom() + ":");
            Optional<String> result = dialog.showAndWait();
            result.ifPresent(valeur -> selectedSeuil.setValeur(valeur));
            seuilsTableView.refresh();
        }
    }

    @FXML
    private void supprimerSeuil() {
        Seuil selectedSeuil = seuilsTableView.getSelectionModel().getSelectedItem();
        if (selectedSeuil != null) {
            seuilsList.remove(selectedSeuil);
        }
    }

    @FXML
    private void ajouterSalle() {
        TextInputDialog dialog = new TextInputDialog();
        dialog.setTitle("Ajouter une salle");
        dialog.setHeaderText(null);
        dialog.setContentText("Entrez le nom de la salle:");
        Optional<String> result = dialog.showAndWait();
        result.ifPresent(salle -> roomsList.add(salle));
    }

    @FXML
    private void supprimerSalle() {
        String selectedSalle = roomsListView.getSelectionModel().getSelectedItem();
        if (selectedSalle != null) {
            roomsList.remove(selectedSalle);
        }
    }

    // Classe interne pour représenter un seuil
    public static class Seuil {
        private String nom;
        private String valeur;

        public Seuil(String nom, String valeur) {
            this.nom = nom;
            this.valeur = valeur;
        }

        public String getNom() {
            return nom;
        }

        public void setNom(String nom) {
            this.nom = nom;
        }

        public String getValeur() {
            return valeur;
        }

        public void setValeur(String valeur) {
            this.valeur = valeur;
        }
    }
}