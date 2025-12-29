<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit;
}

require_once __DIR__ . '/config.php';

$input = json_decode(file_get_contents('php://input'), true);

$name = trim($input['name'] ?? '');
$phone = trim($input['phone'] ?? '');
$address = trim($input['address'] ?? '');

if (empty($name)) {
    echo json_encode(['error' => 'Tên không được để trống']);
    exit;
}

try {
    $pdo = getDb();
    $stmt = $pdo->prepare('UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?');
    $stmt->execute([$name, $phone, $address, $_SESSION['user_id']]);
    
    // Update session
    $_SESSION['name'] = $name;
    
    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Lỗi cập nhật: ' . $e->getMessage()]);
}
