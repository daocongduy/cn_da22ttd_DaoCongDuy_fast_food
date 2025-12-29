<?php 
include '../includes/header.php';
?>

<section class="container" style="padding:24px;">
	<div style="text-align:center; margin-bottom:32px;">
		<h1 style="color:#1f2937; font-size:2.5em; margin-bottom:8px;">🛒 Giỏ hàng</h1>
		<p style="color:#6b7280; font-size:1.1em;">Kiểm tra và thanh toán đơn hàng của bạn</p>
	</div>

	<div style="background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden;">
		<div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; padding:20px 24px;">
			<h3 style="margin:0;">🛒 Danh sách món ăn</h3>
		</div>

		<div style="overflow:auto;">
			<table style="width:100%; border-collapse:collapse;">
				<thead>
					<tr style="background:#f8fafc;">
						<th style="padding:16px 12px; border-bottom:2px solid #e2e8f0; text-align:left;">Món ăn</th>
						<th style="padding:16px 12px; border-bottom:2px solid #e2e8f0; text-align:center;">Đơn giá</th>
						<th style="padding:16px 12px; border-bottom:2px solid #e2e8f0; text-align:center;">Số lượng</th>
						<th style="padding:16px 12px; border-bottom:2px solid #e2e8f0; text-align:center;">Thành tiền</th>
						<th style="padding:16px 12px; border-bottom:2px solid #e2e8f0; text-align:center;">Thao tác</th>
					</tr>
				</thead>
				<tbody id="cart-body"></tbody>
			</table>
		</div>

		<div style="background:#f8fafc; padding:24px; border-top:2px solid #e2e8f0;">
			<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
				<div>
					<span style="color:#6b7280;">Tổng cộng: </span>
					<strong id="cart-total" style="font-size:1.5em; color:#dc2626;"></strong>
				</div>
				<a href="../user/pages/checkout.php" id="go-checkout" style="background:#ff6a00; color:white; padding:16px 32px; border-radius:12px; text-decoration:none; font-weight:600;">
					🚀 Đặt hàng ngay
				</a>
			</div>
		</div>
	</div>
</section>

<?php include '../includes/footer.php'; ?>

<script>
(function(){
	function formatPrice(v) {
		return new Intl.NumberFormat('vi-VN').format(v) + 'đ';
	}

	function getCart() {
		try {
			return JSON.parse(localStorage.getItem('ff_cart_v1')) || [];
		} catch(e) {
			return [];
		}
	}

	function saveCart(items) {
		localStorage.setItem('ff_cart_v1', JSON.stringify(items));
	}

	function render() {
		var tbody = document.getElementById('cart-body');
		var totalEl = document.getElementById('cart-total');
		var checkoutBtn = document.getElementById('go-checkout');
		var items = getCart();

		if (!items.length) {
			tbody.innerHTML = '<tr><td colspan="5" style="padding:48px; text-align:center;"><div style="font-size:3em;">🛒</div><p style="color:#6b7280;">Giỏ hàng trống</p></td></tr>';
			totalEl.textContent = '0đ';
			checkoutBtn.style.display = 'none';
			return;
		}

		checkoutBtn.style.display = '';
		var html = '';
		var total = 0;

		for (var i = 0; i < items.length; i++) {
			var item = items[i];
			var lineTotal = item.quantity * item.price;
			total += lineTotal;

			html += '<tr>';
			html += '<td style="padding:16px 12px; border-bottom:1px solid #f1f5f9; font-weight:600;">' + item.name + '</td>';
			html += '<td style="padding:16px 12px; border-bottom:1px solid #f1f5f9; text-align:center; color:#059669;">' + formatPrice(item.price) + '</td>';
			html += '<td style="padding:16px 12px; border-bottom:1px solid #f1f5f9; text-align:center;">';
			html += '<button type="button" data-minus="' + i + '" style="width:32px; height:32px; border:none; background:#3b82f6; color:white; border-radius:6px; cursor:pointer; font-weight:900; font-size:18px;">−</button>';
			html += '<span style="display:inline-block; width:40px; text-align:center; font-weight:700; font-size:16px;">' + item.quantity + '</span>';
			html += '<button type="button" data-plus="' + i + '" style="width:32px; height:32px; border:none; background:#22c55e; color:white; border-radius:6px; cursor:pointer; font-weight:900; font-size:18px;">+</button>';
			html += '</td>';
			html += '<td style="padding:16px 12px; border-bottom:1px solid #f1f5f9; text-align:center; font-weight:700; color:#dc2626;">' + formatPrice(lineTotal) + '</td>';
			html += '<td style="padding:16px 12px; border-bottom:1px solid #f1f5f9; text-align:center;">';
			html += '<button type="button" data-remove="' + i + '" style="background:#ef4444; color:white; border:none; padding:8px 16px; border-radius:8px; cursor:pointer;">🗑️ Xóa</button>';
			html += '</td>';
			html += '</tr>';
		}

		tbody.innerHTML = html;
		totalEl.textContent = formatPrice(total);
	}

	// Event delegation cho các nút
	document.getElementById('cart-body').addEventListener('click', function(e) {
		var target = e.target;
		var items = getCart();

		// Nút +
		if (target.hasAttribute('data-plus')) {
			var idx = parseInt(target.getAttribute('data-plus'));
			items[idx].quantity++;
			saveCart(items);
			render();
			return;
		}

		// Nút -
		if (target.hasAttribute('data-minus')) {
			var idx = parseInt(target.getAttribute('data-minus'));
			if (items[idx].quantity > 1) {
				items[idx].quantity--;
				saveCart(items);
				render();
			}
			return;
		}

		// Nút xóa
		if (target.hasAttribute('data-remove')) {
			var idx = parseInt(target.getAttribute('data-remove'));
			if (confirm('Xóa món này khỏi giỏ hàng?')) {
				items.splice(idx, 1);
				saveCart(items);
				render();
			}
			return;
		}
	});

	// Render lần đầu
	render();
})();
</script>
