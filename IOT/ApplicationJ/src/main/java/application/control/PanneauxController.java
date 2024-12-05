

/**
 * La classe PanneauxController gère les opérations de gestion des Panneaux.
 */
public class PanneauxController {

    /**
     * Stage pour la fenêtre de gestion des Panneaux.
     */
    private Stage pStage;

    /**
     * Contrôleur de la vue de gestion des clients.
     */
    private PanneauxViewController pViewController;

	/**
     * Constructeur de la classe PanneauxController.
     *
     * @param _parentStage Stage parent de cette fenêtre.
     */
	public PanneauxController(Stage _parentStage) {
		try {
			FXMLLoader loader = new FXMLLoader(PanneauxViewController.class.getResource("lecture.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);
			scene.getStylesheets().add(NauticGestApp.class.getResource("application.css").toExternalForm());

			this.pStage = new Stage();
			this.pStage.initModality(Modality.WINDOW_MODAL);
			this.pStage.initOwner(_parentStage);
			StageManagement.manageCenteringStage(_parentStage, this.pStage);
			this.pStage.setScene(scene);
			this.pStage.setTitle("Gestion des panneaux solaires");
			this.pStage.setResizable(false);

			this.pViewController = loader.getController();
			this.pViewController.initContext(this.pStage, this);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
     * Affiche la vue de gestion des panneaux.
     */
	public void doPanneauxControllerDialog() {
		this.pViewController.displayDialog();
	}

}
