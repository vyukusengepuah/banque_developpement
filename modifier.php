<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: recettes.php');
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM recettes WHERE id_recette = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header('Location: recettes.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_recette = securise($_POST['date_recette']);
    $type_recette = securise($_POST['type_recette']);
    $categorie = securise($_POST['categorie']);
    $montant = securise($_POST['montant']);
    $source = securise($_POST['source']);
    $client = securise($_POST['client']);
    $description = securise($_POST['description']);
    $etat = securise($_POST['etat']);

    $sql = "UPDATE recettes SET 
            date_recette = '$date_recette',
            type_recette = '$type_recette',
            categorie = '$categorie',
            montant = '$montant',
            source = '$source',
            client = '$client',
            description = '$description',
            etat = '$etat'
            WHERE id_recette = $id";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: recettes.php?message=updated');
        exit;
    } else {
        $message = 'Erreur : ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une recette - Banque de Développement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f0f4f8; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        h1 { color: #1a5276; border-bottom: 3px solid #1a5276; padding-bottom: 10px; margin-bottom: 20px; }
        .nav a { text-decoration: none; color: #555; padding: 8px 15px; border-radius: 5px; margin-right: 5px; }
        .nav a:hover { background: #eaf2f8; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; color: #333; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 14px; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: #1a5276; outline: none; }
        .form-row { display: flex; gap: 20px; }
        .form-row .form-group { flex: 1; }
        .btn { padding: 12px 25px; border: none; border-radius: 5px; color: white; font-size: 16px; cursor: pointer; }
        .btn-primary { background: #1a5276; }
        .btn-primary:hover { background: #2e86c1; }
        .btn-danger { background: #e74c3c; text-decoration: none; display: inline-block; }
        .btn-danger:hover { background: #c0392b; }
        .form-actions { display: flex; gap: 10px; margin-top: 20px; }
        .alert-danger { background: #fadbd8; border: 1px solid #e74c3c; color: #922b21; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .footer { margin-top: 30px; text-align: center; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
        .required { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏦 Banque de Développement de la Province</h1>
        <div class="nav">
            <a href="test.php">📊 Tableau de bord</a>
            <a href="recettes.php">💰 Recettes</a>
            <a href="ajouter.php">➕ Ajouter</a>
        </div>

        <h2>✏️ Modifier la recette #<?= $id ?></h2>

        <?php if(isset($message)): ?>
        <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label>Date <span class="required">*</span></label>
                    <input type="date" name="date_recette" value="<?= $row['date_recette'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Type <span class="required">*</span></label>
                    <select name="type_recette" required>
                        <option value="interets" <?= $row['type_recette'] == 'interets' ? 'selected' : '' ?>>Intérêts</option>
                        <option value="commissions" <?= $row['type_recette'] == 'commissions' ? 'selected' : '' ?>>Commissions</option>
                        <option value="remboursement_capital" <?= $row['type_recette'] == 'remboursement_capital' ? 'selected' : '' ?>>Remboursement</option>
                        <option value="subvention" <?= $row['type_recette'] == 'subvention' ? 'selected' : '' ?>>Subvention</option>
                        <option value="autres" <?= $row['type_recette'] == 'autres' ? 'selected' : '' ?>>Autres</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Catégorie <span class="required">*</span></label>
                    <select name="categorie" required>
                        <option value="credit_agricole" <?= $row['categorie'] == 'credit_agricole' ? 'selected' : '' ?>>Crédit agricole</option>
                        <option value="credit_pme" <?= $row['categorie'] == 'credit_pme' ? 'selected' : '' ?>>Crédit PME</option>
                        <option value="frais_service" <?= $row['categorie'] == 'frais_service' ? 'selected' : '' ?>>Frais de service</option>
                        <option value="frais_dossier" <?= $row['categorie'] == 'frais_dossier' ? 'selected' : '' ?>>Frais de dossier</option>
                        <option value="investissement" <?= $row['categorie'] == 'investissement' ? 'selected' : '' ?>>Investissement</option>
                        <option value="autres" <?= $row['categorie'] == 'autres' ? 'selected' : '' ?>>Autres</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Montant (BIF) <span class="required">*</span></label>
                    <input type="number" name="montant" step="0.01" value="<?= $row['montant'] ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Source <span class="required">*</span></label>
                    <input type="text" name="source" value="<?= $row['source'] ?>" required>
                </div>
                <div class="form-group">
                    <label>Client</label>
                    <input type="text" name="client" value="<?= $row['client'] ?? '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"><?= $row['description'] ?? '' ?></textarea>
            </div>

            <div class="form-group">
                <label>État <span class="required">*</span></label>
                <select name="etat" required>
                    <option value="en_attente" <?= $row['etat'] == 'en_attente' ? 'selected' : '' ?>>⏳ En attente</option>
                    <option value="recu" <?= $row['etat'] == 'recu' ? 'selected' : '' ?>>✅ Reçu</option>
                    <option value="verifie" <?= $row['etat'] == 'verifie' ? 'selected' : '' ?>>🔵 Vérifié</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Mettre à jour</button>
                <a href="recettes.php" class="btn btn-danger">❌ Annuler</a>
            </div>
        </form>

        <div class="footer">
            <p>&copy; 2025 - Banque de Développement de la Province - Pilotage Responsable</p>
        </div>
    </div>
</body>
</html>