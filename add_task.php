<?php
session_start();
include 'config.php';

// Provera da li je korisnik prijavljen
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Provera POST zahteva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task_text = trim($_POST['task']);
    $user_id = $_SESSION['user_id'];
    
    // Dobavi priority i deadline
    $priority = isset($_POST['priority']) ? $_POST['priority'] : 'medium';
    $deadline = isset($_POST['deadline']) && !empty($_POST['deadline']) 
                ? $_POST['deadline'] 
                : null;
    
    if (!empty($task_text)) {
        // SQL sa novim poljima
        $sql = "INSERT INTO tasks (task_text, user_id, priority, deadline) 
                VALUES (:task_text, :user_id, :priority, :deadline)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':task_text' => $task_text,
            ':user_id' => $user_id,
            ':priority' => $priority,
            ':deadline' => $deadline
        ]);
        
        $_SESSION['success'] = "✅ Zadatak uspešno dodat!";
    } else {
        $_SESSION['error'] = "❌ Zadatak ne može biti prazan!";
    }
} else {
    $_SESSION['error'] = "❌ Neispravan zahtev!";
}

// Vrati se nazad na glavnu stranu
header('Location: index.php');
exit();
?>