<?php
// Trang liên hệ khách hàng
include '../includes/header.php';
?>

<section class="contact-section">
	<div class="container" style="max-width: 720px; margin: 40px auto; padding: 24px; background: #ffffff; border-radius: 16px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);">
		<h1 style="font-size: 1.8rem; margin-bottom: 8px; color: #111827; text-align: center;">Liên hệ với chúng tôi</h1>
		<p style="margin-bottom: 24px; color: #6b7280; text-align: center;">
			Nếu bạn có thắc mắc về đơn hàng, góp ý về món ăn hoặc cần hỗ trợ, hãy gửi thông tin cho admin qua form dưới đây.
		</p>

		<form id="contact-form" style="display: flex; flex-direction: column; gap: 16px;">
			<div>
				<label for="contact-name" style="display:block; font-weight:600; margin-bottom:4px;">Họ và tên *</label>
				<input id="contact-name" name="name" type="text" required
					style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #d1d5db; outline:none; font-size:0.95rem;">
			</div>

			<div>
				<label for="contact-email" style="display:block; font-weight:600; margin-bottom:4px;">Email *</label>
				<input id="contact-email" name="email" type="email" required
					style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #d1d5db; outline:none; font-size:0.95rem;">
			</div>

			<div>
				<label for="contact-phone" style="display:block; font-weight:600; margin-bottom:4px;">Số điện thoại</label>
				<input id="contact-phone" name="phone" type="text"
					style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #d1d5db; outline:none; font-size:0.95rem;">
			</div>

			<div>
				<label for="contact-message" style="display:block; font-weight:600; margin-bottom:4px;">Nội dung *</label>
				<textarea id="contact-message" name="message" rows="4" required
					style="width:100%; padding:10px 12px; border-radius:8px; border:1px solid #d1d5db; outline:none; font-size:0.95rem; resize:vertical;"></textarea>
			</div>

			<button type="submit"
				style="margin-top:8px; padding:10px 16px; border-radius:999px; border:none; background:linear-gradient(90deg,#ef4444,#f97316); color:white; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; gap:8px;">
				<span>📨 Gửi liên hệ</span>
			</button>

			<p id="contact-status" style="margin-top:8px; font-size:0.9rem;"></p>
		</form>

		<div style="margin-top:24px; padding-top:16px; border-top:1px solid #e5e7eb; font-size:0.9rem; color:#6b7280;">
			<p><strong>Thông tin liên hệ khác:</strong></p>
			<p>Email: <a href="mailto:admin@fastfood.local">admin@fastfood.local</a></p>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('contact-form');
	const statusEl = document.getElementById('contact-status');
	if (!form) return;

	form.addEventListener('submit', function (e) {
		e.preventDefault();

		const name = document.getElementById('contact-name').value.trim();
		const email = document.getElementById('contact-email').value.trim();
		const phone = document.getElementById('contact-phone').value.trim();
		const message = document.getElementById('contact-message').value.trim();

		statusEl.style.color = '#6b7280';
		statusEl.textContent = 'Đang gửi liên hệ...';

		fetch('<?php echo $backendPrefix; ?>backend/contact_create.php', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify({ name: name, email: email, phone: phone, message: message })
		})
			.then(function (r) { return r.json(); })
			.then(function (data) {
				if (data.ok) {
					statusEl.style.color = '#16a34a';
					statusEl.textContent = data.message || 'Gửi liên hệ thành công!';
					form.reset();
				} else {
					statusEl.style.color = '#dc2626';
					statusEl.textContent = data.message || 'Gửi liên hệ thất bại, vui lòng thử lại.';
				}
			})
			.catch(function () {
				statusEl.style.color = '#dc2626';
				statusEl.textContent = 'Lỗi kết nối server, vui lòng thử lại sau.';
			});
	});
});
</script>

<?php
include '../includes/footer.php';
?>


