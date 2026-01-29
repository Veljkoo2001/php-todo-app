<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['task'])) {
    $task = trim($_POST['task']);
    
    $stmt = $pdo->prepare("INSERT INTO tasks (task_text) VALUES (?)");
    $stmt->execute([$task]);
    
    header("Location: index.php");
    exit();
}
?>