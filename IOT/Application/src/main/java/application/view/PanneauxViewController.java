package application.view;

import application.control.PanneauxController;
import javafx.fxml.FXML;
import javafx.collections.FXCollections;
import javafx.collections.ObservableMap;
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

	// Données de la fenêtre
	private ObservableMap<String, Integer> oMapEnergie;

	// Manipulation de la fenêtre
	public void initContext(Stage _containingStage, PanneauxController _p) {
		this.pDialogController = _p;
		this.containingStage = _containingStage;
		this.configure();
	}

	private void configure() {
		this.oMapEnergie = FXCollections.observableHashMap();

		typeColumn.setCellValueFactory(cellData -> new SimpleStringProperty("Énergie")); // Colonne fixe
    	dataColumn.setCellValueFactory(cellData -> 
        new SimpleIntegerProperty(cellData.getValue().getValue()).asObject()); // Valeur

		this.containingStage.setOnCloseRequest(e -> this.closeWindow(e));
	}

	public void displayDialog() {
		startRealTimeUpdate(); // Lance la mise à jour en temps réel
		this.containingStage.showAndWait();
	}

	private void refreshTable() {
		if (!oMapEnergie.isEmpty()) {
			// Obtenir la dernière entrée ajoutée (par date décroissante)
			Map.Entry<String, Integer> latestEntry = oMapEnergie.entrySet()
					.stream()
					.max((e1, e2) -> e1.getKey().compareTo(e2.getKey()))
					.orElse(null);

			// Rafraîchir la TableView avec la dernière entrée
			realTimeTable.setItems(FXCollections.observableArrayList(latestEntry));
		}
	}

	private void startRealTimeUpdate() {
    Task<Void> updateTask = new Task<>() {
        @Override
        protected Void call() {
            while (true) {
                try {
                    // Obtenir les données mises à jour depuis le JSON
                    ObservableMap<String, Integer> latestData = pDialogController.getOMapEnergie();

                    // Mettre à jour la ObservableMap et rafraîchir la TableView
                    Platform.runLater(() -> {
                        oMapEnergie.putAll(latestData); // Mettre à jour les paires date/valeur
                        refreshTable(); // Mettre à jour la TableView
                    });

                    // Pause entre deux mises à jour (par exemple, toutes les 2 secondes)
                    Thread.sleep(2000);
                } catch (Exception e) {
                    e.printStackTrace();
                }
            }
        }
    };

    Thread thread = new Thread(updateTask);
    thread.setDaemon(true); // Arrêter le thread avec l'application
    thread.start();
}


	// Gestion du stage
	private Object closeWindow(WindowEvent e) {
		this.doQuitter();
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
    private TableView<?> realTimeTable;

    @FXML
    private TableColumn<?, ?> typeColumn;

    @FXML
    private TableColumn<?, ?> dataColumn;

    @FXML
    private ScrollPane historyScrollPane;

    @FXML
    private MenuItem quitMenuItem;

    @FXML
    private MenuItem configMenuItem;

    @FXML
    private MenuItem helpMenuItem;

	
	@FXML
	private void doConfig() {
	}
	@FXML
	private void doQuitter() {
		this.containingStage.close();
	}
}
