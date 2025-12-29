<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

// Demo quyền admin (tùy chỉnh theo đăng nhập thật)
$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : true;
if (!$isAdmin) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }

$payload = json_decode(file_get_contents('php://input'), true);
$orderId = (int)($payload['order_id'] ?? 0);
$status = (string)($payload['status'] ?? '');
$allowed = ['pending','confirmed','preparing','delivering','completed','cancelled'];
if (!$orderId || !in_array($status, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error'=>'Dữ liệu không hợp lệ']);
    exit;
}

try {
    $db = getDb();
    $upd = $db->prepare('UPDATE orders SET status=? WHERE id=?');
    $upd->execute([$status, $orderId]);
    $db->prepare('INSERT INTO order_status_history(order_id,status,note) VALUES(?,?,?)')
       ->execute([$orderId, $status, 'Admin cập nhật']);
    echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>'Server error']);
}
?>

