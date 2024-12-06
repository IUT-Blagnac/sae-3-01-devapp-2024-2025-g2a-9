package application.view;

import application.control.PanneauxController;
import javafx.fxml.FXML;
import javafx.scene.control.MenuItem;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TabPane;
import javafx.scene.control.Tab;
import javafx.scene.control.ScrollPane;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

public class PanneauxViewController {

	// Contrôleur de Dialogue associé à PanneauxController
	private PanneauxController pDialogController;

	// Fenêtre physique ou est la scène contenant le fichier xml contrôlé par this
	private Stage containingStage;

	// Manipulation de la fenêtre
	public void initContext(Stage _containingStage, PanneauxController _p) {
		this.pDialogController = _p;
		this.containingStage = _containingStage;
		this.configure();
	}

	private void configure() {
		this.containingStage.setOnCloseRequest(e -> this.closeWindow(e));
	}

	public void displayDialog() {
		this.containingStage.showAndWait();
	}

	// Gestion du stage
	private Object closeWindow(WindowEvent e) {
		this.doCancel();
		e.consume();
		return null;
	}

	/**
     * Actions effectuées lors de l'annulation.
     */
    private void doCancel() {
        System.out.println("Window close request intercepted. Cancelling close.");
    }


	// Attributs de la scene + actions
	@FXML
    private TabPane tabPane;

    @FXML
    private Tab realTimeTab;

    @FXML
    private TableView<?> realTimeTable;

    @FXML
    private TableColumn<?, ?> typeColumn;

    @FXML
    private TableColumn<?, ?> dataColumn;

    @FXML
    private Tab historyTab;

    @FXML
    private ScrollPane historyScrollPane;

    @FXML
    private MenuItem quitMenuItem;

    @FXML
    private MenuItem configMenuItem;

    @FXML
    private MenuItem helpMenuItem;
}
