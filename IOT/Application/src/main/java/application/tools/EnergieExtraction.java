package application.tools;

import com.fasterxml.jackson.databind.JsonNode;
import com.fasterxml.jackson.databind.ObjectMapper;

import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

public class EnergieExtraction {
    private final JsonNode data;

    // Constructeur qui prend une chaîne JSON et la transforme en un arbre JSON
    public EnergieExtraction(String jsonData) throws IOException {
        ObjectMapper mapper = new ObjectMapper();
        this.data = mapper.readTree(jsonData);
    }

    // Méthode pour extraire l'énergie dans une HashMap
    public HashMap<String, Integer> extractEnergyData() {
        HashMap<String, Integer> energieMap = new HashMap<>();

        // Vérifie si les champs "lastDayData" et "date" existent
        if (data.has("lastDayData") && data.has("date")) {
            JsonNode lastDayData = data.get("lastDayData");
            JsonNode energyNode = lastDayData.get("energy");
            String date = data.get("date").asText();

            // Ajoute les données dans la HashMap si "energy" est présent
            if (energyNode != null) {
                int energy = energyNode.asInt();
                energieMap.put(date, energy);
            }
        }

        return energieMap;
    }

}
