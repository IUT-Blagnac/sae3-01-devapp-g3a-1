# Dictionnaire de Données : Table `Mesures`

La table `Mesures` stocke les données mesurées par les capteurs. Voici les détails des colonnes et leur rôle :

| **Nom de la colonne**       | **Type**          | **Description**                                      | **Contraintes**      |
|-----------------------------|-------------------|----------------------------------------------------|----------------------|
| `id`                        | `SERIAL`          | Identifiant unique de la mesure.                   | Clé primaire         |
| `temperature`               | `FLOAT`           | Température mesurée par le capteur.                |                      |
| `humidity`                  | `FLOAT`           | Taux d'humidité mesuré par le capteur.             |                      |
| `activity`                  | `FLOAT`           | Niveau d'activité mesuré par le capteur.           |                      |
| `dioxydeCarbone`            | `FLOAT`           | Niveau de dioxyde de carbone mesuré.               |                      |
| `tvoc`                      | `FLOAT`           | Niveau des composés organiques volatils (TVOC).    |                      |
| `illumination`              | `FLOAT`           | Niveau d'éclairage mesuré.                         |                      |
| `infrared`                  | `FLOAT`           | Valeur infrarouge mesurée.                         |                      |
| `infrared_and_visible`      | `FLOAT`           | Valeur combinée des rayonnements infrarouge et visible. |                      |
| `presure`                   | `FLOAT`           | Pression atmosphérique mesurée.                    |                      |
| `deviceName`                | `VARCHAR(255)`    | Nom du dispositif ayant pris la mesure.            |                      |
| `room`                      | `VARCHAR(255)`    | Salle où les données ont été mesurées.             |                      |
| `date_heure`                | `TIMESTAMPTZ`     | Date et heure de la mesure avec fuseau horaire.    |                      |

### Contraintes

- **Clé primaire :** `id`
- Chaque enregistrement dans cette table est identifié de manière unique par la colonne `id`.

### Utilisation

Cette table est utilisée pour stocker et analyser les mesures collectées par les capteurs afin de fournir des statistiques sur l'environnement et l'occupation des salles.
