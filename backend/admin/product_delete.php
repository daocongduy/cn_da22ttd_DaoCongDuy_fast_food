<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : true; // demo
if (!$isAdmin) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }

try {
    $db = getDb();
    $payload = $_POST ?: json_decode(file_get_contents('php://input'), true);
    $id = (int)($payload['id'] ?? 0);
    if (!$id) { http_response_code(400); echo json_encode(['error'=>'Thiếu id']); exit; }
    $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
    http_response_code(500); echo json_encode(['error'=>'Server error']);
}
?>

