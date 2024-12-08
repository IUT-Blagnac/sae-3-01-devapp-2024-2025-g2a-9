package application.control;

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
     * Contrôleur de la vue de gestion des capteurs.
     */
    private CapteursViewController cViewController;

    /**
     * Constructeur de la classe CapteursController.
     *  
     * @param _parentStage Stage parent de cette fenêtre.
     */
    public CapteursController(Stage _parentStage) {
        try {
            FXMLLoader loader = new FXMLLoader(CapteursViewController.class.getResource("/application/view/lectureC.fxml"));
            BorderPane root = loader.load();

            Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);

            this.cStage = new Stage();
            this.cStage.initModality(Modality.NONE);
            this.cStage.initOwner(_parentStage);
            this.cStage.setScene(scene);
            this.cStage.setTitle("Gestion des Capteurs");
            this.cStage.setResizable(true);

            this.cViewController = loader.getController();
            this.cViewController.initContext(this.cStage, this);

        } catch (Exception e) {
            e.printStackTrace();
            throw new RuntimeException("Erreur lors du chargement de lectureC.fxml", e);
        }
    }

    /**
     * Affiche la vue de gestion des Capteurs.
     */
    public void doCapteursControllerDialog() {
        this.cViewController.displayDialog();
    }
}