package application.view;

import application.control.CapteursController;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

public class CapteursViewController {

	// Contrôleur de Dialogue associé à CapteursController
	private CapteursController cDialogController;

	// Fenêtre physique ou est la scène contenant le fichier xml contrôlé par this
	private Stage cStage;

	// Manipulation de la fenêtre
	public void initContext(Stage _cStage, CapteursController _p) {
		this.cDialogController = _p;
		this.cStage = _cStage;
		this.configure();
	}

	private void configure() {
		this.cStage.setOnCloseRequest(e -> this.closeWindow(e));
	}

	public void displayDialog() {
		this.cStage.showAndWait();
	}

	// Gestion du stage
	private Object closeWindow(WindowEvent e) {
		e.consume();
		return null;
	}

	// Attributs de la scene + actions
	
}
