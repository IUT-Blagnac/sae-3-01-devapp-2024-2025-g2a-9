#!/usr/bin/env python3

#
# ATTENTION le script doit etre executé dans le meme dossier que le fichier config.ini
#

import paho.mqtt.client as mqtt
import json
import logging
import configparser
import os

# Lecture de la configuration
config = configparser.ConfigParser()
config.read('config.ini')

output_file = config.get('OUTPUT', 'file')
mqttServer = config.get('MQTT', 'server')
topics = config.get('MQTT', 'topics').split(',')

logging.basicConfig(level=logging.INFO)

file_path = 'donnees.txt'
file_descriptor = os.open(file_path, os.O_WRONLY | os.O_CREAT | os.O_TRUNC)

def on_message(client, userdata, msg):
    print(f"Message reçu sur le topic {msg.topic}: OK")
    try:
        # Désérialisation du message
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)
        
        # Écrire les données dans le fichier JSON
        with open(output_file, 'a') as f:
            json.dump(jsonMsg, f)
            f.write('\n')  # Pour séparer les enregistrements
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

# Fermer le fichier à la fin du programme
os.close(file_descriptor)