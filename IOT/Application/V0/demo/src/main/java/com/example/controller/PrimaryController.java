package com.example.controller;

import java.io.IOException;
import javafx.fxml.FXML;
import com.example.App;

public class PrimaryController {

    @FXML
    private void switchToSecondary() throws IOException {
        App.setRoot("view/secondary");
    }
}