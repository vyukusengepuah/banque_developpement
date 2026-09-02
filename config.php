bhk<?php
// ============================================================
// CONFIGURATION DE LA BASE DE DONNÉES
// Banque de Développement de la Province
// ============================================================

// Paramètres de connexion
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'banque_developpement';

// Établir la connexion
$conn = mysqli_connect($host, $user, $password, $database);

// Vérifier la connexion
if (!$conn) {
    die("❌ Erreur de connexion : " . mysqli_connect_error());
}

// Définir le jeu de caractères UTF-8
mysqli_set_charset($conn, "utf8");

// ============================================================
// FONCTIONS UTILES
// ============================================================

/**
 * Sécurise les données contre les injections SQL et XSS
 */
function securise($donnee) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($donnee)));
}

/**
 * Formate un montant en BIF
 */
function format_montant($montant) {
    if ($montant === null || $montant === '') {
        return '0 BIF';
    }
    return number_format($montant, 0, ',', ' ') . ' BIF';
}

/**
 * Affiche le badge d'état
 */
function getBadgeEtat($etat) {
    $badges = [
        'en_attente' => '<span style="background:#f39c12; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">⏳ En attente</span>',
        'recu' => '<span style="background:#27ae60; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">✅ Reçu</span>',
        'verifie' => '<span style="background:#2980b9; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">🔵 Vérifié</span>',
        'annule' => '<span style="background:#e74c3c; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">❌ Annulé</span>'
    ];
    return $badges[$etat] ?? '<span style="background:#95a5a6; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">Inconnu</span>';
}

/**
 * Affiche le badge pour le type de recette
 */
function getBadgeType($type) {
    $badges = [
        'interets' => '<span style="background:#1a5276; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">💰 Intérêts</span>',
        'commissions' => '<span style="background:#2e86c1; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">📋 Commissions</span>',
        'remboursement_capital' => '<span style="background:#27ae60; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">🔄 Remboursement</span>',
        'frais_dossier' => '<span style="background:#f39c12; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">📄 Frais dossier</span>',
        'subvention' => '<span style="background:#8e44ad; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">🎁 Subvention</span>',
        'autres' => '<span style="background:#95a5a6; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">📌 Autres</span>'
    ];
    return $badges[$type] ?? '<span style="background:#95a5a6; color:white; padding:4px 10px; border-radius:12px; font-size:12px;">📌 Autre</span>';
}

/**
 * Retourne la date actuelle
 */
function date_actuelle() {
    return date('Y-m-d');
}

/**
 * Formate une date en français (JJ/MM/AAAA)
 */
function date_francais($date) {
    if (empty($date)) return '-';
    return date('d/m/Y', strtotime($date));
}

/**
 * Récupère le nom d'un client par son ID
 */
function getClientName($id_client) {
    global $conn;
    if (!$id_client) return '-';
    $sql = "SELECT nom_complet FROM clients WHERE id_client = $id_client";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['nom_complet'];
    }
    return '-';
}

/**
 * Récupère le nombre total de recettes
 */
function getTotalRecettes() {
    global $conn;
    $sql = "SELECT COUNT(*) AS total FROM recettes";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

/**
 * Récupère le montant total des recettes
 */
function getMontantTotalRecettes() {
    global $conn;
    $sql = "SELECT SUM(montant) AS total FROM recettes WHERE etat IN ('recu', 'verifie')";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

/**
 * Récupère le montant des recettes en attente
 */
function getMontantEnAttente() {
    global $conn;
    $sql = "SELECT SUM(montant) AS total FROM recettes WHERE etat = 'en_attente'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

// ============================================================
// TESTS (décommentez pour vérifier la connexion)
// ============================================================

/*
echo "✅ Connexion réussie !<br>";
echo "📊 Nombre total de recettes : " . getTotalRecettes() . "<br>";
echo "💰 Total : " . format_montant(getMontantTotalRecettes()) . "<br>";
echo "⏳ En attente : " . format_montant(getMontantEnAttente()) . "<br>";
*/

?>