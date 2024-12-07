package application.tools;



import application.model.DataEnergie;
import javafx.collections.ObservableList;

import java.io.IOException;
import java.io.Reader;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonIOException;
import com.google.gson.JsonParser;
import com.google.gson.JsonSyntaxException;

public class EnergieExtraction {
    private JsonArray data;
    
        // Constructeur qui prend une chaîne JSON et la transforme en un arbre JSON
        public EnergieExtraction(String filePath) throws IOException {
            Path path = Paths.get(filePath);
            if (!Files.exists(path)) {
                System.out.println("Le fichier donnees.json n'existe pas.");
                this.data = null;
                return;
            }
    
            try (Reader reader = Files.newBufferedReader(path)) {
                JsonArray data = JsonParser.parseReader(reader).getAsJsonArray();
                reader.close();
    
                if (data.size() == 0) {
                    System.out.println("Le fichier donnees.json est vide.");
                    this.data = null;
                    return;
                }
    
                this.data = data;
            } catch (JsonIOException | JsonSyntaxException e) {
                e.printStackTrace();
                this.data = null;
        }
    }

    // Méthode pour extraire l'énergie dans une HashMap
    public void extractEnergyData(ObservableList<DataEnergie> dataEnergies) {
        if (data == null) {
            System.out.println("Aucun données disponibles pour l'extraction.");
            return;
        }
        // Parcourt chaque élément de l'array JSON
        for (JsonElement element : data) {
            String date = element.getAsJsonObject().get("date").getAsString(); // Récupère la date en String
            double energy = element.getAsJsonObject().get("energy").getAsDouble(); // Récupère la valeur energie en Double

            dataEnergies.add(new DataEnergie(date, energy)); // On ajoute un objet avec les données
        }
    }
}
