package application.view;

import application.control.PanneauxController;
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

	// Attributs de la scene + actions
	
}
