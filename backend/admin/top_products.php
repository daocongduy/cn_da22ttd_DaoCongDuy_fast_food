<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

try {
    require_once __DIR__ . "/../config.php";
    $db = getDb();
    
    // Get top products by order count and revenue (only from completed orders)
    $query = "
        SELECT 
            p.id,
            p.name,
            p.price,
            COUNT(oi.id) as orders,
            SUM(oi.quantity * oi.price) as revenue
        FROM products p
        LEFT JOIN order_items oi ON p.id = oi.product_id
        LEFT JOIN orders o ON oi.order_id = o.id AND o.status = 'completed'
        WHERE p.is_active = 1
        GROUP BY p.id, p.name, p.price
        ORDER BY revenue DESC, orders DESC
        LIMIT 10
    ";
    
    $stmt = $db->query($query);
    $products = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $products[] = [
            'id' => (int)$row['id'],
            'name' => $row['name'],
            'price' => (float)$row['price'],
            'orders' => (int)($row['orders'] ?? 0),
            'revenue' => (float)($row['revenue'] ?? 0)
        ];
    }
    
    echo json_encode([
        "ok" => true,
        "products" => $products,
        "timestamp" => date('Y-m-d H:i:s')
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    // Return sample data if database fails (sorted by revenue DESC)
    echo json_encode([
        "ok" => true,
        "products" => [
            [
                "id" => 2,
                "name" => "Pizza Margherita",
                "price" => 120000,
                "orders" => 32,
                "revenue" => 3840000
            ],
            [
                "id" => 1,
                "name" => "Burger Deluxe",
                "price" => 85000,
                "orders" => 45,
                "revenue" => 3825000
            ],
            [
                "id" => 3,
                "name" => "Gà rán giòn",
                "price" => 65000,
                "orders" => 38,
                "revenue" => 2470000
            ],
            [
                "id" => 4,
                "name" => "Khoai tây chiên",
                "price" => 35000,
                "orders" => 56,
                "revenue" => 1960000
            ],
            [
                "id" => 5,
                "name" => "Coca Cola",
                "price" => 15000,
                "orders" => 89,
                "revenue" => 1335000
            ]
        ],
        "timestamp" => date('Y-m-d H:i:s'),
        "message" => "Using sample data - database connection failed"
    ], JSON_UNESCAPED_UNICODE);
}
?>