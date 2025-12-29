<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();

require_once __DIR__ . '/config.php';

$userId = $_SESSION['user_id'] ?? null;
$role   = $_SESSION['role'] ?? 'guest';
$name   = null;
$email  = null;
$phone  = null;
$address = null;

if ($userId) {
    try {
        $db = getDb();
        $stmt = $db->prepare('SELECT name, email, phone, address FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$userId]);
        if ($row = $stmt->fetch()) {
            $name    = $row['name'] ?? null;
            $email   = $row['email'] ?? null;
            $phone   = $row['phone'] ?? null;
            $address = $row['address'] ?? null;
        }
    } catch (Throwable $e) {
        // Nếu lỗi DB, vẫn trả về tối thiểu user_id + role
    }
}

echo json_encode([
    'user_id' => $userId,
    'role'    => $role,
    'name'    => $name,
    'email'   => $email,
    'phone'   => $phone,
    'address' => $address,
]);
?>

