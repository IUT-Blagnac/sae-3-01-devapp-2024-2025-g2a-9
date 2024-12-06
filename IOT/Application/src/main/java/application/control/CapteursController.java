package application.control;

import application.NauticGestApp;
import application.view.CapteursViewController;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;

/**
 * La classe CapteursController gère les opérations de gestion des Capteurs.
 */
public class CapteursController {

    /**
     * Stage pour la fenêtre de gestion des Capteurs.
     */
    private Stage cStage;

    /**
     * Contrôleur de la vue de gestion des clients.
     */
    private CapteursViewController cViewController;

	/**
     * Constructeur de la classe CapteursController.
     *  
     * @param _parentStage Stage parent de cette fenêtre.
     */
	public CapteursController(Stage _parentStage) {
		try {
			FXMLLoader loader = new FXMLLoader(CapteursViewController.class.getResource("lecture.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);
			scene.getStylesheets().add(NauticGestApp.class.getResource("application.css").toExternalForm());

			this.cStage = new Stage();
			this.cStage.initModality(Modality.WINDOW_MODAL);
			this.cStage.initOwner(_parentStage);
			this.cStage.setScene(scene);
			this.cStage.setTitle("Gestion des Capteurs");
			this.cStage.setResizable(false);

			this.cViewController = loader.getController();
			this.cViewController.initContext(this.cStage, this);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
     * Affiche la vue de gestion des Capteurs.
     */
	public void doCapteursControllerDialog() {
		this.cViewController.displayDialog();
	}

}
