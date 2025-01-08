-- Création de la table Mesures
CREATE TABLE Mesures (
    id SERIAL PRIMARY KEY,          -- Identifiant unique (clé primaire)
    temperature FLOAT,              -- Température
    humidity FLOAT,                 -- Humidité
    activity FLOAT,                 -- Activité
    tvoc FLOAT,                     -- Total Volatile Organic Compounds
    illumination FLOAT,             -- Éclairage
    infrared FLOAT,                 -- Infrarouge
    infrared_and_visible FLOAT,     -- Infrarouge et visible
    presure FLOAT,                  -- Pression
    deviceName VARCHAR(255),        -- Nom du dispositif
    room VARCHAR(255),              -- Salle
    date_heure TIMESTAMPTZ          -- Date et heure avec fuseau horaire
);

-- Insertion d'une ligne de test dans la table Mesures
INSERT INTO Mesures (
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
    23.5,         -- Température (en °C)
    60.2,         -- Humidité (en %)
    0.8,          -- Activité (en % ou unité arbitraire)
    150.0,        -- Total Volatile Organic Compounds (en ppb)
    300.5,        -- Éclairage (en lux)
    120.0,        -- Infrarouge (valeur numérique)
    450.5,        -- Infrarouge et visible (valeur numérique)
    1013.25,      -- Pression (en hPa)
    'Device_A',   -- Nom du dispositif
    'Room_101',   -- Nom de la salle
    '2025-01-08 10:30:00+01' -- Date et heure (avec fuseau horaire)
);
