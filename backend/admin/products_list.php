<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : true; // demo
if (!$isAdmin) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }

try {
    $db = getDb();
    
    // Check if description column exists, if not add it
    $stmt = $db->query("SHOW COLUMNS FROM products LIKE 'description'");
    $hasDescription = $stmt->fetch();
    
    if (!$hasDescription) {
        $db->exec("ALTER TABLE products ADD COLUMN description TEXT AFTER price");
    }
    
    $stmt = $db->query('SELECT id, name, price, description, image_url, is_active FROM products ORDER BY id DESC');
    $products = $stmt->fetchAll();
    
    echo json_encode(['products' => $products], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Lỗi server: ' . $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine()
    ]);
}
?>

