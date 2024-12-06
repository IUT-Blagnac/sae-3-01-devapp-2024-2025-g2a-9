package application.control;

import application.view.MenuViewController;
import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;

/**
 * Classe de controleur de Dialogue de la fenêtre principale.
 */
public class MenuController extends Application {
    // Stage de la fenêtre principale construite par MenuController
    private Stage mStage;

    /**
     * Méthode de démarrage (JavaFX).
     */
    @Override
    public void start(Stage primaryStage) {
        this.mStage = primaryStage;

        try {
            // Chargement du source fxml
            FXMLLoader loader = new FXMLLoader(MenuViewController.class.getResource("menuP.fxml"));
            BorderPane root = loader.load();

            // Paramétrage du Stage : feuille de style, titre
            Scene scene = new Scene(root, root.getPrefWidth() + 20, root.getPrefHeight() + 10);

            this.mStage.setScene(scene);
            this.mStage.setTitle("Fenêtre Principale");

            // Récupération du contrôleur et initialisation (stage, contrôleur de dialogue, état courant)
            MenuViewController menuViewController = loader.getController();
            menuViewController.initContext(this.mStage, this);

            menuViewController.displayDialog();

        } catch (Exception e) {
            e.printStackTrace();
            System.exit(-1);
        }
    }

    /**
     * Méthode principale de lancement de l'application.
     */
    public static void runApp() {
        Application.launch();
    }

    /**
     * Lancer la configuration.
     */
    public void configuration() {
        ConfigController configController = new ConfigController();
        configController.start(new Stage());
    }

    /**
     * Lancer la gestion des Panneaux.
     */
    public void gestionPanneaux() {
        PanneauxController pc = new PanneauxController(this.mStage);
        pc.doPanneauxControllerDialog();
    }

    /**
     * Lancer la gestion des Capteurs.
     */
    public void gestionCapteurs() {
        CapteursController cc = new CapteursController(this.mStage);
        cc.doCapteursControllerDialog();
    }
}