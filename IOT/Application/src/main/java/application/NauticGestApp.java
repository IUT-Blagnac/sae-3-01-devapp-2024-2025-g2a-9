package application;

import application.control.MenuController;
import javafx.application.Application;
import javafx.stage.Stage;

/**
 * Classe principale de lancement.
 */
public class NauticGestApp extends Application {

    /**
     * Méthode de démarrage (JavaFX).
     */
    @Override
    public void start(Stage primaryStage) {
        MenuController menuController = new MenuController();
        menuController.start(primaryStage);
    }

    /**
     * Méthode principale de lancement de l'application.
     */
    public static void main(String[] args) {
        launch(args);
    }
}