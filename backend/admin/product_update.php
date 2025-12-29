<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : true; // demo
if (!$isAdmin) { http_response_code(403); echo json_encode(['error'=>'Forbidden']); exit; }

try {
    $db = getDb();
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $price = (int)($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $isActive = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;
    if (!$id || !$name || $price <= 0) { http_response_code(400); echo json_encode(['error'=>'Dữ liệu không hợp lệ']); exit; }

    $imageSql = '';
    $params = [$name, $price, $description, $isActive, $id];
    if (!empty($_FILES['image']['name'])) {
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
            http_response_code(500); echo json_encode(['error'=>'Upload ảnh thất bại']); exit; }
        $imageSql = ', image_url = ?';
        array_splice($params, 4, 0, [$imageFileName]); // chèn trước id
    }

    $sql = 'UPDATE products SET name=?, price=?, description=?, is_active=?' . $imageSql . ' WHERE id=?';
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['ok'=>true]);
} catch (Throwable $e) {
    http_response_code(500); echo json_encode(['error'=>'Server error']);
}
?>

