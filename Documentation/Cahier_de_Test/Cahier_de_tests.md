<img src="../../images/cahier.jpg" alt="Ma superbe image" />

# Cahier de Test

## Introduction
Ce document décrit les cas de test pour les environnements Node-RED et Docker. Il inclut des scénarios de test détaillés, des prérequis, des étapes à suivre, ainsi que les résultats attendus pour chaque cas de test. L'objectif est de garantir que toutes les fonctionnalités du système fonctionnent correctement et répondent aux exigences spécifiées.

## Sommaire
- [Cahier de Test](#cahier-de-test)
  - [Introduction](#introduction)
  - [Sommaire](#sommaire)
  - [Tests pour Node-RED](#tests-pour-node-red)
    - [Contexte](#contexte)
      - [Références](#références)
    - [Tests à effectuer](#tests-à-effectuer)
      - [1. Récupération des données depuis le flux MQTT](#1-récupération-des-données-depuis-le-flux-mqtt)
      - [2. Insertion manuelle des données dans la base de données](#2-insertion-manuelle-des-données-dans-la-base-de-données)
      - [3. Insertion automatique des données depuis MQTT](#3-insertion-automatique-des-données-depuis-mqtt)
    - [Suivi des Tests](#suivi-des-tests)
  - [Tests pour Docker et ses conteneurs](#tests-pour-docker-et-ses-conteneurs)
    - [Contexte](#contexte-1)
      - [Références](#références-1)
    - [Tests à effectuer](#tests-à-effectuer-1)
      - [1. Démarrage des conteneurs Docker](#1-démarrage-des-conteneurs-docker)
      - [2. Arrêt des conteneurs Docker](#2-arrêt-des-conteneurs-docker)
      - [3. Vérification des ports utilisés](#3-vérification-des-ports-utilisés)
      - [4. Accès à la page d'accueil (Nginx)](#4-accès-à-la-page-daccueil-nginx)
      - [5. Accès à l'interface Node-RED](#5-accès-à-linterface-node-red)
      - [6. Insertion des données dans TimescaleDB via Node-RED](#6-insertion-des-données-dans-timescaledb-via-node-red)
      - [7. Connexion à la base de données TimescaleDB](#7-connexion-à-la-base-de-données-timescaledb)
    - [Suivi des Tests](#suivi-des-tests-1)

---

## Tests pour Node-RED

### Contexte

Ces tests visent à valider les fonctionnalités liées à l'intégration de Node-RED avec MQTT et la base de données.
Les cas de test couvrent la récupération des données MQTT, leur insertion dans la base de données manuellement et automatiquement.

#### Références

**Issue GitHub** : [Effectuer les tests pour Node-RED](https://github.com/IUT-Blagnac/SAE-ALT-S3-Dev-24-25-Dashboard_du_departement-Equipe-3A01/issues/29)

### Tests à effectuer

#### 1. Récupération des données depuis le flux MQTT

**Objectif**

Vérifier que Node-RED peut correctement recevoir les données envoyées via le flux MQTT.

**Préconditions**

- Un broker MQTT est configuré et opérationnel.
- Node-RED est connecté au broker MQTT.

**Étapes**

1. Configurer un flux MQTT dans Node-RED pour écouter un topic spécifique.
2. Observer si les données sont correctement reçues par Node-RED.

**Critères d'acceptation**

- Node-RED affiche les données reçues dans le debug pane.
- Les données reçues correspondent exactement à celles envoyées.

---

#### 2. Insertion manuelle des données dans la base de données

**Objectif**

Tester la capacité de Node-RED à insérer manuellement des données dans la base de données.

**Préconditions**

- Une base de données est configurée et connectée à Node-RED.
- Un flux Node-RED est en place pour insérer les données dans une table spécifique.

**Étapes**

1. Configurer un noeud `inject` dans Node-RED avec des données JSON (exemple : `{"temperature": 25, "humidity": 60}`).
2. Connecter ce noeud à une fonction ou un noeud de base de données.
3. Déclencher l'injection des données manuellement.
4. Vérifier dans la base de données que les données ont été insérées correctement.

**Critères d'acceptation**

- Les données injectées manuellement apparaissent dans la table concernée avec les valeurs exactes.
- Aucun message d'erreur ne s'affiche dans Node-RED.

---

#### 3. Insertion automatique des données depuis MQTT

**Objectif**

Valider l'insertion automatique des données reçues via MQTT dans la base de données.

**Préconditions**

- Le flux MQTT de réception est configuré.
- Le flux d'insertion dans la base de données est connecté à celui de réception MQTT.

**Étapes**

1. Publier des données JSON sur le topic MQTT observé par Node-RED.
2. Laisser Node-RED traiter les données reçues et les insérer automatiquement dans la base de données.
3. Vérifier dans la base de données que les données ont bien été insérées.

**Critères d'acceptation**

- Les données envoyées via MQTT sont insérées automatiquement dans la base de données.
- Les données insérées correspondent exactement à celles envoyées.
- Aucun message d'erreur ne s'affiche dans Node-RED pendant le processus.

---

### Suivi des Tests

| Test | Statut | Observations |
| --- | --- | --- |
| Récupération des données MQTT | ✅ |  |
| Insertion manuelle dans la DB | ✅ |  |
| Insertion automatique dans la DB | ✅ | On pourrait améliorer le système d’incrément de l’ID des données dans la Table pour récupérer le dernier ID Inséré. |

---

## Tests pour Docker et ses conteneurs  

### Contexte

Ces tests visent à valider les fonctionnalités liées à l'utilisation de Docker et de ses conteneurs.
Les cas de test couvrent la création, le démarrage, l'arrêt et la suppression de conteneurs Docker.

#### Références

**Issue GitHub** : [Effectuer les tests pour Docker](https://github.com/IUT-Blagnac/SAE-ALT-S3-Dev-24-25-Dashboard_du_departement-Equipe-3A01/issues/25)

### Tests à effectuer

#### 1. Démarrage des conteneurs Docker

**Objectif**

Vérifier que les conteneurs Docker (Nginx, PHP, Node-RED, TimescaleDB) démarrent correctement.

**Préconditions**

- Docker et Docker Compose sont installés sur la machine.

**Étapes**

1. Exécuter le script `docker_control.sh`.
2. Sélectionner l'option `1` pour démarrer les conteneurs.

**Critères d'acceptation**

- Tous les conteneurs sont démarrés sans erreur.
- Les services sont accessibles via leurs ports respectifs (ex. : `http://localhost` pour Nginx).

---

#### 2. Arrêt des conteneurs Docker

**Objectif**

Vérifier que les conteneurs Docker s'arrêtent correctement et que les volumes sont supprimés.

**Préconditions**

- Les conteneurs sont en cours d'exécution.

**Étapes**

1. Exécuter le script `docker_control.sh`.
2. Sélectionner l'option `2` pour arrêter les conteneurs.

**Critères d'acceptation**

- Tous les conteneurs sont arrêtés.
- Les volumes associés sont supprimés.

---

#### 3. Vérification des ports utilisés

**Objectif**

S'assurer que les ports 80 et 1880 ne sont pas déjà utilisés avant le démarrage des conteneurs.

**Préconditions**

- Aucun autre service n'utilise les ports 80 ou 1880.

**Étapes**

1. Exécuter le script `docker_control.sh`.
2. Observer les messages affichés lors de la vérification des ports.

**Critères d'acceptation**

- Un message d'erreur est affiché si un port est occupé.
- Aucun conflit de port ne se produit lors du démarrage des conteneurs.

---

#### 4. Accès à la page d'accueil (Nginx)

**Objectif**

Vérifier que la page d'accueil du site s'affiche correctement via Nginx.

**Préconditions**

- Les conteneurs Docker sont démarrés.

**Étapes**

1. Ouvrir un navigateur web.
2. Accéder à l'URL `http://localhost`.

**Critères d'acceptation**

- La page d'accueil s'affiche sans erreur.
- Les éléments de la page sont chargés correctement.

---

#### 5. Accès à l'interface Node-RED

**Objectif**

S'assurer que l'interface de Node-RED est accessible.

**Préconditions**

- Le conteneur Node-RED est démarré.

**Étapes**

1. Ouvrir un navigateur web.
2. Accéder à l'URL `http://localhost:1880`.

**Critères d'acceptation**

- L'interface de Node-RED s'affiche correctement.
- Les flux Node-RED sont fonctionnels.

---

#### 6. Insertion des données dans TimescaleDB via Node-RED

**Objectif**

Vérifier que Node-RED insère correctement les données MQTT dans la base de données TimescaleDB.

**Préconditions**

- Un flux Node-RED est configuré pour insérer des données dans TimescaleDB.

**Étapes**

1. Configurer un flux MQTT dans Node-RED pour écouter un topic spécifique.
2. Vérifier l'insertion des données dans la table `Mesures` via une requête SQL.

**Critères d'acceptation**

- Les données sont insérées dans la table `Mesures` sans erreur.
- Les données insérées correspondent aux données MQTT reçues.

---

#### 7. Connexion à la base de données TimescaleDB

**Objectif**

Vérifier la connexion à la base de données TimescaleDB via `psql`.

**Préconditions**

- La base de données TimescaleDB est en cours d'exécution.

**Étapes**

1. Exécuter la commande suivante :
   ```bash
   psql -h localhost -U admin -d dashboard_db

### Suivi des Tests

| Test                                      | Statut | Observations                                                                                  |
|-------------------------------------------|--------|-----------------------------------------------------------------------------------------------|
| Démarrage des conteneurs Docker           | ✅     | |
| Arrêt des conteneurs Docker               | ✅     |  |
| Vérification des ports utilisés           | ✅     | |
| Accès à la page d'accueil (Nginx)         | ✅     | |
| Accès à l'interface Node-RED              | ✅     |  |
| Insertion des données dans TimescaleDB    | ✅     | |
| Connexion à la base de données TimescaleDB | ✅     |                  |
