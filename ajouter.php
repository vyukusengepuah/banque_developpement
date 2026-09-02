<?php
require_once 'config.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_recette = securise($_POST['date_recette']);
    $type_recette = securise($_POST['type_recette']);
    $categorie = securise($_POST['categorie']);
    $montant = securise($_POST['montant']);
    $source = securise($_POST['source']);
    $client = securise($_POST['client']);
    $description = securise($_POST['description']);
    $etat = securise($_POST['etat']);

    if (empty($date_recette) || empty($type_recette) || empty($montant)) {
        $message = 'Veuillez remplir tous les champs obligatoires.';
        $message_type = 'danger';
    } else {
        $sql = "INSERT INTO recettes (date_recette, type_recette, categorie, montant, source, client, description, etat) 
                VALUES ('$date_recette', '$type_recette', '$categorie', '$montant', '$source', '$client', '$description', '$etat')";
        
        if (mysqli_query($conn, $sql)) {
            header('Location: recettes.php?message=success');
            exit;
        } else {
            $message = 'Erreur : ' . mysqli_error($conn);
            $message_type = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une recette - Banque de Développement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f0f4f8; padding: 20px; }
        .container { max-width: 700px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); }
        h1 { color: #1a5276; border-bottom: 3px solid #1a5276; padding-bottom: 10px; margin-bottom: 20px; }
        .nav { margin-bottom: 20px; }
        .nav a { text-decoration: none; color: #555; padding: 8px 15px; border-radius: 5px; margin-right: 5px; }
        .nav a:hover { background: #eaf2f8; }
        .nav a.actif { background: #1a5276; color: white; }
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
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-danger { background: #fadbd8; border: 1px solid #e74c3c; color: #922b21; }
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
            <a href="ajouter.php" class="actif">➕ Ajouter</a>
        </div>

        <h2>➕ Ajouter une nouvelle recette</h2>

        <?php if($message): ?>
        <div class="alert alert-<?= $message_type ?>">
            <?= $message ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label>Date <span class="required">*</span></label>
                    <input type="date" name="date_recette" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Type <span class="required">*</span></label>
                    <select name="type_recette" required>
                        <option value="">Sélectionner</option>
                        <option value="interets">Intérêts</option>
                        <option value="commissions">Commissions</option>
                        <option value="remboursement_capital">Remboursement</option>
                        <option value="subvention">Subvention</option>
                        <option value="autres">Autres</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Catégorie <span class="required">*</span></label>
                    <select name="categorie" required>
                        <option value="">Sélectionner</option>
                        <option value="credit_agricole">Crédit agricole</option>
                        <option value="credit_pme">Crédit PME</option>
                        <option value="frais_service">Frais de service</option>
                        <option value="frais_dossier">Frais de dossier</option>
                        <option value="investissement">Investissement</option>
                        <option value="autres">Autres</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Montant (BIF) <span class="required">*</span></label>
                    <input type="number" name="montant" step="0.01" placeholder="Ex: 1000000" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Source <span class="required">*</span></label>
                    <input type="text" name="source" placeholder="Ex: Agriculteurs - Province Nord" required>
                </div>
                <div class="form-group">
                    <label>Client</label>
                    <input type="text" name="client" placeholder="Ex: Jean Ndayishimiye">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Description détaillée de la recette"></textarea>
            </div>

            <div class="form-group">
                <label>État <span class="required">*</span></label>
                <select name="etat" required>
                    <option value="en_attente">⏳ En attente</option>
                    <option value="recu">✅ Reçu</option>
                    <option value="verifie">🔵 Vérifié</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Enregistrer</button>
                <a href="recettes.php" class="btn btn-danger">❌ Annuler</a>
            </div>
        </form>

        <div class="footer">
            <p>&copy; 2025 - Banque de Développement de la Province - Pilotage Responsable</p>
        </div>
    </div>
</body>
</html>