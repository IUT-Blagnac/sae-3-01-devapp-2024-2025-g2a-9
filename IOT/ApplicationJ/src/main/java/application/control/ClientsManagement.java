package application.control;

import java.util.ArrayList;

import application.DailyBankApp;
import application.DailyBankState;
import application.tools.EditionMode;
import application.tools.StageManagement;
import application.view.ClientsManagementViewController;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;
import model.data.Client;
import model.orm.Access_BD_Client;
import model.orm.exception.ApplicationException;
import model.orm.exception.DatabaseConnexionException;

/**
 * La classe ClientsManagement gère les opérations de gestion des clients dans l'application DailyBank.
 */
public class ClientsManagement {

    /**
     * Stage pour la fenêtre de gestion des clients.
     */
    private Stage cmStage;

    /**
     * Référence à l'état de l'application DailyBank.
     */
    private DailyBankState dailyBankState;

    /**
     * Contrôleur de la vue de gestion des clients.
     */
    private ClientsManagementViewController cmViewController;

	/**
     * Constructeur de la classe ClientsManagement.
     *
     * @param _parentStage Stage parent de cette fenêtre.
     * @param _dbstate     État actuel de l'application DailyBank.
     */
	public ClientsManagement(Stage _parentStage, DailyBankState _dbstate) {
		this.dailyBankState = _dbstate;
		try {
			FXMLLoader loader = new FXMLLoader(ClientsManagementViewController.class.getResource("clientsmanagement.fxml"));
			BorderPane root = loader.load();

			Scene scene = new Scene(root, root.getPrefWidth() + 50, root.getPrefHeight() + 10);
			scene.getStylesheets().add(DailyBankApp.class.getResource("application.css").toExternalForm());

			this.cmStage = new Stage();
			this.cmStage.initModality(Modality.WINDOW_MODAL);
			this.cmStage.initOwner(_parentStage);
			StageManagement.manageCenteringStage(_parentStage, this.cmStage);
			this.cmStage.setScene(scene);
			this.cmStage.setTitle("Gestion des clients");
			this.cmStage.setResizable(false);

			this.cmViewController = loader.getController();
			this.cmViewController.initContext(this.cmStage, this, _dbstate);

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/**
     * Affiche la vue de gestion des clients.
     */
	public void doClientManagementDialog() {
		this.cmViewController.displayDialog();
	}

	/**
     * Ouvre une boîte de dialogue pour récupérer des infos sur les modifications d'un client.
	 * Appel l'accès BD pour update les informations dans la base de données
     *
     * @param c Client à modifier.
     * @return Le client modifié, ou null en cas d'erreur.
     */
	public Client modifierClient(Client c) {
		ClientEditorPane cep = new ClientEditorPane(this.cmStage, this.dailyBankState);
		Client result = cep.doClientEditorDialog(c, EditionMode.MODIFICATION);
		if (result != null) {
			try {
				Access_BD_Client ac = new Access_BD_Client();
				ac.updateClient(result);
			} catch (DatabaseConnexionException e) {
				ExceptionDialog ed = new ExceptionDialog(this.cmStage, this.dailyBankState, e);
				ed.doExceptionDialog();
				result = null;
				this.cmStage.close();
			} catch (ApplicationException ae) {
				ExceptionDialog ed = new ExceptionDialog(this.cmStage, this.dailyBankState, ae);
				ed.doExceptionDialog();
				result = null;
			}
		}
		return result;
	}

	/**
     * Ouvre une boîte de dialogue pour récupérer des infos sur un nouveau client.
	 * Appel l'accès BD pour insérer les informations dans la base de données
     *
     * @return Le nouveau client créé, ou null en cas d'erreur.
     */
	public Client nouveauClient() {
		Client client;
		ClientEditorPane cep = new ClientEditorPane(this.cmStage, this.dailyBankState);
		client = cep.doClientEditorDialog(null, EditionMode.CREATION);
		if (client != null) {
			try {
				Access_BD_Client ac = new Access_BD_Client();

				ac.insertClient(client);
			} catch (DatabaseConnexionException e) {
				ExceptionDialog ed = new ExceptionDialog(this.cmStage, this.dailyBankState, e);
				ed.doExceptionDialog();
				this.cmStage.close();
				client = null;
			} catch (ApplicationException ae) {
				ExceptionDialog ed = new ExceptionDialog(this.cmStage, this.dailyBankState, ae);
				ed.doExceptionDialog();
				client = null;
			}
		}
		return client;
	}

	public void gererComptesClient(Client c) {
		ComptesManagement cm = new ComptesManagement(this.cmStage, this.dailyBankState, c);
		cm.doComptesManagementDialog();
	}
	public void gererEmpruntsClient(Client c) {
		EmpruntsManagement em = new EmpruntsManagement(this.cmStage, this.dailyBankState, c);
		em.doEmpruntsManagementDialog();
	}

	/**
     * Récupère la liste des clients selon les critères de recherche.
     *
     * @param _numCompte Numéro de compte pour la recherche.
     * @param _debutNom  Début du nom pour la recherche.
     * @param _debutPrenom Début du prénom pour la recherche.
     * @return La liste des clients correspondant aux critères.
     */
	public ArrayList<Client> getlisteComptes(int _numCompte, String _debutNom, String _debutPrenom) {
		ArrayList<Client> listeCli = new ArrayList<>();
		try {
			// Recherche des clients en BD. cf. AccessClient > getClients(.)
			// numCompte != -1 => recherche sur numCompte
			// numCompte == -1 et debutNom non vide => recherche nom/prenom
			// numCompte == -1 et debutNom vide => recherche tous les clients

			Access_BD_Client ac = new Access_BD_Client();
			listeCli = ac.getClients(this.dailyBankState.getEmployeActuel().idAg, _numCompte, _debutNom, _debutPrenom);

		} catch (DatabaseConnexionException e) {
			ExceptionDialog ed = new ExceptionDialog(this.cmStage, this.dailyBankState, e);
			ed.doExceptionDialog();
			this.cmStage.close();
			listeCli = new ArrayList<>();
		} catch (ApplicationException ae) {
			ExceptionDialog ed = new ExceptionDialog(this.cmStage, this.dailyBankState, ae);
			ed.doExceptionDialog();
			listeCli = new ArrayList<>();
		}
		return listeCli;
	}
}
