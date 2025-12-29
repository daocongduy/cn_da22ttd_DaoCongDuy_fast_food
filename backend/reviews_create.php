<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';
session_start();

// Kiểm tra đăng nhập
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Bạn cần đăng nhập để đánh giá']);
    exit;
}

// Chặn admin đánh giá
$role = $_SESSION['role'] ?? 'user';
if ($role === 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Admin không thể đánh giá sản phẩm']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$productId = isset($payload['product_id']) ? (int)$payload['product_id'] : 0;
$rating = isset($payload['rating']) ? (int)$payload['rating'] : 0;
$comment = isset($payload['comment']) ? trim($payload['comment']) : '';

// Validation
if (!$productId || $rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['error' => 'Dữ liệu không hợp lệ']);
    exit;
}

if (strlen($comment) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Bình luận quá dài (tối đa 1000 ký tự)']);
    exit;
}

try {
    $db = getDb();
    
    // Kiểm tra sản phẩm tồn tại
    $productStmt = $db->prepare('SELECT id, name FROM products WHERE id = ?');
    $productStmt->execute([$productId]);
    $product = $productStmt->fetch();
    
    if (!$product) {
        http_response_code(404);
        echo json_encode(['error' => 'Sản phẩm không tồn tại']);
        exit;
    }
    
    // Kiểm tra user đã đánh giá chưa
    $existingStmt = $db->prepare('SELECT id FROM product_reviews WHERE user_id = ? AND product_id = ?');
    $existingStmt->execute([$userId, $productId]);
    
    if ($existingStmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Bạn đã đánh giá sản phẩm này rồi']);
        exit;
    }
    
    // Optional: Kiểm tra user đã mua sản phẩm này chưa (có thể bỏ qua nếu muốn cho phép đánh giá tự do)
    $purchaseStmt = $db->prepare('
        SELECT o.id 
        FROM orders o
        JOIN order_items oi ON oi.order_id = o.id
        WHERE o.user_id = ? AND oi.product_id = ? AND o.status = "completed"
        LIMIT 1
    ');
    $purchaseStmt->execute([$userId, $productId]);
    $hasPurchased = $purchaseStmt->fetch();
    
    // Nếu muốn bắt buộc phải mua mới được đánh giá, uncomment dòng dưới:
    // if (!$hasPurchased) {
    //     http_response_code(403);
    //     echo json_encode(['error' => 'Bạn cần mua sản phẩm này trước khi đánh giá']);
    //     exit;
    // }
    
    // Thêm đánh giá mới
    $insertStmt = $db->prepare('
        INSERT INTO product_reviews (product_id, user_id, rating, comment, order_id) 
        VALUES (?, ?, ?, ?, ?)
    ');
    $orderId = $hasPurchased ? $hasPurchased['id'] : null;
    $insertStmt->execute([$productId, $userId, $rating, $comment, $orderId]);
    
    $reviewId = $db->lastInsertId();
    
    // Lấy thông tin đánh giá vừa tạo
    $newReviewStmt = $db->prepare('
        SELECT 
            r.id,
            r.rating,
            r.comment,
            r.created_at,
            u.name as user_name
        FROM product_reviews r
        JOIN users u ON u.id = r.user_id
        WHERE r.id = ?
    ');
    $newReviewStmt->execute([$reviewId]);
    $newReview = $newReviewStmt->fetch();
    
    echo json_encode([
        'ok' => true,
        'message' => 'Cảm ơn bạn đã đánh giá!',
        'review' => $newReview
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Lỗi server: ' . $e->getMessage()]);
}
?>