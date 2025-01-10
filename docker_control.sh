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
    docker-compose -f "$PROJECT_DIR/Docker/docker-compose.yml" down
    echo "Conteneurs arrêtés !"
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
