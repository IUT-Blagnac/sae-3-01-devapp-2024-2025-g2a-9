package application.tools;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;

/**
 * Classe pour gérer la configuration du fichier config.ini.
 */
public class ConfigIni {
    private Map<String, Map<String, String>> config = new HashMap<>();

    /**
     * Charge la configuration à partir d'un fichier INI.
     *
     * @param filePath le chemin du fichier INI
     * @throws IOException si une erreur d'entrée/sortie se produit
     * @author Thomas
     */
    public void loadConfig(String filePath) throws IOException {
        try (BufferedReader reader = new BufferedReader(new FileReader(filePath))) {
            String line;
            String currentSection = null;
            while ((line = reader.readLine()) != null) {
                line = line.trim(); //supprime les espaces en début et fin de ligne
                if (line.isEmpty() || line.startsWith("#")) { //si ligne vide ou commentaire
                    continue;
                }
                if (line.startsWith("[") && line.endsWith("]")) { //si nouvelle section
                    currentSection = line.substring(1, line.length() - 1); //on recup le nom de la section sans les crochets
                    config.put(currentSection, new HashMap<>()); //nouvelle section dans le dict
                } else if (currentSection != null) { //si on est dans une section
                    String[] parts = line.split("=", 2); //on sépare la ligne en 2 parties
                    if (parts.length == 2) { //si on a bien 2 parties
                        config.get(currentSection).put(parts[0].trim(), parts[1].trim()); //on ajoute la clé et la valeur dans la section (en supprimant les espaces)
                    }
                }
            }
        }
    }

    /**
     * Obtient la valeur de configuration pour une clé donnée dans une section donnée.
     *
     * @param section la section de la configuration
     * @param key la clé de la configuration
     * @return la valeur de configuration, ou null si la clé n'existe pas
     * @author Thomas
     */
    public String getConfigValue(String section, String key) {
        return config.getOrDefault(section, new HashMap<>()).get(key);
    }

    /**
     * Définit la valeur de configuration pour une clé donnée dans une section donnée.
     *
     * @param section la section de la configuration
     * @param key la clé de la configuration
     * @param value la valeur de configuration
     * @author Thomas
     */
    public void setConfigValue(String section, String key, String value) {
        config.computeIfAbsent(section, k -> new HashMap<>()).put(key, value);
    }

    /**
     * Sauvegarde la configuration dans un fichier INI.
     *
     * @param filePath le chemin du fichier INI
     * @throws IOException si une erreur d'entrée/sortie se produit
     * @author Thomas
     */
    public void saveConfig(String filePath) throws IOException {
        try (BufferedWriter writer = new BufferedWriter(new FileWriter(filePath))) {
            for (Map.Entry<String, Map<String, String>> section : config.entrySet()) {
                writer.write("[" + section.getKey() + "]\n");
                for (Map.Entry<String, String> entry : section.getValue().entrySet()) {
                    writer.write(entry.getKey() + " = " + entry.getValue() + "\n");
                }
                writer.write("\n");
            }
        }
    }

    public Map<String, Map<String, String>> getConfig() {
        return config;
    }


    /**
     * Récupère une partie de la Map de configuration (la partie passée en paramètre)
     * @param section
     * @return la configuration de la section
     * @author Thomas
     */
    public Map<String, String> getSectionConfig(String section) {
        return new HashMap<>(config.getOrDefault(section, new HashMap<>()));
    }

    public void setSectionConfig(String section, Map<String, String> sectionConfig) {
        config.put(section, sectionConfig);
    }
}