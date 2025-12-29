<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit;
}

require_once __DIR__ . '/config.php';

$input = json_decode(file_get_contents('php://input'), true);

$currentPassword = $input['current_password'] ?? '';
$newPassword = $input['new_password'] ?? '';

if (empty($currentPassword) || empty($newPassword)) {
    echo json_encode(['error' => 'Vui lòng nhập đầy đủ thông tin']);
    exit;
}

if (strlen($newPassword) < 6) {
    echo json_encode(['error' => 'Mật khẩu mới phải có ít nhất 6 ký tự']);
    exit;
}

try {
    $pdo = getDb();
    
    // Get current password hash
    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        echo json_encode(['error' => 'Không tìm thấy người dùng']);
        exit;
    }
    
    // Verify current password
    if (!password_verify($currentPassword, $user['password_hash'])) {
        echo json_encode(['error' => 'Mật khẩu hiện tại không đúng']);
        exit;
    }
    
    // Update new password
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $stmt->execute([$newHash, $_SESSION['user_id']]);
    
    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Lỗi: ' . $e->getMessage()]);
}
