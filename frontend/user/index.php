<?php include '../includes/header.php'; ?>

<section class="banner">
	<div class="container">
		<h2>Khu vực người dùng</h2>
		<p>Quản lý tài khoản, theo dõi đơn và đặt hàng nhanh.</p>
	</div>
</section>

<section class="container" style="padding:24px;">
	<div class="products" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
		<a href="pages/checkout.php" class="item" style="text-decoration:none;">
			<h3>Đặt hàng</h3>
			<p>Chọn món và thanh toán</p>
		</a>
		<a href="pages/orders.php" class="item" style="text-decoration:none;">
			<h3>Đơn hàng của tôi</h3>
			<p>Theo dõi trạng thái đơn hàng</p>
		</a>
		<a href="pages/profile.php" class="item" style="text-decoration:none;">
			<h3>Thông tin cá nhân</h3>
			<p>Chỉnh sửa hồ sơ và địa chỉ</p>
		</a>
	</div>
</section>

<?php include '../includes/footer.php'; ?>


