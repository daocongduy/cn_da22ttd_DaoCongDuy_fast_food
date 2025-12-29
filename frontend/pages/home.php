<?php 
include '../includes/header.php';
?>

<section class="banner">
	<div class="container">
		<h2>Giao đồ ăn nhanh, nóng hổi mỗi ngày</h2>
		<p>Khuyến mãi hấp dẫn – đặt món chỉ với vài cú chạm.</p>
	</div>
</section>

<section class="container">
	<div class="products" id="products"></div>
</section>

<script>
(function(){
	var container = document.getElementById('products');
	if (!container) return;

	function price(v){
		try { return new Intl.NumberFormat('vi-VN').format(v) + 'đ'; } catch(e){ return v + 'đ'; }
	}

	function render(list){
		if (!Array.isArray(list) || list.length === 0) {
			container.innerHTML = '<div style="padding:16px;">Chưa có sản phẩm.</div>';
			return;
		}
		container.innerHTML = list.map(function(p){
			var img = (p.image_url ? '<?php echo $pathPrefix; ?>assets/images/' + p.image_url : '');
			return '\
			<div class="item">\
				' + (img ? '<img src="' + img + '" alt="' + (p.name||'') + '">' : '') + '\
				<h3>' + (p.name||'') + '</h3>\
				<p>' + price(p.price||0) + '</p>\
				<button class="btn btn-primary" data-add="' + p.id + '" data-name="' + (p.name||'') + '" data-price="' + (p.price||0) + '" data-img="' + (p.image_url||'') + '">Thêm vào giỏ</button>\
			</div>';
		}).join('');

		// gắn handler thêm vào giỏ
		container.querySelectorAll('button[data-add]').forEach(function(btn){
			btn.addEventListener('click', function(){
				window.Cart.add({
					product_id: Number(btn.getAttribute('data-add')),
					name: btn.getAttribute('data-name'),
					price: Number(btn.getAttribute('data-price')),
					image_url: btn.getAttribute('data-img'),
					quantity: 1
				});
				window.location.href = 'cart.php';
			});
		});
	}

	fetch('../../backend/products_list.php', { cache: 'no-store' })
		.then(function(r){ return r.json(); })
		.then(function(data){ render((data && data.products) || []); })
		.catch(function(){ container.innerHTML = '<div style="padding:16px;">Không tải được sản phẩm.</div>'; });
})();
</script>

<?php 
include '../includes/footer.php';
?>

