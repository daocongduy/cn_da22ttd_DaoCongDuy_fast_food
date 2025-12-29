<?php 
// pages/login.php 
include '../includes/header.php'; // Quay lại thư mục gốc để include header
?>

<section class="container" style="padding:24px;">
	<!-- Header Section -->
	<div style="text-align:center; margin-bottom:32px;">
		<h1 style="color:#1f2937; font-size:2.5em; margin-bottom:8px;">🔐 Đăng nhập / Đăng ký</h1>
		<p style="color:#6b7280; font-size:1.1em;">Chào mừng bạn đến với Fast Food</p>
	</div>

	<!-- Auth Container -->
	<div class="auth-container" style="max-width:450px; margin:0 auto; background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden;">
		
		<!-- Auth Tabs -->
		<div class="auth-tabs" style="display:flex; background:#f8fafc; border-bottom:1px solid #e5e7eb;">
			<button class="auth-tab active" data-target="#login-pane" style="flex:1; padding:16px 20px; border:none; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; font-weight:600; font-size:16px; cursor:pointer; transition:all 0.3s ease;">
				<span>🔑</span> Đăng nhập
			</button>
			<button class="auth-tab" data-target="#register-pane" style="flex:1; padding:16px 20px; border:none; background:transparent; color:#6b7280; font-weight:600; font-size:16px; cursor:pointer; transition:all 0.3s ease;">
				<span>📝</span> Đăng ký
			</button>
		</div>

		<!-- Login Form -->
		<div id="login-pane" class="auth-pane" style="padding:32px 24px;">
			<form id="form-login" class="login-form auth-form" autocomplete="on">
				<div class="form-group" style="margin-bottom:20px;">
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">👥 Vai trò:</label>
					<div class="role-group" style="display:flex; gap:16px; background:#f8fafc; padding:12px; border-radius:8px;">
						<label class="role-option" style="flex:1; display:flex; align-items:center; gap:8px; cursor:pointer; padding:8px 12px; border-radius:6px; transition:all 0.3s ease;">
							<input type="radio" name="role" value="user" checked style="accent-color:#3b82f6;">
							<span style="font-weight:500;">👤 Người dùng</span>
						</label>
						<label class="role-option" style="flex:1; display:flex; align-items:center; gap:8px; cursor:pointer; padding:8px 12px; border-radius:6px; transition:all 0.3s ease;">
							<input type="radio" name="role" value="admin" style="accent-color:#3b82f6;">
							<span style="font-weight:500;">👨‍💼 Quản trị</span>
						</label>
					</div>
				</div>
				<div class="form-group" style="margin-bottom:20px;">
					<label for="username" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">📧 Tên đăng nhập hoặc Email:</label>
					<input type="text" id="username" name="username" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Nhập tên đăng nhập hoặc email">
				</div>
				<div class="form-group" style="margin-bottom:24px;">
					<label for="password" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">🔒 Mật khẩu:</label>
					<input type="password" id="password" name="password" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Nhập mật khẩu">
				</div>
				<button type="submit" class="login-btn" style="width:100%; background:linear-gradient(135deg, #ff6a00 0%, #e55a00 100%); color:white; padding:16px 24px; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; transition:all 0.3s ease; display:flex; align-items:center; justify-content:center; gap:12px; box-shadow:0 4px 12px rgba(255, 106, 0, 0.3);">
					<span>🚀</span>
					<span>Đăng nhập</span>
				</button>
			</form>
		</div>

		<!-- Register Form -->
		<div id="register-pane" class="auth-pane hidden" style="padding:32px 24px;">
			<form id="form-register" class="login-form auth-form" autocomplete="on">
				<div class="form-group" style="margin-bottom:20px;">
					<label for="fullname" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">👤 Họ và tên:</label>
					<input type="text" id="fullname" name="fullname" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Nhập họ và tên đầy đủ">
				</div>
				<div class="form-group" style="margin-bottom:20px;">
					<label for="email" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">📧 Email:</label>
					<input type="email" id="email" name="email" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Nhập địa chỉ email">
				</div>
				<div class="form-group" style="margin-bottom:20px;">
					<label for="reg_username" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">🏷️ Tên đăng nhập:</label>
					<input type="text" id="reg_username" name="username" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Chọn tên đăng nhập">
				</div>
				<div class="form-group" style="margin-bottom:20px;">
					<label for="reg_password" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">🔒 Mật khẩu:</label>
					<input type="password" id="reg_password" name="password" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Tạo mật khẩu mạnh">
				</div>
				<div class="form-group" style="margin-bottom:24px;">
					<label for="confirm_password" style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">🔐 Xác nhận mật khẩu:</label>
					<input type="password" id="confirm_password" name="confirm_password" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" placeholder="Nhập lại mật khẩu">
				</div>
				<button type="submit" class="register-btn" style="width:100%; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; padding:16px 24px; border:none; border-radius:12px; font-size:18px; font-weight:600; cursor:pointer; transition:all 0.3s ease; display:flex; align-items:center; justify-content:center; gap:12px; box-shadow:0 4px 12px rgba(16, 185, 129, 0.3);">
					<span>✨</span>
					<span>Tạo tài khoản</span>
				</button>
			</form>
		</div>
	</div>
</section>

<?php 
include '../includes/footer.php'; // Quay lại thư mục gốc để include footer
?>

<script>
(function(){
	var fLogin = document.getElementById('form-login');
	var fReg = document.getElementById('form-register');
	var tabs = document.querySelectorAll('.auth-tab');
	var panes = document.querySelectorAll('.auth-pane');

	// Tab switching functionality
	tabs.forEach(function(tab) {
		tab.addEventListener('click', function() {
			var target = this.getAttribute('data-target');
			
			// Remove active class from all tabs and panes
			tabs.forEach(function(t) { 
				t.classList.remove('active');
				t.style.background = 'transparent';
				t.style.color = '#6b7280';
			});
			panes.forEach(function(p) { 
				p.classList.add('hidden');
				p.style.display = 'none';
			});
			
			// Add active class to clicked tab
			this.classList.add('active');
			this.style.background = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
			this.style.color = 'white';
			
			// Show corresponding pane
			var targetPane = document.querySelector(target);
			if (targetPane) {
				targetPane.classList.remove('hidden');
				targetPane.style.display = 'block';
			}
		});
	});

	function toJSON(form){
		var d = new FormData(form); var o = {}; d.forEach(function(v,k){ o[k]=v; }); return o;
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

	function setButtonLoading(button, isLoading) {
		if (isLoading) {
			button.disabled = true;
			button.style.opacity = '0.7';
			var originalText = button.innerHTML;
			button.setAttribute('data-original-text', originalText);
			button.innerHTML = '<span>⏳</span><span>Đang xử lý...</span>';
		} else {
			button.disabled = false;
			button.style.opacity = '1';
			var originalText = button.getAttribute('data-original-text');
			if (originalText) {
				button.innerHTML = originalText;
			}
		}
	}

	if (fLogin) {
		fLogin.addEventListener('submit', function(e){
			e.preventDefault();
			var submitBtn = fLogin.querySelector('.login-btn');
			var data = toJSON(fLogin);
			
			setButtonLoading(submitBtn, true);
			
			fetch('../../backend/auth_login.php', { method:'POST', body: new URLSearchParams(data) })
				.then(function(r){ return r.json(); })
				.then(function(res){ 
					if (res && res.ok) { 
						showNotification('🎉 Đăng nhập thành công!', 'success');
						setTimeout(function() {
							window.location.href = '../index.php';
						}, 1500);
					} else { 
						showNotification('❌ ' + (res.error || 'Đăng nhập thất bại'), 'error');
					}
					setButtonLoading(submitBtn, false);
				})
				.catch(function() {
					showNotification('❌ Lỗi kết nối!', 'error');
					setButtonLoading(submitBtn, false);
				});
		});
	}

	if (fReg) {
		fReg.addEventListener('submit', function(e){
			e.preventDefault();
			var submitBtn = fReg.querySelector('.register-btn');
			var data = toJSON(fReg);
			
			// Validate password confirmation
			if (data.password !== data.confirm_password) {
				showNotification('❌ Mật khẩu xác nhận không khớp!', 'error');
				return;
			}
			
			setButtonLoading(submitBtn, true);
			
			fetch('../../backend/auth_register.php', { method:'POST', body: new URLSearchParams(data) })
				.then(function(r){ return r.json(); })
				.then(function(res){ 
					if (res && res.ok) { 
						showNotification('🎉 Đăng ký thành công!', 'success');
						setTimeout(function() {
							window.location.href = '../index.php';
						}, 1500);
					} else { 
						showNotification('❌ ' + (res.error || 'Đăng ký thất bại'), 'error');
					}
					setButtonLoading(submitBtn, false);
				})
				.catch(function() {
					showNotification('❌ Lỗi kết nối!', 'error');
					setButtonLoading(submitBtn, false);
				});
		});
	}
})();
</script>

<style>
/* Input Focus Effects */
input:focus {
	outline: none !important;
	border-color: #3b82f6 !important;
	box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
	background: white !important;
	transform: translateY(-1px);
}

input:hover {
	background: white !important;
	border-color: #d1d5db !important;
}

/* Button Hover Effects */
.login-btn:hover {
	background: linear-gradient(135deg, #e55a00 0%, #cc4a00 100%) !important;
	transform: translateY(-2px);
	box-shadow: 0 8px 20px rgba(255, 106, 0, 0.4) !important;
}

.register-btn:hover {
	background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
	transform: translateY(-2px);
	box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4) !important;
}

.login-btn:active, .register-btn:active {
	transform: translateY(0);
}

.login-btn:disabled, .register-btn:disabled {
	opacity: 0.7 !important;
	cursor: not-allowed !important;
	transform: none !important;
}

/* Container Animations */
.auth-container {
	transition: all 0.3s ease;
}

.auth-container:hover {
	transform: translateY(-2px);
	box-shadow: 0 12px 40px rgba(0,0,0,0.12) !important;
}

/* Role Option Hover Effects */
.role-option:hover {
	background: #e5e7eb !important;
	transform: scale(1.02);
}

.role-option input[type="radio"]:checked + span {
	color: #3b82f6 !important;
	font-weight: 600 !important;
}

/* Tab Hover Effects */
.auth-tab:hover {
	background: #f1f5f9 !important;
	color: #374151 !important;
}

.auth-tab.active:hover {
	background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%) !important;
	color: white !important;
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

/* Form Animation */
.auth-pane {
	animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
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
	
	.auth-container {
		margin: 0 8px !important;
		border-radius: 12px !important;
	}
	
	.auth-tabs button {
		padding: 14px 16px !important;
		font-size: 14px !important;
	}
	
	.auth-pane {
		padding: 24px 16px !important;
	}
	
	.form-group {
		margin-bottom: 16px !important;
	}
	
	input {
		padding: 10px 12px !important;
		font-size: 14px !important;
	}
	
	.login-btn, .register-btn {
		padding: 14px 20px !important;
		font-size: 16px !important;
	}
	
	.role-group {
		flex-direction: column !important;
		gap: 8px !important;
	}
}

@media (max-width: 480px) {
	.auth-container {
		margin: 0 4px !important;
		border-radius: 8px !important;
	}
	
	.auth-tabs button {
		padding: 12px 14px !important;
		font-size: 13px !important;
	}
	
	.auth-pane {
		padding: 20px 12px !important;
	}
	
	.role-group {
		padding: 8px !important;
	}
	
	.role-option {
		padding: 6px 10px !important;
	}
}

/* Loading Animation */
.login-btn.loading, .register-btn.loading {
	position: relative;
	overflow: hidden;
}

.login-btn.loading::after, .register-btn.loading::after {
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
.form-group.error input {
	border-color: #ef4444 !important;
	box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
}

.form-group.success input {
	border-color: #10b981 !important;
	box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
}

/* Hidden class */
.hidden {
	display: none !important;
}
</style>