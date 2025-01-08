#!/bin/bash

# Chemin du projet
PROJECT_DIR="$(dirname "$(readlink -f "$0")")"

# Fonction pour démarrer Docker Compose
start_containers() {
    echo "Lancement des conteneurs Docker..."
    docker-compose -f "$PROJECT_DIR/docker-compose.yml" up -d
    echo "Conteneurs lancés !"
}

# Fonction pour arrêter Docker Compose
stop_containers() {
    echo "Arrêt des conteneurs Docker..."
    docker-compose -f "$PROJECT_DIR/docker-compose.yml" down
    echo "Conteneurs arrêtés !"
}

# Demande à l'utilisateur s'il veut lancer ou arrêter les conteneurs
echo "Que voulez-vous faire ?"
echo "1) Démarrer les conteneurs"
echo "2) Arrêter les conteneurs"
read -p "Choisissez une option (1/2) : " choice

case $choice in
    1) start_containers ;;
    2) stop_containers ;;
    *) echo "Option invalide. Veuillez choisir 1 ou 2." ;;
esac
gi