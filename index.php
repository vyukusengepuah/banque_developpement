<?php
// Inclure la configuration
require_once 'config.php';

// ============================================================
// REQUÊTES DE TEST
// ============================================================

// 1. Récupérer le total des recettes
$sql = "SELECT SUM(montant) AS total FROM recettes WHERE etat IN ('recu', 'verifie')";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$total_recettes = $row['total'] ?? 0;

// 2. Récupérer les 5 dernières recettes
$sql = "SELECT * FROM recettes ORDER BY date_recette DESC LIMIT 5";
$result = mysqli_query($conn, $sql);

// 3. Compter le nombre total de recettes
$sql = "SELECT COUNT(*) AS total FROM recettes";
$result_count = mysqli_query($conn, $sql);
$nb_recettes = mysqli_fetch_assoc($result_count)['total'] ?? 0;

// 4. Récupérer les recettes en attente
$sql = "SELECT SUM(montant) AS total FROM recettes WHERE etat = 'en_attente'";
$result_attente = mysqli_query($conn, $sql);
$en_attente = mysqli_fetch_assoc($result_attente)['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banque de Développement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 20px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #1a5276; border-bottom: 3px solid #1a5276; padding-bottom: 10px; margin-bottom: 20px; }
        .stats { display: flex; gap: 20px; margin: 20px 0; flex-wrap: wrap; }
        .stat-card { flex: 1; padding: 20px; border-radius: 8px; color: white; text-align: center; min-width: 150px; }
        .stat-card .nombre { font-size: 28px; font-weight: bold; }
        .stat-card .label { opacity: 0.9; font-size: 14px; }
        .stat-card.blue { background: #1a5276; }
        .stat-card.green { background: #27ae60; }
        .stat-card.orange { background: #f39c12; }
        .stat-card.purple { background: #8e44ad; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1a5276; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f1f9ff; }
        .montant { font-weight: bold; text-align: right; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-recu { background: #27ae60; color: white; }
        .badge-attente { background: #f39c12; color: white; }
        .badge-verifie { background: #2980b9; color: white; }
        .footer { margin-top: 30px; text-align: center; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Banque de Développement de la Province</h1>
        <p><strong>Connexion :</strong> ✅ Base de données connectée avec succès !</p>

        <!-- Statistiques -->
        <div class="stats">
            <div class="stat-card blue">
                <div class="nombre"><?= format_montant($total_recettes) ?></div>
                <div class="label">💰 Total recettes</div>
            </div>
            <div class="stat-card orange">
                <div class="nombre"><?= format_montant($en_attente) ?></div>
                <div class="label">⏳ En attente</div>
            </div>
            <div class="stat-card green">
                <div class="nombre"><?= $nb_recettes ?></div>
                <div class="label">📋 Nombre total</div>
            </div>
            <div class="stat-card purple">
                <div class="nombre"><?= mysqli_num_rows($result) ?></div>
                <div class="label">🔄 Dernières recettes</div>
            </div>
        </div>

        <!-- Dernières recettes -->
        <h2>📋 Dernières recettes enregistrées</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Catégorie</th>
                    <th>Montant</th>
                    <th>Source</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($row['date_recette'])) ?></td>
                        <td><?= $row['type_recette'] ?></td>
                        <td><?= $row['categorie'] ?></td>
                        <td class="montant"><?= format_montant($row['montant']) ?></td>
                        <td><?= $row['source'] ?></td>
                        <td>
                            <?php if($row['etat'] == 'recu'): ?>
                                <span class="badge badge-recu">✅ Reçu</span>
                            <?php elseif($row['etat'] == 'en_attente'): ?>
                                <span class="badge badge-attente">⏳ En attente</span>
                            <?php elseif($row['etat'] == 'verifie'): ?>
                                <span class="badge badge-verifie">🔵 Vérifié</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: #999;">Aucune recette enregistrée</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="footer">
            <p>&copy; 2025 - Banque de Développement - Tous droits réservés</p>
        </div>
    </div>
</body>
</html>