<?php
session_start();
require_once '../db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = (int)$_POST['task_id'];
    $userId = $_SESSION['user_id'];
    
    // Provera da li task pripada korisniku
    $checkStmt = $conn->prepare("SELECT id FROM tasks WHERE id = ? AND user_id = ?");
    $checkStmt->bind_param("ii", $taskId, $userId);
    $checkStmt->execute();
    
    if ($checkStmt->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Task not found or access denied']);
        exit;
    }
    
    // Soft delete (ako hoćeš) ili hard delete
    // SOFT DELETE: UPDATE tasks SET deleted_at = NOW() WHERE id = ?
    // HARD DELETE:
    $deleteStmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $deleteStmt->bind_param("ii", $taskId, $userId);
    
    if ($deleteStmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Task deleted successfully',
            'task_id' => $taskId
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Delete failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>