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
    <title>Tin nhắn liên hệ - Fast Food Admin</title>
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
    
    /* Stats Grid - 2 columns */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
        margin-bottom: 20px;
    }
    .stat-card {
        background: white;
        border-radius: 14px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        border-left: 4px solid #f97316;
    }
    .stat-card.info { border-left-color: #3b82f6; }
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
    .btn-primary { background: #f97316; color: white; }
    .btn-primary:hover { background: #ea580c; }
    .btn-secondary { background: #f1f5f9; color: #475569; }
    .btn-secondary:hover { background: #e2e8f0; }
    
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
    
    .email-link { color: #3b82f6; text-decoration: none; }
    .email-link:hover { text-decoration: underline; }
    
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
        max-width: 650px;
        width: 95%;
        max-height: 90vh;
        border-radius: 14px;
        overflow: hidden;
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
    .modal-body { padding: 18px; max-height: 70vh; overflow-y: auto; }
    .btn-close { background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b; }
    
    /* Info box */
    .info-box {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        padding: 14px;
        margin-bottom: 16px;
    }
    .info-box-title {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #0369a1;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        font-size: 0.85rem;
    }
    
    /* Message box */
    .message-box {
        margin-bottom: 16px;
    }
    .message-title {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }
    .message-content {
        background: #f9fafb;
        padding: 14px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    
    /* Reply box */
    .reply-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 14px;
    }
    .reply-title {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #166534;
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }
    .reply-textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9rem;
        resize: vertical;
        font-family: inherit;
        min-height: 100px;
    }
    .reply-textarea:focus { outline: none; border-color: #22c55e; }
    .reply-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }
    .reply-actions .btn { flex: 1; justify-content: center; }
    
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
    @media (max-width: 768px) {
        .admin-nav { display: none; }
        .stats-grid { grid-template-columns: 1fr; }
        .info-grid { grid-template-columns: 1fr; }
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
                <a href="orders.php">📦 Đơn hàng</a>
                <a href="reviews.php">⭐ Đánh giá</a>
                <a href="users.php">👥 Người dùng</a>
                <a href="contacts.php" class="active">📬 Liên hệ</a>
                <a href="../../index.php" style="color:#ef4444;">🏠 Về trang chủ</a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>📬 Tin nhắn liên hệ</h1>
                <p>Xem và quản lý tin nhắn từ khách hàng</p>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📩</div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-contacts">0</div>
                        <div class="stat-label">Tổng tin nhắn</div>
                    </div>
                </div>
                <div class="stat-card info">
                    <div class="stat-icon">📅</div>
                    <div class="stat-info">
                        <div class="stat-value" id="today-contacts">0</div>
                        <div class="stat-label">Hôm nay</div>
                    </div>
                </div>
            </div>

            <!-- Contacts Table -->
            <div class="card">
                <div class="card-header">
                    <h2>📋 Danh sách tin nhắn</h2>
                    <button id="refresh-btn" class="btn btn-secondary">🔄 Làm mới</button>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Nội dung</th>
                                <th>Thời gian</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="contacts-body">
                            <tr><td colspan="7" class="loading-text">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="contact-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📬 Chi tiết & Trả lời tin nhắn</h3>
                <button class="btn-close close-modal">✕</button>
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
        var tbody = document.getElementById('contacts-body');
        var allContacts = [];
        var currentContact = null;
        
        function formatDateTime(d) {
            if (!d) return '-';
            return new Date(d.replace(' ', 'T')).toLocaleString('vi-VN');
        }
        
        function isToday(dateStr) {
            var today = new Date().toISOString().split('T')[0];
            return dateStr && dateStr.startsWith(today);
        }
        
        function showNotification(msg, type) {
            var n = document.createElement('div');
            n.className = 'notification ' + type;
            n.textContent = msg;
            document.body.appendChild(n);
            setTimeout(function() { n.remove(); }, 3000);
        }
        
        function updateStats(contacts) {
            document.getElementById('total-contacts').textContent = contacts.length;
            var todayCount = contacts.filter(function(c) { return isToday(c.created_at); }).length;
            document.getElementById('today-contacts').textContent = todayCount;
        }
        
        function render(contacts) {
            allContacts = contacts;
            updateStats(contacts);
            
            if (!contacts.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="loading-text">Chưa có tin nhắn liên hệ nào.</td></tr>';
                return;
            }
            
            tbody.innerHTML = contacts.map(function(c) {
                var shortMsg = c.message.length > 30 ? c.message.substring(0, 30) + '...' : c.message;
                return '<tr>' +
                    '<td><strong>#' + c.id + '</strong></td>' +
                    '<td>' + (c.name || '-') + '</td>' +
                    '<td><a href="mailto:' + c.email + '" class="email-link">' + (c.email || '-') + '</a></td>' +
                    '<td>' + (c.phone || '-') + '</td>' +
                    '<td>' + shortMsg + '</td>' +
                    '<td class="text-muted">' + formatDateTime(c.created_at) + '</td>' +
                    '<td>' +
                        '<button class="btn btn-primary" data-action="view" data-id="' + c.id + '">💬 Xem & Trả lời</button>' +
                    '</td>' +
                '</tr>';
            }).join('');
        }
        
        function load() {
            fetch(BASE + '/admin/contacts_list.php', {cache:'no-store'})
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    if (d.ok) render(d.contacts || []);
                    else tbody.innerHTML = '<tr><td colspan="7" class="loading-text">Lỗi tải dữ liệu</td></tr>';
                })
                .catch(function() {
                    tbody.innerHTML = '<tr><td colspan="7" class="loading-text">Không tải được dữ liệu</td></tr>';
                });
        }
        
        function showModal(id) {
            var c = allContacts.find(function(x) { return x.id === id; });
            if (!c) return;
            currentContact = c;
            
            document.getElementById('modal-body').innerHTML = 
                '<div class="info-box">' +
                    '<div class="info-box-title">👤 Thông tin người gửi</div>' +
                    '<div class="info-grid">' +
                        '<p><strong>Họ tên:</strong> ' + (c.name || '-') + '</p>' +
                        '<p><strong>Email:</strong> ' + (c.email || '-') + '</p>' +
                        '<p><strong>SĐT:</strong> ' + (c.phone || 'Không có') + '</p>' +
                        '<p><strong>Thời gian:</strong> ' + formatDateTime(c.created_at) + '</p>' +
                    '</div>' +
                '</div>' +
                
                '<div class="message-box">' +
                    '<div class="message-title">💬 Nội dung tin nhắn:</div>' +
                    '<div class="message-content">' + c.message + '</div>' +
                '</div>' +
                
                '<div class="reply-box">' +
                    '<div class="reply-title">✍️ Trả lời khách hàng</div>' +
                    '<textarea id="reply-message" class="reply-textarea" placeholder="Nhập nội dung phản hồi cho khách hàng..."></textarea>' +
                    '<div class="reply-actions">' +
                        '<button id="send-reply-btn" class="btn btn-primary">📤 Gửi phản hồi</button>' +
                        '<button class="btn btn-secondary close-modal">Đóng</button>' +
                    '</div>' +
                '</div>';
            
            document.getElementById('contact-modal').style.display = 'flex';
            
            document.getElementById('send-reply-btn').onclick = sendReply;
        }
        
        function sendReply() {
            var replyMsg = document.getElementById('reply-message').value.trim();
            if (!replyMsg) {
                showNotification('Vui lòng nhập nội dung phản hồi!', 'error');
                return;
            }
            if (!currentContact) return;
            
            var btn = document.getElementById('send-reply-btn');
            btn.disabled = true;
            btn.textContent = '⏳ Đang gửi...';
            
            fetch(BASE + '/admin/contact_reply.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    contact_id: currentContact.id,
                    email: currentContact.email,
                    name: currentContact.name,
                    reply: replyMsg
                })
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.ok) {
                    showNotification('✅ ' + res.message, 'success');
                    document.getElementById('contact-modal').style.display = 'none';
                } else {
                    showNotification('❌ ' + (res.error || 'Lỗi gửi phản hồi'), 'error');
                }
                btn.disabled = false;
                btn.textContent = '📤 Gửi phản hồi';
            })
            .catch(function() {
                showNotification('❌ Lỗi kết nối', 'error');
                btn.disabled = false;
                btn.textContent = '📤 Gửi phản hồi';
            });
        }
        
        tbody.addEventListener('click', function(e) {
            var btn = e.target.closest('button[data-action="view"]');
            if (btn) showModal(Number(btn.getAttribute('data-id')));
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('close-modal')) {
                document.getElementById('contact-modal').style.display = 'none';
            }
        });
        
        document.getElementById('contact-modal').addEventListener('click', function(e) {
            if (e.target === this) this.style.display = 'none';
        });
        
        document.getElementById('refresh-btn').addEventListener('click', load);
        
        load();
        setInterval(load, 30000);
    })();
    </script>
</body>
</html>
