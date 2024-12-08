package application.view;

import application.control.PanneauxController;
import application.model.DataEnergie;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;
import javafx.fxml.FXML;
import javafx.scene.chart.LineChart;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.cell.PropertyValueFactory;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

public class PanneauxViewController {

    // Contrôleur de Dialogue associé à PanneauxController
    private PanneauxController pDialogController;
    private ObservableList<DataEnergie> dataEnergies;

    // Fenêtre physique ou est la scène contenant le fichier xml contrôlé par this
    private Stage containingStage;

    // Eléments FXML
    @FXML
    private TableView<DataEnergie> realTimeTable;

    @FXML
    private TableColumn<DataEnergie, String> typeColumn;

    @FXML
    private TableColumn<DataEnergie, Double> dataColumn;

    @FXML
    private LineChart<Number, Number> lineChart;

    @FXML
    public void initialize() {
        typeColumn.setCellValueFactory(new PropertyValueFactory<>("date"));
        dataColumn.setCellValueFactory(new PropertyValueFactory<>("value"));
    }

    // Manipulation de la fenêtre
    public void initContext(Stage _containingStage, PanneauxController _p) {
        this.pDialogController = _p;
        this.containingStage = _containingStage;
        this.configure();
    }

    private void configure() {
        dataEnergies = FXCollections.observableArrayList();
        pDialogController.loadPanneaux(dataEnergies); // Faut récupérer la liste des données des Panneaux

        pDialogController.loadTable(realTimeTable, dataEnergies);
        pDialogController.loadChart(lineChart, dataEnergies);

        pDialogController.updateData(dataEnergies, realTimeTable, lineChart);
        this.containingStage.setOnCloseRequest(e -> this.closeWindow(e));
    }

    public void displayDialog() {
        this.containingStage.showAndWait();
    }

    // Gestion du stage
    private Object closeWindow(WindowEvent e) {
        pDialogController.stopUpdateThread();
        this.doQuitter();
        e.consume();
        return null;
    }

    @FXML
    private void doAide() {
    }

    @FXML
    private void doConfig() {
    }

    @FXML
    private void doQuitter() {
        this.containingStage.close();
    }
}