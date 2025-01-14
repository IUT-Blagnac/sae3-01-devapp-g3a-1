<?php
// Configuration de la base de données
$host = 'db'; // Nom du service PostgreSQL dans docker-compose.yml
$dbname = 'dashboard_db'; // Nom de la base de données
$user = 'admin'; // Nom d'utilisateur PostgreSQL
$password = 'password';

$salle = $_POST['salle'] ?? null;

if ($salle === null) {
    echo "Veuillez spécifier une salle.";
    exit;
}

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour supprimer les doublons de B111 en conservant une date distincte
    $deleteDuplicatesQuery = "
        DELETE FROM Mesures
        WHERE id IN (
            SELECT id
            FROM (
                SELECT id,
                       ROW_NUMBER() OVER (PARTITION BY room, temperature, humidity, activity, tvoc, illumination, infrared, infrared_and_visible, presure, devicename ORDER BY date_heure) AS row_num
                FROM Mesures
                WHERE room = '$salle'
            ) subquery
            WHERE row_num > 1
        );
    ";

    // Exécuter la requête de suppression
    $stmt = $pdo->prepare($deleteDuplicatesQuery);
    $stmt->execute();

    echo "Les doublons pour la salle B111 ont été supprimés avec succès, sauf ceux avec des dates différentes.";

} catch (PDOException $e) {
    // Gestion des erreurs de connexion ou d'exécution
    echo "Erreur : " . $e->getMessage();
}
?>
