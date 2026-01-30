<?php
include 'config.php';
requireLogin(); // Dodaj ovu liniju na početak

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    $task = trim($_POST['task']);
    
    // DODAJ user_id u INSERT
    $stmt = $pdo->prepare("INSERT INTO tasks (task_text, user_id) VALUES (?, ?)");
    $stmt->execute([$task, $_SESSION['user_id']]);
    
    $_SESSION['success'] = "Zadatak uspešno dodat!";
    header("Location: index.php");
    exit();
}
?>