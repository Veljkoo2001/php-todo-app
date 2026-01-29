<?php
include 'config.php';

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = (int)$_GET['status'];
    
    $stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    header("Location: index.php");
    exit();
}
?>