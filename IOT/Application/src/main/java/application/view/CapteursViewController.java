package application.view;

import application.control.CapteursController;
import javafx.fxml.FXML;
import javafx.scene.control.MenuItem;
import javafx.scene.control.TableColumn;
import javafx.scene.control.TableView;
import javafx.scene.control.TabPane;
import javafx.scene.control.Tab;
import javafx.scene.control.ScrollPane;
import javafx.stage.Stage;
import javafx.stage.WindowEvent;

public class CapteursViewController {

    // Contrôleur de Dialogue associé à CapteursController
    private CapteursController cDialogController;

    // Fenêtre physique où est la scène contenant le fichier XML contrôlé par this
    private Stage cStage;

    // Eléments FXML
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

    /**
     * Initialise le contexte pour la fenêtre.
     *
     * @param _cStage Fenêtre actuelle.
     * @param _p      Contrôleur parent.
     */
    public void initContext(Stage _cStage, CapteursController _p) {
        this.cDialogController = _p;
        this.cStage = _cStage;
        this.configure();
    }

    /**
     * Configure les actions et événements de la fenêtre.
     */
    private void configure() {
        this.cStage.setOnCloseRequest(this::closeWindow);

        // Action quitter
        quitMenuItem.setOnAction(event -> this.cStage.close());

        // Action configuration
        configMenuItem.setOnAction(event -> {
            // Ajouter une logique de configuration si nécessaire
            System.out.println("Configuration action triggered.");
        });

        // Action aide
        helpMenuItem.setOnAction(event -> {
            // Ajouter une logique d'aide si nécessaire
            System.out.println("Help action triggered.");
        });
    }

    /**
     * Affiche la fenêtre.
     */
    public void displayDialog() {
        this.cStage.showAndWait();
    }

    /**
     * Gère la fermeture de la fenêtre.
     *
     * @param e Événement de fermeture.
     * @return null
     */
    private Object closeWindow(WindowEvent e) {
        e.consume(); // Empêche la fermeture par défaut
        this.doCancel();
        return null;
    }

    /**
     * Actions effectuées lors de l'annulation.
     */
    private void doCancel() {
        System.out.println("Window close request intercepted. Cancelling close.");
    }

    // Ajoutez ici des méthodes supplémentaires pour manipuler vos éléments de l'interface utilisateur, si nécessaire.
}
