<?php include '../../includes/header.php'; ?>

<section class="container" style="padding:24px;">
	<!-- Header Section -->
	<div style="text-align:center; margin-bottom:32px;">
		<h1 style="color:#1f2937; font-size:2.5em; margin-bottom:8px;">🚀 Đặt hàng</h1>
		<p style="color:#6b7280; font-size:1.1em;">Hoàn tất thông tin để đặt hàng</p>
	</div>

	<!-- Main Content -->
	<div class="checkout-container" style="display:grid; grid-template-columns: 2fr 1fr; gap:24px; max-width:1200px; margin:0 auto;">
		<!-- Delivery Information Section -->
		<div class="delivery-section" style="background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden;">
			<div class="section-header" style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; padding:20px 24px;">
				<div style="display:flex; align-items:center; gap:12px;">
					<div style="font-size:1.5em;">📋</div>
					<div>
						<h3 style="margin:0; font-size:1.3em;">THÔNG TIN GIAO HÀNG</h3>
						<p style="margin:4px 0 0 0; color:white; font-weight:600; font-size:0.9em;">Nhập thông tin để giao hàng</p>
					</div>
				</div>
			</div>
			
			<form class="checkout-form" id="checkout-form" style="padding:24px;">
				<div class="form-group" style="margin-bottom:20px;">
					<label for="fullname" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">👤 Họ và tên</label>
					<input id="fullname" type="text" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Nhập họ và tên của bạn">
				</div>
				<div class="form-group" style="margin-bottom:20px;">
					<label for="phone" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">📞 Số điện thoại</label>
					<input id="phone" type="tel" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Nhập số điện thoại liên hệ">
				</div>
				<div class="form-group" style="margin-bottom:24px;">
					<label for="address" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">📍 Địa chỉ giao hàng</label>
					<textarea id="address" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb; resize:vertical; min-height:80px;" placeholder="Nhập địa chỉ giao hàng chi tiết"></textarea>
				</div>
				<button class="checkout-btn" type="submit" style="width:100%; background:linear-gradient(135deg, #ff6a00 0%, #e55a00 100%); color:white; padding:16px 24px; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; transition:all 0.3s ease; display:flex; align-items:center; justify-content:center; gap:12px; box-shadow:0 4px 12px rgba(255, 106, 0, 0.3);">
					<span>🚀</span>
					<span>Xác nhận đặt hàng</span>
				</button>
			</form>
		</div>

		<!-- Order Summary Section -->
		<div class="summary-section" style="background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden; height:fit-content;">
			<div class="section-header" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; padding:20px 24px;">
				<div style="display:flex; align-items:center; gap:12px;">
					<div style="font-size:1.5em;">📦</div>
					<div>
						<h3 style="margin:0; font-size:1.3em;">TÓM TẮT ĐƠN HÀNG</h3>
						<p style="margin:4px 0 0 0; color:white; font-weight:600; font-size:0.9em;">Chi tiết đơn hàng của bạn</p>
					</div>
				</div>
			</div>
			
			<div style="padding:24px;">
				<div id="order-summary" style="margin-bottom:20px;">
					<!-- Order items will be rendered here -->
				</div>
				
				<div class="total-section" style="border-top:2px solid #f1f5f9; padding-top:20px;">
					<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
						<span style="font-size:1.1em; font-weight:600; color:#374151;">Tổng cộng:</span>
						<span id="total-amount" style="font-size:1.5em; font-weight:700; color:#dc2626;"></span>
					</div>
					<div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px; text-align:center;">
						<div style="color:#059669; font-size:0.9em; font-weight:600;">✅ Miễn phí giao hàng</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include '../../includes/footer.php'; ?>

<script>
(function(){
	function price(v){ try { return new Intl.NumberFormat('vi-VN').format(v) + 'đ'; } catch(e){ return v + 'đ'; } }

	// Kiểm tra đăng nhập trước khi cho đặt hàng
	fetch('../../../backend/auth_me.php', {cache:'no-store'})
		.then(function(r){ return r.json(); })
		.then(function(auth){ if (!auth || !auth.user_id) { alert('Bạn cần đăng nhập trước khi đặt hàng.'); window.location.href='../../pages/login.php'; } });

	function renderSummary(){
		var summaryDiv = document.getElementById('order-summary');
		var totalAmountEl = document.getElementById('total-amount');
		var items = (window.Cart && window.Cart.read()) || [];
		
		var html = '';
		var total = 0;
		
		if (!items.length) {
			summaryDiv.innerHTML = '<div style="text-align:center; padding:20px; color:#6b7280;"><div style="font-size:2em; margin-bottom:8px;">🛒</div><p>Giỏ hàng trống</p></div>';
			totalAmountEl.textContent = price(0);
			return;
		}
		
		items.forEach(function(it, index){
			var line = (it.quantity||1) * (it.price||0);
			total += line;
			html += '<div style="display:flex; justify-content:space-between; align-items:center; padding:12px; background:#f8fafc; border-radius:8px; margin-bottom:8px; border-left:4px solid #3b82f6;">'
				+ '<div><div style="font-weight:600; color:#1f2937;">' + (it.name || ('SP #'+it.product_id)) + '</div><div style="font-size:0.9em; color:#6b7280;">Số lượng: x' + (it.quantity||1) + '</div></div>'
				+ '<div style="font-weight:700; color:#dc2626; font-size:1.1em;">' + price(line) + '</div>'
				+ '</div>';
		});
		
		summaryDiv.innerHTML = html;
		totalAmountEl.textContent = price(total);
	}

	document.addEventListener('DOMContentLoaded', function(){ renderSummary(); });

	var form = document.getElementById('checkout-form');
	if (!form) return;

	form.addEventListener('submit', function(e){
		e.preventDefault();
		var name = document.getElementById('fullname').value.trim();
		var phone = document.getElementById('phone').value.trim();
		var address = document.getElementById('address').value.trim();

		// Validation
		if (!name || !phone || !address) {
			showNotification('Vui lòng điền đầy đủ thông tin!', 'error');
			return;
		}

		// Validate phone number format
		var phoneRegex = /^[0-9]{10,11}$/;
		if (!phoneRegex.test(phone.replace(/\s+/g, ''))) {
			showNotification('Số điện thoại không hợp lệ! Vui lòng nhập 10-11 chữ số.', 'error');
			return;
		}

		// Validate name length
		if (name.length < 2) {
			showNotification('Tên phải có ít nhất 2 ký tự!', 'error');
			return;
		}

		// Validate address length
		if (address.length < 10) {
			showNotification('Địa chỉ quá ngắn! Vui lòng nhập địa chỉ chi tiết.', 'error');
			return;
		}

		var items = (window.Cart && window.Cart.read()) || [];
		if (!items.length) { 
			showNotification('Giỏ hàng đang trống!', 'error');
			return; 
		}

		// Validate cart items
		var hasInvalidItems = items.some(function(item) {
			return !item.product_id || !item.quantity || item.quantity <= 0 || !item.price;
		});
		
		if (hasInvalidItems) {
			showNotification('Giỏ hàng có dữ liệu không hợp lệ! Vui lòng làm mới trang.', 'error');
			return;
		}

		// Show loading state
		var submitBtn = form.querySelector('.checkout-btn');
		var originalText = submitBtn.innerHTML;
		submitBtn.innerHTML = '<span>⏳</span><span>Đang xử lý...</span>';
		submitBtn.disabled = true;
		submitBtn.style.opacity = '0.7';

		// Validate product IDs trước khi submit
		fetch('../../../backend/products_list.php', { cache: 'no-store' })
			.then(function(r){ return r.json(); })
			.then(function(p){
				var validIds = new Set(((p && p.products) || []).map(function(x){ return Number(x.id); }));
				var validItems = items.filter(function(it){ return validIds.has(Number(it.product_id)); });
				
				if (validItems.length !== items.length) {
					showNotification('Một số món trong giỏ đã bị xóa. Vui lòng kiểm tra lại giỏ hàng!', 'error');
					resetButtonState();
					return;
				}
				
				if (!validItems.length) {
					showNotification('Giỏ hàng trống hoặc tất cả sản phẩm đã bị xóa!', 'error');
					resetButtonState();
					return;
				}
				
				// Submit order với valid items
				submitOrder(validItems);
			})
			.catch(function(err){ 
				console.warn('Không thể validate sản phẩm:', err);
				// Nếu không validate được, vẫn thử submit
				submitOrder(items); 
			});

		function resetButtonState() {
			submitBtn.innerHTML = originalText;
			submitBtn.disabled = false;
			submitBtn.style.opacity = '1';
		}

		function submitOrder(items){
			var payload = { 
				shipping: { name: name, phone: phone, address: address }, 
				items: items.map(function(it){ return { product_id: it.product_id, quantity: it.quantity }; }) 
			};

			fetch('../../../backend/orders_create.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(payload)
			}).then(function(r){ 
				if (!r.ok) {
					throw new Error('HTTP ' + r.status + ': ' + r.statusText);
				}
				return r.json(); 
			})
			.then(function(res){
				if (res && res.ok) {
					showNotification('🎉 Đặt hàng thành công! Mã đơn #' + res.order_id, 'success');
					if (window.Cart) window.Cart.clear();
					setTimeout(function() {
						window.location.href = 'order_status.php';
					}, 2000);
				} else {
					var errorMsg = (res && res.error) ? res.error : 'Có lỗi xảy ra khi đặt hàng';
					showNotification('❌ Đặt hàng thất bại: ' + errorMsg, 'error');
					resetButtonState();
				}
			}).catch(function(err){ 
				console.error('Checkout error:', err);
				showNotification('❌ Không gửi được đơn hàng! Vui lòng thử lại.', 'error');
				resetButtonState();
			});
		}
	});

	function showNotification(message, type) {
		// Create notification element
		var notification = document.createElement('div');
		notification.style.cssText = `
			position: fixed;
			top: 20px;
			right: 20px;
			padding: 16px 20px;
			border-radius: 8px;
			color: white;
			font-weight: 600;
			z-index: 1000;
			animation: slideIn 0.3s ease;
			max-width: 400px;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15);
		`;
		
		if (type === 'success') {
			notification.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
		} else {
			notification.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
		}
		
		notification.textContent = message;
		document.body.appendChild(notification);
		
		setTimeout(function() {
			notification.style.animation = 'slideOut 0.3s ease';
			setTimeout(function() {
				document.body.removeChild(notification);
			}, 300);
		}, 3000);
	}
})();
</script>

<style>
/* Form Input Focus Effects */
input:focus, textarea:focus {
	outline: none !important;
	border-color: #3b82f6 !important;
	box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
	background: white !important;
}

/* Button Hover Effects */
.checkout-btn:hover {
	background: linear-gradient(135deg, #e55a00 0%, #cc4a00 100%) !important;
	transform: translateY(-2px);
	box-shadow: 0 8px 20px rgba(255, 106, 0, 0.4) !important;
}

.checkout-btn:active {
	transform: translateY(0);
}

.checkout-btn:disabled {
	opacity: 0.7 !important;
	cursor: not-allowed !important;
	transform: none !important;
}

/* Container Animations */
.delivery-section, .summary-section {
	transition: all 0.3s ease;
}

.delivery-section:hover, .summary-section:hover {
	transform: translateY(-2px);
	box-shadow: 0 12px 40px rgba(0,0,0,0.12) !important;
}

/* Notification Animations */
@keyframes slideIn {
	from {
		transform: translateX(100%);
		opacity: 0;
	}
	to {
		transform: translateX(0);
		opacity: 1;
	}
}

@keyframes slideOut {
	from {
		transform: translateX(0);
		opacity: 1;
	}
	to {
		transform: translateX(100%);
		opacity: 0;
	}
}

/* Order Summary Item Animation */
#order-summary > div {
	animation: fadeInUp 0.3s ease;
}

@keyframes fadeInUp {
	from {
		opacity: 0;
		transform: translateY(10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* Responsive Design */
@media (max-width: 768px) {
	.container {
		padding: 16px !important;
	}
	
	h1 {
		font-size: 2em !important;
	}
	
	.checkout-container {
		grid-template-columns: 1fr !important;
		gap: 16px !important;
	}
	
	.delivery-section, .summary-section {
		border-radius: 12px !important;
	}
	
	.section-header {
		padding: 16px 20px !important;
	}
	
	.section-header h3 {
		font-size: 1.1em !important;
	}
	
	.checkout-form {
		padding: 20px 16px !important;
	}
	
	.form-group {
		margin-bottom: 16px !important;
	}
	
	input, textarea {
		padding: 10px 12px !important;
		font-size: 14px !important;
	}
	
	.checkout-btn {
		padding: 14px 20px !important;
		font-size: 16px !important;
	}
}

@media (max-width: 480px) {
	.checkout-container {
		margin: 0 -8px !important;
	}
	
	.delivery-section, .summary-section {
		margin: 0 8px !important;
		border-radius: 8px !important;
	}
	
	.section-header {
		padding: 12px 16px !important;
	}
	
	.checkout-form {
		padding: 16px 12px !important;
	}
}

/* Loading Animation */
.checkout-btn.loading {
	position: relative;
	overflow: hidden;
}

.checkout-btn.loading::after {
	content: '';
	position: absolute;
	top: 50%;
	left: 50%;
	width: 20px;
	height: 20px;
	margin: -10px 0 0 -10px;
	border: 2px solid rgba(255,255,255,0.3);
	border-top: 2px solid white;
	border-radius: 50%;
	animation: spin 1s linear infinite;
}

@keyframes spin {
	0% { transform: rotate(0deg); }
	100% { transform: rotate(360deg); }
}

/* Form Validation Styles */
.form-group.error input,
.form-group.error textarea {
	border-color: #ef4444 !important;
	box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
}

.form-group.success input,
.form-group.success textarea {
	border-color: #10b981 !important;
	box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
}

/* Free shipping badge animation */
.total-section > div:last-child {
	animation: pulse 2s infinite;
}

@keyframes pulse {
	0% { transform: scale(1); }
	50% { transform: scale(1.05); }
	100% { transform: scale(1); }
}
</style>


