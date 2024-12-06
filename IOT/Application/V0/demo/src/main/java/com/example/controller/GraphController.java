package com.example.controller;

import com.example.service.FichierJson;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import javafx.application.Platform;
import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;

import java.time.*;
import java.time.format.DateTimeFormatter;
import java.time.format.DateTimeParseException;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.*;

public class GraphController {

    @FXML
    private LineChart<Number, Number> temperatureChart;

    private Map<String, XYChart.Series<Number, Number>> seriesMap = new HashMap<>();
    private Instant startTimeInstant = Instant.now();

    private ScheduledExecutorService executorService;
    private FichierJson FichierJson = new FichierJson();

    private static final DateTimeFormatter DATE_FORMATTER = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");
    private static final int MAX_POINTS = 8; // Nombre maximum de points visibles dans le graphique

    @FXML
    public void initialize() {
        // Configurer axes
        temperatureChart.getXAxis().setLabel("Temps (s)");
        temperatureChart.getYAxis().setLabel("Température (°C)");

        startDataUpdate();
    }

    private void startDataUpdate() {
        executorService = Executors.newSingleThreadScheduledExecutor();
        executorService.scheduleAtFixedRate(() -> {
            Platform.runLater(this::updateData);
        }, 0, 5, TimeUnit.SECONDS); // MAJ toutes les 5 secondes
    }

    private void updateData() {
        try {
            JsonArray dataArray = FichierJson.readJsonFile("IOT/donnees.json");
            if (dataArray.size() == 0) {
                System.out.println("Le fichier donnees.json est vide.");
                return;
            }

            // Heure actuelle et seuil de 2 heures (pour enlever les données plus anciennes)
            LocalDateTime now = LocalDateTime.now(ZoneId.systemDefault());
            LocalDateTime cutoff = now.minusHours(2);

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

                // Verif si la donnée est dans les deux dernières heures
                if (dataTime.isBefore(cutoff)) {
                    continue; // Ignorer les données plus anciennes
                }

                // Calculer le temps écoulé depuis le début en secondes
                long elapsedSeconds = Duration.between(startTimeInstant, dataTime.atZone(ZoneId.systemDefault()).toInstant()).getSeconds();

                String room = data.has("room") ? data.get("room").getAsString() : "Inconnu";
                Number temperature = null;

                // Gestion des différents formats de la température dans les fichiers JSON
                if (data.has("temperature")) {
                    if (data.get("temperature").isJsonArray()) {
                        // Si "temperature" est un tableau (comme dans alert.json)
                        JsonArray tempArray = data.get("temperature").getAsJsonArray();
                        if (tempArray.size() > 0 && tempArray.get(0).isJsonPrimitive()) {
                            temperature = tempArray.get(0).getAsDouble();
                        }
                    } else if (data.get("temperature").isJsonPrimitive()) {
                        // Si "temperature" est une valeur simple
                        temperature = data.get("temperature").getAsDouble();
                    }
                }

                if (temperature != null) {
                    XYChart.Series<Number, Number> series = seriesMap.get(room);
                    if (series == null) {
                        series = new XYChart.Series<>();
                        series.setName(room);
                        seriesMap.put(room, series);
                        temperatureChart.getData().add(series);
                    }

                    // Ajouter la nouvelle donnée
                    series.getData().add(new XYChart.Data<>(elapsedSeconds, temperature));
                    System.out.println("Ajout de la donnée: " + elapsedSeconds + ", " + temperature + "°C pour la salle " + room);

                    // Limiter le nombre de points par série
                    if (series.getData().size() > MAX_POINTS) {
                        series.getData().remove(0);
                    }
                } else {
                    System.out.println("Aucune donnée de température trouvée dans l'enregistrement.");
                }
            }

            // Nettoyer les données plus anciennes que deux heures
            nettoyerDonnees(cutoff);

        } catch (Exception e) {
            System.err.println("Erreur lors de la mise à jour des données: " + e.getMessage());
            e.printStackTrace();
        }
    }

    // Méthode pour nettoyer les données plus anciennes que le seuil
    private void nettoyerDonnees(LocalDateTime cutoff) {
        for (Map.Entry<String, XYChart.Series<Number, Number>> entry : seriesMap.entrySet()) {
            XYChart.Series<Number, Number> series = entry.getValue();
            series.getData().removeIf(data -> {
                // Recalculer la date à partir du temps écoulé
                long elapsedSeconds = data.getXValue().longValue();
                Instant dataInstant = startTimeInstant.plusSeconds(elapsedSeconds);
                LocalDateTime dataTime = LocalDateTime.ofInstant(dataInstant, ZoneId.systemDefault());
                return dataTime.isBefore(cutoff);
            });
        }
    }

    // Pour arrêter le ScheduledExecutorService lors de la fermeture de l'application
    public void shutdown() {
        if (executorService != null && !executorService.isShutdown()) {
            executorService.shutdown();
            try {
                if (!executorService.awaitTermination(1, TimeUnit.SECONDS)) {
                    executorService.shutdownNow();
                }
            } catch (InterruptedException e) {
                executorService.shutdownNow();
            }
        }
    }
}