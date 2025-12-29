<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';
session_start();

$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

if (!$userId || !$productId) {
    echo json_encode(['can_review' => false, 'not_logged_in' => true]);
    exit;
}

try {
    $db = getDb();
    
    // Kiểm tra xem user đã đánh giá sản phẩm này chưa
    $reviewCheck = $db->prepare('SELECT id FROM product_reviews WHERE user_id = ? AND product_id = ?');
    $reviewCheck->execute([$userId, $productId]);
    $existingReview = $reviewCheck->fetch();
    
    if ($existingReview) {
        echo json_encode(['can_review' => false, 'already_reviewed' => true]);
        exit;
    }
    
    // Kiểm tra xem user đã mua sản phẩm này và đơn hàng đã hoàn thành chưa
    $orderCheck = $db->prepare('
        SELECT o.id, o.status 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.user_id = ? AND oi.product_id = ?
        ORDER BY o.created_at DESC
        LIMIT 1
    ');
    $orderCheck->execute([$userId, $productId]);
    $order = $orderCheck->fetch();
    
    if (!$order) {
        echo json_encode(['can_review' => false, 'not_purchased' => true]);
        exit;
    }
    
    if ($order['status'] !== 'completed') {
        echo json_encode(['can_review' => false, 'order_not_completed' => true, 'order_status' => $order['status']]);
        exit;
    }
    
    // User có thể đánh giá
    echo json_encode(['can_review' => true]);
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['can_review' => false, 'error' => 'Server error']);
}
?>