#!/usr/bin/env python3

import paho.mqtt.client as mqtt
import json
import logging
import configparser
import os


#
# ATTENTION le script doit etre executé dans le meme dossier que le fichier config.ini
#


#verif si le fichier de config existe bien
config_file = 'config.ini'
if not os.path.isfile(config_file):
    raise FileNotFoundError(f"Le fichier de configuration '{config_file}' est introuvable dans le répertoire courant., Il faut lancer le script dans le repertoire contenant le fichier config.ini")

# Lecture de la configuration
config = configparser.ConfigParser()
config.read(config_file)

# Afficher les sections trouvées
print("Sections trouvées dans config.ini :", config.sections())

# Afficher le contenu brut du fichier
with open(config_file, 'r', encoding='utf-8') as f:
    content = f.read()
    print("Contenu de config.ini :")
    print(repr(content))

# Vérification des valeurs de configuration
if not config.has_section('MQTT'):
    raise ValueError("La section 'MQTT' est manquante dans le fichier config.ini")
if not config.has_option('MQTT', 'server'):
    raise ValueError("La configuration 'server' est manquante dans le fichier config.ini")
if not config.has_option('MQTT', 'topics'):
    raise ValueError("La configuration 'topics' est manquante dans le fichier config.ini")
if not config.has_section('OUTPUT'):
    raise ValueError("La section 'OUTPUT' est manquante dans le fichier config.ini")
if not config.has_option('OUTPUT', 'file'):
    raise ValueError("La configuration 'file' est manquante dans le fichier config.ini")

mqttServer = config.get('MQTT', 'server')
topics = config.get('MQTT', 'topics').split(',')
output_file = config.get('OUTPUT', 'file')

print("Topics:", topics)
print("Output file:", output_file)

logging.basicConfig(level=logging.INFO)

def on_message(client, userdata, msg):
    print(f"Message reçu sur le topic {msg.topic}: OK")
    try:
        # Désérialisation du message
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)

        # Lire les données existantes
        if os.path.exists(output_file):
            with open(output_file, 'r') as f:
                try:
                    data_list = json.load(f)
                except json.JSONDecodeError:
                    data_list = []
        else:
            data_list = []

        # Ajouter les nouvelles données
        data_list.append(jsonMsg)

        # Écrire les données mises à jour dans le fichier JSON
        with open(output_file, 'w') as f:
            json.dump(data_list, f, indent=4)

    except json.JSONDecodeError as e:
        logging.error("Erreur de décodage JSON : %s", e)

# Connexion et souscription
client = mqtt.Client()
client.on_message = on_message
client.connect(mqttServer, port=1883, keepalive=60)

# S'abonner aux topics définis dans la configuration
for topic in topics:
    client.subscribe(topic.strip(), qos=0)

client.loop_forever()