#!/bin/bash

# Chemin du projet
PROJECT_DIR="$(dirname "$(readlink -f "$0")")"

# Vérification de docker-compose
if ! command -v docker-compose &> /dev/null; then
    echo "Erreur : docker-compose n'est pas installé. Veuillez l'installer avant de lancer ce script."
    exit 1
fi

# Fonction pour vérifier les ports
check_ports() {
    echo "Vérification des ports 80 et 1880..."
    if lsof -i:80 &> /dev/null; then
        echo "Le port 80 est déjà utilisé. Voulez-vous arrêter le service en conflit ? (y/n)"
        read -r response
        if [[ $response == "y" ]]; then
            sudo systemctl stop nginx || echo "Aucun service nginx actif à arrêter."
        else
            echo "Impossible de continuer tant que le port 80 est utilisé."
            exit 1
        fi
    fi

    if lsof -i:1880 &> /dev/null; then
        echo "Le port 1880 est déjà utilisé. Voulez-vous arrêter le service en conflit ? (y/n)"
        read -r response
        if [[ $response == "y" ]]; then
            sudo systemctl stop nodered || echo "Aucun service Node-RED actif à arrêter."
        else
            echo "Impossible de continuer tant que le port 1880 est utilisé."
            exit 1
        fi
    fi

    echo "Ports vérifiés, aucun conflit détecté."
}

# Fonction pour démarrer Docker Compose
start_containers() {
    check_ports
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
