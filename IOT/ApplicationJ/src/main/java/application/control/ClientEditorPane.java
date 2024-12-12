package application.control;

import application.DailyBankApp;
import application.DailyBankState;
import application.tools.EditionMode;
import application.tools.StageManagement;
import application.view.ClientEditorPaneViewController;
import application.view.ClientsManagementViewController;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Modality;
import javafx.stage.Stage;
import model.data.Client;

/**
 * La classe ClientEditorPane est utilisée pour gérer l'interface graphique de l'édition des clients.
 */
public class ClientEditorPane {

    /**
     * Référence à l'état de l'application DailyBank.
     */
    private DailyBankState dailyBankState;

    /**
     * Stage pour la fenêtre de l'éditeur de client.
     */
    private Stage cepStage;

    /**
     * Contrôleur de la vue de l'éditeur de client.
     */
    private ClientEditorPaneViewController cepViewController;

    /**
     * Constructeur de la classe ClientEditorPane.
     *
     * @param _parentStage Stage parent de cette fenêtre.
     * @param _dbstate     État actuel de l'application DailyBank.
     */
    public ClientEditorPane(Stage _parentStage, DailyBankState _dbstate) {
        this.dailyBankState = _dbstate;
        try {
            FXMLLoader loader = new FXMLLoader(ClientsManagementViewController.class.getResource("clienteditorpane.fxml"));
            BorderPane root = loader.load();

            Scene scene = new Scene(root, root.getPrefWidth() + 20, root.getPrefHeight() + 10);
            scene.getStylesheets().add(DailyBankApp.class.getResource("application.css").toExternalForm());

            this.cepStage = new Stage();
            this.cepStage.initModality(Modality.WINDOW_MODAL);
            this.cepStage.initOwner(_parentStage);
            StageManagement.manageCenteringStage(_parentStage, this.cepStage);
            this.cepStage.setScene(scene);
            this.cepStage.setTitle("Gestion d'un client");
            this.cepStage.setResizable(false);

            this.cepViewController = loader.getController();
            this.cepViewController.initContext(this.cepStage, this.dailyBankState);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    /**
     * Ouvre une boîte de dialogue pour éditer un client.
     *
     * @param client Client à éditer.
     * @param em     Mode d'édition (création, modification, suppression).
     * @return Le client édité.
     */
    public Client doClientEditorDialog(Client client, EditionMode em) {
        return this.cepViewController.displayDialog(client, em);
    }
}
