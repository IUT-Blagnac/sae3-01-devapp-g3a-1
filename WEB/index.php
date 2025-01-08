<?php
// Configuration de la base de données
$host = 'db'; // Adresse du serveur
$dbname = 'dashboard_db'; // Nom de la base de données
$user = 'admin'; // Nom d'utilisateur
$password = 'password'; // Mot de passe

try {
    // Connexion à la base de données
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les membres
    $query = "SELECT * FROM membre";
    $stmt = $pdo->query($query);

    // Afficher les membres
    echo "<h1>Liste des Membres</h1>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($row['prenom']) . "</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    // Gestion des erreurs
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
