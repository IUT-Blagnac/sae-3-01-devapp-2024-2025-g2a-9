package application.control;

import application.model.DataEnergie;
import application.view.PanneauxViewController;
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
        // Un moyen de lire le json et en faire une liste de dataEnergie (dans model)
    }

    public void loadTable(TableView<DataEnergie> realTimeTable, ObservableList<DataEnergie> dataEnergies) {
        realTimeTable.getItems().clear();
        if (!dataEnergies.isEmpty()) {
            DataEnergie last = dataEnergies.get(dataEnergies.size() - 1);
            ObservableList<DataEnergie> lastData = javafx.collections.FXCollections.observableArrayList(last);
            realTimeTable.setItems(lastData);
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
        loadTable(realTimeTable, dataEnergies);
        loadChart(lineChart, dataEnergies);
        // Faudrait faire un thread qui reload les panneaux tout les X secondes et vérifie si les données changent
    }
}
