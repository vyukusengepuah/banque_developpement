<?php
// Fichier de test pour vérifier la connexion
require_once 'config.php';

// Vérifier que la connexion fonctionne
if ($conn) {
    echo "✅ Connexion réussie à la base de données !<br><br>";
    
    // Tester une requête simple
    $sql = "SELECT COUNT(*) AS total FROM recettes";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    
    echo "📊 Nombre total de recettes : " . $row['total'] . "<br>";
    
    // Afficher les tables disponibles
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);
    echo "<br>📁 Tables dans la base :<br>";
    while($row = mysqli_fetch_row($result)) {
        echo "- " . $row[0] . "<br>";
    }
} else {
    echo "❌ Échec de connexion";
}
?>