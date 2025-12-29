<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : true; // demo
if (!$isAdmin) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }

$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId) { http_response_code(400); echo json_encode(['error'=>'Thiếu id']); exit; }

try {
    $db = getDb();
    $o = $db->prepare('SELECT o.*, u.name as customer FROM orders o LEFT JOIN users u ON u.id=o.user_id WHERE o.id=?');
    $o->execute([$orderId]);
    $order = $o->fetch();
    if (!$order) { http_response_code(404); echo json_encode(['error'=>'Không tìm thấy đơn']); exit; }

    $it = $db->prepare('SELECT oi.product_id, p.name, oi.quantity, oi.unit_price, oi.total_price FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id=?');
    $it->execute([$orderId]);
    $items = $it->fetchAll();

    $hs = $db->prepare('SELECT status, note, created_at FROM order_status_history WHERE order_id=? ORDER BY id DESC');
    $hs->execute([$orderId]);
    $history = $hs->fetchAll();

    echo json_encode(['order'=>$order, 'items'=>$items, 'history'=>$history], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500); echo json_encode(['error'=>'Server error']);
}
?>

