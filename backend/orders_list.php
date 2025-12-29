<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';
session_start();

// Demo: nếu chưa có đăng nhập thì tạm set user_id = 1
$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

try {
    $db = getDb();
    $stmt = $db->prepare('SELECT id, status, total_amount, created_at FROM orders WHERE user_id = ? ORDER BY id DESC');
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();

    $orders = array_map(function ($r) {
        return [
            'id' => (int)$r['id'],
            'date' => $r['created_at'],
            'total' => (int)$r['total_amount'],
            'status' => mapStatus($r['status']),
        ];
    }, $rows);

    echo json_encode(['orders' => $orders], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}

function mapStatus($s)
{
    switch ($s) {
        case 'pending': return 'Chờ xác nhận';
        case 'confirmed': return 'Đã xác nhận';
        case 'preparing': return 'Đang chuẩn bị';
        case 'delivering': return 'Đang giao';
        case 'completed': return 'Hoàn thành';
        case 'cancelled': return 'Đã hủy';
        default: return $s;
    }
}
?>

