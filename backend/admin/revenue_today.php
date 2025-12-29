<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../config.php';
session_start();

// Kiểm tra quyền admin
$isAdmin = isset($_SESSION['role']) ? $_SESSION['role'] === 'admin' : false;
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

try {
    $db = getDb();
    
    // Lấy ngày hôm nay
    $today = date('Y-m-d');
    
    // Tính doanh thu hôm nay (chỉ đơn hàng đã hoàn thành)
    $stmt = $db->prepare('SELECT 
        COUNT(*) as total_orders,
        SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as revenue,
        SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders,
        SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed_orders,
        SUM(CASE WHEN status = "preparing" THEN 1 ELSE 0 END) as preparing_orders,
        SUM(CASE WHEN status = "delivering" THEN 1 ELSE 0 END) as delivering_orders,
        SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders
        FROM orders 
        WHERE DATE(created_at) = ?');
    
    // Tính tổng số đơn hàng theo trạng thái (tất cả đơn hàng, không chỉ hôm nay)
    $stmtAll = $db->query('SELECT 
        COUNT(*) as total_orders_all,
        SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_orders_all,
        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders_all,
        SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed_orders_all,
        SUM(CASE WHEN status = "preparing" THEN 1 ELSE 0 END) as preparing_orders_all,
        SUM(CASE WHEN status = "delivering" THEN 1 ELSE 0 END) as delivering_orders_all,
        SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders_all
        FROM orders');
    
    $stmt->execute([$today]);
    $todayStats = $stmt->fetch();
    
    $allStats = $stmtAll->fetch();
    
    // Tính doanh thu hôm qua để so sánh
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $stmt->execute([$yesterday]);
    $yesterdayStats = $stmt->fetch();
    
    // Tính tổng doanh thu tháng này
    $thisMonth = date('Y-m');
    $stmt = $db->prepare('SELECT 
        SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as monthly_revenue,
        COUNT(*) as monthly_orders
        FROM orders 
        WHERE DATE_FORMAT(created_at, "%Y-%m") = ?');
    
    $stmt->execute([$thisMonth]);
    $monthStats = $stmt->fetch();
    
    // Tính doanh thu theo giờ hôm nay
    $stmt = $db->prepare('SELECT 
        HOUR(created_at) as hour,
        COUNT(*) as orders_count,
        SUM(CASE WHEN status = "completed" THEN total_amount ELSE 0 END) as revenue
        FROM orders 
        WHERE DATE(created_at) = ? 
        GROUP BY HOUR(created_at) 
        ORDER BY hour');
    
    $stmt->execute([$today]);
    $hourlyStats = $stmt->fetchAll();
    
    // Tính % thay đổi so với hôm qua
    $revenueChange = 0;
    if ($yesterdayStats['revenue'] > 0) {
        $revenueChange = (($todayStats['revenue'] - $yesterdayStats['revenue']) / $yesterdayStats['revenue']) * 100;
    } elseif ($todayStats['revenue'] > 0) {
        $revenueChange = 100; // Tăng 100% nếu hôm qua = 0, hôm nay > 0
    }
    
    $orderChange = 0;
    if ($yesterdayStats['total_orders'] > 0) {
        $orderChange = (($todayStats['total_orders'] - $yesterdayStats['total_orders']) / $yesterdayStats['total_orders']) * 100;
    } elseif ($todayStats['total_orders'] > 0) {
        $orderChange = 100;
    }
    
    echo json_encode([
        'ok' => true,
        'today' => [
            'revenue' => (int)($todayStats['revenue'] ?: 0),
            'total_orders' => (int)($todayStats['total_orders'] ?: 0),
            'completed_orders' => (int)($allStats['completed_orders_all'] ?: 0),
            'pending_orders' => (int)($allStats['pending_orders_all'] ?: 0),
            'confirmed_orders' => (int)($allStats['confirmed_orders_all'] ?: 0),
            'preparing_orders' => (int)($allStats['preparing_orders_all'] ?: 0),
            'delivering_orders' => (int)($allStats['delivering_orders_all'] ?: 0),
            'cancelled_orders' => (int)($allStats['cancelled_orders_all'] ?: 0)
        ],
        'yesterday' => [
            'revenue' => (int)($yesterdayStats['revenue'] ?: 0),
            'total_orders' => (int)($yesterdayStats['total_orders'] ?: 0)
        ],
        'month' => [
            'revenue' => (int)($monthStats['monthly_revenue'] ?: 0),
            'total_orders' => (int)($monthStats['monthly_orders'] ?: 0)
        ],
        'changes' => [
            'revenue_change' => round($revenueChange, 1),
            'order_change' => round($orderChange, 1)
        ],
        'hourly' => $hourlyStats
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
