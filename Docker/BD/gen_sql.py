#!/usr/bin/env python3

import random
import datetime
import subprocess
import os

def generate_inserts(output_file):
    # Définition de l'intervalle de dates
    end_date = datetime.datetime.now()
    start_date = end_date - datetime.timedelta(days=6 * 30)  # Approximation des 6 derniers mois
    
    current_date = start_date
    inserts = []

    while current_date < end_date:
        # Vérifier si on est dans la dernière semaine
        if current_date >= end_date - datetime.timedelta(days=7):
            interval = datetime.timedelta(minutes=10)  # Intervalle de 10 minutes
        else:
            interval = datetime.timedelta(hours=5)  # Intervalle de 5 heures

        # Vérifier les jours et horaires
        if (
            current_date.weekday() not in [4, 5]  # Pas de vendredi (4) ni samedi (5)
            and (current_date.weekday() != 3 or current_date.hour < 12)  # Pas le jeudi après-midi
            and 8 <= current_date.hour < 20  # Horaires entre 8h et 20h
        ):
            # Génération des valeurs réalistes
            temperature = round(random.uniform(20.0, 24.0), 1)  # Température typique en intérieur
            humidity = round(random.uniform(30.0, 50.0), 1)  # Humidité typique en salle
            activity = round(random.uniform(0.0, 0.9), 2) if random.random() > 0.1 else None  # Activité faible
            dioxideCarbon = round(random.uniform(400, 1200), 1)  # CO2, plus élevé en classe
            tvoc = round(random.uniform(100, 500), 1)  # Composés organiques volatils
            illumination = round(random.uniform(100, 700), 1)  # Éclairage de la salle
            infrared = round(random.uniform(50, 150), 1)  # Infrarouge (occupants)
            infrared_and_visible = round(random.uniform(200, 800), 1)  # Infrarouge et visible
            presure = round(random.uniform(980.0, 1020.0), 2)  # Pression atmosphérique normale
            deviceName = "AM107-17"
            room = "B105"
            
            # Formatage de la date pour SQL
            date_heure_str = current_date.strftime("%Y-%m-%d %H:%M:%S")
            
            # Construction de la requête INSERT
            insert_query = f"""INSERT INTO Mesures (
    temperature,
    humidity,
    activity,
    dioxideCarbon,
    tvoc,
    illumination,
    infrared,
    infrared_and_visible,
    presure,
    deviceName,
    room,
    date_heure
) VALUES (
    {temperature},
    {humidity},
    {activity if activity is not None else 'NULL'},  -- Permet de gérer NULL pour l'activité
    {dioxideCarbon},
    {tvoc},
    {illumination},
    {infrared},
    {infrared_and_visible},
    {presure},
    '{deviceName}',
    '{room}',
    '{date_heure_str}'
);"""
            inserts.append(insert_query)

        # Avancer dans le temps selon l'intervalle
        current_date += interval

    # Écriture dans le fichier de sortie
    with open(output_file, "w", encoding="utf-8") as f:
        for query in inserts:
            f.write(query + "\n")

def execute_sql_in_docker(sql_file, container_name="timescaledb", user="admin", db_name="dashboard_db"):
    """
    Copie le fichier SQL dans le conteneur Docker et l'exécute via psql.
    """
    # 1) docker cp
    print(f"> Copie du fichier {sql_file} dans le conteneur {container_name}...")
    subprocess.run(["docker", "cp", sql_file, f"{container_name}:/resultat.sql"], check=True)
    
    # 2) docker exec
    print(f"> Exécution du script SQL dans le conteneur {container_name}...")
    subprocess.run([
        "docker", "exec", "-i", container_name,
        "psql", "-U", user, "-d", db_name, "-f", "/resultat.sql"
    ], check=True)
    print("> Script SQL exécuté avec succès.")

if __name__ == "__main__":
    # Nom du fichier de sortie
    output_file = "resultat.sql"
    
    print("=== Génération des requêtes SQL ===")
    generate_inserts(output_file)
    print(f"Fichier '{output_file}' généré avec succès.\n")
    
    # Maintenant, on exécute immédiatement dans Docker
    # (Assure-toi que le conteneur 'timescaledb' est démarré et accessible)
    try:
        execute_sql_in_docker(output_file, container_name="timescaledb", user="admin", db_name="dashboard_db")
    except subprocess.CalledProcessError as e:
        print(f"Erreur lors de l'exécution du script SQL dans le conteneur Docker : {e}")
    except FileNotFoundError as e:
        print("Erreur : La commande 'docker' n'a pas été trouvée. Assure-toi d'avoir Docker installé et accessible dans le PATH.")
    except Exception as e:
        print(f"Une erreur inattendue s'est produite : {e}")
