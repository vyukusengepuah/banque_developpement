<?php
// Inclure la configuration
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de connexion - Banque de Développement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f0f4f8; padding: 30px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        h1 { color: #1a5276; border-bottom: 3px solid #1a5276; padding-bottom: 10px; margin-bottom: 20px; }
        .success { color: #27ae60; font-weight: bold; font-size: 18px; }
        .error { color: #e74c3c; font-weight: bold; }
        .info-box { background: #d6eaf8; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .info-box strong { color: #1a5276; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background: #1a5276; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        tr:hover { background: #f1f9ff; }
        .stats { display: flex; gap: 15px; flex-wrap: wrap; margin: 20px 0; }
        .stat-card { flex: 1; min-width: 150px; padding: 20px; border-radius: 10px; color: white; text-align: center; }
        .stat-card .number { font-size: 26px; font-weight: bold; }
        .stat-card .label { font-size: 14px; opacity: 0.9; }
        .stat-blue { background: #1a5276; }
        .stat-green { background: #27ae60; }
        .stat-orange { background: #f39c12; }
        .stat-purple { background: #8e44ad; }
        .footer { margin-top: 30px; text-align: center; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; color: white; }
        .badge-recu { background: #27ae60; }
        .badge-attente { background: #f39c12; }
        .badge-verifie { background: #2980b9; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Test de connexion - Banque de Développement</h1>

        <?php if ($conn): ?>
            <p class="success">✅ Connexion réussie à la base de données !</p>

            <!-- Statistiques -->
            <div class="stats">
                <div class="stat-card stat-blue">
                    <div class="number"><?= getTotalRecettes() ?></div>
                    <div class="label">📋 Nombre de recettes</div>
                </div>
                <div class="stat-card stat-green">
                    <div class="number"><?= format_montant(getMontantTotalRecettes()) ?></div>
                    <div class="label">💰 Total recettes</div>
                </div>
                <div class="stat-card stat-orange">
                    <div class="number"><?= format_montant(getMontantEnAttente()) ?></div>
                    <div class="label">⏳ En attente</div>
                </div>
                <div class="stat-card stat-purple">
                    <div class="number">
                        <?php 
                        $sql = "SHOW TABLES";
                        $result = mysqli_query($conn, $sql);
                        echo mysqli_num_rows($result);
                        ?>
                    </div>
                    <div class="label">📁 Tables</div>
                </div>
            </div>

            <!-- Liste des tables -->
            <div class="info-box">
                <strong>📁 Tables dans la base :</strong><br>
                <?php
                $sql = "SHOW TABLES";
                $result = mysqli_query($conn, $sql);
                $tables = [];
                while ($row = mysqli_fetch_row($result)) {
                    $tables[] = $row[0];
                }
                echo implode(' | ', $tables);
                ?>
            </div>

            <!-- 5 dernières recettes -->
            <h2 style="margin-top: 25px;">🔄 5 dernières recettes</h2>
            <?php
            $sql = "SELECT * FROM recettes ORDER BY date_recette DESC LIMIT 5";
            $result = mysqli_query($conn, $sql);
            ?>
            <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Catégorie</th>
                        <th>Montant</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= date('d/m/Y', strtotime($row['date_recette'])) ?></td>
                        <td><?= $row['type_recette'] ?></td>
                        <td><?= $row['categorie'] ?></td>
                        <td><strong><?= format_montant($row['montant']) ?></strong></td>
                        <td>
                            <?php if($row['etat'] == 'recu'): ?>
                                <span class="badge badge-recu">✅ Reçu</span>
                            <?php elseif($row['etat'] == 'en_attente'): ?>
                                <span class="badge badge-attente">⏳ En attente</span>
                            <?php elseif($row['etat'] == 'verifie'): ?>
                                <span class="badge badge-verifie">🔵 Vérifié</span>
                            <?php else: ?>
                                <span class="badge" style="background: #95a5a6;"><?= $row['etat'] ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p style="color: #999;">⚠️ Aucune recette trouvée.</p>
            <?php endif; ?>

            <div style="margin-top: 20px; text-align: center;">
                <a href="recettes.php" style="display: inline-block; padding: 12px 25px; background: #1a5276; color: white; text-decoration: none; border-radius: 8px;">
                    📊 Voir toutes les recettes
                </a>
                <a href="ajouter.php" style="display: inline-block; padding: 12px 25px; background: #27ae60; color: white; text-decoration: none; border-radius: 8px;">
                    ➕ Ajouter une recette
                </a>
            </div>

        <?php else: ?>
            <p class="error">❌ Échec de connexion à la base de données.</p>
            <div class="info-box">
                <strong>🔧 Vérifiez :</strong>
                <ul>
                    <li>Que XAMPP est bien démarré (Apache + MySQL verts)</li>
                    <li>Que la base de données <code>banque_developpement</code> existe</li>
                    <li>Que les identifiants sont corrects (root, mot de passe vide)</li>
                </ul>
            </div>
        <?php endif; ?>

        <div class="footer">
            <p>&copy; 2025 - Banque de Développement de la Province - Pilotage Responsable</p>
        </div>
    </div>
</body>
</html>