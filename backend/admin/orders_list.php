<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config.php';

try {
    $db = getDb();
    
    // Get orders with user info
    $stmt = $db->query('
        SELECT 
            o.id,
            o.status,
            o.total_amount,
            o.created_at,
            o.updated_at,
            COALESCE(u.name, "Khách hàng") AS customer,
            u.email as customer_email
        FROM orders o 
        LEFT JOIN users u ON u.id = o.user_id 
        ORDER BY o.created_at DESC
    ');
    
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format data
    foreach ($orders as &$order) {
        $order['id'] = (int)$order['id'];
        $order['total_amount'] = (int)$order['total_amount'];
        $order['created_at'] = $order['created_at'] ?: date('Y-m-d H:i:s');
        $order['updated_at'] = $order['updated_at'] ?: $order['created_at'];
    }
    
    echo json_encode([
        'ok' => true,
        'orders' => $orders,
        'total' => count($orders)
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>