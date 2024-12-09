package application.control;

import application.model.Seuil;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.stage.Stage;
import application.tools.ConfigIni;
import javafx.scene.control.TextInputDialog;

import java.io.*;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.*;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;

/**
 * Contrôleur de la fenêtre de configuration.
 * @author Thomas
 */
public class ConfigController {

    private ConfigIni configIni = new ConfigIni();
    private ObservableList<Seuil> seuilsList = FXCollections.observableArrayList();
    private ObservableList<String> roomsList = FXCollections.observableArrayList();
    private ScheduledExecutorService executorService;
    private Process pythonProcess;

    private String mqttServer;
    private String outputFrequence;
    private boolean capteursSelected;
    private boolean solaredgeSelected;
    private boolean temperatureSelected;
    private boolean humiditySelected;
    private boolean activitySelected;
    private boolean co2Selected;
    private boolean tvocSelected;
    private boolean illuminationSelected;
    private boolean infraredSelected;
    private boolean infraredVisibleSelected;
    private boolean pressureSelected;


    /**
     * Démarre la fenêtre de configuration.
     * 
     * @param stage la scène principale de l'application
     * 
     * @author Thomas
     */
    public void start(Stage stage) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/config.fxml"));
            Scene scene = new Scene(loader.load(), 600, 800);
            application.view.ConfigViewController configViewController = loader.getController();
            configViewController.initContext(stage, this);
            stage.setScene(scene);
            stage.setTitle("Configuration");
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }


    /**
     * Charge la configuration depuis le fichier config.ini.
     * 
     * @author Thomas
     */
    public void loadConfig() {
        try {
            String configFilePath = "../config.ini";
            configIni.loadConfig(configFilePath);

            // Charger les seuils
            Map<String, String> seuilsCapteur = configIni.getSectionConfig("seuils_capteur");
            seuilsCapteur.forEach((key, value) -> seuilsList.add(new Seuil(key, value)));

            // Charger les salles
            String rooms = configIni.getConfigValue("salles", "rooms");
            if (rooms != null && !rooms.isEmpty()) {
                roomsList.addAll(Arrays.asList(rooms.split(",")));
            }

            // Charger les autres configurations
            mqttServer = configIni.getConfigValue("MQTT", "server");
            outputFrequence = configIni.getConfigValue("OUTPUT", "frequence");
            capteursSelected = Boolean.parseBoolean(configIni.getConfigValue("MQTT", "capteursSelected"));
            solaredgeSelected = Boolean.parseBoolean(configIni.getConfigValue("MQTT", "solaredgeSelected"));
            temperatureSelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "temperatureSelected"));
            humiditySelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "humiditySelected"));
            activitySelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "activitySelected"));
            co2Selected = Boolean.parseBoolean(configIni.getConfigValue("variables", "co2Selected"));
            tvocSelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "tvocSelected"));
            illuminationSelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "illuminationSelected"));
            infraredSelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "infraredSelected"));
            infraredVisibleSelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "infraredVisibleSelected"));
            pressureSelected = Boolean.parseBoolean(configIni.getConfigValue("variables", "pressureSelected"));

        } catch (IOException e) {
            e.printStackTrace();
        }
    }


    /**
     * Sauvegarde la configuration dans le fichier config.ini.
     * 
     * @param mqttServer l'adresse du serveur MQTT
     * @param outputFrequence la fréquence de sortie
     * @param capteursSelected si les capteurs sont sélectionnés
     * @param solaredgeSelected si Solaredge est sélectionné
     * @param variablesCapteur la liste des variables des capteurs sélectionnées
     * 
     * @author Thomas
     */
    public void saveConfig(String mqttServer, String outputFrequence, boolean capteursSelected, boolean solaredgeSelected, List<String> variablesCapteur) {
        try {
            String configFilePath = "../config.ini";

            // Section MQTT
            configIni.setConfigValue("MQTT", "server", mqttServer);

            // Sélection des topics
            List<String> topics = new ArrayList<>();
            if (capteursSelected) {
                topics.add("AM107/by-room/#");
            }
            if (solaredgeSelected) {
                topics.add("solaredge/#");
            }
            configIni.setConfigValue("MQTT", "topics", String.join(",", topics));

            // Sélection des variables à récupérer
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
            configIni.setConfigValue("OUTPUT", "frequence", outputFrequence);

            // Sauvegarde
            configIni.saveConfig(configFilePath);

            Files.deleteIfExists(Paths.get("../donnees.json"));
            Files.deleteIfExists(Paths.get("../alert.json"));

            // Lancer le script Python de manière asynchrone
            ProcessBuilder pb = new ProcessBuilder("python3", "prg.py");
            pb.directory(new File("../")); // Définir le répertoire de travail
            pb.redirectErrorStream(true);
            pythonProcess = pb.start();

            // Capturer la sortie du script Python dans un thread séparé
            Executors.newSingleThreadExecutor().submit(() -> {
                try (BufferedReader reader = new BufferedReader(new InputStreamReader(pythonProcess.getInputStream()))) {
                    String line;
                    while ((line = reader.readLine()) != null) {
                        System.out.println(line);
                    }
                } catch (IOException e) {
                    e.printStackTrace();
                }
            });

            // Lire les fichiers JSON en continu
            startReadingJsonFiles();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }


    /**
     * Démarre la lecture périodique des fichiers JSON.
     * 
     * @author Thomas
     */
    private void startReadingJsonFiles() {
        executorService = Executors.newSingleThreadScheduledExecutor();
        executorService.scheduleAtFixedRate(() -> {
            readJsonFile("../donnees.json");
            readJsonFile("../alert.json");
        }, 0, 2, TimeUnit.SECONDS); // Mise à jour toutes les 2 secondes
    }


    /**
     * Lit et traite le fichier JSON spécifié.
     * 
     * @param filePath le chemin du fichier JSON à lire
     * 
     * @author Thomas
     */
    private void readJsonFile(String filePath) {
        try {
            Path path = Paths.get(filePath);
            if (!Files.exists(path)) {
                return;
            }

            Reader reader = Files.newBufferedReader(path);
            JsonArray dataArray = JsonParser.parseReader(reader).getAsJsonArray();
            reader.close();

            // Traitez les données JSON ici
            for (int i = 0; i < dataArray.size(); i++) {
                JsonObject jsonObject = dataArray.get(i).getAsJsonObject();
                System.out.println(jsonObject);
                // Exemple: Mettre à jour une TableView ou autre composant UI
                // Vous pouvez ajouter des méthodes pour actualiser votre UI avec les données
            }

        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    /**
     * Retourne la liste des seuils.
     * 
     * @return la liste des seuils
     * 
     * @author Thomas
     */
    public ObservableList<Seuil> getSeuilsList() {
        return seuilsList;
    }


    /**
     * Retourne la liste des salles.
     * 
     * @return la liste des salles
     * 
     * @author Thomas
     */
    public ObservableList<String> getRoomsList() {
        return roomsList;
    }


    /**
     * Ajoute un nouveau seuil à la liste des seuils.
     * 
     * @author Thomas
     */
    public void ajouterSeuil() {
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


    /**
     * Modifie la valeur d'un seuil sélectionné.
     * 
     * @param selectedSeuil le seuil sélectionné à modifier
     * 
     * @author Thomas
     */
    public void modifierSeuil(Seuil selectedSeuil) {
        if (selectedSeuil != null) {
            TextInputDialog dialog = new TextInputDialog(selectedSeuil.getValeur());
            dialog.setTitle("Modifier le seuil");
            dialog.setHeaderText(null);
            dialog.setContentText("Nouvelle valeur pour " + selectedSeuil.getNom() + ":");
            Optional<String> result = dialog.showAndWait();
            result.ifPresent(valeur -> selectedSeuil.setValeur(valeur));
        }
    }


    /**
     * Ajoute une nouvelle salle à la liste des salles.
     * 
     * @author Thomas
     */
    public void ajouterSalle() {
        TextInputDialog dialog = new TextInputDialog();
        dialog.setTitle("Ajouter une salle");
        dialog.setHeaderText(null);
        dialog.setContentText("Entrez le nom de la salle:");
        Optional<String> result = dialog.showAndWait();
        result.ifPresent(salle -> roomsList.add(salle));
    }


    /**
     * Arrête le processus Python en cours d'exécution.
     * 
     * @author Thomas
     */
    public void stopPythonProcess() {
        if (pythonProcess != null && pythonProcess.isAlive()) {
            pythonProcess.destroy();
        }
    }

    
    // Getters
    public String getMqttServer() {
        return mqttServer;
    }

    public String getOutputFrequence() {
        return outputFrequence;
    }

    public boolean isCapteursSelected() {
        return capteursSelected;
    }

    public boolean isSolaredgeSelected() {
        return solaredgeSelected;
    }

    public boolean isTemperatureSelected() {
        return temperatureSelected;
    }

    public boolean isHumiditySelected() {
        return humiditySelected;
    }

    public boolean isActivitySelected() {
        return activitySelected;
    }

    public boolean isCo2Selected() {
        return co2Selected;
    }

    public boolean isTvocSelected() {
        return tvocSelected;
    }

    public boolean isIlluminationSelected() {
        return illuminationSelected;
    }

    public boolean isInfraredSelected() {
        return infraredSelected;
    }

    public boolean isInfraredVisibleSelected() {
        return infraredVisibleSelected;
    }

    public boolean isPressureSelected() {
        return pressureSelected;
    }
}