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
    <title>Quản lý đánh giá - Fast Food Admin</title>
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
    
    /* Stats Grid */
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
        border-left: 4px solid #3b82f6;
    }
    .stat-card.success { border-left-color: #22c55e; }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.star { border-left-color: #fbbf24; }
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
    
    /* Rating Distribution */
    .rating-dist { padding: 18px; }
    .rating-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .rating-row:last-child { margin-bottom: 0; }
    .rating-label { width: 35px; font-weight: 600; font-size: 0.85rem; color: #374151; }
    .rating-bar {
        flex: 1;
        height: 10px;
        background: #f3f4f6;
        border-radius: 999px;
        overflow: hidden;
    }
    .rating-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.3s;
    }
    .rating-bar-fill.high { background: linear-gradient(90deg, #22c55e, #16a34a); }
    .rating-bar-fill.mid { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
    .rating-bar-fill.low { background: linear-gradient(90deg, #f97316, #ea580c); }
    .rating-count { width: 70px; text-align: right; font-size: 0.8rem; color: #6b7280; }
    
    /* Filter */
    .filter-section {
        padding: 16px 18px;
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }
    .filter-group { display: flex; flex-direction: column; gap: 4px; }
    .filter-label { font-size: 0.75rem; color: #64748b; font-weight: 500; }
    .filter-select {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.8rem;
        background: white;
        min-width: 120px;
    }
    .filter-select:focus { outline: none; border-color: #f97316; }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
    }
    .btn-primary { background: #f97316; color: white; }
    .btn-primary:hover { background: #ea580c; }
    .btn-success { background: #22c55e; color: white; }
    .btn-success:hover { background: #16a34a; }
    .btn-danger { background: #ef4444; color: white; }
    .btn-danger:hover { background: #dc2626; }
    .btn-secondary { background: #f1f5f9; color: #475569; }
    .btn-secondary:hover { background: #e2e8f0; }
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        justify-content: center;
        border-radius: 8px;
    }
    .btn-view { background: #fef3c7; color: #92400e; }
    .btn-approve { background: #22c55e; color: white; }
    .btn-reject { background: #f97316; color: white; }
    .btn-delete { background: #ef4444; color: white; }
    
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
    
    /* Stars */
    .stars { color: #fbbf24; letter-spacing: 1px; }
    .stars-empty { color: #e5e7eb; }
    
    /* Badges */
    .badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .badge-approved { background: #d1fae5; color: #065f46; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    
    /* Action buttons */
    .action-btns { display: flex; gap: 6px; }
    
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
        max-width: 550px;
        width: 90%;
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
    .modal-body { padding: 18px; }
    .modal-footer { padding: 14px 18px; border-top: 1px solid #f1f5f9; display: flex; gap: 8px; }
    .btn-close { background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #64748b; }
    
    /* Footer */
    .admin-footer { text-align: center; padding: 20px; color: #64748b; font-size: 0.8rem; margin-top: auto; background: rgba(255,255,255,0.5); }
    
    /* Utilities */
    .loading-text { text-align: center; padding: 24px; color: #94a3b8; }
    .text-muted { color: #64748b; font-size: 0.75rem; }
    
    /* Notification */
    .notification { position: fixed; top: 20px; right: 20px; padding: 12px 20px; border-radius: 8px; color: white; font-weight: 500; z-index: 9999; }
    .notification.success { background: #22c55e; }
    .notification.error { background: #ef4444; }
    
    /* Responsive */
    @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
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
                <a href="reviews.php" class="active">⭐ Đánh giá</a>
                <a href="users.php">👥 Người dùng</a>
                <a href="contacts.php">📬 Liên hệ</a>
                <a href="../../index.php" style="color:#ef4444;">🏠 Về trang chủ</a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="page-header">
                <h1>⭐ Quản lý đánh giá</h1>
                <p>Quản lý và kiểm duyệt đánh giá từ khách hàng</p>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-info">
                        <div class="stat-value" id="total-reviews">0</div>
                        <div class="stat-label">Tổng đánh giá</div>
                    </div>
                </div>
                <div class="stat-card success">
                    <div class="stat-icon">✅</div>
                    <div class="stat-info">
                        <div class="stat-value" id="approved-reviews">0</div>
                        <div class="stat-label">Đã duyệt</div>
                    </div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-info">
                        <div class="stat-value" id="pending-reviews">0</div>
                        <div class="stat-label">Chờ duyệt</div>
                    </div>
                </div>
                <div class="stat-card star">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-info">
                        <div class="stat-value" id="average-rating">0</div>
                        <div class="stat-label">Đánh giá TB</div>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="card">
                <div class="card-header">
                    <h2>📈 Phân bố đánh giá</h2>
                </div>
                <div class="rating-dist" id="rating-distribution"></div>
            </div>

            <!-- Filter -->
            <div class="card">
                <div class="card-header">
                    <h2>🔍 Bộ lọc</h2>
                </div>
                <div class="filter-section">
                    <div class="filter-group">
                        <span class="filter-label">Trạng thái</span>
                        <select id="status-filter" class="filter-select">
                            <option value="all">Tất cả</option>
                            <option value="approved">Đã duyệt</option>
                            <option value="pending">Chờ duyệt</option>
                        </select>
                    </div>
                    <button id="refresh-btn" class="btn btn-secondary">🔄 Làm mới</button>
                </div>
            </div>

            <!-- Reviews Table -->
            <div class="card">
                <div class="card-header">
                    <h2>💬 Danh sách đánh giá</h2>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sản phẩm</th>
                                <th>Khách hàng</th>
                                <th>Đánh giá</th>
                                <th>Bình luận</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody id="reviews-body">
                            <tr><td colspan="8" class="loading-text">Đang tải...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="review-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📋 Chi tiết đánh giá</h3>
                <button class="btn-close close-modal">✕</button>
            </div>
            <div id="modal-body" class="modal-body"></div>
            <div class="modal-footer">
                <button id="modal-approve" class="btn btn-success">✅ Duyệt</button>
                <button id="modal-reject" class="btn btn-secondary">❌ Từ chối</button>
                <button id="modal-delete" class="btn btn-danger">🗑️ Xóa</button>
            </div>
        </div>
    </div>

    <footer class="admin-footer">
        <p>© 2025 Fast Food Admin Panel. All rights reserved.</p>
    </footer>

    <script>
    (function(){
        var BASE = '../../../backend';
        var tbody = document.getElementById('reviews-body');
        var currentReviews = [];
        var currentReviewId = null;
        
        function formatDate(d) { return d ? new Date(d.replace(' ','T')).toLocaleDateString('vi-VN') : '-'; }
        
        function generateStars(r) {
            var s = '';
            for(var i = 1; i <= 5; i++) {
                s += i <= r ? '<span class="stars">★</span>' : '<span class="stars-empty">★</span>';
            }
            return s;
        }
        
        function getStatusBadge(a) {
            return a ? '<span class="badge badge-approved">✅ Đã duyệt</span>' : '<span class="badge badge-pending">⏳ Chờ duyệt</span>';
        }
        
        function showNotification(m, t) {
            var n = document.createElement('div');
            n.className = 'notification ' + t;
            n.textContent = m;
            document.body.appendChild(n);
            setTimeout(function() { n.remove(); }, 3000);
        }
        
        function updateStats(s) {
            document.getElementById('total-reviews').textContent = s.total_reviews || 0;
            document.getElementById('approved-reviews').textContent = s.approved_reviews || 0;
            document.getElementById('pending-reviews').textContent = s.pending_reviews || 0;
            document.getElementById('average-rating').textContent = (s.average_rating || 0).toFixed(1);
            
            var c = document.getElementById('rating-distribution');
            var h = '';
            for(var i = 5; i >= 1; i--) {
                var cnt = (s.rating_distribution && s.rating_distribution[i]) || 0;
                var pct = s.total_reviews > 0 ? (cnt / s.total_reviews * 100) : 0;
                var barClass = i >= 4 ? 'high' : (i === 3 ? 'mid' : 'low');
                h += '<div class="rating-row">' +
                    '<div class="rating-label">' + i + ' ★</div>' +
                    '<div class="rating-bar"><div class="rating-bar-fill ' + barClass + '" style="width:' + pct.toFixed(1) + '%;"></div></div>' +
                    '<div class="rating-count">' + cnt + ' (' + pct.toFixed(1) + '%)</div>' +
                '</div>';
            }
            c.innerHTML = h;
        }
        
        function render(reviews) {
            currentReviews = reviews;
            if(!reviews.length) {
                tbody.innerHTML = '<tr><td colspan="8" class="loading-text">Không có đánh giá.</td></tr>';
                return;
            }
            
            tbody.innerHTML = reviews.map(function(r) {
                return '<tr>' +
                    '<td><strong>#' + r.id + '</strong></td>' +
                    '<td>' + (r.product_name || '-') + '</td>' +
                    '<td>' + (r.user_name || '-') + '<br><span class="text-muted">' + (r.user_email || '') + '</span></td>' +
                    '<td>' + generateStars(r.rating) + ' <strong>' + r.rating + '</strong></td>' +
                    '<td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">' + (r.comment || '<em class="text-muted">Không có</em>') + '</td>' +
                    '<td class="text-muted">' + formatDate(r.created_at) + '</td>' +
                    '<td>' + getStatusBadge(r.is_approved) + '</td>' +
                    '<td><div class="action-btns">' +
                        '<button class="btn btn-icon btn-view" data-action="view" data-id="' + r.id + '" title="Xem">👁️</button>' +
                        (!r.is_approved ? '<button class="btn btn-icon btn-approve" data-action="approve" data-id="' + r.id + '" title="Duyệt">✅</button>' : '<button class="btn btn-icon btn-reject" data-action="reject" data-id="' + r.id + '" title="Từ chối">❌</button>') +
                        '<button class="btn btn-icon btn-delete" data-action="delete" data-id="' + r.id + '" title="Xóa">🗑️</button>' +
                    '</div></td>' +
                '</tr>';
            }).join('');
        }
        
        function load() {
            var status = document.getElementById('status-filter').value;
            fetch(BASE + '/admin/reviews_list.php?status=' + status, {cache:'no-store'})
                .then(function(r) { return r.json(); })
                .then(function(d) {
                    if(d.ok) {
                        updateStats(d.stats);
                        render(d.reviews || []);
                    }
                })
                .catch(function() {
                    tbody.innerHTML = '<tr><td colspan="8" class="loading-text">Không tải được</td></tr>';
                });
        }
        
        function updateReview(id, action) {
            fetch(BASE + '/admin/reviews_update.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({review_id: id, action: action})
            })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if(res.ok) {
                    showNotification(res.message || 'Thành công!', 'success');
                    load();
                    document.getElementById('review-modal').style.display = 'none';
                } else {
                    showNotification('❌ ' + (res.error || 'Lỗi'), 'error');
                }
            });
        }
        
        function showModal(id) {
            var r = currentReviews.find(function(x) { return x.id === id; });
            if(!r) return;
            currentReviewId = id;
            
            document.getElementById('modal-body').innerHTML = 
                '<p><strong>Sản phẩm:</strong> ' + (r.product_name || '-') + '</p>' +
                '<p><strong>Khách hàng:</strong> ' + (r.user_name || '-') + ' (' + (r.user_email || '') + ')</p>' +
                '<p><strong>Đánh giá:</strong> ' + generateStars(r.rating) + ' ' + r.rating + '/5</p>' +
                '<p><strong>Trạng thái:</strong> ' + getStatusBadge(r.is_approved) + '</p>' +
                (r.comment ? '<div style="margin-top:1rem;"><strong>Bình luận:</strong><div style="background:#f8fafc;padding:1rem;border-radius:8px;margin-top:0.5rem;">' + r.comment + '</div></div>' : '<p class="text-muted"><em>Không có bình luận</em></p>');
            
            document.getElementById('review-modal').style.display = 'flex';
        }
        
        tbody.addEventListener('click', function(e) {
            var btn = e.target.closest('button[data-action]');
            if(!btn) return;
            var id = Number(btn.getAttribute('data-id'));
            var action = btn.getAttribute('data-action');
            
            if(action === 'view') showModal(id);
            else if(action === 'approve' && confirm('Duyệt đánh giá này?')) updateReview(id, 'approve');
            else if(action === 'reject' && confirm('Từ chối đánh giá này?')) updateReview(id, 'reject');
            else if(action === 'delete' && confirm('Xóa đánh giá này?')) updateReview(id, 'delete');
        });
        
        document.getElementById('modal-approve').addEventListener('click', function() {
            if(currentReviewId && confirm('Duyệt?')) updateReview(currentReviewId, 'approve');
        });
        document.getElementById('modal-reject').addEventListener('click', function() {
            if(currentReviewId && confirm('Từ chối?')) updateReview(currentReviewId, 'reject');
        });
        document.getElementById('modal-delete').addEventListener('click', function() {
            if(currentReviewId && confirm('Xóa?')) updateReview(currentReviewId, 'delete');
        });
        
        document.querySelectorAll('.close-modal').forEach(function(b) {
            b.addEventListener('click', function() {
                document.getElementById('review-modal').style.display = 'none';
            });
        });
        
        document.getElementById('review-modal').addEventListener('click', function(e) {
            if(e.target === this) this.style.display = 'none';
        });
        
        document.getElementById('status-filter').addEventListener('change', load);
        document.getElementById('refresh-btn').addEventListener('click', load);
        
        load();
        setInterval(load, 30000);
    })();
    </script>
</body>
</html>
