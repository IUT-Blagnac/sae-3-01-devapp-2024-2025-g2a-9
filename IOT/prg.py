#!/usr/bin/env python3

import paho.mqtt.client as mqtt
import json
import logging
import configparser
import os

# Lecture de la configuration
config = configparser.ConfigParser()
config.read('config.ini')

mqttServer = config.get('MQTT', 'server')
topics = config.get('MQTT', 'topics').split(',')
data = config.get('MQTT', 'data').split(',')

logging.basicConfig(level=logging.INFO)

file_path = 'donnees.txt'
file_descriptor = os.open(file_path, os.O_WRONLY | os.O_CREAT | os.O_TRUNC)

def on_message(client, userdata, msg):
    print(f"Message reçu sur le topic {msg.topic}: OK")
    try:
        # Désérialisation du message
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)
        
        if "AM107" in msg.topic:
            if isinstance(jsonMsg, list) and len(jsonMsg) >= 2:
                sensor_data = jsonMsg[0]
                device_info = jsonMsg[1]
                
                temperature = sensor_data.get('temperature')
                room = device_info.get('room')
                
                if temperature is not None and room is not None:
                    data_str = f"Température: {temperature} °C dans la salle {room}\n"
                    os.write(file_descriptor, data_str.encode())
                else:
                    logging.error("Température ou salle non trouvée dans les données")
            else:
                logging.error("Format de message inattendu pour AM107")
        
        elif "solaredge" in msg.topic:
            lastDayData = jsonMsg.get('lastDayData', {}).get('energy')
            
            if lastDayData is not None:
                data_str = f"Production solaire sur la dernière journée: {lastDayData} Wh\n"
                os.write(file_descriptor, data_str.encode())
            else:
                logging.error("Données de production solaire non trouvées")
    
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