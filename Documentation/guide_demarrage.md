# Guide de Démarrage pour le Projet Dashboard du Département

Bienvenue dans le projet **Dashboard du Département**. Ce guide vous accompagne pour cloner le dépôt, démarrer les services avec Docker, et résoudre les problèmes fréquents.

---

## 📋 Prérequis
Avant de commencer, assurez-vous d'avoir :
- **Git** installé ([Télécharger ici](https://git-scm.com/))
- **Docker** et **Docker Compose** installés ([Guide d'installation](https://docs.docker.com/get-docker/))
- Un environnement Linux mis à jour (Ubuntu, Debian, Fedora, etc.)

---

## 🚀 Étapes de démarrage

### 1. **Cloner le dépôt**
Exécutez la commande suivante pour cloner le dépôt GitHub :
```bash
git clone https://github.com/IUT-Blagnac/SAE-ALT-S3-Dev-24-25-Dashboard_du_departement-Equipe-3A01.git
```
Puis, naviguez dans le dossier du projet :
```bash
cd SAE-ALT-S3-Dev-24-25-Dashboard_du_departement-Equipe-3A01
```

### 2. **Lancer les conteneurs Docker**
Assurez-vous que vous êtes à la racine du projet et exécutez :
```bash
docker-compose up -d
```
Cette commande démarre tous les services nécessaires en arrière-plan :
- **Base de données TimescaleDB**
- **Interface Node-RED**
- **Serveur Nginx**
- **Service PHP**

### 3. **Accéder à l'application**
Une fois les services lancés, ouvrez votre navigateur et accédez à :
- **Dashboard web** : [http://localhost](http://localhost)
- **Interface Node-RED** : [http://localhost:1880](http://localhost:1880)

### 4. **Arrêter les conteneurs**
Lorsque vous avez terminé, arrêtez et supprimez les conteneurs avec :
```bash
docker-compose down
```

---

## ⚠️ Problèmes fréquents et solutions

### 1. **Conflit de conteneur**
**Erreur :** `The container name is already in use.`  
**Solution :** Supprimez le conteneur existant avant de relancer :
```bash
docker rm -f nom_du_conteneur
```

### 2. **Script SQL non exécuté**
**Problème :** Les tables ne sont pas créées dans la base de données.  
**Cause :** Le script SQL n'est exécuté qu'au premier démarrage du conteneur.  
**Solution :**
- Supprimez le volume associé à la base de données :
  ```bash
  docker-compose down -v
  docker-compose up -d
  ```

### 3. **Ports déjà utilisés**
**Erreur :** `Bind for 0.0.0.0:80 failed: port is already allocated.`  
**Solution :**
- Identifiez le processus qui utilise le port :
  ```bash
  sudo lsof -i -P -n | grep LISTEN
  ```
- Arrêtez le processus ou modifiez le port dans le fichier `docker-compose.yml`.

### 4. **Problèmes de permissions**
**Erreur :** `Permission denied` lors du montage des volumes.  
**Solution :**
- Donnez les droits nécessaires aux fichiers :
  ```bash
  chmod -R 755 ./Docker
  ```

---

Merci d'utiliser notre projet ! 😊

