<?php
// ============================================================
// CONFIGURATION AVEC PDO (recommandé pour les projets modernes)
// ============================================================

$host = 'localhost';
$dbname = 'banque_developpement';
$user = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    // echo "✅ Connexion PDO réussie !";
} catch (PDOException $e) {
    die("❌ Erreur de connexion PDO : " . $e->getMessage());
}

// Fonction pour formater les montants
function format_montant($montant) {
    return number_format($montant, 0, ',', ' ') . ' BIF';
}
?>