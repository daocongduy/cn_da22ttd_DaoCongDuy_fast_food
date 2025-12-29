<?php
// includes/header.php

$pathPrefix = '';
if (isset($_SERVER['PHP_SELF'])) {
    $self = $_SERVER['PHP_SELF'];
    if (strpos($self, '/user/pages/') !== false || strpos($self, '/admin/pages/') !== false) {
        $pathPrefix = '../../';
    } elseif (strpos($self, '/pages/') !== false) {
        $pathPrefix = '../';
    } else {
        $pathPrefix = '';
    }
}

$backendPrefix = $pathPrefix . '../';

// Detect logo
$logoUrl = '';
$logoCandidates = ['logo.svg', 'logo.png', 'logo.jpg', 'logo.jpeg', 'brand.svg', 'brand.png'];
foreach ($logoCandidates as $candidate) {
    $relativeFsPath = __DIR__ . '/../assets/images/' . $candidate;
    if (file_exists($relativeFsPath)) {
        $logoUrl = $pathPrefix . 'assets/images/' . $candidate;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fast Food</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $pathPrefix; ?>assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body<?php if (basename($_SERVER['PHP_SELF']) === 'index.php' && strpos($_SERVER['PHP_SELF'], '/pages/') === false) echo ' class="home-page"'; ?>>

<header class="navbar">
	<div class="container">
		<div class="logo">
			<a href="<?php echo $pathPrefix; ?>index.php">
				<?php if ($logoUrl): ?>
					<img src="<?php echo htmlspecialchars($logoUrl); ?>" alt="Fast Food Logo" class="nav-logo">
				<?php else: ?>
					🍔 FAST FOOD
				<?php endif; ?>
			</a>
		</div>
		<nav>
			<ul>
				<li><a href="<?php echo $pathPrefix; ?>index.php">Trang Chủ</a></li>
				<li><a href="<?php echo $pathPrefix; ?>pages/menu.php">Món Ăn</a></li>
				<li id="nav-cart"><a href="<?php echo $pathPrefix; ?>pages/cart.php">Giỏ Hàng</a></li>
				<li class="auth-link" id="nav-order-status" style="display:none;">
					<a href="<?php echo $pathPrefix; ?>user/pages/order_status.php">📋 Đơn hàng</a>
				</li>
				<li class="auth-link" id="nav-dashboard" style="display:none;">
					<a href="<?php echo $pathPrefix; ?>admin/" class="dashboard-link">📊 Dashboard</a>
				</li>
				<li id="nav-contact"><a href="<?php echo $pathPrefix; ?>pages/contact.php">Liên hệ</a></li>
				
				<!-- Nút đăng nhập (khi chưa login) -->
				<li class="auth-link" id="nav-login">
					<a href="<?php echo $pathPrefix; ?>pages/login.php" class="btn btn-secondary">Đăng nhập</a>
				</li>
				
				<!-- User dropdown (khi đã login) -->
				<li class="auth-link" id="nav-user-dropdown" style="display:none;">
					<div class="user-dropdown">
						<button class="user-dropdown-toggle" type="button">
							<div class="user-avatar" id="user-avatar-letter">?</div>
							<span class="user-name" id="user-display-name">User</span>
							<span class="dropdown-arrow">▼</span>
						</button>
						<div class="user-dropdown-menu">
							<div class="dropdown-header">
								<div class="user-avatar-large" id="user-avatar-large">?</div>
								<div class="user-info">
									<div class="user-fullname" id="user-fullname">Người dùng</div>
									<div class="user-email" id="user-email">email@example.com</div>
								</div>
							</div>
							<div class="dropdown-divider"></div>
							<a href="<?php echo $pathPrefix; ?>user/pages/profile.php" class="dropdown-item">
								<span>👤</span> Hồ sơ cá nhân
							</a>
							<a href="<?php echo $pathPrefix; ?>user/pages/order_status.php" class="dropdown-item">
								<span>📦</span> Đơn hàng của tôi
							</a>
							<a href="<?php echo $pathPrefix; ?>user/pages/my_contacts.php" class="dropdown-item">
								<span>📬</span> Tin nhắn của tôi
							</a>
							<div class="dropdown-divider"></div>
							<button class="dropdown-item logout-btn" id="btn-logout" type="button">
								<span>🚪</span> Đăng xuất
							</button>
						</div>
					</div>
				</li>
			</ul>
		</nav>
	</div>
</header>

<style>
/* User Dropdown Styles */
.user-dropdown {
	position: relative;
}

.user-dropdown-toggle {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 6px 12px;
	background: #f3f4f6;
	border: 1px solid #e5e7eb;
	border-radius: 50px;
	cursor: pointer;
	transition: all 0.2s ease;
}

.user-dropdown-toggle:hover {
	background: #e5e7eb;
	border-color: #d1d5db;
}

.user-avatar {
	width: 32px;
	height: 32px;
	border-radius: 50%;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	display: flex;
	align-items: center;
	justify-content: center;
	font-weight: 700;
	font-size: 0.9em;
	text-transform: uppercase;
}

.user-name {
	font-weight: 600;
	font-size: 0.9em;
	color: #374151;
	max-width: 100px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
}

.dropdown-arrow {
	font-size: 0.6em;
	color: #6b7280;
	transition: transform 0.2s ease;
}

.user-dropdown.open .dropdown-arrow {
	transform: rotate(180deg);
}

.user-dropdown-menu {
	position: absolute;
	top: calc(100% + 8px);
	right: 0;
	min-width: 240px;
	background: white;
	border-radius: 12px;
	box-shadow: 0 10px 40px rgba(0,0,0,0.15);
	border: 1px solid #e5e7eb;
	opacity: 0;
	visibility: hidden;
	transform: translateY(-10px);
	transition: all 0.2s ease;
	z-index: 1000;
	overflow: hidden;
}

.user-dropdown.open .user-dropdown-menu {
	opacity: 1;
	visibility: visible;
	transform: translateY(0);
}

.dropdown-header {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 16px;
	background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
}

.user-avatar-large {
	width: 48px;
	height: 48px;
	border-radius: 50%;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	display: flex;
	align-items: center;
	justify-content: center;
	font-weight: 700;
	font-size: 1.2em;
	text-transform: uppercase;
}

.user-info {
	flex: 1;
	min-width: 0;
}

.user-fullname {
	font-weight: 700;
	color: #1f2937;
	font-size: 0.95em;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.user-email {
	font-size: 0.8em;
	color: #6b7280;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.dropdown-divider {
	height: 1px;
	background: #e5e7eb;
	margin: 4px 0;
}

.dropdown-item {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 12px 16px;
	color: #374151;
	text-decoration: none;
	font-size: 0.9em;
	font-weight: 500;
	transition: all 0.2s ease;
	border: none;
	background: none;
	width: 100%;
	cursor: pointer;
	text-align: left;
}

.dropdown-item:hover {
	background: #f3f4f6;
	color: #1f2937;
}

.dropdown-item span {
	font-size: 1.1em;
}

.logout-btn {
	color: #dc2626 !important;
}

.logout-btn:hover {
	background: #fef2f2 !important;
}

/* Responsive */
@media (max-width: 768px) {
	.user-name {
		display: none;
	}
	
	.user-dropdown-toggle {
		padding: 4px;
		background: transparent;
		border: none;
	}
	
	.dropdown-arrow {
		display: none;
	}
	
	.user-dropdown-menu {
		right: -10px;
		min-width: 220px;
	}
}
</style>

<script>
(function(){
	var pathPrefix = '<?php echo $pathPrefix; ?>';
	var backendPrefix = '<?php echo $backendPrefix; ?>';
	
	function applyAuthUI(auth){
		var isLogged = !!auth.user_id;

		// Hiển thị/ẩn các phần tử
		document.getElementById('nav-login').style.display = isLogged ? 'none' : '';
		document.getElementById('nav-user-dropdown').style.display = isLogged ? '' : 'none';
		document.getElementById('nav-dashboard').style.display = (auth.role === 'admin') ? '' : 'none';
		document.getElementById('nav-order-status').style.display = (auth.role === 'user' && isLogged) ? '' : 'none';

		// Ẩn giỏ hàng với admin
		var cartLi = document.getElementById('nav-cart');
		if (cartLi) cartLi.style.display = (auth.role === 'admin') ? 'none' : '';

		// Ẩn liên hệ với admin
		var contactLi = document.getElementById('nav-contact');
		if (contactLi) contactLi.style.display = (auth.role === 'admin') ? 'none' : '';

		if (!isLogged) return;

		// Cập nhật thông tin user
		var name = (auth.name || '').trim();
		var email = (auth.email || '').trim();
		var displayName = name || email || 'Người dùng';
		var firstLetter = displayName.charAt(0).toUpperCase();

		document.getElementById('user-avatar-letter').textContent = firstLetter;
		document.getElementById('user-avatar-large').textContent = firstLetter;
		document.getElementById('user-display-name').textContent = displayName;
		document.getElementById('user-fullname').textContent = displayName;
		document.getElementById('user-email').textContent = email || 'Chưa có email';
	}

	// Load auth info
	fetch(backendPrefix + 'backend/auth_me.php', { cache: 'no-store' })
		.then(function(r){ return r.json(); })
		.then(function(auth){ applyAuthUI(auth || {}); })
		.catch(function(){ });

	// Toggle dropdown
	var dropdown = document.querySelector('.user-dropdown');
	var toggle = document.querySelector('.user-dropdown-toggle');
	
	if (toggle) {
		toggle.addEventListener('click', function(e) {
			e.stopPropagation();
			dropdown.classList.toggle('open');
		});
	}

	// Close dropdown when clicking outside
	document.addEventListener('click', function(e) {
		if (dropdown && !dropdown.contains(e.target)) {
			dropdown.classList.remove('open');
		}
	});

	// Logout
	var logoutBtn = document.getElementById('btn-logout');
	if (logoutBtn) {
		logoutBtn.addEventListener('click', function(){
			fetch(backendPrefix + 'backend/auth_logout.php')
				.then(function(){ window.location.href = pathPrefix + 'index.php'; });
		});
	}
})();
</script>

<?php 
$prefix = isset($pathPrefix) ? $pathPrefix : '';
?>
<script src="<?php echo $prefix; ?>assets/js/script.js"></script>
