<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$limit = isset($_GET['limit']) ? min(50, max(1, (int)$_GET['limit'])) : 10;
$offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;

try {
    $db = getDb();
    
    if ($product_id > 0) {
        // Lấy đánh giá cho sản phẩm cụ thể
        $stmt = $db->prepare("
            SELECT 
                r.id,
                r.rating,
                r.comment,
                r.created_at,
                u.name as user_name,
                u.id as user_id
            FROM product_reviews r
            JOIN users u ON u.id = r.user_id
            WHERE r.product_id = ? AND r.is_approved = 1
            ORDER BY r.created_at DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute([$product_id]);
        $reviews = $stmt->fetchAll();
        
        // Đếm tổng số đánh giá
        $countStmt = $db->prepare('SELECT COUNT(*) as total FROM product_reviews WHERE product_id = ? AND is_approved = 1');
        $countStmt->execute([$product_id]);
        $total = $countStmt->fetch()['total'];
        
        // Lấy thống kê rating
        $statsStmt = $db->prepare('
            SELECT 
                AVG(rating) as average_rating,
                COUNT(*) as total_reviews,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1
            FROM product_reviews 
            WHERE product_id = ? AND is_approved = 1
        ');
        $statsStmt->execute([$product_id]);
        $stats = $statsStmt->fetch();
        
        echo json_encode([
            'ok' => true,
            'reviews' => $reviews,
            'total' => (int)$total,
            'stats' => [
                'average_rating' => round((float)$stats['average_rating'], 1),
                'total_reviews' => (int)$stats['total_reviews'],
                'rating_distribution' => [
                    5 => (int)$stats['rating_5'],
                    4 => (int)$stats['rating_4'],
                    3 => (int)$stats['rating_3'],
                    2 => (int)$stats['rating_2'],
                    1 => (int)$stats['rating_1']
                ]
            ],
            'pagination' => [
                'limit' => $limit,
                'offset' => $offset,
                'has_more' => ($offset + $limit) < $total
            ]
        ], JSON_UNESCAPED_UNICODE);
        
    } else {
        // Lấy tất cả đánh giá gần đây
        $stmt = $db->prepare("
            SELECT 
                r.id,
                r.product_id,
                r.rating,
                r.comment,
                r.created_at,
                u.name as user_name,
                p.name as product_name
            FROM product_reviews r
            JOIN users u ON u.id = r.user_id
            JOIN products p ON p.id = r.product_id
            WHERE r.is_approved = 1
            ORDER BY r.created_at DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute();
        $reviews = $stmt->fetchAll();
        
        echo json_encode([
            'ok' => true,
            'reviews' => $reviews
        ], JSON_UNESCAPED_UNICODE);
    }
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>