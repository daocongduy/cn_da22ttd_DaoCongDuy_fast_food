<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__.'/config.php';

// Cấu hình session cookie để lưu lâu hơn
ini_set('session.cookie_lifetime', 86400);
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400, '/');
session_start();

$payload = $_POST ?: json_decode(file_get_contents('php://input'), true);
$name = trim($payload['fullname'] ?? '');
$email = trim($payload['email'] ?? '');
$username = trim($payload['username'] ?? '');
$password = (string)($payload['password'] ?? '');
$confirm = (string)($payload['confirm_password'] ?? '');

if (!$name || (!$email && !$username) || strlen($password) < 4 || $password !== $confirm) {
    http_response_code(400);
    echo json_encode(['error'=>'Dữ liệu đăng ký không hợp lệ']);
    exit;
}

try {
    $db = getDb();
    // chọn email làm duy nhất, nếu không có email thì dùng username@local
    if (!$email) { $email = $username.'@local'; }
    $exists = $db->prepare('SELECT id FROM users WHERE email = ?');
    $exists->execute([$email]);
    if ($exists->fetch()) { http_response_code(409); echo json_encode(['error'=>'Email đã tồn tại']); exit; }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $db->prepare('INSERT INTO users(name, email, password_hash, role) VALUES(?,?,?,"user")');
    $ins->execute([$name, $email, $hash]);
    $userId = (int)$db->lastInsertId();
    $_SESSION['user_id'] = $userId;
    $_SESSION['role'] = 'user';
    echo json_encode(['ok'=>true, 'user_id'=>$userId]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>'Server error']);
}
?>

