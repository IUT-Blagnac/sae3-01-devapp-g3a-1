#!/usr/bin/env python3

import random
import datetime

def generate_inserts(output_file):
    # Définition de l'intervalle de dates
    start_date = datetime.datetime(2025, 1, 1, 0, 0, 0)
    end_date   = datetime.datetime(2025, 1, 31, 23, 59, 59)
    
    current_date = start_date
    inserts = []

    while current_date <= end_date:
        # Génération des valeurs aléatoires
        temperature           = round(18.0 + random.random() * 10.0, 1)  # [18.0, 28.0]
        humidity              = round(40.0 + random.random() * 30.0, 1)  # [40.0, 70.0]
        activity              = round(random.random() * 1.0, 2)         # [0.0, 1.0]
        tvoc                  = round(50.0 + random.random() * 400.0, 1)# [50, 450]
        illumination          = round(10.0 + random.random() * 500.0, 1)# [10, 510]
        infrared              = round(50.0 + random.random() * 200.0, 1)# [50, 250]
        infrared_and_visible  = round(300.0 + random.random() * 400.0, 1)# [300, 700]
        presure               = round(980.0 + random.random() * 50.0, 2) # [980, 1030]
        deviceName            = "Device_A"
        room                  = "B105"
        
        # Formatage de la date pour SQL
        date_heure_str = current_date.strftime("%Y-%m-%d %H:%M:%S")
        
        # Construction de la requête INSERT
        # NB : Si tes colonnes diffèrent, adapte-les ici.
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
        
        # Passage à l'heure suivante
        current_date += datetime.timedelta(hours=1)

    # Écriture dans le fichier de sortie
    with open(output_file, "w", encoding="utf-8") as f:
        for query in inserts:
            f.write(query + "\n")

if __name__ == "__main__":
    # Nom du fichier de sortie
    output_file = "resultat.sql"
    generate_inserts(output_file)
    print(f"Fichier '{output_file}' généré avec succès.")
