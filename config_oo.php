<?php
// ============================================================
// CONNEXION ORIENTÉE OBJET AVEC MySQLi
// ============================================================

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'banque_developpement';

// Créer une instance de mysqli
$conn = new mysqli($host, $user, $password, $database);

// Vérifier la connexion
if ($conn->connect_error) {
    die("❌ Erreur de connexion : " . $conn->connect_error);
}

// Définir le charset
$conn->set_charset("utf8");

// echo "✅ Connexion OO réussie !";
?>