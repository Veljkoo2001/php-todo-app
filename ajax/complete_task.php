<?php
session_start();
require_once '../db.php'; // Prilagodi path

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = (int)$_POST['task_id'];
    $userId = $_SESSION['user_id'];
    
    // 1. Prvo proveri da li task pripada korisniku i dohvati trenutni status
    $checkStmt = $conn->prepare("SELECT is_completed FROM tasks WHERE id = ? AND user_id = ?");
    $checkStmt->bind_param("ii", $taskId, $userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Task not found']);
        exit;
    }
    
    $task = $result->fetch_assoc();
    $newStatus = $task['is_completed'] ? 0 : 1;
    
    // 2. Update statusa
    $updateStmt = $conn->prepare("UPDATE tasks SET is_completed = ? WHERE id = ? AND user_id = ?");
    $updateStmt->bind_param("iii", $newStatus, $taskId, $userId);
    
    if ($updateStmt->execute()) {
        // 3. Vrati nove podatke
        echo json_encode([
            'success' => true,
            'completed' => $newStatus,
            'task_id' => $taskId,
            'new_text' => $newStatus ? 'Completed!' : 'Pending'
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>