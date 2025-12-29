<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

// Kiểm tra quyền admin
$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : false;
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden - Admin access required'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Kiểm tra method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // Lấy dữ liệu từ request body
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['user_id']) || empty($input['user_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'User ID is required'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $userId = (int)$input['user_id'];
    
    if ($userId <= 0) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid User ID'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $db = getDb();
    
    // Kiểm tra xem user có tồn tại không
    $stmt = $db->prepare('SELECT id, role FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        http_response_code(404);
        echo json_encode(['error' => 'User not found'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Kiểm tra xem admin có đang cố xóa chính mình không
    if ($user['role'] === 'admin' && $userId == $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['error' => 'Cannot delete your own admin account'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Bắt đầu transaction
    $db->beginTransaction();
    
    try {
        // Xóa order_items của user (thông qua orders)
        $stmt = $db->prepare('DELETE oi FROM order_items oi INNER JOIN orders o ON oi.order_id = o.id WHERE o.user_id = ?');
        $stmt->execute([$userId]);
        
        // Xóa order_status_history của user (thông qua orders)
        $stmt = $db->prepare('DELETE osh FROM order_status_history osh INNER JOIN orders o ON osh.order_id = o.id WHERE o.user_id = ?');
        $stmt->execute([$userId]);
        
        // Xóa orders của user
        $stmt = $db->prepare('DELETE FROM orders WHERE user_id = ?');
        $stmt->execute([$userId]);
        $deletedOrders = $stmt->rowCount();
        
        // Xóa reviews của user (nếu có bảng reviews)
        try {
            $stmt = $db->prepare('DELETE FROM reviews WHERE user_id = ?');
            $stmt->execute([$userId]);
        } catch (Exception $e) {
            // Bỏ qua nếu bảng reviews không tồn tại
        }
        
        // Xóa user
        $stmt = $db->prepare('DELETE FROM users WHERE id = ? AND id != ?');
        $stmt->execute([$userId, $_SESSION['user_id']]);
        
        if ($stmt->rowCount() === 0) {
            throw new Exception('Failed to delete user');
        }
        
        // Commit transaction
        $db->commit();
        
        echo json_encode([
            'ok' => true,
            'message' => 'User deleted successfully' . ($deletedOrders > 0 ? ' (including ' . $deletedOrders . ' orders)' : ''),
            'deleted_user_id' => $userId
        ], JSON_UNESCAPED_UNICODE);
        
    } catch (Exception $e) {
        // Rollback transaction
        $db->rollBack();
        throw $e;
    }
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
