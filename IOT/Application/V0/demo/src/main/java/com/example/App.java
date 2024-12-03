package com.example;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.stage.Stage;
import com.example.controller.ConfigController;
import com.example.controller.GraphController;

import java.io.IOException;

public class App extends Application {

    private static Scene scene;
    private ConfigController configController;
    private GraphController graphController;

    @Override
    public void start(Stage stage) {
        try {
            FXMLLoader loader = new FXMLLoader(App.class.getResource("/com/example/view/config.fxml"));
            scene = new Scene(loader.load(), 600, 800);
            configController = loader.getController();
            stage.setScene(scene);
            stage.setTitle("Configuration");
            stage.show();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public static void setRoot(String fxml) throws IOException {
        scene.setRoot(loadFXML(fxml));
    }

    private static javafx.scene.Parent loadFXML(String fxml) throws IOException {
        FXMLLoader fxmlLoader = new FXMLLoader(App.class.getResource("/com/example/view/" + fxml + ".fxml"));
        return fxmlLoader.load();
    }

    @Override
    public void stop() throws Exception {
        // Récupérer le contrôleur et arrêter l'executorService
        FXMLLoader loader = new FXMLLoader(getClass().getResource("/com/example/view/graph.fxml"));
        loader.load();
        graphController = loader.getController();
        if (graphController != null) {
            graphController.shutdown();
        }
        // Arrêter le processus Python
        if (configController != null) {
            configController.stopPythonProcess();
        }
        super.stop();
    }

    public static void main(String[] args) {
        launch();
    }
}