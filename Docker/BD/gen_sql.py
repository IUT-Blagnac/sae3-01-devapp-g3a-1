#!/usr/bin/env python3

import random
import datetime
import subprocess
import os

def generate_inserts(output_file):
    # Définition de l'intervalle de dates
    start_date = datetime.datetime(2025, 1, 1)
    end_date = datetime.datetime(2030, 1, 1)  # Jusqu'à 5 ans (2025-2030)
    
    current_date = start_date
    inserts = []

    while current_date < end_date:
        # Génération des valeurs aléatoires
        temperature = round(18.0 + random.random() * 10.0, 1)
        humidity = round(40.0 + random.random() * 30.0, 1)
        activity = round(random.random() * 1.0, 2)
        tvoc = round(50.0 + random.random() * 400.0, 1)
        illumination = round(10.0 + random.random() * 500.0, 1)
        infrared = round(50.0 + random.random() * 200.0, 1)
        infrared_and_visible = round(300.0 + random.random() * 400.0, 1)
        presure = round(980.0 + random.random() * 50.0, 2)
        deviceName = "Device_A"
        room = "B105"
        
        # Formatage de la date pour SQL
        date_heure_str = current_date.strftime("%Y-%m-%d %H:%M:%S")
        
        # Construction de la requête INSERT
        insert_query = f"""INSERT INTO Mesures (
    temperature,
    humidity,
    activity,
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
    {activity},
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
        
        # Passage au mois suivant
        next_month = current_date.month + 1
        year_increment = 1 if next_month > 12 else 0
        next_month = next_month if next_month <= 12 else 1
        current_date = current_date.replace(year=current_date.year + year_increment, month=next_month)

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
