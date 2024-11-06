#!/usr/bin/env python3

import paho.mqtt.client as mqtt
import json
import logging

# Configuration
mqttServer = "mqtt.iut-blagnac.fr"
topics = ["AM107/#", "solaredge/#"]

logging.basicConfig(level=logging.INFO)


def on_message(client, userdata, msg):
    print(f"Message reçu sur le topic {msg.topic}: OK")
    try:
        #deserialisation
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)
        
        if "AM107" in msg.topic:
            if isinstance(jsonMsg, list) and len(jsonMsg) >= 2:
                
                sensor_data = jsonMsg[0]
                
                device_info = jsonMsg[1]
                
                temperature = sensor_data.get('temperature')
                room = device_info.get('room')
                
                if temperature is not None and room is not None:
                    print(f"Température: {temperature} °C dans la salle {room}")
                else:
                    logging.error("Température ou salle non trouvée dans les données")
            else:
                logging.error("Format de message inattendu pour AM107")
        
        elif "solaredge" in msg.topic:
            lastDayData = jsonMsg.get('lastDayData', {}).get('energy')
            
            if lastDayData is not None:
                print(f"Production solaire sur la dernière journée: {lastDayData} Wh")
            else:
                logging.error("Données de production solaire non trouvées")
    
    except json.JSONDecodeError as e:
        logging.error("Erreur de décodage JSON : %s", e)

#connexion au broker
client = mqtt.Client()
client.on_message = on_message
client.connect(mqttServer, port=1883, keepalive=60)

#abonnement aux topics
for topic in topics:
    client.subscribe(topic.strip(), qos=0)

client.loop_forever()