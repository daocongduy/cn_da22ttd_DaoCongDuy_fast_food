<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';
session_start();

// Chặn admin không thể đặt hàng
$role = $_SESSION['role'] ?? 'user';
if ($role === 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Admin không thể đặt hàng']);
    exit;
}

$userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 1;

$input = file_get_contents('php://input');
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Không nhận được dữ liệu đơn hàng']);
    exit;
}

$payload = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Dữ liệu JSON không hợp lệ']);
    exit;
}

$items = isset($payload['items']) && is_array($payload['items']) ? $payload['items'] : [];
$shipping = isset($payload['shipping']) && is_array($payload['shipping']) ? $payload['shipping'] : [];

if (!$items) {
    http_response_code(400);
    echo json_encode(['error' => 'Danh sách sản phẩm trống']);
    exit;
}

// Validate shipping info
$requiredShipping = ['name', 'phone', 'address'];
foreach ($requiredShipping as $field) {
    if (empty($shipping[$field]) || !is_string($shipping[$field]) || strlen(trim($shipping[$field])) < 2) {
        http_response_code(400);
        echo json_encode(['error' => 'Thông tin giao hàng không đầy đủ hoặc không hợp lệ']);
        exit;
    }
}

try {
    $db = getDb();
    $db->beginTransaction();

    // Cho phép đặt hàng nếu sản phẩm tồn tại, không chặn theo is_active để tránh lỗi khi sản phẩm tạm ngưng
    $getP = $db->prepare('SELECT id, price FROM products WHERE id = ?');
    $total = 0;
    $validated = [];
    $invalidProductIds = [];
    foreach ($items as $it) {
        $pid = (int)($it['product_id'] ?? 0);
        $qty = max(1, (int)($it['quantity'] ?? 1));
        $getP->execute([$pid]);
        $p = $getP->fetch();
        if (!$p) { $invalidProductIds[] = $pid; continue; }
        $unit = (int)$p['price'];
        $total += $qty * $unit;
        $validated[] = ['product_id' => (int)$p['id'], 'quantity' => $qty, 'unit_price' => $unit];
    }

    if (empty($validated)) {
        // Không có món hợp lệ nào
        $db->rollBack();
        http_response_code(400);
        echo json_encode([
            'error' => 'Sản phẩm không tồn tại hoặc đã bị xóa',
            'invalid_ids' => $invalidProductIds
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $insOrder = $db->prepare('INSERT INTO orders(user_id,status,total_amount,shipping_name,shipping_phone,shipping_address) VALUES(?,"pending",?,?,?,?)');
    $insOrder->execute([$userId, $total, $shipping['name'] ?? null, $shipping['phone'] ?? null, $shipping['address'] ?? null]);
    $orderId = (int)$db->lastInsertId();

    $insItem = $db->prepare('INSERT INTO order_items(order_id,product_id,quantity,unit_price,total_price) VALUES(?,?,?,?,?)');
    foreach ($validated as $vi) {
        $insItem->execute([$orderId, $vi['product_id'], $vi['quantity'], $vi['unit_price'], $vi['quantity'] * $vi['unit_price']]);
    }

    $db->prepare('INSERT INTO order_status_history(order_id,status,note) VALUES(?,"pending","Tạo đơn")')->execute([$orderId]);
    $db->commit();

    echo json_encode(['ok' => true, 'order_id' => $orderId]);
} catch (Throwable $e) {
    if ($db && $db->inTransaction()) { $db->rollBack(); }
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}
?>

