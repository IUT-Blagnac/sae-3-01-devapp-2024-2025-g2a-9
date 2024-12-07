package application.view;

import application.control.MenuController;
import javafx.fxml.FXML;
import javafx.scene.control.Button;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

public class MenuViewController {

	// Contrôleur de Dialogue associé à MenuController
	private MenuController mDialogController;

	// Fenêtre physique ou est la scène contenant le fichier xml contrôlé par this
	private Stage containingStage;

	// Manipulation de la fenêtre
	public void initContext(Stage _containingStage, MenuController _m) {
		this.mDialogController = _m;
		this.containingStage = _containingStage;
		this.configure();
	}

	private void configure() {
		this.containingStage.setOnCloseRequest(e -> this.closeWindow(e));
	}

	public void displayDialog() {
		this.containingStage.show();
	}

	// Gestion du stage
	private Object closeWindow(WindowEvent e) {
		this.doQuitter();
		e.consume();
		return null;
	}

	// Attributs de la scene + actions
	@FXML
	private Button btnCapteurs;
	@FXML
	private Button btnPanneaux;
	@FXML
	private Button btnConfig;
	@FXML
	private Button btnQuitter;


	@FXML
	private void doCapteurs() {
		mDialogController.gestionCapteurs();
	}
	@FXML
	private void doPanneaux() {
		mDialogController.gestionPanneaux();
	}
	@FXML
	private void doConfig() {
		mDialogController.configuration();
	}
	@FXML
	private void doQuitter() {
		this.containingStage.close();
	}
}
