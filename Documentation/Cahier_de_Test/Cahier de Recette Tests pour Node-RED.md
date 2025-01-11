# Cahier de Recette : Tests pour Node-RED

## Contexte

Ces tests visent à valider les fonctionnalités liées à l'intégration de Node-RED avec MQTT et la base de données.
Les cas de test couvrent la récupération des données MQTT, leur insertion dans la base de données manuellement et automatiquement.

### Références

**Issue GitHub** : [Effectuer les tests pour Node-RED](https://github.com/IUT-Blagnac/SAE-ALT-S3-Dev-24-25-Dashboard_du_departement-Equipe-3A01/issues/29)

### Assignees

- @boubast
- @leonardo-correiamendes

---

## Tests à effectuer

### 1. Récupération des données depuis le flux MQTT

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

### 2. Insertion manuelle des données dans la base de données

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

### 3. Insertion automatique des données depuis MQTT

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

## Suivi des Tests

| Test | Statut | Observations |
| --- | --- | --- |
| Récupération des données MQTT | ✅ |  |
| Insertion manuelle dans la DB | ✅ |  |
| Insertion automatique dans la DB | ✅ | On pourrait améliorer le système d’incrément de l’ID des données dans la Table pour récupérer le dernier ID Inséré. |

---
