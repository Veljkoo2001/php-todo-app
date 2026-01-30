<?php
include 'config.php';
requireLogin();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = (int)$_GET['status'];
    
    // DODAJ proveru da li zadatak pripada korisniku
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$status, $id, $_SESSION['user_id']]);
    
    $_SESSION['success'] = "Zadatak ažuriran!";
    header("Location: index.php");
    exit();
}
?>