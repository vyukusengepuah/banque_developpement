<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $sql = "DELETE FROM recettes WHERE id_recette = $id";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: recettes.php?message=deleted');
    } else {
        header('Location: recettes.php?message=error');
    }
} else {
    header('Location: recettes.php');
}
exit;
?>