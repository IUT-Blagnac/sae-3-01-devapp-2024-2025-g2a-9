package application.view;

import java.util.Map;

import application.control.PanneauxController;
import application.model.DataEnergie;
import javafx.fxml.FXML;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.scene.chart.LineChart;
import javafx.scene.control.MenuItem;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.scene.control.ScrollPane;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

public class PanneauxViewController {

	// Contrôleur de Dialogue associé à PanneauxController
	private PanneauxController pDialogController;
	private ObservableList<DataEnergie> dataEnergies;

	// Fenêtre physique ou est la scène contenant le fichier xml contrôlé par this
	private Stage containingStage;

	// Manipulation de la fenêtre
	public void initContext(Stage _containingStage, PanneauxController _p) {
		this.pDialogController = _p;
		this.containingStage = _containingStage;
		this.configure();
	}

	private void configure() {
		dataEnergies = FXCollections.observableArrayList();
		pDialogController.loadPanneaux(dataEnergies);

		 // Configuration des colonnes
		typeColumn.setCellValueFactory(new PropertyValueFactory<>("type"));
		dataColumn.setCellValueFactory(new PropertyValueFactory<>("value"));

		pDialogController.loadTable(realTimeTable, dataEnergies);
		pDialogController.loadChart(lineChart, dataEnergies);
		this.containingStage.setOnCloseRequest(e -> this.closeWindow(e));
	}

	public void displayDialog() {
		this.containingStage.showAndWait();
	}

	// Gestion du stage
	private Object closeWindow(WindowEvent e) {
		this.doQuitter();
		e.consume();
		return null;
	}

	// Attributs de la scene + actions
    @FXML
    private TableView<DataEnergie> realTimeTable;

    @FXML
    private TableColumn<DataEnergie, String> typeColumn;

    @FXML
    private TableColumn<DataEnergie, Double> dataColumn;

    @FXML
    private LineChart<String, Number> lineChart;

    @FXML
    private MenuItem quitMenuItem;

    @FXML
    private MenuItem configMenuItem;

    @FXML
    private MenuItem helpMenuItem;

	
	@FXML
	private void doConfig() {
	}
	@FXML
	private void doQuitter() {
		this.containingStage.close();
	}
}
