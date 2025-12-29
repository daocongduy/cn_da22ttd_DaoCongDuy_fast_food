<?php 
include '../includes/header.php';
?>

<section class="container" style="padding:32px 24px;">
	<!-- Page Header -->
	<div class="menu-header" style="text-align:center; margin-bottom:40px;">
		<h1 style="font-size:2.5em; font-weight:700; color:#1f2937; margin:0 0 12px 0; display:flex; align-items:center; justify-content:center; gap:12px;">
			<span style="font-size:1.2em;">🍔</span>
			<span>Thực đơn</span>
		</h1>
		<p style="color:#6b7280; font-size:1.1em; margin:0 0 32px 0;">Khám phá các món ăn ngon và đặt hàng ngay</p>
		
		<!-- Search Box -->
		<div class="search-container" style="max-width:600px; margin:0 auto;">
			<div class="search-box" style="position:relative; background:white; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden; border:2px solid transparent; transition:all 0.3s ease;">
				<div style="display:flex; align-items:center; padding:4px 4px 4px 20px;">
					<span style="font-size:1.5em; color:#6b7280; margin-right:12px;">🔍</span>
					<input 
						type="text" 
						id="search-input" 
						placeholder="Tìm kiếm món ăn (burger, pizza, gà rán...)" 
						style="flex:1; padding:14px 8px; border:none; font-size:16px; background:transparent; outline:none; color:#1f2937;"
					>
					<button 
						id="clear-search" 
						style="background:linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border:none; color:white; cursor:pointer; font-size:18px; display:none; padding:10px 16px; border-radius:12px; transition:all 0.3s ease; font-weight:600; min-width:44px; height:44px; display:flex; align-items:center; justify-content:center;"
						title="Xóa tìm kiếm"
					>✕</button>
				</div>
			</div>
			
			<!-- Quick Search Tags -->
			<div class="quick-search-tags" style="display:flex; gap:8px; margin-top:16px; flex-wrap:wrap; justify-content:center;">
				<button class="quick-tag" data-keyword="burger" style="padding:8px 16px; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:20px; font-size:0.875em; color:#374151; cursor:pointer; transition:all 0.2s ease; font-weight:500;">
					🍔 Burger
				</button>
				<button class="quick-tag" data-keyword="pizza" style="padding:8px 16px; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:20px; font-size:0.875em; color:#374151; cursor:pointer; transition:all 0.2s ease; font-weight:500;">
					🍕 Pizza
				</button>
				<button class="quick-tag" data-keyword="gà" style="padding:8px 16px; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:20px; font-size:0.875em; color:#374151; cursor:pointer; transition:all 0.2s ease; font-weight:500;">
					🍗 Gà rán
				</button>
				<button class="quick-tag" data-keyword="coca" style="padding:8px 16px; background:#f3f4f6; border:1px solid #e5e7eb; border-radius:20px; font-size:0.875em; color:#374151; cursor:pointer; transition:all 0.2s ease; font-weight:500;">
					🥤 Nước uống
				</button>
			</div>
		</div>
	</div>
	
	<!-- Kết quả tìm kiếm -->
	<div id="search-result" style="display:none; margin-bottom:24px; padding:16px 24px; background:linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left:4px solid #3b82f6; border-radius:12px; box-shadow:0 4px 12px rgba(59, 130, 246, 0.1);">
		<div style="display:flex; align-items:center; gap:12px;">
			<span style="font-size:1.5em;">🎯</span>
			<span id="result-text" style="color:#1e40af; font-weight:600; font-size:1.05em;"></span>
		</div>
	</div>
	
	<!-- Danh sách sản phẩm -->
	<div class="products" id="menu-products" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; max-width: 900px; margin: 0 auto;"></div>
	
	<!-- Thông báo không tìm thấy -->
	<div id="no-results" style="display:none; text-align:center; padding:80px 20px; background:white; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08);">
		<div style="font-size:5em; margin-bottom:20px; opacity:0.5;">🔍</div>
		<h3 style="color:#374151; margin-bottom:12px; font-size:1.5em; font-weight:600;">Không tìm thấy món ăn</h3>
		<p style="color:#6b7280; font-size:1.05em; margin-bottom:24px;">Thử tìm kiếm với từ khóa khác hoặc xem tất cả món ăn</p>
		<button id="show-all-btn" style="background:linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color:white; border:none; padding:12px 32px; border-radius:12px; font-size:1em; font-weight:600; cursor:pointer; transition:all 0.3s ease; box-shadow:0 4px 12px rgba(59, 130, 246, 0.3);">
			<span>📋</span>
			<span>Xem tất cả món ăn</span>
		</button>
	</div>
</section>

<script>
(function(){
	var container = document.getElementById('menu-products');
	var searchInput = document.getElementById('search-input');
	var clearBtn = document.getElementById('clear-search');
	var searchResult = document.getElementById('search-result');
	var resultText = document.getElementById('result-text');
	var noResults = document.getElementById('no-results');
	var allProducts = []; // Lưu tất cả sản phẩm
	
	if (!container) return;

	function price(v){ try { return new Intl.NumberFormat('vi-VN').format(v) + 'đ'; } catch(e){ return v + 'đ'; } }

	function render(list){
		// Ẩn/hiện thông báo không tìm thấy
		if (!Array.isArray(list) || list.length === 0) {
			container.style.display = 'none';
			noResults.style.display = 'block';
			return;
		}
		
		container.style.display = 'grid';
		noResults.style.display = 'none';
		
		container.innerHTML = list.map(function(p, index){
			var img = p.image_url ? 
				(p.image_url.startsWith('http') ? p.image_url : '../assets/images/' + p.image_url) : '';
			var rating = parseFloat(p.average_rating) || 0;
			var totalReviews = parseInt(p.total_reviews) || 0;
			var name = (p.name || '').replace(/'/g, '&#39;');
			var description = p.description || '';
			
			// Generate stars
			var stars = '';
			for (var i = 0; i < 5; i++) {
				stars += i < Math.floor(rating) ? '⭐' : '☆';
			}

			// Rating text
			var ratingText;
			if (totalReviews > 0) {
				ratingText = rating.toFixed(1) + ' / 5 (' + totalReviews + ' đánh giá)';
			} else {
				ratingText = 'Chưa có đánh giá';
			}
			
			return '\
				<div class="product-card" style="\
					background: white; \
					border-radius: 12px; \
					overflow: hidden; \
					box-shadow: 0 4px 12px rgba(0,0,0,0.1); \
					transition: all 0.3s ease;\
					border: 1px solid #e5e7eb;\
					position: relative;\
					max-width: 280px;\
					margin: 0 auto;\
					animation: fadeInUp 0.4s ease ' + (index * 0.1) + 's both;\
				">\
					<!-- Product Image -->\
					<div class="product-image" style="\
						height: 200px; \
						background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);\
						position: relative;\
						overflow: hidden;\
						display: flex;\
						align-items: center;\
						justify-content: center;\
					">\
						' + (img ? '\
							<img src="' + img + '" alt="' + name + '" style="\
								width: 100%;\
								height: 100%;\
								object-fit: cover;\
								object-position: center;\
							">\
						' : '\
							<div style="\
								width: 100%;\
								height: 100%;\
								display: flex;\
								align-items: center;\
								justify-content: center;\
								font-size: 4em; \
								color: #cbd5e1; \
								opacity: 0.6;\
							">🍽️</div>\
						') + '\
					</div>\
					\
					<!-- Product Info -->\
					<div style="padding: 16px 20px 20px;">\
						<!-- Product Name -->\
						<h3 style="\
							font-size: 1.1em; \
							font-weight: 700; \
							color: #1f2937; \
							margin-bottom: 8px; \
							line-height: 1.3;\
							text-align: center;\
							text-transform: uppercase;\
							letter-spacing: 0.5px;\
							min-height: 2.2em;\
							display: flex;\
							align-items: center;\
							justify-content: center;\
						">' + name + '</h3>\
						\
						<!-- Rating -->\
						<div style="\
							text-align: center; \
							margin-bottom: 10px; \
						">\
							<div style="\
								font-size: 1.1em; \
								margin-bottom: 4px; \
								color: #f59e0b; \
								letter-spacing: 1px; \
							">' + stars + '</div>\
							<div style="\
								font-size: 0.85em; \
								color: #6b7280; \
								font-weight: 500; \
							">' + ratingText + '</div>\
						</div>\
						\
						<!-- Description -->\
						<p style="\
							color: #6b7280; \
							font-size: 0.85em; \
							line-height: 1.4; \
							margin-bottom: 12px;\
							text-align: center;\
							min-height: 2.8em;\
							display: -webkit-box;\
							-webkit-line-clamp: 2;\
							-webkit-box-orient: vertical;\
							overflow: hidden;\
						">' + (description || 'Sản phẩm chất lượng cao') + '</p>\
						\
						<!-- Price -->\
						<div style="\
							text-align: center;\
							margin-bottom: 16px;\
						">\
							<div style="\
								font-size: 1.3em; \
								font-weight: 800; \
								color: #dc2626;\
							">' + price(p.price || 0) + '</div>\
						</div>\
						\
						<!-- Action Buttons -->\
						<div style="display: flex; gap: 8px; justify-content: center;">\
							<button onclick="addToCart(' + p.id + ', \x27' + name + '\x27, ' + p.price + ')" style="\
								background: #f3f4f6; \
								color: #374151; \
								border: 1px solid #e5e7eb; \
								padding: 10px 14px; \
								border-radius: 6px; \
								font-weight: 600; \
								cursor: pointer; \
								transition: all 0.3s ease;\
								font-size: 1.1em;\
							" title="Thêm vào giỏ hàng">\
								🛒\
							</button>\
							<button onclick="viewProductDetail(' + p.id + ')" style="\
								background: #ff6a00; \
								color: white; \
								border: none; \
								padding: 10px 20px; \
								border-radius: 6px; \
								font-weight: 600; \
								cursor: pointer; \
								transition: all 0.3s ease;\
								font-size: 0.85em;\
								text-transform: uppercase;\
								letter-spacing: 0.5px;\
							">\
								MUA NGAY\
							</button>\
						</div>\
					</div>\
				</div>\
			';
		}).join('');
	}
	
	// Biến lưu role của user
	var userRole = null;
	
	// Lấy thông tin user để biết role
	fetch('../../backend/auth_me.php', { cache: 'no-store' })
		.then(function(r) { return r.json(); })
		.then(function(auth) {
			userRole = auth.role || null;
		})
		.catch(function() {});
	
	// Add to cart function
	window.addToCart = function(id, name, price) {
		// Kiểm tra nếu là admin
		if (userRole === 'admin') {
			showNotification('👑 Admin không cần dùng giỏ hàng! Vui lòng quản lý đơn hàng trong Dashboard.', 'info');
			return;
		}
		
		// Kiểm tra nếu Cart tồn tại
		if (typeof window.Cart === 'undefined' || !window.Cart.add) {
			showNotification('❌ Vui lòng đăng nhập để thêm vào giỏ hàng!', 'error');
			return;
		}
		
		window.Cart.add({
			product_id: id,
			name: name,
			price: price,
			quantity: 1
		});
		// Chuyển thẳng đến giỏ hàng
		window.location.href = 'cart.php';
	};
	
	// Notification với nút đến giỏ hàng
	function showNotificationWithAction(message, type) {
		var notification = document.createElement('div');
		notification.style.cssText = 'position:fixed;top:20px;right:20px;padding:16px 20px;border-radius:12px;color:white;font-weight:600;z-index:1000;animation:slideIn 0.3s ease;max-width:400px;box-shadow:0 8px 24px rgba(0,0,0,0.2);';
		notification.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
		
		notification.innerHTML = '<div style="margin-bottom:12px;">' + message + '</div>' +
			'<div style="display:flex;gap:8px;">' +
				'<button onclick="window.location.href=\'cart.php\'" style="flex:1;padding:8px 16px;background:white;color:#059669;border:none;border-radius:6px;font-weight:600;cursor:pointer;">🛒 Xem giỏ hàng</button>' +
				'<button onclick="this.parentElement.parentElement.remove()" style="padding:8px 12px;background:rgba(255,255,255,0.2);color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">✕</button>' +
			'</div>';
		
		document.body.appendChild(notification);
		
		setTimeout(function() {
			if (document.body.contains(notification)) {
				notification.style.animation = 'slideOut 0.3s ease';
				setTimeout(function() {
					if (document.body.contains(notification)) {
						document.body.removeChild(notification);
					}
				}, 300);
			}
		}, 5000);
	}

	// Xem chi tiết sản phẩm + đánh giá
	window.viewProductDetail = function(id) {
		// Kiểm tra nếu Cart tồn tại (user đã đăng nhập và không phải admin)
		if (typeof window.Cart !== 'undefined' && window.Cart.add) {
			window.location.href = 'product_detail.php?id=' + id;
		} else {
			// Admin hoặc chưa đăng nhập - vẫn cho xem chi tiết
			window.location.href = 'product_detail.php?id=' + id;
		}
	};

	function searchProducts(keyword) {
		keyword = keyword.toLowerCase().trim();
		
		if (!keyword) {
			// Nếu không có từ khóa, hiển thị tất cả
			searchResult.style.display = 'none';
			clearBtn.style.display = 'none';
			render(allProducts);
			return;
		}
		
		// Hiển thị nút xóa
		clearBtn.style.display = 'block';
		
		// Tìm kiếm theo tên và mô tả
		var filtered = allProducts.filter(function(p) {
			var name = (p.name || '').toLowerCase();
			var desc = (p.description || '').toLowerCase();
			return name.includes(keyword) || desc.includes(keyword);
		});
		
		// Hiển thị kết quả tìm kiếm
		if (filtered.length > 0) {
			searchResult.style.display = 'block';
			resultText.textContent = '🎯 Tìm thấy ' + filtered.length + ' món ăn phù hợp với "' + keyword + '"';
		} else {
			searchResult.style.display = 'none';
		}
		
		render(filtered);
	}

	function showNotification(message, type) {
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
		} else if (type === 'info') {
			notification.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
		} else {
			notification.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
		}
		
		notification.textContent = message;
		document.body.appendChild(notification);
		
		setTimeout(function() {
			notification.style.animation = 'slideOut 0.3s ease';
			setTimeout(function() {
				if (document.body.contains(notification)) {
					document.body.removeChild(notification);
				}
			}, 300);
		}, 3000);
	}

	// Event listeners
	searchInput.addEventListener('input', function(e) {
		searchProducts(e.target.value);
	});

	searchInput.addEventListener('keypress', function(e) {
		if (e.key === 'Enter') {
			searchProducts(e.target.value);
		}
	});

	clearBtn.addEventListener('click', function() {
		searchInput.value = '';
		searchProducts('');
		searchInput.focus();
	});

	// Quick search tags
	document.querySelectorAll('.quick-tag').forEach(function(tag) {
		tag.addEventListener('click', function() {
			var keyword = this.getAttribute('data-keyword');
			searchInput.value = keyword;
			searchProducts(keyword);
			searchInput.focus();
		});
	});

	// Show all button
	var showAllBtn = document.getElementById('show-all-btn');
	if (showAllBtn) {
		showAllBtn.addEventListener('click', function() {
			searchInput.value = '';
			searchProducts('');
			searchInput.focus();
		});
	}

	// Load dữ liệu
	fetch('../../backend/products_list.php', { cache: 'no-store' })
		.then(function(r){ return r.json(); })
		.then(function(data){ 
			allProducts = (data && data.products) || [];
			render(allProducts); 
		})
		.catch(function(){ 
			container.innerHTML = '<div style="padding:16px; text-align:center; color:#ef4444;">❌ Không tải được sản phẩm.</div>'; 
		});
})();
</script>

<style>
/* Menu Header Styles */
.menu-header {
	animation: fadeInDown 0.6s ease;
}

@keyframes fadeInDown {
	from {
		opacity: 0;
		transform: translateY(-20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* Search Box Styles */
.search-box {
	transition: all 0.3s ease;
}

.search-box:hover {
	border-color: #3b82f6 !important;
	box-shadow: 0 12px 40px rgba(59, 130, 246, 0.15) !important;
	transform: translateY(-2px);
}

.search-box:focus-within {
	border-color: #3b82f6 !important;
	box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1), 0 12px 40px rgba(59, 130, 246, 0.2) !important;
	transform: translateY(-2px);
}

#search-input::placeholder {
	color: #9ca3af;
}

#clear-search:hover {
	background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
	transform: scale(1.05);
	box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

#clear-search:active {
	transform: scale(0.95);
}

/* Quick Search Tags */
.quick-tag:hover {
	background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
	color: white;
	border-color: #3b82f6;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.quick-tag:active {
	transform: translateY(0);
}

/* Show All Button */
#show-all-btn:hover {
	background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
	transform: translateY(-2px);
	box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
}

#show-all-btn:active {
	transform: translateY(0);
}

/* Search Result Animation */
#search-result {
	animation: slideDown 0.3s ease;
}

@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* Product Item Animation */
@keyframes fadeInUp {
	from {
		opacity: 0;
		transform: translateY(20px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

/* No Results Animation */
#no-results {
	animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
	from {
		opacity: 0;
	}
	to {
		opacity: 1;
	}
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

/* Product Card Styles */
.product-card {
	transition: all 0.3s ease;
	display: flex;
	flex-direction: column;
	height: 100%;
	width: 100%;
}

.product-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.product-card button:hover {
	background: #e55a00 !important;
	transform: translateY(-1px);
}

.product-image {
	background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
	height: 200px !important;
	min-height: 200px !important;
	max-height: 200px !important;
}

.product-image img {
	transition: transform 0.3s ease;
	width: 100% !important;
	height: 100% !important;
	object-fit: contain !important;
	object-position: center !important;
	padding: 10px;
}

.product-card:hover .product-image img {
	transform: scale(1.02);
}

/* Đảm bảo tất cả card có cùng chiều cao */
#menu-products {
	align-items: stretch;
}

#menu-products .product-card {
	height: auto;
}

/* Responsive Design */
@media (max-width: 768px) {
	.menu-header h1 {
		font-size: 2em !important;
	}
	
	.menu-header p {
		font-size: 1em !important;
	}
	
	.search-container {
		max-width: 100% !important;
	}
	
	.search-box > div {
		padding: 4px 4px 4px 12px !important;
	}
	
	.search-box span:first-child {
		font-size: 1.2em !important;
		margin-right: 8px !important;
	}
	
	#search-input {
		font-size: 14px !important;
		padding: 12px 8px !important;
	}
	
	#search-input::placeholder {
		font-size: 13px;
	}
	
	#clear-search {
		min-width: 40px !important;
		height: 40px !important;
		font-size: 16px !important;
		padding: 8px 12px !important;
	}
	
	.quick-search-tags {
		gap: 6px !important;
	}
	
	.quick-tag {
		padding: 6px 12px !important;
		font-size: 0.8em !important;
	}
	
	#search-result {
		font-size: 0.9em;
		padding: 12px 16px;
	}
	
	#search-result > div {
		gap: 8px !important;
	}
	
	#search-result span:first-child {
		font-size: 1.2em !important;
	}
	
	#no-results {
		padding: 60px 16px !important;
	}
	
	#no-results > div:first-child {
		font-size: 4em !important;
	}
	
	#no-results h3 {
		font-size: 1.3em !important;
	}
	
	#no-results p {
		font-size: 0.95em !important;
	}
	
	#show-all-btn {
		padding: 10px 24px !important;
		font-size: 0.9em !important;
	}
	
	/* Product grid responsive */
	.products {
		grid-template-columns: 1fr !important;
		gap: 20px !important;
		max-width: 320px !important;
	}
}

@media (max-width: 1024px) and (min-width: 769px) {
	.products {
		grid-template-columns: repeat(2, 1fr) !important;
		max-width: 600px !important;
	}
}

/* Loading state for search */
.search-box.loading::after {
	content: '';
	position: absolute;
	right: 45px;
	top: 50%;
	transform: translateY(-50%);
	width: 16px;
	height: 16px;
	border: 2px solid #e5e7eb;
	border-top: 2px solid #3b82f6;
	border-radius: 50%;
	animation: spin 1s linear infinite;
}

@keyframes spin {
	0% { transform: translateY(-50%) rotate(0deg); }
	100% { transform: translateY(-50%) rotate(360deg); }
}

/* Highlight search results */
.item.highlight {
	animation: pulse 0.5s ease;
}

@keyframes pulse {
	0%, 100% {
		transform: scale(1);
	}
	50% {
		transform: scale(1.02);
		box-shadow: 0 12px 40px rgba(59, 130, 246, 0.2);
	}
}

/* Product link hover effects */
.item a {
	transition: all 0.3s ease;
	display: block;
	height: 100%;
}

.item a:hover h3 {
	color: #ff6a00 !important;
	transform: translateY(-1px);
}

.item h3 {
	transition: all 0.3s ease;
	cursor: pointer;
}

/* Add click indicator */
.item:hover h3::after {
	content: ' 👁️';
	font-size: 0.8em;
	opacity: 0.7;
	animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
	from { opacity: 0; }
	to { opacity: 0.7; }
}

/* Review button hover effect */
.btn-secondary:hover {
	background: #e5e7eb !important;
	color: #1f2937 !important;
	border-color: #d1d5db !important;
	transform: translateY(-1px);
	box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Adjust button layout */
.item .btn {
	font-size: 0.9em;
	padding: 10px 12px;
}
</style>

<?php 
include '../includes/footer.php';
?>

