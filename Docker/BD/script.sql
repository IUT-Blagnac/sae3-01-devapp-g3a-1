-- Création de la table Mesures
CREATE TABLE IF NOT EXISTS Mesures (
    id SERIAL PRIMARY KEY,           -- Identifiant unique (clé primaire, auto-incrémenté)
    temperature FLOAT,               -- Température
    humidity FLOAT,                  -- Humidité
    activity FLOAT,                  -- Activité
    tvoc FLOAT,                      -- Total Volatile Organic Compounds
    illumination FLOAT,              -- Éclairage
    infrared FLOAT,                  -- Infrarouge
    infrared_and_visible FLOAT,      -- Infrarouge et visible
    presure FLOAT,                   -- Pression
    deviceName VARCHAR(255),         -- Nom du dispositif
    room VARCHAR(255),               -- Salle
    date_heure TIMESTAMPTZ           -- Date et heure avec fuseau horaire
);

-- On vide la table et réinitialise la séquence
TRUNCATE TABLE Mesures RESTART IDENTITY;

-- Réalignement de la séquence avec la valeur maximale de la colonne `id` (utile si des valeurs existent déjà)
SELECT setval('mesures_id_seq', COALESCE((SELECT MAX(id) FROM Mesures), 0) + 1, false);

