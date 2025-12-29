<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../pages/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Fast Food Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: #e8f4f8;
        color: #1e293b;
        min-height: 100vh;
        font-size: 14px;
    }
    
    /* Header */
    .admin-header {
        background: white;
        padding: 10px 20px;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .admin-header .container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .admin-logo {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #0f172a;
        font-weight: 700;
        font-size: 1rem;
    }
    .admin-logo-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #f97316, #ef4444);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    .admin-nav { display: flex; gap: 4px; }
    .admin-nav a {
        padding: 6px 12px;
        color: #475569;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.8rem;
    }
    .admin-nav a:hover { background: #f1f5f9; color: #f97316; }
    .admin-nav a.active { background: #fff7ed; color: #f97316; }
    
    /* Main */
    .main-content { padding: 20px; }
    .container { max-width: 1400px; margin: 0 auto; }
    
    /* Page Header */
    .page-header { margin-bottom: 20px; }
    .page-header h1 { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    .page-header p { color: #64748b; font-size: 0.85rem; }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 18px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border-left: 4px solid #f97316;
    }
    .stat-card.success { border-left-color: #22c55e; }
    .stat-card.info { border-left-color: #3b82f6; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.purple { border-left-color: #8b5cf6; }
    .stat-icon { font-size: 2rem; }
    .stat-value { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
    .stat-label { font-size: 0.75rem; color: #64748b; margin-top: 2px; }
    
    /* Card */
    .card {
        background: white;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        margin-bottom: 20px;
    }
    .card-header {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-header h2 { font-size: 0.95rem; font-weight: 600; color: #1e293b; }
    
    /* Status Grid */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        padding: 18px;
    }
    .status-item {
        text-align: center;
        padding: 16px 10px;
        border-radius: 12px;
    }
    .status-item.pending { background: linear-gradient(180deg, #dbeafe 0%, #bfdbfe 100%); }
    .status-item.confirmed { background: linear-gradient(180deg, #dcfce7 0%, #bbf7d0 100%); }
    .status-item.preparing { background: linear-gradient(180deg, #fed7aa 0%, #fdba74 100%); }
    .status-item.delivering { background: linear-gradient(180deg, #fef3c7 0%, #fde68a 100%); }
    .status-item.completed { background: linear-gradient(180deg, #d1fae5 0%, #a7f3d0 100%); }
    .status-item.cancelled { background: linear-gradient(180deg, #fee2e2 0%, #fecaca 100%); }
    .status-count { display: block; font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 4px; }
    .status-label { font-size: 0.7rem; color: #475569; }
    
    /* Two Columns */
    .two-columns {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    /* Chart */
    .chart-container { padding: 18px; min-height: 240px; }
    #revenue-chart {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 200px;
        padding: 0 10px;
    }
    
    /* Top Products */
    .top-products { padding: 10px 18px 18px; }
    .product-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 10px;
        margin-bottom: 8px;
        background: #f8fafc;
    }
    .product-item:last-child { margin-bottom: 0; }
    .product-rank { font-size: 1.3rem; min-width: 28px; }
    .product-info { flex: 1; }
    .product-name { font-weight: 600; color: #0f172a; font-size: 0.85rem; }
    .product-orders { font-size: 0.7rem; color: #64748b; }
    .product-revenue { font-weight: 700; color: #22c55e; font-size: 0.85rem; }
    
    /* Table */
    .table-container { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #f1f5f9; }
    .table th { background: #f8fafc; font-weight: 600; color: #475569; font-size: 0.7rem; text-transform: uppercase; }
    .table td { font-size: 0.85rem; color: #334155; }
    .text-success { color: #22c55e; font-weight: 600; }
    
    /* Badges */
    .badge { display: inline-block; padding: 4px 10px; border-radius: 14px; font-size: 0.7rem; font-weight: 600; }
    .badge-pending { background: #dbeafe; color: #1e40af; }
    .badge-confirmed { background: #dcfce7; color: #166534; }
    .badge-preparing { background: #fed7aa; color: #c2410c; }
    .badge-delivering { background: #fef3c7; color: #92400e; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    
    /* Quick Actions */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        padding: 18px;
    }
    .action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px 14px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        color: #334155;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s;
    }
    .action-btn:hover { background: white; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .action-icon { font-size: 2.2rem; }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        padding: 7px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        cursor: pointer;
        border: none;
    }
    .btn-primary { background: #f97316; color: white; }
    .btn-primary:hover { background: #ea580c; }
    
    /* Footer - always at bottom */
    .admin-footer { text-align: center; padding: 20px; color: #64748b; font-size: 0.8rem; margin-top: auto; background: rgba(255,255,255,0.5); }
    
    /* Make body flex to push footer down */
    body { display: flex; flex-direction: column; }
    .main-content { flex: 1; }
    
    /* Utilities */
    .loading-text, .no-data { text-align: center; padding: 24px; color: #94a3b8; }
    
    /* Responsive */
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .status-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 1024px) {
        .two-columns { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .status-grid { grid-template-columns: repeat(2, 1fr); }
        .quick-actions { grid-template-columns: repeat(2, 1fr); }
    }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="admin-header">
        <div class="container">
            <a href="dashboard.php" class="admin-logo">
                <div class="admin-logo-icon">🍔</div>
                <span>Fast Food Admin</span>
            </a>
            <nav class="admin-nav">
                <a href="dashboard.php" class="active">📊 Dashboard</a>
                <a href="products.php">🍔 Món ăn</a>
                <a href="orders.php">📦 Đơn hàng</a>
                <a href="reviews.php">⭐ Đánh giá</a>
                <a href="users.php">👥 Người dùng</a>
                <a href="contacts.php">📬 Liên hệ</a>
                <a href="../../index.php" style="color:#ef4444;">🏠 Về trang chủ</a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>📊 Dashboard</h1>
                <p>Tổng quan hoạt động hệ thống</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🍔</div>
                    <div class="stat-info">
                        <div class="stat-value" id="today-orders">0</div>
                        <div class="stat-label">Đơn hôm nay</div>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-icon">💰</div>
                    <div class="stat-info">
                        <div class="stat-value" id="today-revenue">0đ</div>
                        <div class="stat-label">Doanh thu hôm nay</div>
                    </div>
                </div>
                <div class="stat-card info">
                    <div class="stat-icon">🍕</div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-orders">0</div>
                        <div class="stat-label">Tổng đơn hàng</div>
                    </div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-icon">🍟</div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-products">0</div>
                        <div class="stat-label">Sản phẩm</div>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-users">0</div>
                        <div class="stat-label">Người dùng</div>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="card">
                <div class="card-header">
                    <h2>📋 Trạng thái đơn hàng</h2>
                </div>
                <div class="status-grid">
                    <div class="status-item pending">
                        <span class="status-count" id="pending-count">0</span>
                        <span class="status-label">⏳ Chờ duyệt</span>
                    </div>
                    <div class="status-item confirmed">
                        <span class="status-count" id="confirmed-count">0</span>
                        <span class="status-label">✅ Đã xác nhận</span>
                    </div>
                    <div class="status-item preparing">
                        <span class="status-count" id="preparing-count">0</span>
                        <span class="status-label">👨‍🍳 Đang chuẩn bị</span>
                    </div>
                    <div class="status-item delivering">
                        <span class="status-count" id="delivering-count">0</span>
                        <span class="status-label">🚚 Đang giao</span>
                    </div>
                    <div class="status-item completed">
                        <span class="status-count" id="completed-count">0</span>
                        <span class="status-label">🎉 Hoàn thành</span>
                    </div>
                    <div class="status-item cancelled">
                        <span class="status-count" id="cancelled-count">0</span>
                        <span class="status-label">❌ Đã hủy</span>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="two-columns">
                <!-- Revenue Chart -->
                <div class="card">
                    <div class="card-header">
                        <h2>📈 Doanh thu 7 ngày</h2>
                    </div>
                    <div class="chart-container">
                        <div id="revenue-chart"></div>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="card">
                    <div class="card-header">
                        <h2>🏆 Top sản phẩm bán chạy</h2>
                    </div>
                    <div class="top-products" id="top-products">
                        <div class="loading-text">Đang tải...</div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h2>📦 Đơn hàng gần đây</h2>
                    <a href="orders.php" class="btn btn-primary">Xem tất cả</a>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody id="recent-orders">
                            <tr><td colspan="5" class="loading-text">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h2>⚡ Thao tác nhanh</h2>
                </div>
                <div class="quick-actions">
                    <a href="products.php" class="action-btn">
                        <span class="action-icon">🍔</span>
                        <span>Quản lý món ăn</span>
                    </a>
                    <a href="orders.php" class="action-btn">
                        <span class="action-icon">📦</span>
                        <span>Quản lý đơn hàng</span>
                    </a>
                    <a href="users.php" class="action-btn">
                        <span class="action-icon">👥</span>
                        <span>Quản lý người dùng</span>
                    </a>
                    <a href="reviews.php" class="action-btn">
                        <span class="action-icon">⭐</span>
                        <span>Quản lý đánh giá</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <p>© 2025 Fast Food Admin Panel. All rights reserved.</p>
    </footer>

    <script>
    (function() {
        // Base path - điều chỉnh theo cấu trúc thư mục của bạn
        var BASE = '../../../backend';
        
        function formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN').format(value || 0) + 'đ';
        }

        function formatDateTime(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr.replace(' ', 'T')).toLocaleString('vi-VN');
        }

        function getStatusBadge(status) {
            var map = {
                'pending': '<span class="badge badge-pending">Chờ duyệt</span>',
                'confirmed': '<span class="badge badge-confirmed">Đã xác nhận</span>',
                'preparing': '<span class="badge badge-preparing">Đang chuẩn bị</span>',
                'delivering': '<span class="badge badge-delivering">Đang giao</span>',
                'completed': '<span class="badge badge-completed">Hoàn thành</span>',
                'cancelled': '<span class="badge badge-cancelled">Đã hủy</span>'
            };
            return map[status] || status;
        }

        function loadStats() {
            fetch(BASE + '/dashboard_stats.php', { cache: 'no-store' })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (!data.ok) return;
                    
                    document.getElementById('today-orders').textContent = data.today?.total_orders || 0;
                    document.getElementById('today-revenue').textContent = formatCurrency(data.today?.revenue || 0);
                    document.getElementById('total-orders').textContent = data.total_orders || 0;
                    document.getElementById('total-products').textContent = data.total_products || 0;
                    document.getElementById('total-users').textContent = data.total_users || 0;

                    var sc = data.status_counts || {};
                    document.getElementById('pending-count').textContent = sc.pending || 0;
                    document.getElementById('confirmed-count').textContent = sc.confirmed || 0;
                    document.getElementById('preparing-count').textContent = sc.preparing || 0;
                    document.getElementById('delivering-count').textContent = sc.delivering || 0;
                    document.getElementById('completed-count').textContent = sc.completed || 0;
                    document.getElementById('cancelled-count').textContent = sc.cancelled || 0;

                    renderRevenueChart(data.revenue_7days || []);
                })
                .catch(function(e) { console.error('Stats error:', e); });
        }

        function renderRevenueChart(data) {
            var container = document.getElementById('revenue-chart');
            
            if (!data || !data.length) {
                container.innerHTML = '<div class="no-data">Chưa có dữ liệu</div>';
                return;
            }

            var dayOrder = [1, 2, 3, 4, 5, 6, 0];
            var days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
            
            var revenueByDay = {};
            data.forEach(function(d) {
                var date = new Date(d.date + 'T00:00:00');
                var dayOfWeek = date.getDay();
                revenueByDay[dayOfWeek] = {
                    revenue: d.revenue || 0,
                    dayNum: date.getDate() + '/' + (date.getMonth() + 1)
                };
            });

            var maxRevenue = Math.max.apply(null, data.map(function(d) { return d.revenue || 0; }));
            if (maxRevenue === 0) maxRevenue = 1;

            var html = dayOrder.map(function(dayOfWeek) {
                var dayData = revenueByDay[dayOfWeek] || { revenue: 0, dayNum: '-' };
                var revenue = dayData.revenue;
                var heightPercent = revenue > 0 ? Math.max((revenue / maxRevenue) * 100, 8) : 5;
                var heightPx = Math.round((heightPercent / 100) * 140);
                var dayName = days[dayOfWeek];
                var dayNum = dayData.dayNum;
                var barColor = revenue > 0 ? 'linear-gradient(180deg, #f97316 0%, #ea580c 100%)' : '#e2e8f0';
                var revenueShort = revenue >= 1000000 ? (revenue / 1000000).toFixed(0) + 'tr' : (revenue >= 1000 ? Math.round(revenue / 1000) + 'k' : '');
                var labelColor = revenue > 0 ? '#f97316' : '#cbd5e1';
                
                return '<div style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%;">' +
                    '<div style="font-size:0.65rem;color:' + labelColor + ';font-weight:700;margin-bottom:4px;min-height:16px;">' + revenueShort + '</div>' +
                    '<div style="width:34px;height:' + heightPx + 'px;background:' + barColor + ';border-radius:5px 5px 0 0;"></div>' +
                    '<div style="font-size:0.7rem;color:#64748b;font-weight:600;margin-top:8px;">' + dayName + '</div>' +
                    '<div style="font-size:0.6rem;color:#94a3b8;">' + dayNum + '</div>' +
                    '</div>';
            }).join('');

            container.innerHTML = html;
        }

        function loadTopProducts() {
            fetch(BASE + '/admin/top_products.php', { cache: 'no-store' })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var container = document.getElementById('top-products');
                    var products = data.products || [];

                    if (!products.length) {
                        container.innerHTML = '<div class="no-data">Chưa có dữ liệu</div>';
                        return;
                    }

                    var icons = ['🍕', '🍔', '🍗', '🍟', '🥤'];
                    var html = products.slice(0, 5).map(function(p, i) {
                        return '<div class="product-item">' +
                            '<span class="product-rank">' + icons[i % icons.length] + '</span>' +
                            '<div class="product-info">' +
                            '<div class="product-name">' + (p.name || 'N/A') + '</div>' +
                            '<div class="product-orders">' + (p.orders || 0) + ' đơn</div>' +
                            '</div>' +
                            '<div class="product-revenue">' + formatCurrency(p.revenue) + '</div>' +
                            '</div>';
                    }).join('');

                    container.innerHTML = html;
                })
                .catch(function() {
                    document.getElementById('top-products').innerHTML = '<div class="no-data">Lỗi tải dữ liệu</div>';
                });
        }

        function loadRecentOrders() {
            fetch(BASE + '/admin/orders_list.php', { cache: 'no-store' })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var tbody = document.getElementById('recent-orders');
                    var orders = (data.orders || []).slice(0, 5);

                    if (!orders.length) {
                        tbody.innerHTML = '<tr><td colspan="5" class="no-data">Chưa có đơn hàng</td></tr>';
                        return;
                    }

                    var html = orders.map(function(o) {
                        return '<tr>' +
                            '<td><strong>#' + o.id + '</strong></td>' +
                            '<td>' + (o.customer || o.shipping_name || 'N/A') + '</td>' +
                            '<td class="text-success">' + formatCurrency(o.total_amount) + '</td>' +
                            '<td>' + getStatusBadge(o.status) + '</td>' +
                            '<td>' + formatDateTime(o.created_at) + '</td>' +
                            '</tr>';
                    }).join('');

                    tbody.innerHTML = html;
                })
                .catch(function() {
                    document.getElementById('recent-orders').innerHTML = '<tr><td colspan="5" class="no-data">Lỗi tải dữ liệu</td></tr>';
                });
        }

        loadStats();
        loadTopProducts();
        loadRecentOrders();

        setInterval(function() {
            loadStats();
            loadRecentOrders();
        }, 30000);
    })();
    </script>
</body>
</html>
