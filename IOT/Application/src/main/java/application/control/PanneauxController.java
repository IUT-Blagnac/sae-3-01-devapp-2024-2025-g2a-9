package application.control;

import java.io.IOException;
import java.util.concurrent.Executors;
import java.util.concurrent.ScheduledExecutorService;
import java.util.concurrent.TimeUnit;

import application.model.DataEnergie;
import application.tools.EnergieExtraction;
import application.view.PanneauxViewController;
import javafx.application.Platform;
import javafx.collections.ObservableList;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.chart.LineChart;
import javafx.scene.chart.XYChart;
import javafx.scene.control.TableView;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

/**
 * La classe PanneauxController gère les opérations de gestion des Panneaux.
 */
public class PanneauxController {

    /**
     * Stage pour la fenêtre de gestion des Panneaux.
     */
    private Stage pStage;

    /**
     * Contrôleur de la vue de gestion des Panneaux.
     */
    private PanneauxViewController pViewController;

    private ScheduledExecutorService executorService;


	/**
     * Constructeur de la classe PanneauxController.
     *
     * @param _parentStage Stage parent de cette fenêtre.
     */
	public PanneauxController(Stage _parentStage) {
		try {
			FXMLLoader loader = new FXMLLoader(PanneauxViewController.class.getResource("lectureP.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);

			this.pStage = new Stage();
			this.pStage.initModality(Modality.NONE);
			this.pStage.initOwner(_parentStage);
			this.pStage.setScene(scene);
			this.pStage.setTitle("Gestion des panneaux solaires");
            this.pStage.setMaximized(true);
			this.pStage.setResizable(true);

			this.pViewController = loader.getController();
			this.pViewController.initContext(this.pStage, this);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}
    
	/**
     * Affiche la vue de gestion des panneaux.
     */
	public void doPanneauxControllerDialog() {
		this.pViewController.displayDialog();
	}

    public void loadPanneaux(ObservableList<DataEnergie> dataEnergies) {
        EnergieExtraction extraction;
        try {
            // On utilise la classe extraction qui va récupérer les données à partir du json
            extraction = new EnergieExtraction("../donnees.json");
            extraction.extractEnergyData(dataEnergies);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void loadTable(TableView<DataEnergie> realTimeTable, ObservableList<DataEnergie> dataEnergies) {
        realTimeTable.getItems().clear();
        if (!dataEnergies.isEmpty()) {
            DataEnergie last = dataEnergies.get(dataEnergies.size() - 1);
            ObservableList<DataEnergie> lastData = javafx.collections.FXCollections.observableArrayList(last); // Obligé de refaire une List
            realTimeTable.setItems(lastData); // On met que la dernière (c'est celle qui nous intéresse)
        }
    }

    public void loadChart(LineChart<String, Number> lineChart, ObservableList<DataEnergie> dataEnergies) {
        lineChart.getData().clear(); // Efface les anciennes données
        XYChart.Series<String, Number> series = new XYChart.Series<>();
        series.setName("Énergie");
        for (DataEnergie data : dataEnergies) {
            series.getData().add(new XYChart.Data<>(data.getDate().toString(), data.getValue()));
        }
        lineChart.getData().add(series); // Ajoute les données à la courbe
    }

    public void updateData(ObservableList<DataEnergie> dataEnergies, TableView<DataEnergie> realTimeTable, LineChart<String, Number> lineChart) {
        executorService = Executors.newSingleThreadScheduledExecutor(); // Nouveau Thread

        executorService.scheduleAtFixedRate(() -> {
            int sizeOld = dataEnergies.size();
            loadPanneaux(dataEnergies);

            if (sizeOld != dataEnergies.size()) {
                Platform.runLater(() -> {
                    loadTable(realTimeTable, dataEnergies);
                    loadChart(lineChart, dataEnergies);
                        });
            }
        }, 0, 5, TimeUnit.SECONDS); // Mise à jour toutes les 5 secondes
    }

    /**
     * Arrête le thread de la méthode updateData.
     */
    public void stopUpdateThread() {
        if (executorService != null && !executorService.isShutdown()) {
            executorService.shutdown(); // Propre
            try {
                // Attendre jusqu'à 5 secondes pour les tâches en cours
                if (!executorService.awaitTermination(5, TimeUnit.SECONDS)) {
                    executorService.shutdownNow(); // Pas propre mais pour l'utilisateur
                }
            } catch (InterruptedException e) {
                executorService.shutdownNow(); // Forcer l'arrêt en cas d'interruption
                Thread.currentThread().interrupt(); // Réinterrompre le thread principal
            }
        }
    }
}
