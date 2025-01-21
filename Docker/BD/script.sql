-- Création de la table Mesures
CREATE TABLE Mesures (
    id SERIAL PRIMARY KEY,           --Identifiant unique (clé primaire)
    temperature FLOAT,               --Température
    humidity FLOAT,                  --Humidité
    activity FLOAT,                  --Activité
    tvoc FLOAT,                      --Total Volatile Organic Compounds
    illumination FLOAT,              --Éclairage
    infrared FLOAT,                  --Infrarouge
    infrared_and_visible FLOAT,      --Infrarouge et visible
    presure FLOAT,                   --Pression
    deviceName VARCHAR(255),         --Nom du dispositif
    room VARCHAR(255),               --Salle
    date_heure TIMESTAMPTZ           --Date et heure avec fuseau horaire
);

-- On vide la base de donnée au démarrage au cas ou des valeurs s'y trouvent
TRUNCATE TABLE Mesures;