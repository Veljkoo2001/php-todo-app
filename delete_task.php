<?php
include 'config.php';
requireLogin();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // DODAJ proveru da li zadatak pripada korisniku
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    
    $_SESSION['success'] = "Zadatak obrisan!";
    header("Location: index.php");
    exit();
}
?>