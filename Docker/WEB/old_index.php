<?php
// Configuration de la base de données
$host = 'db'; // Nom du service PostgreSQL dans docker-compose.yml
$dbname = 'dashboard_db'; // Nom de la base de données
$user = 'admin'; // Nom d'utilisateur PostgreSQL
$password = 'password'; // Mot de passe PostgreSQL

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête pour récupérer les données de la table Mesures
    $query = "SELECT * FROM Mesures;";
    $stmt = $pdo->query($query);

    // Affichage des résultats
    echo "<h1>Données de la Table Mesures</h1>";
    echo "<table border='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Activity</th>
            <th>co2</th>
            <th>TVOC</th>
            <th>Illumination</th>
            <th>Infrared</th>
            <th>Infrared and Visible</th>
            <th>Presure</th>
            <th>Device Name</th>
            <th>Room</th>
            <th>Date Heure</th>
          </tr>";

    // Boucle pour afficher chaque ligne de résultat
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['temperature']) . "</td>";
        echo "<td>" . htmlspecialchars($row['humidity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['activity']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dioxidecarbon']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tvoc']) . "</td>";
        echo "<td>" . htmlspecialchars($row['illumination']) . "</td>";
        echo "<td>" . htmlspecialchars($row['infrared']) . "</td>";
        echo "<td>" . htmlspecialchars($row['infrared_and_visible']) . "</td>";
        echo "<td>" . htmlspecialchars($row['presure']) . "</td>";
        echo "<td>" . htmlspecialchars($row['devicename']) . "</td>";
        echo "<td>" . htmlspecialchars($row['room']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_heure']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Requête pour trouver la salle la plus récurrente
    $mostRecurringRoomQuery = "
        SELECT room, COUNT(room) AS room_count 
        FROM Mesures 
        GROUP BY room 
        ORDER BY room_count DESC 
        LIMIT 1;
    ";
    $mostRecurringStmt = $pdo->query($mostRecurringRoomQuery);
    $mostRecurringRoom = $mostRecurringStmt->fetch(PDO::FETCH_ASSOC);

    if ($mostRecurringRoom) {
        echo "<h2>Salle la plus récurrente</h2>";
        echo "<p>Room: " . htmlspecialchars($mostRecurringRoom['room']) . "</p>";
        echo "<p>Occurrences: " . htmlspecialchars($mostRecurringRoom['room_count']) . "</p>";
    } else {
        echo "<p>Aucune donnée pour déterminer la salle la plus récurrente.</p>";
    }

} catch (PDOException $e) {
    // Gestion des erreurs de connexion ou d'exécution
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>
