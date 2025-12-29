<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="admin-header">
    <div class="container">
        <a href="../../../frontend/index.php" class="admin-logo">
            <div class="admin-logo-icon">🍔</div>
            <span>Fast Food Admin</span>
        </a>
        
        <nav class="admin-nav">
            <a href="dashboard.php" class="<?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">📊 Dashboard</a>
            <a href="products.php" class="<?php echo $currentPage === 'products' ? 'active' : ''; ?>">🍔 Món ăn</a>
            <a href="orders.php" class="<?php echo $currentPage === 'orders' ? 'active' : ''; ?>">📦 Đơn hàng</a>
            <a href="reviews.php" class="<?php echo $currentPage === 'reviews' ? 'active' : ''; ?>">⭐ Đánh giá</a>
            <a href="users.php" class="<?php echo $currentPage === 'users' ? 'active' : ''; ?>">👥 Người dùng</a>
            <a href="contacts.php" class="<?php echo $currentPage === 'contacts' ? 'active' : ''; ?>">📬 Liên hệ</a>
            <a href="../../../frontend/index.php" style="color:#ef4444;">🏠 Về trang chủ</a>
        </nav>
    </div>
</header>
