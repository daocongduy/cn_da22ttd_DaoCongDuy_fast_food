<?php include '../../includes/header.php'; ?>

<section class="container" style="padding:24px;">
	<h2 style="margin-bottom:16px;">Đơn hàng của tôi</h2>
	<div class="item" style="overflow:auto;">
		<table style="width:100%; border-collapse: collapse;">
			<thead>
				<tr style="text-align:left;">
					<th style="padding:12px; border-bottom:1px solid #eee;">Mã đơn</th>
					<th style="padding:12px; border-bottom:1px solid #eee;">Ngày & Giờ</th>
					<th style="padding:12px; border-bottom:1px solid #eee;">Tổng</th>
					<th style="padding:12px; border-bottom:1px solid #eee;">Trạng thái</th>
					<th style="padding:12px; border-bottom:1px solid #eee;">Chi tiết</th>
				</tr>
			</thead>
			<tbody id="orders-body"></tbody>
		</table>
	</div>
</section>

<script>
(function () {
	var tbody = document.getElementById('orders-body');

	function formatCurrency(value) {
		try {
			return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
		} catch (e) {
			return value + 'đ';
		}
	}

	function renderOrders(orders) {
		if (!Array.isArray(orders) || orders.length === 0) {
			tbody.innerHTML = '<tr><td colspan="5" style="padding:12px; border-bottom:1px solid #f3f4f6;">Chưa có đơn hàng nào.</td></tr>';
			return;
		}

		var rows = orders.map(function (o) {
			var dateStr = o.date ? new Date((o.date || '').replace(' ', 'T')).toLocaleString('vi-VN') : '';
			var statusText = (o.status || '');
			var statusClass = 'status-pending';
			if (statusText.indexOf('Đã xác nhận') === 0) statusClass = 'status-confirmed';
			else if (statusText.indexOf('Đang chuẩn bị') === 0) statusClass = 'status-preparing';
			else if (statusText.indexOf('Đang giao') === 0) statusClass = 'status-delivering';
			else if (statusText.indexOf('Hoàn thành') === 0) statusClass = 'status-completed';
			else if (statusText.indexOf('Đã hủy') === 0) statusClass = 'status-cancelled';

			// ép hiển thị pending là “Chờ admin duyệt” + badge xanh
			if (!statusText || statusText === 'Chờ xác nhận') {
				statusText = 'Chờ admin duyệt';
				statusClass = 'status-pending';
			}

			return '' +
				'<tr>' +
					'<td style="padding:12px; border-bottom:1px solid #f3f4f6;">#' + (o.id || '') + '</td>' +
					'<td style="padding:12px; border-bottom:1px solid #f3f4f6;">' + dateStr + '</td>' +
					'<td style="padding:12px; border-bottom:1px solid #f3f4f6;">' + formatCurrency(o.total || 0) + '</td>' +
					'<td style="padding:12px; border-bottom:1px solid #f3f4f6;"><span class="status-badge ' + statusClass + '">' + statusText + '</span></td>' +
					'<td style="padding:12px; border-bottom:1px solid #f3f4f6;">' +
						'<a class="btn btn-secondary" href="detail.php?id=' + (o.id || '') + '" style="margin-right:8px;">Xem</a>' +
						(o.status === 'completed' ? '<button class="btn btn-secondary" data-action="delete" data-id="' + (o.id || '') + '" style="background:#ef4444; color:#fff; border:none; padding:6px 8px;" title="Xóa đơn hàng">🗑️</button>' : '') +
					'</td>' +
				'</tr>';
		}).join('');

		tbody.innerHTML = rows;
	}

	function loadOrders() {
		fetch('../../../backend/orders_list.php', { cache: 'no-store' })
			.then(function (r) { return r.json(); })
			.then(function (data) { renderOrders((data && data.orders) || []); })
			.catch(function () {
				tbody.innerHTML = '<tr><td colspan="5" style="padding:12px; border-bottom:1px solid #f3f4f6;">Không tải được dữ liệu đơn hàng.</td></tr>';
			});
	}

	// Event listener for delete button
	tbody.addEventListener('click', function(e) {
		if (e.target.hasAttribute('data-action') && e.target.getAttribute('data-action') === 'delete') {
			var orderId = e.target.getAttribute('data-id');
			deleteUserOrder(orderId);
		}
	});

	function deleteUserOrder(orderId) {
		if (!confirm('⚠️ Bạn có chắc chắn muốn xóa đơn hàng #' + orderId + '?\n\nHành động này không thể hoàn tác!')) {
			return;
		}
		
		fetch('../../../backend/user_order_delete.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ order_id: Number(orderId) })
		})
		.then(function(r) { return r.json(); })
		.then(function(res) {
			if (res && res.ok) {
				alert('✅ Xóa đơn hàng thành công!');
				loadOrders();
			} else {
				alert('❌ Xóa thất bại: ' + (res.error || 'Lỗi không xác định'));
			}
		})
		.catch(function(err) {
			console.error('❌ Delete error:', err);
			alert('❌ Không thể xóa đơn hàng');
		});
	}

	loadOrders();
	setInterval(loadOrders, 15000);
})();
</script>

<?php include '../../includes/footer.php'; ?>
