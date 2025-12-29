<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : true; // demo
if (!$isAdmin) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }

try {
    $db = getDb();
    $name = trim($_POST['name'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    
    if (!$name || $price <= 0) { 
        http_response_code(400); 
        echo json_encode(['error'=>'Dữ liệu không hợp lệ: tên món và giá phải được nhập']); 
        exit; 
    }

    $imageFileName = null;
    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            http_response_code(400);
            echo json_encode(['error'=>'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WebP)']);
            exit;
        }
        
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $safe = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($_FILES['image']['name'], PATHINFO_FILENAME));
        if ($ext === '') { $ext = 'jpg'; }
        $imageFileName = $safe . '_' . time() . '.' . strtolower($ext);
        
        // Create directory if not exists
        $uploadDir = __DIR__ . '/../../frontend/assets/images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $dest = $uploadDir . $imageFileName;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            http_response_code(500); 
            echo json_encode(['error'=>'Upload ảnh thất bại: không thể lưu file']); 
            exit; 
        }
    }

    $stmt = $db->prepare('INSERT INTO products(name, price, description, image_url, is_active) VALUES(?,?,?,?,1)');
    $stmt->execute([$name, $price, $description, $imageFileName]);
    
    $newId = (int)$db->lastInsertId();
    echo json_encode([
        'ok'=>true, 
        'id'=>$newId, 
        'image_url'=>$imageFileName,
        'message'=>'Đã thêm món ăn thành công'
    ]);
    
} catch (Throwable $e) {
    http_response_code(500); 
    echo json_encode([
        'error'=>'Lỗi server: ' . $e->getMessage(),
        'file'=>$e->getFile(),
        'line'=>$e->getLine()
    ]);
}
?>

