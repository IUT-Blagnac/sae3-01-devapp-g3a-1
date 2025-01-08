-- Crée la table "membre"
CREATE TABLE IF NOT EXISTS membre (
    id SERIAL PRIMARY KEY,
    prenom VARCHAR(255) NOT NULL
);

-- Insère des données dans la table "membre"
INSERT INTO membre (prenom) VALUES
    ('Diego'),
    ('Leonardo'),
    ('Aidan'),
    ('Bastien'),
    ('Yolan'),
    ('Dany');
