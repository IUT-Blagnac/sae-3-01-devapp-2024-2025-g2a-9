#!/usr/bin/env python3

#
# ATTENTION le script doit être exécuté dans le même dossier que le fichier config.ini
# True pour une des valeurs du JSON signifie que le seuil a été dépassé
# mettre le timestamp
# dans fichier config frequence a laquelle on ecrit
# choisir les types de donnes, salles ou panneaux dont on recupere les données
# valeurs minimale de production pour les panneaux solaires
# fichier à part pour les depassements de seuils
#

import paho.mqtt.client as mqtt
import json
import logging
import configparser
import os
from datetime import datetime, timedelta

#on verifie que config.ini existe
fichier_config = 'config.ini'
if not os.path.isfile(fichier_config):
    raise FileNotFoundError(f"Le fichier de configuration '{fichier_config}' est introuvable dans le répertoire courant.")

#on lit le fichier de config
config = configparser.ConfigParser()
config.read(fichier_config)

serveur_mqtt = config.get('MQTT', 'server')
topics = config.get('MQTT', 'topics').split(',')
fichier_sortie = config.get('OUTPUT', 'file')
frequence_ecriture = config.getint('OUTPUT', 'frequence', fallback=0)  # Fréquence d'écriture en secondes

#on lit les valeurs de seuil dans le fichier de config et on prends des valeurs par défaut (fallback) si ces données ne sont pas trouvées
max_temp = float(config.getint('seuils_capteur', 'temperature', fallback=35))
max_humidite = float(config.getint('seuils_capteur', 'humidity', fallback=60))
max_activite = float(config.getint('seuils_capteur', 'activity', fallback=0))
max_co2 = float(config.getint('seuils_capteur', 'co2', fallback=1000))
max_tvoc = float(config.getint('seuils_capteur', 'tvoc', fallback=500))
max_illumination = float(config.getint('seuils_capteur', 'illumination', fallback=100))
max_infrarouge = float(config.getint('seuils_capteur', 'infrared', fallback=10))
max_infrarouge_visible = float(config.getint('seuils_capteur', 'infrared_and_visible', fallback=20))
max_pression = float(config.getint('seuils_capteur', 'pressure', fallback=1013))

#recuperation variables capteur et solaredge
variables_capteur = config.get('variables', 'variable_capteur').split(',')
if "global" in variables_capteur:
    variables_capteur = ["temperature", "humidity", "activity", "co2", "tvoc", "illumination", "infrared", "infrared_and_visible", "pressure", "deviceName", "devEUI", "room", "floor", "Building"]

variables_solaredge = config.get('variables', 'variable_solaredge').split(',')
if "global" in variables_solaredge:
    variables_solaredge = ["lastUpdateTime", "lifeTimeData", "lastYearData", "lastMonthData", "lastDayData", "currentPower", "measuredBy"]

# Lire les numéros de salle depuis le fichier de configuration
salles = [salle.strip() for salle in config.get('salles', 'rooms', fallback='').split(',')]
if not salles or salles == ['']:
    salles = None  # Si 'rooms' est vide, récupérer toutes les données

logging.basicConfig(level=logging.INFO)

# Variable pour stocker les données en attente d'écriture
donnees_en_attente = []
dernier_ecriture = datetime.now()

def verifier_donnees(donnees):
    seuils = {
        'temperature': max_temp,
        'humidity': max_humidite,
        'activity': max_activite,
        'co2': max_co2,
        'tvoc': max_tvoc,
        'illumination': max_illumination,
        'infrared': max_infrarouge,
        'infrared_and_visible': max_infrarouge_visible,
        'pressure': max_pression
    }

    resultat = {}
    for cle, valeur in donnees.items():
        if cle in variables_capteur:
            if cle in seuils:
                resultat[cle] = (valeur, valeur > seuils[cle])
            else:
                resultat[cle] = (valeur, False)
    return resultat

def ecrire_donnees():
    global dernier_ecriture
    # Lire les données existantes
    if os.path.exists(fichier_sortie):
        with open(fichier_sortie, 'r') as f:
            try:
                liste_donnees = json.load(f)
            except json.JSONDecodeError:
                liste_donnees = []
    else:
        liste_donnees = []

    # Ajouter les nouvelles données
    liste_donnees.extend(donnees_en_attente)

    # Écrire les données mises à jour dans le fichier JSON
    with open(fichier_sortie, 'w') as f:
        json.dump(liste_donnees, f, indent=4)

    # Réinitialiser la liste des données en attente et mettre à jour le dernier temps d'écriture
    donnees_en_attente.clear()
    dernier_ecriture = datetime.now()

def on_message(client, userdata, msg):
    global dernier_ecriture
    print(f"Message reçu sur le sujet {msg.topic}: OK")
    try:
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)
        donnees_combinees = {}
        if "AM107" in msg.topic:
            if isinstance(jsonMsg, list) and len(jsonMsg) >= 2:
                donnees_capteur = jsonMsg[0]
                infos_appareil = jsonMsg[1]
                room = infos_appareil.get('room')
                if salles is None or room in salles:
                    donnees_capteur_verifiees = verifier_donnees(donnees_capteur)
                    infos_appareil_verifiees = {k: (v, False) for k, v in infos_appareil.items() if k in variables_capteur}
                    donnees_combinees = {**donnees_capteur_verifiees, **infos_appareil_verifiees}
                    donnees_combinees["date"] = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                    donnees_en_attente.append(donnees_combinees)
        elif "solaredge" in msg.topic:
            donnees_combinees = {k: v for k, v in jsonMsg.items() if k in variables_solaredge}
            donnees_en_attente.append(donnees_combinees)
        if frequence_ecriture == 0 or (datetime.now() - dernier_ecriture).total_seconds() >= frequence_ecriture:
            ecrire_donnees()
    except json.JSONDecodeError as e:
        logging.error("Erreur de décodage JSON : %s", e)

#connexion au serveur MQTT
client = mqtt.Client()
client.on_message = on_message
client.connect(serveur_mqtt, port=1883, keepalive=60)

#on s'abonne aux topics
for topic in topics:
    client.subscribe(topic.strip(), qos=0)


client.loop_forever()