package application.tools;

import application.model.DataEnergie;
import javafx.collections.ObservableList;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonIOException;
import com.google.gson.JsonObject;
import com.google.gson.JsonParser;
import com.google.gson.JsonSyntaxException;

import java.io.IOException;
import java.io.Reader;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

public class EnergieExtraction {
    private JsonArray data;

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

    public void extractEnergyData(ObservableList<DataEnergie> dataEnergies) {
        if (data == null) {
            System.out.println("Aucun données disponibles pour l'extraction.");
            return;
        }
        for (JsonElement element : data) {
            JsonObject jsonObject = element.getAsJsonObject();
            String date = jsonObject.get("date").getAsString();

            if (jsonObject.has("lastDayData") && jsonObject.get("lastDayData").getAsJsonObject().has("energy")) {
                double energy = jsonObject.get("lastDayData").getAsJsonObject().get("energy").getAsDouble();
                dataEnergies.add(new DataEnergie(date, energy));
            } else {
                System.out.println("Le champ 'energy' est manquant ou null pour la date: " + date);
            }
        }
    }
}