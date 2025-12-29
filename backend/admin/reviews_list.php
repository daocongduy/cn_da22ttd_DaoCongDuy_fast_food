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

$limit = isset($_GET['limit']) ? min(100, max(1, (int)$_GET['limit'])) : 20;
$offset = isset($_GET['offset']) ? max(0, (int)$_GET['offset']) : 0;
$status = isset($_GET['status']) ? $_GET['status'] : 'all'; // all, approved, pending

try {
    $db = getDb();
    
    // Build WHERE clause
    $whereClause = '1=1';
    $params = [];
    
    if ($status === 'approved') {
        $whereClause .= ' AND r.is_approved = 1';
    } elseif ($status === 'pending') {
        $whereClause .= ' AND r.is_approved = 0';
    }
    
    // Lấy danh sách đánh giá
    $stmt = $db->prepare("
        SELECT 
            r.id,
            r.product_id,
            r.rating,
            r.comment,
            r.is_approved,
            r.created_at,
            r.updated_at,
            u.name as user_name,
            u.email as user_email,
            p.name as product_name
        FROM product_reviews r
        JOIN users u ON u.id = r.user_id
        JOIN products p ON p.id = r.product_id
        WHERE {$whereClause}
        ORDER BY r.created_at DESC
        LIMIT $limit OFFSET $offset
    ");
    
    $stmt->execute($params);
    $reviews = $stmt->fetchAll();
    
    // Đếm tổng số
    $countStmt = $db->prepare("
        SELECT COUNT(*) as total 
        FROM product_reviews r
        WHERE {$whereClause}
    ");
    $countStmt->execute($params);
    $total = $countStmt->fetch()['total'];
    
    // Thống kê tổng quan
    $statsStmt = $db->query('
        SELECT 
            COUNT(*) as total_reviews,
            SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as approved_reviews,
            SUM(CASE WHEN is_approved = 0 THEN 1 ELSE 0 END) as pending_reviews,
            AVG(rating) as average_rating,
            SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
            SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
            SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
            SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
            SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1
        FROM product_reviews
    ');
    $stats = $statsStmt->fetch();
    
    echo json_encode([
        'ok' => true,
        'reviews' => $reviews,
        'total' => (int)$total,
        'stats' => [
            'total_reviews' => (int)$stats['total_reviews'],
            'approved_reviews' => (int)$stats['approved_reviews'],
            'pending_reviews' => (int)$stats['pending_reviews'],
            'average_rating' => round((float)$stats['average_rating'], 1),
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
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>