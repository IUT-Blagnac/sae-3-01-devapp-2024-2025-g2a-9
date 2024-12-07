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

    private Stage pStage;
    private PanneauxViewController pViewController;
    private ScheduledExecutorService executorService;

    public PanneauxController(Stage _parentStage) {
        try {
            FXMLLoader loader = new FXMLLoader(getClass().getResource("/application/view/lectureP.fxml"));
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

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void doPanneauxControllerDialog() {
        this.pViewController.displayDialog();
    }

    public void loadPanneaux(ObservableList<DataEnergie> dataEnergies) {
        EnergieExtraction extraction;
        try {
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
            ObservableList<DataEnergie> lastData = javafx.collections.FXCollections.observableArrayList(last);
            realTimeTable.setItems(lastData);
        }
    }

    public void loadChart(LineChart<Number, Number> lineChart, ObservableList<DataEnergie> dataEnergies) {
        lineChart.getData().clear();
        XYChart.Series<Number, Number> series = new XYChart.Series<>();
        series.setName("Énergie");

        int elapsedSeconds = 0;
        for (DataEnergie data : dataEnergies) {
            series.getData().add(new XYChart.Data<>(elapsedSeconds++, data.getValue()));
        }
        lineChart.getData().add(series);
    }

    public void updateData(ObservableList<DataEnergie> dataEnergies, TableView<DataEnergie> realTimeTable, LineChart<Number, Number> lineChart) {
        executorService = Executors.newSingleThreadScheduledExecutor();

        executorService.scheduleAtFixedRate(() -> {
            int sizeOld = dataEnergies.size();
            loadPanneaux(dataEnergies);

            if (sizeOld != dataEnergies.size()) {
                Platform.runLater(() -> {
                    loadTable(realTimeTable, dataEnergies);
                    loadChart(lineChart, dataEnergies);
                });
            }
        }, 0, 5, TimeUnit.SECONDS);
    }

    public void stopUpdateThread() {
        if (executorService != null && !executorService.isShutdown()) {
            executorService.shutdown();
        }
    }
}