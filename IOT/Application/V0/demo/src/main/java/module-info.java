module com.example {
    requires javafx.controls;
    requires javafx.fxml;
    requires javafx.graphics;
    requires com.google.gson;

    opens com.example.controller to javafx.fxml, javafx.base;
    exports com.example;
}