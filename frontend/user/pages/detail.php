<?php include '../../includes/header.php'; ?>

<section class="container" style="padding:24px;">
	<h2 style="margin-bottom:16px;">Chi tiết đơn hàng</h2>
	<div class="item" id="order-info" style="margin-bottom:16px;"></div>
	<div class="item" style="overflow:auto; margin-bottom:16px;">
		<table style="width:100%; border-collapse: collapse;">
			<thead>
				<tr style="text-align:left;">
					<th style="padding:12px; border-bottom:1px solid #eee;">Món</th>
					<th style="padding:12px; border-bottom:1px solid #eee;">SL</th>
					<th style="padding:12px; border-bottom:1px solid #eee;">Đơn giá</th>
					<th style="padding:12px; border-bottom:1px solid #eee;">Thành tiền</th>
				</tr>
			</thead>
			<tbody id="items-body"></tbody>
		</table>
	</div>
	<div class="item" id="history" style="overflow:auto;"></div>
</section>

<?php include '../../includes/footer.php'; ?>

<script>
(function(){
	function price(v){ try { return new Intl.NumberFormat('vi-VN').format(v) + 'đ'; } catch(e){ return v + 'đ'; } }
	var q = new URLSearchParams(window.location.search);
	var id = q.get('id');
	if(!id){ document.getElementById('order-info').textContent = 'Thiếu mã đơn'; return; }

	fetch('../../../backend/orders_detail.php?id=' + encodeURIComponent(id), {cache:'no-store'})
		.then(function(r){ return r.json(); })
		.then(function(d){
			if (!d || !d.order){ document.getElementById('order-info').textContent = 'Không tìm thấy đơn'; return; }
			var o = d.order;
			document.getElementById('order-info').innerHTML = '<div><strong>Mã đơn:</strong> #' + o.id + '</div>'
				+ '<div><strong>Trạng thái:</strong> ' + o.status + '</div>'
				+ '<div><strong>Tổng tiền:</strong> ' + price(o.total_amount) + '</div>'
				+ '<div><strong>Tên:</strong> ' + (o.shipping_name||'') + ' · <strong>Điện thoại:</strong> ' + (o.shipping_phone||'') + '</div>'
				+ '<div><strong>Địa chỉ:</strong> ' + (o.shipping_address||'') + '</div>';

			var rows = (d.items||[]).map(function(it){
				return '<tr>'
					+ '<td style="padding:12px; border-bottom:1px solid #f3f4f6;">' + (it.name||('SP #'+it.product_id)) + '</td>'
					+ '<td style="padding:12px; border-bottom:1px solid #f3f4f6;">' + it.quantity + '</td>'
					+ '<td style="padding:12px; border-bottom:1px solid #f3f4f6;">' + price(it.unit_price) + '</td>'
					+ '<td style="padding:12px; border-bottom:1px solid #f3f4f6;">' + price(it.total_price) + '</td>'
				+ '</tr>';
			}).join('');
			document.getElementById('items-body').innerHTML = rows || '<tr><td colspan="4" style="padding:12px;">Không có món.</td></tr>';

			var hist = (d.history||[]).map(function(h){ return '<div style="padding:8px 0; border-bottom:1px solid #f3f4f6;"><strong>'+h.status+'</strong> · <span>'+h.created_at+'</span> · '+(h.note||'')+'</div>'; }).join('');
			document.getElementById('history').innerHTML = '<h3>Lịch sử trạng thái</h3>' + (hist || '<div style="padding:12px;">Chưa có lịch sử.</div>');
		});
})();
</script>

