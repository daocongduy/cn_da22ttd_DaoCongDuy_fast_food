<?php include '../../includes/header.php'; ?>

<section class="container" style="padding:24px;">
	<!-- Header -->
	<div style="text-align:center; margin-bottom:32px;">
		<h1 style="color:#1f2937; font-size:2.5em; margin-bottom:8px;">📋 Trạng thái đơn hàng</h1>
		<p style="color:#6b7280; font-size:1.1em;">Theo dõi và tra cứu đơn hàng của bạn</p>
	</div>

	<!-- Search Section -->
	<div class="search-section" style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; border-radius:16px; padding:24px; margin-bottom:24px; box-shadow:0 8px 30px rgba(102, 126, 234, 0.3);">
		<div style="text-align:center; margin-bottom:20px;">
			<div style="font-size:2.5em; margin-bottom:8px;">🔍</div>
			<h3 style="margin:0; font-size:1.5em; font-weight:700;">Tra cứu đơn hàng</h3>
			<p style="margin:8px 0 0 0; opacity:0.9;">Nhập mã đơn hàng để xem chi tiết</p>
		</div>
		<div style="display:flex; gap:12px; max-width:500px; margin:0 auto;">
			<input type="text" id="order-id-input" placeholder="Nhập mã đơn (VD: 123)" style="flex:1; padding:14px 18px; border:none; border-radius:10px; font-size:16px; background:rgba(255,255,255,0.95);">
			<button id="search-btn" style="padding:14px 24px; font-size:16px; font-weight:600; border-radius:10px; background:rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.4); color:white; cursor:pointer;">🔍 Tra cứu</button>
		</div>
	</div>

	<!-- Search Result -->
	<div id="search-result" style="display:none; margin-bottom:24px;"></div>

	<!-- Orders List -->
	<div class="orders-card" style="background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden;">
		<div style="display:flex; align-items:center; gap:12px; padding:20px 24px; border-bottom:2px solid #f1f5f9; background:#f8fafc;">
			<div style="font-size:1.8em;">📦</div>
			<div>
				<h3 style="margin:0; font-size:1.3em; color:#1f2937; font-weight:700;">Danh sách đơn hàng</h3>
				<p style="margin:4px 0 0 0; color:#6b7280; font-size:0.9em;">Tất cả đơn hàng bạn đã đặt</p>
			</div>
		</div>
		<div style="overflow-x:auto;">
			<table style="width:100%; border-collapse:collapse; min-width:700px;">
				<thead>
					<tr style="background:#f8fafc;">
						<th style="padding:14px 16px; border-bottom:2px solid #e2e8f0; font-weight:600; color:#475569; text-align:center;">Mã đơn</th>
						<th style="padding:14px 16px; border-bottom:2px solid #e2e8f0; font-weight:600; color:#475569; text-align:left;">Ngày đặt</th>
						<th style="padding:14px 16px; border-bottom:2px solid #e2e8f0; font-weight:600; color:#475569; text-align:right;">Tổng tiền</th>
						<th style="padding:14px 16px; border-bottom:2px solid #e2e8f0; font-weight:600; color:#475569; text-align:center;">Trạng thái</th>
						<th style="padding:14px 16px; border-bottom:2px solid #e2e8f0; font-weight:600; color:#475569; text-align:center;">Thao tác</th>
					</tr>
				</thead>
				<tbody id="orders-list">
					<tr><td colspan="5" style="padding:32px; text-align:center; color:#64748b;">⏳ Đang tải...</td></tr>
				</tbody>
			</table>
		</div>
	</div>
</section>

<style>
.status-badge {
	display: inline-block;
	padding: 5px 12px;
	border-radius: 20px;
	font-size: 0.85em;
	font-weight: 600;
}
.status-pending { background: #dbeafe; color: #1e40af; }
.status-confirmed { background: #dcfce7; color: #166534; }
.status-preparing { background: #fed7aa; color: #c2410c; }
.status-delivering { background: #fef3c7; color: #92400e; }
.status-completed { background: #d1fae5; color: #065f46; }
.status-cancelled { background: #fee2e2; color: #991b1b; }

.btn-detail {
	background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
	color: white;
	border: none;
	padding: 8px 14px;
	border-radius: 6px;
	font-weight: 600;
	cursor: pointer;
	font-size: 0.85em;
	margin-right: 6px;
}
.btn-detail:hover { opacity: 0.9; }

.btn-review {
	background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
	color: white;
	border: none;
	padding: 8px 14px;
	border-radius: 6px;
	font-weight: 600;
	cursor: pointer;
	font-size: 0.85em;
}
.btn-review:hover { opacity: 0.9; }

#search-btn:hover {
	background: rgba(255,255,255,0.35);
}

#order-id-input:focus {
	outline: none;
	box-shadow: 0 0 0 3px rgba(255,255,255,0.3);
}

tbody tr:hover {
	background: #f8fafc;
}

/* Timeline */
.timeline {
	position: relative;
	padding-left: 28px;
	margin: 16px 0;
}
.timeline::before {
	content: '';
	position: absolute;
	left: 12px;
	top: 0;
	bottom: 0;
	width: 3px;
	background: #e5e7eb;
	border-radius: 2px;
}
.timeline-step {
	position: relative;
	margin-bottom: 16px;
	padding-left: 20px;
}
.timeline-step::before {
	content: '';
	position: absolute;
	left: -10px;
	top: 4px;
	width: 14px;
	height: 14px;
	border-radius: 50%;
	background: #e5e7eb;
	border: 2px solid #fff;
	box-shadow: 0 0 0 2px #e5e7eb;
}
.timeline-step.active::before {
	background: #10b981;
	box-shadow: 0 0 0 2px #10b981;
}
.timeline-step.completed::before {
	background: #059669;
	box-shadow: 0 0 0 2px #059669;
}

@media (max-width: 768px) {
	.search-section > div:last-child {
		flex-direction: column;
	}
	h1 { font-size: 2em !important; }
}
</style>

<script>
(function(){
	var orderIdInput = document.getElementById('order-id-input');
	var searchBtn = document.getElementById('search-btn');
	var resultDiv = document.getElementById('search-result');
	var ordersList = document.getElementById('orders-list');

	function formatCurrency(v) {
		try { return new Intl.NumberFormat('vi-VN').format(v) + 'đ'; } 
		catch(e) { return v + 'đ'; }
	}

	function formatDateTime(d) {
		try { return new Date(d.replace(' ', 'T')).toLocaleString('vi-VN'); }
		catch(e) { return d || 'N/A'; }
	}

	function getStatusInfo(status) {
		var map = {
			'pending': { text: 'Chờ duyệt', class: 'status-pending' },
			'confirmed': { text: 'Đã xác nhận', class: 'status-confirmed' },
			'preparing': { text: 'Đang chuẩn bị', class: 'status-preparing' },
			'delivering': { text: 'Đang giao', class: 'status-delivering' },
			'completed': { text: 'Hoàn thành', class: 'status-completed' },
			'cancelled': { text: 'Đã hủy', class: 'status-cancelled' }
		};
		return map[status] || { text: status, class: 'status-pending' };
	}

	function renderOrderDetail(order) {
		var info = getStatusInfo(order.status);
		var steps = [
			{ status: 'pending', text: '📝 Đơn hàng đã tạo' },
			{ status: 'confirmed', text: '✅ Đã xác nhận' },
			{ status: 'preparing', text: '👨‍🍳 Đang chuẩn bị' },
			{ status: 'delivering', text: '🚚 Đang giao' },
			{ status: 'completed', text: '🎉 Hoàn thành' }
		];
		var statusOrder = ['pending', 'confirmed', 'preparing', 'delivering', 'completed'];
		var currentIdx = statusOrder.indexOf(order.status);

		var timelineHtml = '<div class="timeline">';
		steps.forEach(function(step, i) {
			var cls = i < currentIdx ? 'completed' : (i === currentIdx ? 'active' : '');
			timelineHtml += '<div class="timeline-step ' + cls + '"><strong>' + step.text + '</strong></div>';
		});
		timelineHtml += '</div>';

		var itemsHtml = '';
		if (order.items && order.items.length) {
			itemsHtml = '<div style="margin-top:20px; padding-top:16px; border-top:1px solid #e5e7eb;"><h4 style="margin-bottom:12px;">📦 Chi tiết đơn hàng</h4>';
			order.items.forEach(function(item) {
				itemsHtml += '<div style="display:flex; justify-content:space-between; padding:10px; background:#f8fafc; border-radius:6px; margin-bottom:8px;">' +
					'<span><strong>' + item.product_name + '</strong> x' + item.quantity + '</span>' +
					'<span style="color:#dc2626; font-weight:600;">' + formatCurrency(item.total_price) + '</span></div>';
			});
			itemsHtml += '</div>';
		}

		return '<div style="background:#fff; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.08); padding:24px; border-left:4px solid #3b82f6;">' +
			'<h3 style="margin:0 0 16px 0;">📋 Đơn hàng #' + order.id + '</h3>' +
			'<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:12px; margin-bottom:16px;">' +
			'<div style="padding:10px; background:#f8fafc; border-radius:6px;"><strong>📅 Ngày đặt:</strong><br>' + formatDateTime(order.created_at) + '</div>' +
			'<div style="padding:10px; background:#f8fafc; border-radius:6px;"><strong>💰 Tổng tiền:</strong><br><span style="color:#dc2626; font-weight:700;">' + formatCurrency(order.total_amount) + '</span></div>' +
			'<div style="padding:10px; background:#f8fafc; border-radius:6px;"><strong>📍 Địa chỉ:</strong><br>' + (order.shipping_address || 'N/A') + '</div>' +
			'<div style="padding:10px; background:#f8fafc; border-radius:6px;"><strong>📊 Trạng thái:</strong><br><span class="status-badge ' + info.class + '">' + info.text + '</span></div>' +
			'</div>' +
			'<h4 style="margin-bottom:8px;">📈 Tiến trình</h4>' +
			timelineHtml +
			itemsHtml +
			'</div>';
	}

	function searchOrder() {
		var id = orderIdInput.value.trim();
		if (!id) { alert('Vui lòng nhập mã đơn hàng'); return; }

		fetch('../../../backend/orders_detail.php?id=' + id, { cache: 'no-store' })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (data && data.order) {
					resultDiv.innerHTML = renderOrderDetail(data.order);
					resultDiv.style.display = 'block';
				} else {
					resultDiv.innerHTML = '<div style="background:#fff; border-radius:12px; padding:24px; text-align:center; border-left:4px solid #ef4444;"><div style="font-size:2.5em; margin-bottom:12px;">❌</div><h3 style="color:#ef4444;">Không tìm thấy đơn hàng #' + id + '</h3></div>';
					resultDiv.style.display = 'block';
				}
			})
			.catch(function() {
				resultDiv.innerHTML = '<div style="background:#fff; border-radius:12px; padding:24px; text-align:center; border-left:4px solid #ef4444;"><div style="font-size:2.5em; margin-bottom:12px;">⚠️</div><h3 style="color:#ef4444;">Lỗi kết nối</h3></div>';
				resultDiv.style.display = 'block';
			});
	}

	function loadOrdersList() {
		fetch('../../../backend/orders_list.php', { cache: 'no-store' })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				var orders = (data && data.orders) || [];
				if (!orders.length) {
					ordersList.innerHTML = '<tr><td colspan="5" style="padding:32px; text-align:center; color:#64748b;">📭 Chưa có đơn hàng nào</td></tr>';
					return;
				}

				var html = orders.map(function(o) {
					var info = getStatusInfo(o.status);
					var actions = '<button class="btn-detail" onclick="searchOrderById(' + o.id + ')">👁️ Chi tiết</button>';
					if (o.status === 'completed' || o.status === 'Hoàn thành') {
						actions += '<button class="btn-review" onclick="goToReview(' + o.id + ')">⭐ Đánh giá</button>';
					}
					return '<tr>' +
						'<td style="padding:14px 16px; border-bottom:1px solid #f1f5f9; text-align:center; font-weight:700; color:#2563eb;">#' + o.id + '</td>' +
						'<td style="padding:14px 16px; border-bottom:1px solid #f1f5f9;">' + formatDateTime(o.date) + '</td>' +
						'<td style="padding:14px 16px; border-bottom:1px solid #f1f5f9; text-align:right; font-weight:700; color:#dc2626;">' + formatCurrency(o.total) + '</td>' +
						'<td style="padding:14px 16px; border-bottom:1px solid #f1f5f9; text-align:center;"><span class="status-badge ' + info.class + '">' + info.text + '</span></td>' +
						'<td style="padding:14px 16px; border-bottom:1px solid #f1f5f9; text-align:center;">' + actions + '</td>' +
						'</tr>';
				}).join('');

				ordersList.innerHTML = html;
			})
			.catch(function() {
				ordersList.innerHTML = '<tr><td colspan="5" style="padding:32px; text-align:center; color:#ef4444;">❌ Không tải được danh sách</td></tr>';
			});
	}

	window.searchOrderById = function(id) {
		orderIdInput.value = id;
		searchOrder();
		window.scrollTo({ top: 0, behavior: 'smooth' });
	};

	window.goToReview = function(id) {
		window.location.href = 'review_order.php?order_id=' + id;
	};

	searchBtn.addEventListener('click', searchOrder);
	orderIdInput.addEventListener('keypress', function(e) {
		if (e.key === 'Enter') searchOrder();
	});

	loadOrdersList();
	setInterval(loadOrdersList, 30000);
})();
</script>

<?php include '../../includes/footer.php'; ?>
