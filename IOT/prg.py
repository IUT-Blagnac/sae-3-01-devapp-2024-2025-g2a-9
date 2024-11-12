#!/usr/bin/env python3


#
# ATTENTION le script doit etre executé dans le meme dossier que le fichier config.ini
# True pour une des valeurs du json signifie que le seuil a été dépassé
#

import paho.mqtt.client as mqtt
import json
import logging
import configparser
import os

#verif existance config.ini
config_file = 'config.ini'
if not os.path.isfile(config_file):
    raise FileNotFoundError(f"Le fichier de configuration '{config_file}' est introuvable dans le répertoire courant.")

#on lit le fichier de config
config = configparser.ConfigParser()
config.read(config_file)


mqttServer = config.get('MQTT', 'server')
topics = config.get('MQTT', 'topics').split(',')
output_file = config.get('OUTPUT', 'file')


#on lit les seuils depuis le fichier de config avec des valeurs par défaut (on convertit en float)
max_temp = float(config.getint('valeurs_max', 'temperature', fallback=25))
max_humidity = float(config.getint('valeurs_max', 'humidity', fallback=60))
max_activite = float(config.getint('valeurs_max', 'activity', fallback=10))
max_co2 = float(config.getint('valeurs_max', 'co2', fallback=1000))
max_tvoc = float(config.getint('valeurs_max', 'tvoc', fallback=500))
max_illumination = float(config.getint('valeurs_max', 'illumination', fallback=100))
max_infrarouge = float(config.getint('valeurs_max', 'infrared', fallback=10))
max_infrarouge_visible = float(config.getint('valeurs_max', 'infrared_and_visible', fallback=20))
max_pression = float(config.getint('valeurs_max', 'pressure', fallback=1013))

logging.basicConfig(level=logging.INFO)

def verif_données(data):
    seuil = {
        'temperature': max_temp,
        'humidity': max_humidity,
        'activity': max_activite,
        'co2': max_co2,
        'tvoc': max_tvoc,
        'illumination': max_illumination,
        'infrared': max_infrarouge,
        'infrared_and_visible': max_infrarouge_visible,
        'pressure': max_pression
    }
    result = {}
    for key, value in data.items():
        if key in seuil:
            result[key] = (value, value > seuil[key])
        else:
            result[key] = (value, False)
    return result

def on_message(client, userdata, msg):
    print(f"Message reçu sur le topic {msg.topic}: OK")
    try:
        # Désérialisation du message
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)

        combined_data = {}

        if "AM107" in msg.topic:
            if isinstance(jsonMsg, list) and len(jsonMsg) >= 2:
                sensor_data = jsonMsg[0]
                device_info = jsonMsg[1]

                # Vérifier les seuils pour les données des capteurs
                donnees_capteur_verifie = verif_données(sensor_data)
                donnees_appareil_verifie = {k: (v, False) for k, v in device_info.items()}  # Pas de seuils pour les infos de l'appareil

                # Combiner les données vérifiées
                combined_data = {**donnees_capteur_verifie, **donnees_appareil_verifie}

        elif "solaredge" in msg.topic:
            # Pas de vérification des seuils pour les données des panneaux solaires
            combined_data = jsonMsg

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
        data_list.append(combined_data)

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