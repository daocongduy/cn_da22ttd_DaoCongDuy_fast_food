<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';

try {
    $db = getDb();
    $stmt = $db->query('
        SELECT 
            id, 
            name, 
            price, 
            description, 
            image_url,
            average_rating,
            total_reviews
        FROM products 
        WHERE is_active = 1 
        ORDER BY id DESC
    ');
    $rows = $stmt->fetchAll();
    echo json_encode(['products' => $rows], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
?>

