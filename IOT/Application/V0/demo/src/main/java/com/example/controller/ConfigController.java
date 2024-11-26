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
    private TextField mqttServerField, mqttTopicsField, outputFrequenceField;
    @FXML
    private RadioButton variableSolaredgeButton, variableCapteurButton;
    @FXML
    private ToggleGroup variablesGroup;
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
            String configFilePath = "../../config.ini"; // Remplacez par le chemin réel

            configIni.loadConfig(configFilePath);

            // Section MQTT
            mqttServerField.setText(configIni.getConfigValue("MQTT", "server"));
            mqttTopicsField.setText(configIni.getConfigValue("MQTT", "topics"));

            // Variables à récupérer
            variablesGroup = new ToggleGroup();
            variableSolaredgeButton.setToggleGroup(variablesGroup);
            variableCapteurButton.setToggleGroup(variablesGroup);
            String variableChoisie = configIni.getConfigValue("variables", "variable_choisie");
            if ("solaredge".equalsIgnoreCase(variableChoisie)) {
                variableSolaredgeButton.setSelected(true);
            } else if ("capteur".equalsIgnoreCase(variableChoisie)) {
                variableCapteurButton.setSelected(true);
            }

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

    @FXML
    private void saveConfig() {
        try {
            String configFilePath = "path/to/config.ini"; // Remplacez par le chemin réel

            // Section MQTT
            configIni.setConfigValue("MQTT", "server", mqttServerField.getText());
            configIni.setConfigValue("MQTT", "topics", mqttTopicsField.getText());

            // Variables à récupérer
            RadioButton selectedRadioButton = (RadioButton) variablesGroup.getSelectedToggle();
            String variableChoisie = selectedRadioButton.getText().toLowerCase();
            configIni.setConfigValue("variables", "variable_choisie", variableChoisie);

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