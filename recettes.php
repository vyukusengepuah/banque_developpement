<?php
require_once 'config.php';

$sql = "SELECT * FROM recettes ORDER BY date_recette DESC";
$result = mysqli_query($conn, $sql);

$sql_total = "SELECT SUM(montant) AS total FROM recettes WHERE etat IN ('recu', 'verifie')";
$result_total = mysqli_query($conn, $sql_total);
$total_general = mysqli_fetch_assoc($result_total)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des recettes - Banque de Développement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f0f4f8; padding: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        h1 { color: #1a5276; border-bottom: 3px solid #1a5276; padding-bottom: 10px; margin-bottom: 20px; }
        .header-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .header-actions h2 { color: #1a5276; }
        .btn { display: inline-block; padding: 10px 20px; background: #1a5276; color: white; text-decoration: none; border-radius: 8px; border: none; cursor: pointer; font-size: 14px; }
        .btn:hover { background: #2e86c1; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #1e8449; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-warning { background: #f39c12; }
        .btn-warning:hover { background: #b7950b; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #1a5276; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f1f9ff; }
        .montant { font-weight: bold; text-align: right; }
        .total-ligne { background: #d5f5e3; font-weight: bold; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; color: white; }
        .badge-recu { background: #27ae60; }
        .badge-attente { background: #f39c12; }
        .badge-verifie { background: #2980b9; }
        .badge-annule { background: #e74c3c; }
        .actions a { text-decoration: none; padding: 5px 10px; border-radius: 5px; margin: 0 2px; font-size: 14px; }
        .actions .edit { background: #f39c12; color: white; }
        .actions .delete { background: #e74c3c; color: white; }
        .actions .view { background: #2980b9; color: white; }
        .nav { margin-bottom: 20px; }
        .nav a { text-decoration: none; color: #555; padding: 8px 15px; border-radius: 5px; margin-right: 5px; }
        .nav a:hover { background: #eaf2f8; }
        .nav a.actif { background: #1a5276; color: white; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d5f5e3; border: 1px solid #27ae60; color: #1a6e3a; }
        .footer { margin-top: 30px; text-align: center; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
        .filtres { background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
        .filtres input, .filtres select { padding: 8px 12px; border: 2px solid #ddd; border-radius: 5px; }
        .filtres input:focus, .filtres select:focus { border-color: #1a5276; outline: none; }
        @media (max-width: 768px) { table { font-size: 12px; } th, td { padding: 6px; } .header-actions { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Banque de Développement de la Province</h1>
        
        <div class="nav">
            <a href="test.php">📊 Tableau de bord</a>
            <a href="recettes.php" class="actif">💰 Recettes</a>
            <a href="ajouter.php">➕ Ajouter</a>
        </div>

        <div class="header-actions">
            <h2>💰 Liste des recettes</h2>
            <div>
                <a href="ajouter.php" class="btn btn-success">➕ Nouvelle recette</a>
            </div>
        </div>

        <?php if(isset($_GET['message'])): ?>
            <?php if($_GET['message'] == 'success'): ?>
                <div class="alert alert-success">✅ Recette ajoutée avec succès !</div>
            <?php elseif($_GET['message'] == 'updated'): ?>
                <div class="alert alert-success">✅ Recette modifiée avec succès !</div>
            <?php elseif($_GET['message'] == 'deleted'): ?>
                <div class="alert alert-success">✅ Recette supprimée avec succès !</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Filtres -->
        <div class="filtres">
            <form method="GET" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center; width: 100%;">
                <select name="type">
                    <option value="">Tous les types</option>
                    <option value="interets">Intérêts</option>
                    <option value="commissions">Commissions</option>
                    <option value="remboursement_capital">Remboursements</option>
                    <option value="subvention">Subventions</option>
                    <option value="autres">Autres</option>
                </select>
                <select name="etat">
                    <option value="">Tous les états</option>
                    <option value="recu">Reçu</option>
                    <option value="en_attente">En attente</option>
                    <option value="verifie">Vérifié</option>
                    <option value="annule">Annulé</option>
                </select>
                <input type="date" name="date_debut" placeholder="Date début">
                <input type="date" name="date_fin" placeholder="Date fin">
                <button type="submit" class="btn">🔍 Filtrer</button>
                <a href="recettes.php" class="btn btn-danger">🔄 Réinitialiser</a>
            </form>
        </div>

        <?php if(mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Catégorie</th>
                    <th>Montant</th>
                    <th>Source</th>
                    <th>Client</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                while($row = mysqli_fetch_assoc($result)): 
                ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= date('d/m/Y', strtotime($row['date_recette'])) ?></td>
                    <td><?= $row['type_recette'] ?></td>
                    <td><?= $row['categorie'] ?></td>
                    <td class="montant"><?= format_montant($row['montant']) ?></td>
                    <td><?= $row['source'] ?></td>
                    <td><?= $row['client'] ?? '-' ?></td>
                    <td>
                        <?php if($row['etat'] == 'recu'): ?>
                            <span class="badge badge-recu">✅ Reçu</span>
                        <?php elseif($row['etat'] == 'en_attente'): ?>
                            <span class="badge badge-attente">⏳ En attente</span>
                        <?php elseif($row['etat'] == 'verifie'): ?>
                            <span class="badge badge-verifie">🔵 Vérifié</span>
                        <?php else: ?>
                            <span class="badge badge-annule">❌ Annulé</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="modifier.php?id=<?= $row['id_recette'] ?>" class="edit">✏️</a>
                        <a href="supprimer.php?id=<?= $row['id_recette'] ?>" class="delete" onclick="return confirm('Supprimer cette recette ?')">🗑️</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-ligne">
                    <td colspan="4" style="text-align: right;"><strong>TOTAL GÉNÉRAL</strong></td>
                    <td class="montant"><strong><?= format_montant($total_general) ?></strong></td>
                    <td colspan="4"></td>
                </tr>
            </tbody>
        </table>
        <?php else: ?>
            <p style="text-align: center; color: #999; padding: 30px;">⚠️ Aucune recette enregistrée pour le moment.</p>
        <?php endif; ?>

        <div class="footer">
            <p>&copy; 2025 - Banque de Développement de la Province - Pilotage Responsable</p>
        </div>
    </div>
</body>
</html>