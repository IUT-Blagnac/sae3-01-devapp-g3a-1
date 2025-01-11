# Guide de démarrage du Docker

## 1. Installation de Docker (Linux)

1. **Mettez à jour les paquets :**
   ```bash
   sudo apt update
   ```

2. **Installez Docker :**
   ```bash
   sudo apt install docker.io
   ```

3. **Vérifiez l'installation :**
   ```bash
   docker --version
   ```

## 2. Installation de Docker Compose (Linux)

1. **Installez Docker Compose :**
   ```bash
   sudo apt install docker-compose
   ```

2. **Vérifiez l'installation :**
   ```bash
   docker-compose --version
   ```

## 3. Clonnage du dépôt

```bash
git clone https://github.com/IUT-Blagnac/SAE-ALT-S3-Dev-24-25-Dashboard_du_departement-Equipe-3A01.git
 
cd SAE-ALT-S3-Dev-24-25-Dashboard_du_departement-Equipe-3A01
```

## 4. Lancement de l'application/docker

> [!NOTE]
> Nous avons créé un script bash interactif pour simplifier le démarrage et l'arrêt des services Docker du projet. 
>
> Vous pouvez tout de meme lancer les services manuellement en utilisant les commandes `docker-compose` suivantes :
> - Démarrer les services : `docker-compose up -d
`
> - Arrêter les services : `docker-compose down --volumes --remove-orphans
`

Pour lancer l'application, exécutez le script `docker_control.sh` situé à la racine du projet :

```bash
./docker_control.sh
```

Suivre les instructions affiché à l'écran pour lancer l'application.
Il vous faudra entrer `1` pour lancer l'application.

### 4.1. Accès à l'application

L'application est accessible à l'adresse suivante : [http://localhost](http://localhost)

### 4.2. Au node-red

L'interface de node-red est accessible à l'adresse suivante : [http://localhost:1880](http://localhost:1880)

### 4.3. À la base de données

Dans votre terminal, connectez-vous à la base de données avec la commande suivante :

```bash
psql -h localhost -U admin -d dashboard_db
```

Le mot de passe est `password`.

## 5. Arrêt de l'application/docker

```bash
./docker_control.sh
```

Suivre les instructions affiché à l'écran pour lancer l'application.
Il vous faudra entrer `2` pour arrêter l'application.

## 6. Structure et rôle du fichier `docker_control.sh`

Le fichier `docker_control.sh` est un script bash interactif conçu pour simplifier le démarrage et l'arrêt des services Docker du projet. Voici une description de sa structure et de ses fonctionnalités principales :

### Fonctionnalités principales

- **Démarrer les conteneurs** : Lance tous les conteneurs définis dans le fichier `docker-compose.yml` via la commande `docker-compose up -d`.
- **Arrêter les conteneurs** : Arrête et supprime les conteneurs ainsi que les volumes persistants via la commande `docker-compose down --volumes --remove-orphans`.
- **Menu interactif** : Propose un menu clair pour choisir entre démarrer ou arrêter les services.

### Structure du script

1. **Vérification des prérequis** : 
   Le script vérifie si Docker Compose est installé avant de continuer. En cas d'absence, il affiche un message d'erreur et arrête l'exécution.

2. **Définition des fonctions** :
   - `start_containers` : Lance les services définis dans `docker-compose.yml`.
   - `stop_containers` : Arrête les services et nettoie les volumes associés.

3. **Interaction utilisateur** :
   Le script affiche un menu interactif dans le terminal, demandant à l'utilisateur de choisir entre démarrer ou arrêter les conteneurs.

### Utilisation

- **Pour lancer les conteneurs** :
  Exécutez le script, puis choisissez l'option `1` dans le menu. Tous les services (Node-RED, TimescaleDB, Nginx, PHP) démarreront automatiquement.

- **Pour arrêter les conteneurs** :
  Exécutez le script, puis choisissez l'option `2`. Cela arrêtera tous les services et libérera les ressources.

### Code complet du script

```bash
#!/bin/bash

# Chemin du projet
PROJECT_DIR="$(dirname "$(readlink -f "$0")")"

# Vérification de docker-compose
if ! command -v docker-compose &> /dev/null; then
    echo "Erreur : docker-compose n'est pas installé. Veuillez l'installer avant de lancer ce script."
    exit 1
fi

# Fonction pour démarrer Docker Compose
start_containers() {
    echo "Lancement des conteneurs Docker..."
    docker-compose -f "$PROJECT_DIR/Docker/docker-compose.yml" up -d
    echo "Conteneurs lancés !"
}

# Fonction pour arrêter Docker Compose
stop_containers() {
    echo "Arrêt des conteneurs Docker..."
    docker-compose -f "$PROJECT_DIR/Docker/docker-compose.yml" down --volumes --remove-orphans
    echo "Conteneurs arrêtés et nettoyés !"
}

# Menu interactif
echo
echo "=== Gestion des Conteneurs Docker ==="
echo "Chemin du projet : $PROJECT_DIR"
echo
echo "Que voulez-vous faire ?"
echo "1) Démarrer les conteneurs"
echo "2) Arrêter les conteneurs"
read -p "Choisissez une option (1/2) : " choice

case $choice in
    1) start_containers ;;
    2) stop_containers ;;
    *) echo "Option invalide. Veuillez choisir 1 ou 2." ;;
esac

```

Le script est situé à la racine du projet et peut être exécuté directement pour gérer les conteneurs.

