# Documentation du Dossier BD

## Description
Le dossier **BD** contient tous les fichiers nécessaires à la gestion de la base de données du projet. Il centralise les scripts SQL pour la création et l'initialisation des tables, ainsi que les fichiers de documentation associés.

## Contenu

### 1. `README.md`
- Ce fichier contient la documentation détaillée du dossier **BD**, avec une description des fichiers présents et leur utilité.

### 2. `script.sql`
- **Description** : Script SQL permettant de créer et initialiser les tables de la base de données.
- **Fonctionnalités** :
  - Crée la table `membre`.
  - Insère des valeurs initiales dans la table `membre`.

Exemple de structure créée :
```sql
code a mettre ici une fois fait
```

### 3. Dictionnaire de données SQL
- **Description** : Document détaillant les tables, colonnes, types, contraintes et relations de la base de données.

### 4. Code PlantUML du Diagramme de Classe Base de Données
- **Description** : Code source PlantUML représentant la structure de la base de données sous forme de diagramme de classe.

### 5. Diagramme de Classe Base de Données
- **Description** : Diagramme visuel illustrant les relations et la structure des tables de la base de données.

## Utilisation
1. Placez le fichier `script.sql` dans le répertoire racine du conteneur PostgreSQL au moment de l'exécution (par exemple, via le montage Docker).
2. Le script est exécuté automatiquement lors du lancement des conteneurs, initialisant la base de données avec les tables et données par défaut.

## Objectif
Ce dossier permet de structurer et maintenir les informations relatives à la base de données. Toute modification ou ajout aux tables doit être documenté dans le script SQL et ce fichier README pour garantir la traçabilité.
