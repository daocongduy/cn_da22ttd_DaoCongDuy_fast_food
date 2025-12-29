<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : true; // demo
if (!$isAdmin) { 
    http_response_code(403); 
    echo json_encode(['error'=>'Forbidden']); 
    exit; 
}

try {
    $db = getDb();
    $stmt = $db->query('SELECT id, name, email, phone, address, role, created_at FROM users ORDER BY created_at DESC');
    $users = $stmt->fetchAll();
    
    echo json_encode(['users' => $users], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
