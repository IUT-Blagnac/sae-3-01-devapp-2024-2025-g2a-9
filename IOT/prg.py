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

#on lit le fichier de config
config = configparser.ConfigParser()
config.read(config_file)

#on recupere les valeurs des parametres
mqttServer = config.get('MQTT', 'server')
topics = config.get('MQTT', 'topics').split(',')
output_file = config.get('OUTPUT', 'file')

max_temp = config.getfloat('valeurs_max', 'temperature')
max_humidity = config.getfloat('valeurs_max', 'humidity')
max_activite = config.getfloat('valeurs_max', 'activity')
max_co2 = config.getfloat('valeurs_max', 'co2')
max_tvoc = config.getfloat('valeurs_max', 'tvoc')
max_illumination = config.getfloat('valeurs_max', 'illumination')
max_infrarouge = config.getfloat('valeurs_max', 'infrared')
max_infrarouge_visible = config.getfloat('valeurs_max', 'infrared_and_visible')
max_pression = config.getfloat('valeurs_max', 'pressure')


#on configure le logging (pour afficher les messages d'erreur)
logging.basicConfig(level=logging.INFO)

def on_message(client, userdata, msg):
    print(f"Message reçu sur le topic {msg.topic}: OK")
    try:
        #on deserialise le message
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)

        #on lit les données
        if os.path.exists(output_file):
            with open(output_file, 'r') as f:
                try:
                    data_list = json.load(f)
                except json.JSONDecodeError:
                    data_list = []
        else:
            data_list = []

        #on ajoute les nouvelles données à la liste
        data_list.append(jsonMsg)

        # on écrit la liste dans le fichier
        with open(output_file, 'w') as f:
            json.dump(data_list, f, indent=4)

    except json.JSONDecodeError as e:
        logging.error("Erreur de décodage JSON : %s", e)

#connexion et souscription
client = mqtt.Client()
client.on_message = on_message
client.connect(mqttServer, port=1883, keepalive=60)

#on s'abonne aux topics données dans le fichier de config
for topic in topics:
    client.subscribe(topic.strip(), qos=0)

client.loop_forever()