package application.model;

import javafx.beans.property.SimpleStringProperty;
import javafx.beans.property.StringProperty;

/**
 * Classe pour gérer les seuils.
 * @author Thomas
 */
public class Seuil {
    private final StringProperty nom;
    private final StringProperty valeur;

    public Seuil(String nom, String valeur) {
        this.nom = new SimpleStringProperty(nom);
        this.valeur = new SimpleStringProperty(valeur);
    }

    public String getNom() {
        return nom.get();
    }

    public void setNom(String nom) {
        this.nom.set(nom);
    }

    public StringProperty nomProperty() {
        return nom;
    }

    public String getValeur() {
        return valeur.get();
    }

    public void setValeur(String valeur) {
        this.valeur.set(valeur);
    }

    public StringProperty valeurProperty() {
        return valeur;
    }
}