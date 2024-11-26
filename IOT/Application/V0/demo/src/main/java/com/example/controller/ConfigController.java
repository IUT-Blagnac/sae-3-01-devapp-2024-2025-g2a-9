package com.example.controller;

import java.io.IOException;
import com.example.model.ConfigIni;
import javafx.fxml.FXML;
import javafx.scene.control.TextField;

/**
 * Contrôleur pour gérer l'interface utilisateur de configuration.
 */
public class ConfigController {
    @FXML
    private TextField mqttServerField;
    @FXML
    private TextField mqttTopicsField;
    @FXML
    private TextField outputFileField;
    @FXML
    private TextField outputAlertField;
    @FXML
    private TextField outputFrequenceField;

    private ConfigIni configIni = new ConfigIni();

    /**
     * initialisation du controleur et chargement de la config actuelle
     * methode appelle automatique dès le chargement de la vue (config.fxml)
     * @author Thomas
     */
    @FXML
    public void initialize() {
        try {
            // on est dans le dossier V0 donc au niveau du dossier demo
            String configFilePath = "../../config.ini";
            configIni.loadConfig(configFilePath);
            mqttServerField.setText(configIni.getConfigValue("MQTT", "server"));
            mqttTopicsField.setText(configIni.getConfigValue("MQTT", "topics"));
            outputFileField.setText(configIni.getConfigValue("OUTPUT", "file"));
            outputAlertField.setText(configIni.getConfigValue("OUTPUT", "alert"));
            outputFrequenceField.setText(configIni.getConfigValue("OUTPUT", "frequence"));
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    /**
     * sauvegarde les modifications
     * appelée dès le clic sur "save" dans la vue (config.fxml)
     * @author Thomas
     */
    @FXML
    private void saveConfig() {
        try {
            String configFilePath = "../../config.ini";
            configIni.setConfigValue("MQTT", "server", mqttServerField.getText());
            configIni.setConfigValue("MQTT", "topics", mqttTopicsField.getText());
            configIni.setConfigValue("OUTPUT", "file", outputFileField.getText());
            configIni.setConfigValue("OUTPUT", "alert", outputAlertField.getText());
            configIni.setConfigValue("OUTPUT", "frequence", outputFrequenceField.getText());
            configIni.saveConfig(configFilePath);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}