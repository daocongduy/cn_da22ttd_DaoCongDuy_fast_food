<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';
session_start();

// Kiểm tra đăng nhập
$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Chưa đăng nhập']);
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
    
    // Kiểm tra đơn hàng thuộc về user này và đã hoàn thành
    $stmt = $db->prepare('SELECT id, status FROM orders WHERE id = ? AND user_id = ?');
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        http_response_code(404);
        echo json_encode(['error' => 'Không tìm thấy đơn hàng hoặc không có quyền xóa']);
        exit;
    }
    
    // Chỉ cho phép xóa đơn đã hoàn thành
    if ($order['status'] !== 'completed') {
        http_response_code(400);
        echo json_encode(['error' => 'Chỉ có thể xóa đơn hàng đã hoàn thành']);
        exit;
    }
    
    // Xóa đơn hàng (cascade sẽ xóa order_items và order_status_history)
    $stmt = $db->prepare('DELETE FROM orders WHERE id = ? AND user_id = ?');
    $stmt->execute([$orderId, $userId]);
    
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
