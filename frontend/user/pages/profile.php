<?php include '../../includes/header.php'; ?>

<section class="container" style="padding:24px; max-width:800px;">
	<!-- Header -->
	<div style="text-align:center; margin-bottom:32px;">
		<h1 style="color:#1f2937; font-size:2.5em; margin-bottom:8px;">👤 Hồ sơ cá nhân</h1>
		<p style="color:#6b7280; font-size:1.1em;">Quản lý thông tin tài khoản của bạn</p>
	</div>

	<div style="display:grid; grid-template-columns: 1fr 2fr; gap:24px;">
		<!-- Avatar Section -->
		<div style="background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); padding:24px; text-align:center;">
			<div id="profile-avatar" style="width:120px; height:120px; border-radius:50%; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; display:flex; align-items:center; justify-content:center; font-size:3em; font-weight:700; margin:0 auto 16px; text-transform:uppercase;">?</div>
			<h3 id="profile-name" style="margin:0 0 4px 0; color:#1f2937; font-size:1.3em;">Đang tải...</h3>
			<p id="profile-role" style="margin:0; color:#6b7280; font-size:0.9em;">Người dùng</p>
			<div style="margin-top:16px; padding-top:16px; border-top:1px solid #e5e7eb;">
				<div style="font-size:0.85em; color:#6b7280;">
					<div style="margin-bottom:8px;">📧 <span id="profile-email-display">-</span></div>
					<div>📞 <span id="profile-phone-display">-</span></div>
				</div>
			</div>
		</div>

		<!-- Form Section -->
		<div style="background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden;">
			<div style="background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; padding:20px 24px;">
				<h3 style="margin:0; font-size:1.2em;">📝 Cập nhật thông tin</h3>
				<p style="margin:4px 0 0 0; opacity:0.9; font-size:0.9em;">Chỉnh sửa thông tin cá nhân của bạn</p>
			</div>
			
			<form id="profile-form" style="padding:24px;">
				<div style="margin-bottom:20px;">
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">👤 Họ và tên</label>
					<input id="name" type="text" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease;">
				</div>
				<div style="margin-bottom:20px;">
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">📧 Email</label>
					<input id="email" type="email" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; background:#f9fafb;" readonly>
					<small style="color:#6b7280; font-size:0.8em;">Email không thể thay đổi</small>
				</div>
				<div style="margin-bottom:20px;">
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">📞 Số điện thoại</label>
					<input id="phone" type="tel" style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease;" placeholder="Nhập số điện thoại">
				</div>
				<div style="margin-bottom:24px;">
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">📍 Địa chỉ</label>
					<textarea id="address" style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; transition:all 0.3s ease; resize:vertical; min-height:80px;" placeholder="Nhập địa chỉ của bạn"></textarea>
				</div>
				
				<div id="message" style="display:none; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-weight:500;"></div>
				
				<button type="submit" style="width:100%; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; padding:14px 24px; border:none; border-radius:10px; font-size:16px; font-weight:600; cursor:pointer; transition:all 0.3s ease;">
					💾 Lưu thay đổi
				</button>
			</form>
		</div>
	</div>

	<!-- Change Password Section -->
	<div style="background:#fff; border-radius:16px; box-shadow:0 8px 30px rgba(0,0,0,0.08); overflow:hidden; margin-top:24px;">
		<div style="background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; padding:20px 24px;">
			<h3 style="margin:0; font-size:1.2em;">🔐 Đổi mật khẩu</h3>
			<p style="margin:4px 0 0 0; opacity:0.9; font-size:0.9em;">Cập nhật mật khẩu để bảo mật tài khoản</p>
		</div>
		
		<form id="password-form" style="padding:24px;">
			<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
				<div>
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">Mật khẩu hiện tại</label>
					<input id="current-password" type="password" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px;">
				</div>
				<div>
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">Mật khẩu mới</label>
					<input id="new-password" type="password" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px;">
				</div>
				<div>
					<label style="display:block; margin-bottom:8px; font-weight:600; color:#374151;">Xác nhận mật khẩu</label>
					<input id="confirm-password" type="password" required style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px;">
				</div>
			</div>
			
			<div id="password-message" style="display:none; padding:12px 16px; border-radius:8px; margin:16px 0; font-weight:500;"></div>
			
			<button type="submit" style="margin-top:16px; background:linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; padding:12px 24px; border:none; border-radius:8px; font-size:15px; font-weight:600; cursor:pointer; transition:all 0.3s ease;">
				🔐 Đổi mật khẩu
			</button>
		</form>
	</div>
</section>

<style>
input:focus, textarea:focus {
	outline: none;
	border-color: #667eea !important;
	box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

button[type="submit"]:hover {
	transform: translateY(-2px);
	box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.success-msg {
	background: #d1fae5 !important;
	color: #065f46 !important;
	border: 1px solid #6ee7b7;
}

.error-msg {
	background: #fee2e2 !important;
	color: #991b1b !important;
	border: 1px solid #fca5a5;
}

@media (max-width: 768px) {
	.container > div:first-of-type {
		grid-template-columns: 1fr !important;
	}
	
	h1 { font-size: 2em !important; }
}
</style>

<script>
(function(){
	// Load user info
	fetch('../../../backend/auth_me.php', { cache: 'no-store' })
		.then(function(r) { return r.json(); })
		.then(function(auth) {
			if (!auth || !auth.user_id) {
				alert('Vui lòng đăng nhập');
				window.location.href = '../../pages/login.php';
				return;
			}
			
			// Update avatar section
			var name = auth.name || 'Người dùng';
			var email = auth.email || '';
			var phone = auth.phone || '';
			var address = auth.address || '';
			var firstLetter = name.charAt(0).toUpperCase();
			
			document.getElementById('profile-avatar').textContent = firstLetter;
			document.getElementById('profile-name').textContent = name;
			document.getElementById('profile-role').textContent = auth.role === 'admin' ? '👑 Quản trị viên' : '👤 Người dùng';
			document.getElementById('profile-email-display').textContent = email || 'Chưa có';
			document.getElementById('profile-phone-display').textContent = phone || 'Chưa có';
			
			// Fill form
			document.getElementById('name').value = name;
			document.getElementById('email').value = email;
			document.getElementById('phone').value = phone;
			document.getElementById('address').value = address;
		})
		.catch(function() {
			alert('Không thể tải thông tin');
		});

	// Update profile form
	document.getElementById('profile-form').addEventListener('submit', function(e) {
		e.preventDefault();
		
		var data = {
			name: document.getElementById('name').value.trim(),
			phone: document.getElementById('phone').value.trim(),
			address: document.getElementById('address').value.trim()
		};
		
		if (!data.name) {
			showMessage('message', 'Vui lòng nhập họ tên', 'error');
			return;
		}
		
		fetch('../../../backend/user_update.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify(data)
		})
		.then(function(r) { return r.json(); })
		.then(function(res) {
			if (res && res.ok) {
				showMessage('message', '✅ Cập nhật thành công!', 'success');
				// Update display
				document.getElementById('profile-name').textContent = data.name;
				document.getElementById('profile-avatar').textContent = data.name.charAt(0).toUpperCase();
				document.getElementById('profile-phone-display').textContent = data.phone || 'Chưa có';
			} else {
				showMessage('message', '❌ ' + (res.error || 'Cập nhật thất bại'), 'error');
			}
		})
		.catch(function() {
			showMessage('message', '❌ Lỗi kết nối', 'error');
		});
	});

	// Change password form
	document.getElementById('password-form').addEventListener('submit', function(e) {
		e.preventDefault();
		
		var currentPwd = document.getElementById('current-password').value;
		var newPwd = document.getElementById('new-password').value;
		var confirmPwd = document.getElementById('confirm-password').value;
		
		if (newPwd !== confirmPwd) {
			showMessage('password-message', '❌ Mật khẩu xác nhận không khớp', 'error');
			return;
		}
		
		if (newPwd.length < 6) {
			showMessage('password-message', '❌ Mật khẩu mới phải có ít nhất 6 ký tự', 'error');
			return;
		}
		
		fetch('../../../backend/user_change_password.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				current_password: currentPwd,
				new_password: newPwd
			})
		})
		.then(function(r) { return r.json(); })
		.then(function(res) {
			if (res && res.ok) {
				showMessage('password-message', '✅ Đổi mật khẩu thành công!', 'success');
				document.getElementById('password-form').reset();
			} else {
				showMessage('password-message', '❌ ' + (res.error || 'Đổi mật khẩu thất bại'), 'error');
			}
		})
		.catch(function() {
			showMessage('password-message', '❌ Lỗi kết nối', 'error');
		});
	});

	function showMessage(id, text, type) {
		var el = document.getElementById(id);
		el.textContent = text;
		el.className = type === 'success' ? 'success-msg' : 'error-msg';
		el.style.display = 'block';
		
		setTimeout(function() {
			el.style.display = 'none';
		}, 5000);
	}
})();
</script>

<?php include '../../includes/footer.php'; ?>
