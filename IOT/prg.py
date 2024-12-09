#!/usr/bin/env python3

#
# ATTENTION le script doit être exécuté dans le même dossier que le fichier config.ini
# True pour une des valeurs du JSON signifie que le seuil a été dépassé false sinon
# dans fichier config frequence a laquelle on ecrit en secondes
# choisir les types de donnes, salles ou panneaux dont on recupere les données
# valeurs minimale de production pour les panneaux solaires
# fichier à part pour les depassements de seuils, uniquement les valeurs dont les seuils ont été depassés
# variable global pour les salles
# seuils solaredge
#

import paho.mqtt.client as mqtt
import json
import configparser
import os
from datetime import datetime, timedelta
import time

# Vérification de l'existence du fichier de configuration
fichier_config = 'config.ini'
if not os.path.isfile(fichier_config):
    print(f"Erreur : Le fichier de configuration '{fichier_config}' est introuvable dans le répertoire courant.")
    raise FileNotFoundError(f"Le fichier de configuration '{fichier_config}' est introuvable dans le répertoire courant.")

# Lecture du fichier de configuration
config = configparser.ConfigParser()
config.read(fichier_config)

serveur_mqtt = config.get('MQTT', 'server')
topics = config.get('MQTT', 'topics').split(',')
fichier_sortie = config.get('OUTPUT', 'file')
fichier_alertes = config.get('OUTPUT', 'alert')
frequence_ecriture = config.getint('OUTPUT', 'frequence', fallback=0)  # Fréquence d'écriture en secondes

# Lecture des valeurs de seuil dans le fichier de config avec valeurs par défaut
max_temp = float(config.get('seuils_capteur', 'temperature', fallback=35))
max_humidite = float(config.get('seuils_capteur', 'humidity', fallback=60))
max_activite = float(config.get('seuils_capteur', 'activity', fallback=0))
max_co2 = float(config.get('seuils_capteur', 'co2', fallback=1000))
max_tvoc = float(config.get('seuils_capteur', 'tvoc', fallback=500))
max_illumination = float(config.get('seuils_capteur', 'illumination', fallback=100))
max_infrarouge = float(config.get('seuils_capteur', 'infrared', fallback=10))
max_infrarouge_visible = float(config.get('seuils_capteur', 'infrared_and_visible', fallback=20))
max_pression = float(config.get('seuils_capteur', 'pressure', fallback=1013))
puissance_min = float(config.get('seuils_solaredge', 'puissance_min', fallback=500))

# Récupération des variables capteur et solaredge
variables_capteur = config.get('variables', 'variable_capteur').split(',')
if "global" in variables_capteur:
    variables_capteur = ["temperature", "humidity", "activity", "co2", "tvoc", "illumination", "infrared", "infrared_and_visible", "pressure", "deviceName", "devEUI", "room", "floor", "Building"]

variables_solaredge = config.get('variables', 'variable_solaredge').split(',')
if "global" in variables_solaredge:
    variables_solaredge = ["lastUpdateTime", "lifeTimeData", "lastYearData", "lastMonthData", "lastDayData", "currentPower", "measuredBy"]

# Lecture numéros de salle
salles = [salle.strip() for salle in config.get('salles', 'rooms', fallback='').split(',')]
if not salles or salles in [[''], ['global']]:
    salles = None  # Si 'rooms' est vide, récupérer toutes les données

# Stockage des données en attente
donnees_en_attente = []
derniere_ecriture = datetime.now()

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
    global derniere_ecriture
    print("Début de l'écriture des données dans le fichier JSON.")
    
    try:
        # Lecture des données existantes
        if os.path.exists(fichier_sortie):
            with open(fichier_sortie, 'r') as f:
                try:
                    liste_donnees = json.load(f)
                except json.JSONDecodeError:
                    liste_donnees = []
        else:
            liste_donnees = []

        # Lecture des alertes existantes
        if os.path.exists(fichier_alertes):
            with open(fichier_alertes, 'r') as f:
                try:
                    liste_alertes = json.load(f)
                except json.JSONDecodeError:
                    liste_alertes = []
        else:
            liste_alertes = []

        # Traitement des nouvelles données
        nouvelles_donnees = []
        nouvelles_alertes = []

        for donnees in donnees_en_attente:
            alertes = {}
            # Parcours du dictionnaire
            for cle, valeur in donnees.items():
                if isinstance(valeur, tuple):
                    # valeur = tuple (valeur, booleen dépassement_seuil)
                    # si le booleen de seuil est True on ajoute aux alertes
                    if valeur[1]:
                        alertes[cle] = valeur[0]
            if alertes:
                # Si il y a des alertes à ajouter
                alertes["date"] = donnees.get("date", datetime.now().strftime("%Y-%m-%d %H:%M:%S"))
                alertes["room"] = donnees.get("room", "")
                nouvelles_alertes.append(alertes)

            nouvelles_donnees.append(donnees)

        # On a déjà une liste contenant les données existantes, on ajoute les nouvelles données
        liste_donnees.extend(nouvelles_donnees)
        liste_alertes.extend(nouvelles_alertes)

        # Écriture dans le fichier principal
        with open(fichier_sortie, 'w') as f:
            json.dump(liste_donnees, f, indent=4)

        # Écriture dans le fichier d'alertes
        with open(fichier_alertes, 'w') as f:
            json.dump(liste_alertes, f, indent=4)

        # Efface les données en attente et met à jour l'heure de dernière écriture
        donnees_en_attente.clear()
        derniere_ecriture = datetime.now()
        print("Écriture des données terminée.")

    except Exception as e:
        print(f"Erreur lors de l'écriture des données : {e}")

def on_message(client, userdata, msg):
    global derniere_ecriture
    try:
        payload_str = msg.payload.decode()
        jsonMsg = json.loads(payload_str)
        donnees_combinees = {}
        message_recu = False

        if "AM107" in msg.topic:
            if isinstance(jsonMsg, list) and len(jsonMsg) >= 2:
                donnees_capteur = jsonMsg[0]
                infos_appareil = jsonMsg[1]
                room = infos_appareil.get('room')
                if salles is None or room in salles:  # Vérifie si doit récupérer les données
                    donnees_capteur_verifiees = verifier_donnees(donnees_capteur)
                    infos_appareil_verifiees = {}

                    for k, v in infos_appareil.items():
                        if k in variables_capteur and k != "room":
                            infos_appareil_verifiees[k] = (v, False)

                    donnees_combinees = {**donnees_capteur_verifiees, **infos_appareil_verifiees}  # Combine les dictionnaires
                    donnees_combinees["date"] = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                    donnees_combinees["room"] = room  # Assurez-vous que room est une chaîne de caractères
                    donnees_en_attente.append(donnees_combinees)
                    print(f"Message reçu pour la salle {room}.")
                    message_recu = True

        elif "solaredge" in msg.topic:
            donnees_filtrees = {}
            for k, v in jsonMsg.items():
                if k in variables_solaredge:
                    donnees_filtrees[k] = v
            # Vérification du seuil de puissance minimale
            current_power = jsonMsg.get('currentPower', {}).get('power', None)
            if current_power is not None:
                depassement_seuil = current_power < puissance_min
                donnees_filtrees['currentPower'] = (current_power, depassement_seuil)
            else:
                donnees_filtrees['currentPower'] = (None, False)
            donnees_filtrees["date"] = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
            donnees_en_attente.append(donnees_filtrees)
            print("Message reçu pour SolarEdge.")
            message_recu = True

        # Écriture en fonction de la fréquence
        if message_recu and (frequence_ecriture == 0 or (datetime.now() - derniere_ecriture).total_seconds() >= frequence_ecriture):
            ecrire_donnees()
    except json.JSONDecodeError as e:
        print(f"Erreur de décodage JSON : {e}")
    except Exception as e:
        print(f"Erreur lors du traitement du message : {e}")

# Connexion au serveur MQTT
client = mqtt.Client()
client.on_message = on_message
try:
    client.connect(serveur_mqtt, port=1883, keepalive=60)
except Exception as e:
    print(f"Erreur de connexion au serveur MQTT : {e}")
    exit(1)

# Abonnement aux topics
for topic in topics:
    topic = topic.strip()
    if topic:
        client.subscribe(topic, qos=0)

# Démarrage du loop
try:
    client.loop_forever()
except KeyboardInterrupt:
    print("Arrêt du script Python.")
    ecrire_donnees()
    exit(0)