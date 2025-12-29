<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';
session_start();

$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$orderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$orderId || !$userId) { http_response_code(400); echo json_encode(['error'=>'Thiếu dữ liệu']); exit; }

try {
    $db = getDb();
    // Chỉ cho phép xem đơn của chính mình
    $o = $db->prepare('SELECT id, user_id, status, total_amount, shipping_name, shipping_phone, shipping_address, created_at, updated_at FROM orders WHERE id=? AND user_id=?');
    $o->execute([$orderId, $userId]);
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

