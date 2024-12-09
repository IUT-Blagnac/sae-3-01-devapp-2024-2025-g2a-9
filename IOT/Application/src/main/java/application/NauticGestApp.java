package application;


// sur linux lancer depuis le jar depuis le repertoire Application avec
// java --module-path target/dependencies --add-modules javafx.controls,javafx.fxml,javafx.graphics -jar target/NauticGest-1.0.jar
// attention de bien avoir javafx-graphics-17-linux.jar sinon le prendre ici : https://repo1.maven.org/maven2/org/openjfx/javafx-graphics/17/



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