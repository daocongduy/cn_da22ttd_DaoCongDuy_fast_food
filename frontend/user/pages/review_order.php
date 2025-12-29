<?php
$order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if (!$order_id) {
    header('Location: order_status.php');
    exit;
}
?>

<?php include '../../includes/header.php'; ?>

<section class="container" style="padding:24px;">
    <!-- Header Section -->
    <div style="text-align:center; margin-bottom:32px;">
        <h1 style="color:#1f2937; font-size:2.5em; margin-bottom:8px;">⭐ Đánh giá đơn hàng</h1>
        <p style="color:#6b7280; font-size:1.1em;">Chia sẻ trải nghiệm của bạn về các sản phẩm đã mua</p>
    </div>

    <!-- Back Button -->
    <div style="margin-bottom:24px;">
        <button onclick="window.history.back()" class="back-button" style="background:transparent; border:2px solid #e5e7eb; color:#6b7280; padding:10px 20px; border-radius:8px; cursor:pointer; font-weight:600; transition:all 0.3s ease; display:flex; align-items:center; gap:8px;">
            <span>←</span>
            <span>Quay lại</span>
        </button>
    </div>

    <!-- Order Info -->
    <div id="order-info" class="order-info-card" style="background:white; border-radius:16px; padding:24px; margin-bottom:24px; box-shadow:0 8px 30px rgba(0,0,0,0.08); border-left:4px solid #3b82f6;">
        <div style="text-align:center; padding:20px; color:#6b7280;">
            <div style="font-size:2em; margin-bottom:16px;">⏳</div>
            <div>Đang tải thông tin đơn hàng...</div>
        </div>
    </div>

    <!-- Products Review Section -->
    <div id="products-review" class="products-review-section">
        <!-- Sản phẩm sẽ được load ở đây -->
    </div>

    <!-- Success Message -->
    <div id="success-message" style="display:none; background:white; border-radius:16px; padding:32px; text-align:center; box-shadow:0 8px 30px rgba(0,0,0,0.08); border-left:4px solid #10b981;">
        <div style="font-size:3em; margin-bottom:16px;">🎉</div>
        <h3 style="color:#10b981; margin-bottom:12px;">Cảm ơn bạn đã đánh giá!</h3>
        <p style="color:#6b7280; margin-bottom:24px;">Đánh giá của bạn sẽ giúp khách hàng khác có thêm thông tin để lựa chọn.</p>
        <button onclick="window.location.href='order_status.php'" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; padding:12px 24px; border:none; border-radius:8px; font-weight:600; cursor:pointer;">
            Quay lại danh sách đơn hàng
        </button>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>

<script>
(function(){
    const orderId = <?php echo $order_id; ?>;
    let reviewedProducts = new Set();
    let totalProducts = 0;

    function formatCurrency(value) {
        try {
            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
        } catch (e) {
            return value + 'đ';
        }
    }

    function formatDate(dateStr) {
        try {
            return new Date(dateStr.replace(' ', 'T')).toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            return dateStr;
        }
    }

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
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
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    function loadOrderInfo() {
        fetch('../../../backend/orders_detail.php?id=' + orderId, { cache: 'no-store' })
            .then(r => r.json())
            .then(data => {
                if (data && data.order && data.items) {
                    renderOrderInfo(data.order);
                    renderProductsReview(data.items);
                } else {
                    document.getElementById('order-info').innerHTML = 
                        '<div style="text-align:center; padding:32px; color:#ef4444;"><div style="font-size:2em; margin-bottom:16px;">❌</div><div>Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập</div></div>';
                }
            })
            .catch(() => {
                document.getElementById('order-info').innerHTML = 
                    '<div style="text-align:center; padding:32px; color:#ef4444;"><div style="font-size:2em; margin-bottom:16px;">⚠️</div><div>Lỗi kết nối. Vui lòng thử lại sau.</div></div>';
            });
    }

    function renderOrderInfo(order) {
        const orderInfo = document.getElementById('order-info');
        orderInfo.innerHTML = `
            <div style="display:flex; align-items:center; gap:16px; margin-bottom:20px;">
                <div style="font-size:2em;">📋</div>
                <div>
                    <h3 style="margin:0; color:#1f2937; font-size:1.5em;">Đơn hàng #${order.id}</h3>
                    <p style="margin:4px 0 0 0; color:#6b7280;">Đặt ngày ${formatDate(order.created_at)}</p>
                </div>
            </div>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
                <div style="background:#f8fafc; padding:16px; border-radius:8px;">
                    <div style="color:#6b7280; font-size:0.9em; margin-bottom:4px;">Tổng tiền</div>
                    <div style="color:#dc2626; font-weight:700; font-size:1.2em;">${formatCurrency(order.total_amount)}</div>
                </div>
                <div style="background:#f8fafc; padding:16px; border-radius:8px;">
                    <div style="color:#6b7280; font-size:0.9em; margin-bottom:4px;">Trạng thái</div>
                    <div style="color:#10b981; font-weight:700;">Hoàn thành</div>
                </div>
                <div style="background:#f8fafc; padding:16px; border-radius:8px;">
                    <div style="color:#6b7280; font-size:0.9em; margin-bottom:4px;">Giao đến</div>
                    <div style="color:#374151; font-weight:600;">${order.shipping_name}</div>
                </div>
            </div>
        `;
    }

    function renderProductsReview(items) {
        const container = document.getElementById('products-review');
        totalProducts = items.length;
        
        let html = `
            <div style="background:white; border-radius:16px; padding:24px; box-shadow:0 8px 30px rgba(0,0,0,0.08);">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
                    <div style="font-size:1.5em;">⭐</div>
                    <div>
                        <h3 style="margin:0; color:#1f2937; font-size:1.3em;">Đánh giá sản phẩm</h3>
                        <p style="margin:4px 0 0 0; color:#6b7280;">Hãy chia sẻ trải nghiệm của bạn về từng sản phẩm</p>
                    </div>
                </div>
        `;

        items.forEach((item, index) => {
            html += `
                <div class="product-review-card" data-product-id="${item.product_id}" style="background:#f8fafc; border-radius:12px; padding:24px; margin-bottom:20px; border-left:4px solid #3b82f6;">
                    <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:20px;">
                        <div>
                            <h4 style="margin:0 0 8px 0; color:#1f2937; font-size:1.2em;">${item.product_name}</h4>
                            <div style="color:#6b7280; font-size:0.9em;">
                                Số lượng: x${item.quantity} | Giá: ${formatCurrency(item.total_price)}
                            </div>
                        </div>
                    </div>
                    
                    <div class="review-form" data-product-id="${item.product_id}">
                        <div style="margin-bottom:16px;">
                            <label style="display:block; font-weight:600; color:#374151; margin-bottom:8px;">Đánh giá của bạn:</label>
                            <div class="star-rating" style="display:flex; gap:4px; font-size:2em; cursor:pointer;" data-product-id="${item.product_id}">
                                <span data-rating="1" onclick="setRating(${item.product_id}, 1)">☆</span>
                                <span data-rating="2" onclick="setRating(${item.product_id}, 2)">☆</span>
                                <span data-rating="3" onclick="setRating(${item.product_id}, 3)">☆</span>
                                <span data-rating="4" onclick="setRating(${item.product_id}, 4)">☆</span>
                                <span data-rating="5" onclick="setRating(${item.product_id}, 5)">☆</span>
                            </div>
                            <div class="rating-text" style="margin-top:8px; color:#6b7280; font-size:0.9em;">Chọn số sao để đánh giá</div>
                        </div>
                        
                        <div style="margin-bottom:20px;">
                            <label style="display:block; font-weight:600; color:#374151; margin-bottom:8px;">Bình luận (tùy chọn):</label>
                            <textarea placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..." style="width:100%; padding:12px 16px; border:2px solid #e5e7eb; border-radius:8px; font-size:16px; resize:vertical; min-height:80px; font-family:inherit;" data-product-id="${item.product_id}"></textarea>
                        </div>
                        
                        <button onclick="submitReview(${item.product_id})" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; padding:12px 24px; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:all 0.3s ease;">
                            📝 Gửi đánh giá
                        </button>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        container.innerHTML = html;

        // Kiểm tra sản phẩm nào đã được đánh giá
        items.forEach(item => {
            checkReviewStatus(item.product_id);
        });
    }

    window.setRating = function(productId, rating) {
        const starContainer = document.querySelector(`.star-rating[data-product-id="${productId}"]`);
        const stars = starContainer.querySelectorAll('span');
        const ratingText = starContainer.parentElement.querySelector('.rating-text');
        
        // Cập nhật hiển thị sao
        stars.forEach((star, index) => {
            if (index < rating) {
                star.textContent = '★';
                star.style.color = '#fbbf24';
            } else {
                star.textContent = '☆';
                star.style.color = '#e5e7eb';
            }
        });
        
        // Cập nhật text
        const ratingLabels = {
            1: 'Rất tệ 😞',
            2: 'Tệ 😕', 
            3: 'Bình thường 😐',
            4: 'Tốt 😊',
            5: 'Xuất sắc 🤩'
        };
        
        ratingText.textContent = `${rating} sao - ${ratingLabels[rating]}`;
        
        // Lưu rating
        starContainer.setAttribute('data-rating', rating);
    };

    window.submitReview = function(productId) {
        const form = document.querySelector(`.review-form[data-product-id="${productId}"]`);
        const starContainer = form.querySelector('.star-rating');
        const rating = parseInt(starContainer.getAttribute('data-rating')) || 0;
        const comment = form.querySelector('textarea').value.trim();
        const button = form.querySelector('button');
        
        if (rating === 0) {
            showNotification('Vui lòng chọn số sao đánh giá!', 'error');
            return;
        }
        
        // Disable button
        button.disabled = true;
        button.textContent = '⏳ Đang gửi...';
        
        fetch('../../../backend/reviews_create.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                product_id: productId,
                rating: rating,
                comment: comment
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.ok) {
                // Thành công
                form.innerHTML = `
                    <div style="text-align:center; padding:24px; background:#dcfce7; border-radius:8px; color:#166534;">
                        <div style="font-size:2em; margin-bottom:12px;">✅</div>
                        <div style="font-weight:600; font-size:1.1em; margin-bottom:8px;">Đã gửi đánh giá thành công!</div>
                        <div style="font-size:0.9em;">Cảm ơn bạn đã đánh giá sản phẩm này.</div>
                    </div>
                `;
                
                reviewedProducts.add(productId);
                showNotification('Gửi đánh giá thành công!');
                
                // Kiểm tra xem đã đánh giá hết chưa
                if (reviewedProducts.size === totalProducts) {
                    setTimeout(() => {
                        document.getElementById('products-review').style.display = 'none';
                        document.getElementById('success-message').style.display = 'block';
                    }, 1500);
                }
            } else {
                showNotification(data.error || 'Có lỗi xảy ra khi gửi đánh giá', 'error');
                button.disabled = false;
                button.textContent = '📝 Gửi đánh giá';
            }
        })
        .catch(() => {
            showNotification('Lỗi kết nối! Vui lòng thử lại.', 'error');
            button.disabled = false;
            button.textContent = '📝 Gửi đánh giá';
        });
    };

    function checkReviewStatus(productId) {
        fetch(`../../../backend/user_order_check.php?product_id=${productId}`, { cache: 'no-store' })
            .then(r => r.json())
            .then(data => {
                if (data.already_reviewed) {
                    const form = document.querySelector(`.review-form[data-product-id="${productId}"]`);
                    if (form) {
                        form.innerHTML = `
                            <div style="text-align:center; padding:24px; background:#fef3c7; border-radius:8px; color:#92400e;">
                                <div style="font-size:2em; margin-bottom:12px;">⭐</div>
                                <div style="font-weight:600; font-size:1.1em; margin-bottom:8px;">Bạn đã đánh giá sản phẩm này</div>
                                <div style="font-size:0.9em;">Mỗi sản phẩm chỉ có thể đánh giá một lần.</div>
                            </div>
                        `;
                        reviewedProducts.add(productId);
                    }
                }
            })
            .catch(() => {
                // Ignore error - let user try to review
            });
    }

    // Initialize
    loadOrderInfo();
})();
</script>

<style>
/* Button hover effects */
.back-button:hover {
    background: #f3f4f6 !important;
    border-color: #d1d5db !important;
    transform: translateX(-2px);
}

/* Star rating hover effects */
.star-rating span {
    transition: all 0.2s ease;
}

.star-rating span:hover {
    transform: scale(1.1);
    color: #fbbf24 !important;
}

/* Product card animations */
.product-review-card {
    animation: fadeInUp 0.3s ease;
}

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

/* Notification animations */
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

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 16px !important;
    }
    
    h1 {
        font-size: 2em !important;
    }
    
    .product-review-card {
        padding: 16px !important;
        margin-bottom: 16px !important;
    }
    
    .star-rating {
        font-size: 1.5em !important;
    }
    
    textarea {
        min-height: 60px !important;
    }
}
</style>