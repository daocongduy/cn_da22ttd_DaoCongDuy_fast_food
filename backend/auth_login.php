<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__.'/config.php';

// Cấu hình session cookie để lưu lâu hơn
ini_set('session.cookie_lifetime', 86400); // 24 giờ
ini_set('session.gc_maxlifetime', 86400);
session_set_cookie_params(86400, '/'); // Cookie tồn tại 24h, path=/
session_start();

$payload = $_POST ?: json_decode(file_get_contents('php://input'), true);
$username = trim($payload['username'] ?? '');
$password = (string)($payload['password'] ?? '');
$role = $payload['role'] ?? 'user';

if (!$username || !$password) { http_response_code(400); echo json_encode(['error'=>'Thiếu thông tin']); exit; }

try {
    $db = getDb();
    // Cho phép đăng nhập bằng email hoặc username (email ưu tiên)
    $stmt = $db->prepare('SELECT id, email, password_hash, role FROM users WHERE email = ? OR email = CONCAT(?, "@local") LIMIT 1');
    $stmt->execute([$username, $username]);
    $u = $stmt->fetch();
    if (!$u || !password_verify($password, $u['password_hash'])) {
        http_response_code(401); echo json_encode(['error'=>'Sai tài khoản hoặc mật khẩu']); exit; }

    // Kiểm tra vai trò phải khớp
    if ($role === 'admin' && $u['role'] !== 'admin') {
        http_response_code(403); echo json_encode(['error'=>'Tài khoản này không có quyền quản trị']); exit;
    }
    if ($role === 'user' && $u['role'] === 'admin') {
        http_response_code(403); echo json_encode(['error'=>'Tài khoản quản trị vui lòng chọn vai trò Quản trị']); exit;
    }

    $_SESSION['user_id'] = (int)$u['id'];
    $_SESSION['email'] = $u['email'];
    $_SESSION['role'] = $u['role'];
    echo json_encode(['ok'=>true, 'user_id'=>(int)$u['id'], 'role'=>$_SESSION['role']]);
} catch (Throwable $e) {
    http_response_code(500); echo json_encode(['error'=>'Server error']);
}
?>

