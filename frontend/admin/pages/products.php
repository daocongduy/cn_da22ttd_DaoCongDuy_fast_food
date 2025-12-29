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
    <title>Quản lý món ăn - Fast Food Admin</title>
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
    
    /* Form */
    .form-section { padding: 20px; }
    .form-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    .form-group { margin-bottom: 0; }
    .form-label {
        display: block;
        font-weight: 500;
        color: #475569;
        margin-bottom: 6px;
        font-size: 0.8rem;
    }
    .form-input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        background: white;
        transition: border-color 0.2s;
    }
    .form-input:focus {
        outline: none;
        border-color: #f97316;
    }
    textarea.form-input {
        resize: vertical;
        min-height: 80px;
    }
    .form-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        margin-top: 16px;
    }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
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
    .btn-sm { padding: 6px 12px; font-size: 0.75rem; }
    
    /* Badge */
    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: #f97316;
        color: white;
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
    
    .table .form-input {
        padding: 8px 10px;
        font-size: 0.8rem;
    }
    .table textarea.form-input {
        min-height: 60px;
    }
    .table select.form-input {
        padding: 6px 8px;
        min-width: 100px;
    }
    
    /* Product Image */
    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        background: #f1f5f9;
    }
    .change-img {
        display: block;
        margin-top: 4px;
        color: #f97316;
        font-size: 0.7rem;
        cursor: pointer;
        text-decoration: none;
    }
    .change-img:hover { text-decoration: underline; }
    
    /* Action buttons in table */
    .action-btns {
        display: flex;
        gap: 6px;
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
        animation: slideIn 0.3s ease;
    }
    .notification.success { background: #22c55e; }
    .notification.error { background: #ef4444; }
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .form-row { grid-template-columns: 1fr; }
        .admin-nav { display: none; }
        .action-btns { flex-direction: column; }
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
                <a href="products.php" class="active">🍔 Món ăn</a>
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
                <h1>🍔 Quản lý món ăn</h1>
                <p>Thêm, sửa và quản lý menu món ăn trong hệ thống</p>
            </div>

            <!-- Form thêm món ăn -->
            <div class="card">
                <div class="card-header">
                    <h2>➕ Thêm món ăn mới</h2>
                </div>
                <form id="form-create-product" class="form-section" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tên món</label>
                            <input type="text" name="name" class="form-input" required placeholder="Nhập tên món ăn">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giá (đ)</label>
                            <input type="number" name="price" class="form-input" required placeholder="Nhập giá">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mô tả món ăn</label>
                        <textarea name="description" class="form-input" rows="3" placeholder="Nhập mô tả chi tiết về món ăn..."></textarea>
                    </div>
                    <div class="form-group" style="margin-top:16px;">
                        <label class="form-label">Ảnh món ăn</label>
                        <input type="file" name="image" class="form-input" accept="image/*" style="padding:8px;">
                    </div>
                    <div class="form-actions">
                        <button class="btn btn-primary" type="submit">➕ Thêm món</button>
                    </div>
                </form>
            </div>

            <!-- Danh sách món ăn -->
            <div class="card">
                <div class="card-header">
                    <h2>📋 Danh sách món ăn</h2>
                    <span id="products-count" class="badge">0 món</span>
                </div>
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên món</th>
                                <th>Giá</th>
                                <th>Mô tả</th>
                                <th>Ảnh</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody id="products-body">
                            <tr><td colspan="7" class="loading-text">Đang tải...</td></tr>
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
        var form = document.getElementById('form-create-product');
        var tbody = document.getElementById('products-body');
        
        function showNotification(message, type) {
            var notification = document.createElement('div');
            notification.className = 'notification ' + type;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(function() {
                notification.style.opacity = '0';
                setTimeout(function() { notification.remove(); }, 300);
            }, 3000);
        }
        
        form.addEventListener('submit', function(e){
            e.preventDefault();
            var fd = new FormData(form);
            fetch(BASE + '/admin/product_create.php', { method:'POST', body: fd })
                .then(function(r){ return r.json(); })
                .then(function(res){
                    if (res && res.ok) { 
                        showNotification('✅ Đã thêm món thành công!', 'success');
                        form.reset();
                        load(); 
                    } else { 
                        showNotification('❌ Lỗi: ' + (res.error || 'Có lỗi xảy ra'), 'error'); 
                    }
                })
                .catch(function(err) {
                    showNotification('❌ Lỗi kết nối: ' + err.message, 'error');
                });
        });
        
        function load(){
            fetch(BASE + '/admin/products_list.php', {cache:'no-store'})
                .then(function(r){ return r.json(); })
                .then(function(d){ render((d && d.products) || []); })
                .catch(function(err){ 
                    tbody.innerHTML = '<tr><td colspan="7" class="loading-text">Không tải được sản phẩm</td></tr>'; 
                });
        }
        
        function render(list){
            document.getElementById('products-count').textContent = list.length + ' món';
            
            if (!list.length) { 
                tbody.innerHTML = '<tr><td colspan="7" class="loading-text">Chưa có sản phẩm.</td></tr>'; 
                return; 
            }
            
            tbody.innerHTML = list.map(function(p){
                var img = p.image_url ? 
                    (p.image_url.startsWith('http') ? p.image_url : '../../assets/images/' + p.image_url) : '';
                return '<tr>' +
                    '<td><strong>#' + p.id + '</strong></td>' +
                    '<td><input type="text" value="' + (p.name||'').replace(/"/g, '&quot;') + '" data-f="name" data-id="' + p.id + '" class="form-input"></td>' +
                    '<td><input type="number" value="' + (p.price||0) + '" data-f="price" data-id="' + p.id + '" class="form-input" style="width:90px;"></td>' +
                    '<td><textarea data-f="description" data-id="' + p.id + '" class="form-input">' + (p.description||'').replace(/</g, '&lt;') + '</textarea></td>' +
                    '<td style="text-align:center;">' + 
                        (img ? '<img src="' + img + '" class="product-img">' : '<div class="product-img"></div>') +
                        '<label class="change-img"><input type="file" data-f="image" data-id="' + p.id + '" style="display:none" accept="image/*">📷 Đổi ảnh</label>' +
                    '</td>' +
                    '<td><select data-f="is_active" data-id="' + p.id + '" class="form-input">' +
                        '<option value="1"' + (p.is_active ? ' selected' : '') + '>Đang bán</option>' +
                        '<option value="0"' + (!p.is_active ? ' selected' : '') + '>Tạm ẩn</option>' +
                    '</select></td>' +
                    '<td><div class="action-btns">' +
                        '<button class="btn btn-success btn-sm" data-action="save" data-id="' + p.id + '">💾 Lưu</button>' +
                        '<button class="btn btn-danger btn-sm" data-action="delete" data-id="' + p.id + '">🗑️ Xóa</button>' +
                    '</div></td>' +
                '</tr>';
            }).join('');
        }

        tbody.addEventListener('click', function(e){
            var btn = e.target.closest('button[data-action]');
            if (!btn) return;
            var id = Number(btn.getAttribute('data-id'));
            var action = btn.getAttribute('data-action');
            
            if (action === 'delete') {
                if (!confirm('Xóa sản phẩm #' + id + '?')) return;
                fetch(BASE + '/admin/product_delete.php', { 
                    method:'POST', 
                    headers:{'Content-Type':'application/json'}, 
                    body: JSON.stringify({id:id}) 
                })
                .then(function(r){ return r.json(); })
                .then(function(res){ 
                    if(res && res.ok){ 
                        showNotification('✅ Đã xóa sản phẩm #' + id, 'success');
                        load(); 
                    } else { 
                        showNotification('❌ Xóa thất bại: ' + (res.error || 'Có lỗi'), 'error'); 
                    }
                });
            } else if (action === 'save') {
                var name = tbody.querySelector('input[data-f="name"][data-id="'+id+'"]').value.trim();
                var priceVal = Number(tbody.querySelector('input[data-f="price"][data-id="'+id+'"]').value);
                var description = tbody.querySelector('textarea[data-f="description"][data-id="'+id+'"]').value.trim();
                var isActive = Number(tbody.querySelector('select[data-f="is_active"][data-id="'+id+'"]').value);
                
                var fd = new FormData(); 
                fd.append('id', id); 
                fd.append('name', name); 
                fd.append('price', priceVal); 
                fd.append('description', description); 
                fd.append('is_active', isActive);
                
                var imgInput = tbody.querySelector('input[data-f="image"][data-id="'+id+'"]');
                if (imgInput && imgInput.files && imgInput.files[0]) { 
                    fd.append('image', imgInput.files[0]); 
                }
                
                fetch(BASE + '/admin/product_update.php', { method:'POST', body: fd })
                .then(function(r){ return r.json(); })
                .then(function(res){ 
                    if(res && res.ok){ 
                        showNotification('✅ Đã cập nhật sản phẩm #' + id, 'success');
                        load(); 
                    } else { 
                        showNotification('❌ Cập nhật thất bại: ' + (res.error || 'Có lỗi'), 'error'); 
                    }
                });
            }
        });

        load();
    })();
    </script>
</body>
</html>
