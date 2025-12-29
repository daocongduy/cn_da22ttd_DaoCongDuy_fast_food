<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

// Kiểm tra quyền admin
$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : false;
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$orderId = isset($payload['order_id']) ? (int)$payload['order_id'] : 0;

if (!$orderId) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu mã đơn hàng']);
    exit;
}

try {
    $db = getDb();
    $db->beginTransaction();
    
    // Kiểm tra đơn hàng có tồn tại không
    $stmt = $db->prepare('SELECT id, status FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Không tìm thấy đơn hàng']);
        exit;
    }
    
    // Chỉ cho phép xóa đơn đã hoàn thành
    if ($order['status'] !== 'completed') {
        http_response_code(400);
        echo json_encode(['error' => 'Chỉ có thể xóa đơn hàng đã hoàn thành']);
        exit;
    }
    
    // Xóa đơn hàng (cascade sẽ xóa order_items và order_status_history)
    $stmt = $db->prepare('DELETE FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    
    $db->commit();
    echo json_encode(['ok' => true, 'message' => 'Xóa đơn hàng thành công']);
    
} catch (Throwable $e) {
    if ($db && $db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => 'Lỗi server: ' . $e->getMessage()]);
}
?>
