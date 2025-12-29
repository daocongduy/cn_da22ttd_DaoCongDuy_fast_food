<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

// Kiểm tra quyền admin
$role = $_SESSION['role'] ?? '';
if ($role !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$reviewId = isset($payload['review_id']) ? (int)$payload['review_id'] : 0;
$action = isset($payload['action']) ? $payload['action'] : ''; // approve, reject, delete

if (!$reviewId || !in_array($action, ['approve', 'reject', 'delete'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dữ liệu không hợp lệ']);
    exit;
}

try {
    $db = getDb();
    
    // Kiểm tra đánh giá tồn tại
    $reviewStmt = $db->prepare('SELECT id, product_id, is_approved FROM product_reviews WHERE id = ?');
    $reviewStmt->execute([$reviewId]);
    $review = $reviewStmt->fetch();
    
    if (!$review) {
        http_response_code(404);
        echo json_encode(['error' => 'Đánh giá không tồn tại']);
        exit;
    }
    
    if ($action === 'delete') {
        // Xóa đánh giá
        $deleteStmt = $db->prepare('DELETE FROM product_reviews WHERE id = ?');
        $deleteStmt->execute([$reviewId]);
        
        echo json_encode([
            'ok' => true,
            'message' => 'Đã xóa đánh giá'
        ], JSON_UNESCAPED_UNICODE);
        
    } else {
        // Cập nhật trạng thái duyệt
        $isApproved = ($action === 'approve') ? 1 : 0;
        
        $updateStmt = $db->prepare('UPDATE product_reviews SET is_approved = ? WHERE id = ?');
        $updateStmt->execute([$isApproved, $reviewId]);
        
        $message = ($action === 'approve') ? 'Đã duyệt đánh giá' : 'Đã từ chối đánh giá';
        
        echo json_encode([
            'ok' => true,
            'message' => $message
        ], JSON_UNESCAPED_UNICODE);
    }
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>