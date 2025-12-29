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
    <title>Quản lý đơn hàng - Fast Food Admin</title>
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
    
    /* Stats Grid - 4 columns */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
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
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.info { border-left-color: #3b82f6; }
    .stat-card.success { border-left-color: #22c55e; }
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
    
    /* Search Input */
    .search-input {
        padding: 8px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.8rem;
        width: 220px;
        background: #f8fafc;
    }
    .search-input:focus { outline: none; border-color: #f97316; background: white; }
    
    /* Table */
    .table-container { overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td {
        padding: 12px 14px;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table th {
        background: #f8fafc;
        font-weight: 600;
        color: #475569;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
    .table td { font-size: 0.85rem; color: #334155; }
    .table tr:hover { background: #fafafa; }
    .text-success { color: #22c55e; font-weight: 600; }
    
    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-confirmed { background: #dbeafe; color: #1e40af; }
    .badge-preparing { background: #fed7aa; color: #c2410c; }
    .badge-delivering { background: #e0e7ff; color: #4338ca; }
    .badge-completed { background: #d1fae5; color: #065f46; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }
    
    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        border-radius: 8px;
    }
    .btn-view { background: #fef3c7; color: #92400e; }
    .btn-view:hover { background: #fde68a; }
    .btn-save { background: #22c55e; color: white; }
    .btn-save:hover { background: #16a34a; }
    .btn-delete { background: #ef4444; color: white; }
    .btn-delete:hover { background: #dc2626; }
    .btn-secondary { background: #f1f5f9; color: #475569; }
    
    /* Select */
    .status-select {
        padding: 6px 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.75rem;
        background: white;
        cursor: pointer;
        min-width: 100px;
    }
    .status-select:focus { outline: none; border-color: #f97316; }
    
    /* Modal */
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }
    .modal-content {
        background: white;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }
    .modal-header h3 { font-size: 1rem; font-weight: 600; }
    .modal-body { padding: 18px; max-height: 60vh; overflow-y: auto; }
    .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        color: #64748b;
    }
    
    /* Footer - always at bottom */
    .admin-footer {
        text-align: center;
        padding: 20px;
        color: #64748b;
        font-size: 0.8rem;
        margin-top: auto;
        background: rgba(255,255,255,0.5);
    }
    
    /* Make body flex to push footer down */
    body {
        display: flex;
        flex-direction: column;
    }
    .main-content {
        flex: 1;
    }
    
    /* Utilities */
    .loading-text { text-align: center; padding: 24px; color: #94a3b8; }
    
    /* Notification */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
    }
    .notification.success { background: #22c55e; }
    .notification.error { background: #ef4444; }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .admin-nav { display: none; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .action-btns { flex-wrap: wrap; }
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
                <a href="dashboard.php">📊 Dashboard</a>
                <a href="products.php">🍔 Món ăn</a>
                <a href="orders.php" class="active">📦 Đơn hàng</a>
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
                <h1>📦 Quản lý đơn hàng</h1>
                <p>Theo dõi, cập nhật trạng thái và xử lý đơn hàng</p>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card warning">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-info">
                        <div class="stat-value" id="pending-count">0</div>
                        <div class="stat-label">Chờ xác nhận</div>
                    </div>
                </div>
                <div class="stat-card info">
                    <div class="stat-icon">👨‍🍳</div>
                    <div class="stat-info">
                        <div class="stat-value" id="preparing-count">0</div>
                        <div class="stat-label">Đang chuẩn bị</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🚚</div>
                    <div class="stat-info">
                        <div class="stat-value" id="delivering-count">0</div>
                        <div class="stat-label">Đang giao</div>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-icon">🎉</div>
                    <div class="stat-info">
                        <div class="stat-value" id="completed-count">0</div>
                        <div class="stat-label">Hoàn thành</div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <div class="card-header">
                    <h2>📋 Danh sách đơn hàng</h2>
                    <input type="text" id="search-order" class="search-input" placeholder="🔍 Tìm mã đơn hoặc khách hàng...">
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Thời gian</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="orders-body">
                            <tr><td colspan="6" class="loading-text">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal chi tiết -->
    <div id="order-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📋 Chi tiết đơn hàng</h3>
                <button id="close-modal" class="btn-close">✕</button>
            </div>
            <div id="modal-body" class="modal-body"></div>
        </div>
    </div>

    <footer class="admin-footer">
        <p>© 2025 Fast Food Admin Panel. All rights reserved.</p>
    </footer>

    <script>
    (function(){
        var BASE = '../../../backend';
        var tbody = document.getElementById('orders-body');
        var allOrders = [];
        
        function formatCurrency(v) { return new Intl.NumberFormat('vi-VN').format(v) + 'đ'; }
        function formatDateTime(d) { 
            if (!d) return '-';
            return new Date(d.replace(' ', 'T')).toLocaleString('vi-VN'); 
        }
        
        function getStatusBadge(status) {
            var map = {
                'pending': '<span class="badge badge-pending">⏳ Chờ xác nhận</span>',
                'confirmed': '<span class="badge badge-confirmed">✅ Đã xác nhận</span>',
                'preparing': '<span class="badge badge-preparing">👨‍🍳 Đang chuẩn bị</span>',
                'delivering': '<span class="badge badge-delivering">🚚 Đang giao</span>',
                'completed': '<span class="badge badge-completed">🎉 Hoàn thành</span>',
                'cancelled': '<span class="badge badge-cancelled">❌ Đã hủy</span>'
            };
            return map[status] || status;
        }
        
        function showNotification(msg, type) {
            var n = document.createElement('div');
            n.className = 'notification ' + type;
            n.textContent = msg;
            document.body.appendChild(n);
            setTimeout(function() { n.remove(); }, 3000);
        }
        
        function updateStats(orders) {
            var stats = { pending: 0, preparing: 0, delivering: 0, completed: 0 };
            orders.forEach(function(o) { if (stats[o.status] !== undefined) stats[o.status]++; });
            document.getElementById('pending-count').textContent = stats.pending;
            document.getElementById('preparing-count').textContent = stats.preparing;
            document.getElementById('delivering-count').textContent = stats.delivering;
            document.getElementById('completed-count').textContent = stats.completed;
        }
        
        function render(orders) {
            updateStats(orders);
            if (!orders.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="loading-text">Không có đơn hàng.</td></tr>';
                return;
            }
            
            tbody.innerHTML = orders.map(function(o) {
                return '<tr>' +
                    '<td><strong>#' + o.id + '</strong></td>' +
                    '<td>' + (o.customer || o.shipping_name || 'Khách hàng') + '</td>' +
                    '<td style="color:#64748b;">' + formatDateTime(o.created_at) + '</td>' +
                    '<td class="text-success">' + formatCurrency(o.total_amount) + '</td>' +
                    '<td>' + getStatusBadge(o.status) + '</td>' +
                    '<td>' +
                        '<div class="action-btns">' +
                            '<button class="btn btn-icon btn-view" data-action="view" data-id="' + o.id + '" title="Xem chi tiết">👁️</button>' +
                            '<select data-id="' + o.id + '" class="status-select">' +
                                '<option value="pending"' + (o.status==='pending'?' selected':'') + '>⏳ Chờ</option>' +
                                '<option value="confirmed"' + (o.status==='confirmed'?' selected':'') + '>✅ Xác nhận</option>' +
                                '<option value="preparing"' + (o.status==='preparing'?' selected':'') + '>👨‍🍳 Chuẩn bị</option>' +
                                '<option value="delivering"' + (o.status==='delivering'?' selected':'') + '>🚚 Giao</option>' +
                                '<option value="completed"' + (o.status==='completed'?' selected':'') + '>🎉 Xong</option>' +
                                '<option value="cancelled"' + (o.status==='cancelled'?' selected':'') + '>❌ Hủy</option>' +
                            '</select>' +
                            '<button class="btn btn-icon btn-save" data-action="save" data-id="' + o.id + '" title="Lưu">💾</button>' +
                            '<button class="btn btn-icon btn-delete" data-action="delete" data-id="' + o.id + '" title="Xóa">🗑️</button>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
            }).join('');
        }
        
        function load() {
            fetch(BASE + '/admin/orders_list.php', {cache:'no-store'})
                .then(function(r) { return r.json(); })
                .then(function(d) { 
                    allOrders = (d && d.orders) || [];
                    filterOrders(document.getElementById('search-order').value);
                })
                .catch(function() { 
                    tbody.innerHTML = '<tr><td colspan="6" class="loading-text">Không tải được dữ liệu</td></tr>'; 
                });
        }
        
        function filterOrders(term) {
            if (!term.trim()) { render(allOrders); return; }
            var filtered = allOrders.filter(function(o) {
                var s = term.toLowerCase();
                return o.id.toString().includes(s) || 
                    (o.customer && o.customer.toLowerCase().includes(s));
            });
            render(filtered);
        }
        
        function updateStatus(id, status) {
            fetch(BASE + '/admin/order_update_status.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({order_id: Number(id), status: status})
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok) {
                    showNotification('✅ Cập nhật thành công!', 'success');
                    load();
                } else {
                    showNotification('❌ ' + (res.error || 'Lỗi'), 'error');
                }
            });
        }
        
        function deleteOrder(id) {
            if (!confirm('Xóa đơn hàng #' + id + '?')) return;
            fetch(BASE + '/admin/order_delete.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({order_id: Number(id)})
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok) {
                    showNotification('✅ Đã xóa đơn hàng!', 'success');
                    load();
                } else {
                    showNotification('❌ ' + (res.error || 'Lỗi'), 'error');
                }
            });
        }
        
        tbody.addEventListener('click', function(e) {
            var btn = e.target.closest('button[data-action]');
            if (!btn) return;
            var id = btn.getAttribute('data-id');
            var action = btn.getAttribute('data-action');
            
            if (action === 'save') {
                var sel = tbody.querySelector('select[data-id="' + id + '"]');
                updateStatus(id, sel.value);
            } else if (action === 'delete') {
                deleteOrder(id);
            } else if (action === 'view') {
                var modal = document.getElementById('order-modal');
                var modalBody = document.getElementById('modal-body');
                modal.style.display = 'flex';
                modalBody.innerHTML = '<div class="loading-text">Đang tải...</div>';
                
                fetch(BASE + '/admin/order_detail.php?id=' + id, {cache:'no-store'})
                    .then(function(r) { return r.json(); })
                    .then(function(d) {
                        if (!d || !d.order) {
                            modalBody.innerHTML = '<div class="loading-text">Không tìm thấy đơn hàng.</div>';
                            return;
                        }
                        var o = d.order;
                        var html = '<div style="margin-bottom:1rem;">' +
                            '<h3 style="margin:0 0 0.5rem;">Đơn hàng #' + o.id + '</h3>' +
                            '<p><strong>Khách hàng:</strong> ' + (o.customer || '-') + '</p>' +
                            '<p><strong>Tổng tiền:</strong> <span class="text-success">' + formatCurrency(o.total_amount) + '</span></p>' +
                            '<p><strong>Trạng thái:</strong> ' + getStatusBadge(o.status) + '</p>' +
                            '</div>';
                        
                        if (d.items && d.items.length) {
                            html += '<h4>📦 Danh sách món:</h4><ul style="margin:0.5rem 0;padding-left:1.5rem;">';
                            d.items.forEach(function(item) {
                                html += '<li>' + item.product_name + ' x' + item.quantity + ' - ' + formatCurrency(item.total_price) + '</li>';
                            });
                            html += '</ul>';
                        }
                        modalBody.innerHTML = html;
                    });
            }
        });
        
        document.getElementById('search-order').addEventListener('input', function(e) {
            filterOrders(e.target.value);
        });
        
        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('order-modal').style.display = 'none';
        });
        
        document.getElementById('order-modal').addEventListener('click', function(e) {
            if (e.target === this) this.style.display = 'none';
        });
        
        load();
        setInterval(load, 30000);
    })();
    </script>
</body>
</html>
