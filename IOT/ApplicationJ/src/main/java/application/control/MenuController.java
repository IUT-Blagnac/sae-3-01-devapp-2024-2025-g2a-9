

/**
 * Classe de controleur de Dialogue de la fenêtre principale.
 *
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
			FXMLLoader loader = new FXMLLoader(
					MenuViewController.class.getResource("menuP.fxml"));
			BorderPane root = loader.load();

			// Paramétrage du Stage : feuille de style, titre
			Scene scene = new Scene(root, root.getPrefWidth() + 20, root.getPrefHeight() + 10);
			scene.getStylesheets().add(NauticGestApp.class.getResource("application.css").toExternalForm());

			this.mStage.setScene(scene);
			this.mStage.setTitle("Fenêtre Principale");

			// Récupération du contrôleur et initialisation (stage, contrôleur de dialogue,
			// état courant)
			MenuViewController MenuViewController = loader.getController();
			MenuViewController.initContext(this.mStage, this);

			MenuViewController.displayDialog();

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
		GestionCapteurs gc = new GestionCapteurs(this.mStage);
		cm.doClientManagementDialog();
	}

	/**
	 * Lancer la gestion des Panneaux.
	 */
	public void gestionPanneaux() {
		PanneauxController pc = new Panneaux(this.mStage);
		cm.doEmployeManagementDialog();
	}
	
	/**
	 * Lancer la gestion des Capteurs.
	 */
	public void gestionCapteurs() {
		Capteurs cm = new Capteurs(this.mStage);
		cm.doEmployeManagementDialog();
	}
}
