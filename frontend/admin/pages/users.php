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
    <title>Quản lý người dùng - Fast Food Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'Inter', -apple-system, sans-serif;
        background: #e8f4f8;
        color: #1e293b;
        min-height: 100vh;
        font-size: 14px;
        display: flex;
        flex-direction: column;
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
    .main-content { padding: 20px; flex: 1; }
    .container { max-width: 1400px; margin: 0 auto; }
    
    /* Page Header */
    .page-header { margin-bottom: 20px; }
    .page-header h1 { font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    .page-header p { color: #64748b; font-size: 0.85rem; }
    
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
    
    /* Badge count */
    .badge-count {
        background: #f97316;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
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
    
    /* Role Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .badge-user { background: #dbeafe; color: #1e40af; }
    .badge-admin { background: #d1fae5; color: #065f46; }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }
    
    /* Footer */
    .admin-footer { text-align: center; padding: 20px; color: #64748b; font-size: 0.8rem; margin-top: auto; background: rgba(255,255,255,0.5); }
    
    /* Utilities */
    .loading-text { text-align: center; padding: 24px; color: #94a3b8; }
    .text-muted { color: #64748b; font-size: 0.8rem; }
    
    /* Notification */
    .notification { position: fixed; top: 20px; right: 20px; padding: 12px 20px; border-radius: 8px; color: white; font-weight: 500; z-index: 9999; }
    .notification.success { background: #22c55e; }
    .notification.error { background: #ef4444; }
    
    /* Responsive */
    @media (max-width: 768px) { .admin-nav { display: none; } }
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
                <a href="orders.php">📦 Đơn hàng</a>
                <a href="reviews.php">⭐ Đánh giá</a>
                <a href="users.php" class="active">👥 Người dùng</a>
                <a href="contacts.php">📬 Liên hệ</a>
                <a href="../../index.php" style="color:#ef4444;">🏠 Về trang chủ</a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>👥 Quản lý người dùng</h1>
                <p>Quản lý và theo dõi thông tin người dùng trong hệ thống</p>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h2>📋 Danh sách người dùng</h2>
                    <span id="users-count" class="badge-count">0 người</span>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Địa chỉ</th>
                                <th>Vai trò</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="users-body">
                            <tr><td colspan="8" class="loading-text">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <footer class="admin-footer">
        <p>© 2025 Fast Food Admin Panel. All rights reserved.</p>
    </footer>

    <script>
    (function(){
        var BASE = '../../../backend';
        var tbody = document.getElementById('users-body');
        
        function formatDateTime(d) {
            if (!d) return '-';
            return new Date(d.replace(' ', 'T')).toLocaleString('vi-VN');
        }
        
        function getRoleBadge(role) {
            if (role === 'admin') {
                return '<span class="badge badge-admin">👑 Quản trị</span>';
            }
            return '<span class="badge badge-user">👤 Người dùng</span>';
        }
        
        function showNotification(msg, type) {
            var n = document.createElement('div');
            n.className = 'notification ' + type;
            n.textContent = msg;
            document.body.appendChild(n);
            setTimeout(function() { n.remove(); }, 3000);
        }
        
        function load() {
            fetch(BASE + '/admin/users_list.php', {cache:'no-store'})
                .then(function(r) { return r.json(); })
                .then(function(d) { render((d && d.users) || []); })
                .catch(function() { 
                    tbody.innerHTML = '<tr><td colspan="8" class="loading-text">Không tải được danh sách</td></tr>'; 
                });
        }
        
        function render(users) {
            document.getElementById('users-count').textContent = users.length + ' người';
            
            if (!users.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="loading-text">Chưa có người dùng nào.</td></tr>';
                return;
            }
            
            tbody.innerHTML = users.map(function(u) {
                return '<tr>' +
                    '<td><strong>#' + u.id + '</strong></td>' +
                    '<td>' + (u.name || 'Chưa có tên') + '</td>' +
                    '<td>' + (u.email || '-') + '</td>' +
                    '<td>' + (u.phone || '-') + '</td>' +
                    '<td>' + (u.address || '-') + '</td>' +
                    '<td>' + getRoleBadge(u.role) + '</td>' +
                    '<td class="text-muted">' + formatDateTime(u.created_at) + '</td>' +
                    '<td>' +
                        '<button class="btn btn-danger" data-action="delete" data-id="' + u.id + '" data-name="' + (u.name || 'Người dùng') + '">🗑️ Xóa</button>' +
                    '</td>' +
                '</tr>';
            }).join('');
        }
        
        tbody.addEventListener('click', function(e) {
            var btn = e.target.closest('button[data-action="delete"]');
            if (!btn) return;
            
            var id = btn.getAttribute('data-id');
            var name = btn.getAttribute('data-name');
            
            if (!confirm('Xóa người dùng "' + name + '" (ID: ' + id + ')?\n\nHành động này không thể hoàn tác!')) return;
            
            btn.disabled = true;
            btn.textContent = '⏳...';
            
            fetch(BASE + '/admin/user_delete.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({user_id: id})
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res && res.ok) {
                    showNotification('✅ Đã xóa người dùng "' + name + '"', 'success');
                    load();
                } else {
                    showNotification('❌ Lỗi: ' + (res.error || 'Có lỗi xảy ra'), 'error');
                    btn.disabled = false;
                    btn.textContent = '🗑️ Xóa';
                }
            })
            .catch(function() {
                showNotification('❌ Lỗi kết nối', 'error');
                btn.disabled = false;
                btn.textContent = '🗑️ Xóa';
            });
        });
        
        load();
        setInterval(load, 30000);
    })();
    </script>
</body>
</html>
