<?php 
include '../includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$product_id) {
    header('Location: menu.php');
    exit;
}
?>

<section class="container" style="padding:32px 24px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); min-height: 100vh;">
    <!-- Breadcrumb -->
    <nav class="breadcrumb" style="margin-bottom: 24px; padding: 12px 0;">
        <div style="display: flex; align-items: center; gap: 8px; color: #6b7280; font-size: 0.9em;">
            <a href="menu.php" style="color: #3b82f6; text-decoration: none; font-weight: 500;">🏠 Trang chủ</a>
            <span>›</span>
            <a href="menu.php" style="color: #3b82f6; text-decoration: none; font-weight: 500;">Menu</a>
            <span>›</span>
            <span id="breadcrumb-product" style="color: #1f2937; font-weight: 600;">Chi tiết sản phẩm</span>
        </div>
    </nav>

    <!-- Product Detail -->
    <div id="product-detail" style="display:none;">
        <!-- Product Info Section -->
        <div class="product-detail-container" style="display:grid; grid-template-columns: 1fr 1fr; gap:48px; margin-bottom:48px; background:white; border-radius:20px; padding:40px; box-shadow:0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(255,255,255,0.8); backdrop-filter: blur(10px);">
            <div class="product-image-section">
                <div class="image-container" style="position: relative; border-radius: 16px; overflow: hidden; box-shadow: 0 12px 40px rgba(0,0,0,0.12);">
                    <div id="product-image" style="width:100%; height:450px; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); display:flex; align-items:center; justify-content:center; position: relative;">
                        <div style="font-size:5em; color:#cbd5e1; opacity: 0.6;">📷</div>
                        <div class="image-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(45deg, rgba(59,130,246,0.1) 0%, rgba(16,185,129,0.1) 100%); opacity: 0; transition: opacity 0.3s ease;"></div>
                    </div>
                    <!-- Image badges -->
                    <div class="image-badges" style="position: absolute; top: 16px; left: 16px; display: flex; flex-direction: column; gap: 8px;">
                        <span class="badge-new" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.8em; font-weight: 600; box-shadow: 0 4px 12px rgba(16,185,129,0.3);">✨ Món ngon</span>
                    </div>
                </div>
                
                <!-- Product Gallery Thumbnails (placeholder) -->
                <div class="product-thumbnails" style="display: flex; gap: 12px; margin-top: 16px; justify-content: center;">
                    <div class="thumb active" style="width: 60px; height: 60px; border-radius: 8px; background: #f1f5f9; border: 2px solid #3b82f6; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5em;">📷</div>
                    <div class="thumb" style="width: 60px; height: 60px; border-radius: 8px; background: #f1f5f9; border: 2px solid transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5em; opacity: 0.6;">📷</div>
                    <div class="thumb" style="width: 60px; height: 60px; border-radius: 8px; background: #f1f5f9; border: 2px solid transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5em; opacity: 0.6;">📷</div>
                </div>
            </div>
            
            <div class="product-info-section">
                <!-- Product Category -->
                <div class="product-category" style="margin-bottom: 12px;">
                    <span style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; padding: 6px 16px; border-radius: 20px; font-size: 0.85em; font-weight: 600; border: 1px solid #93c5fd;">🍔 Fast Food</span>
                </div>
                
                <h1 id="product-name" style="font-size:2.8em; font-weight:800; color:#1f2937; margin-bottom:20px; line-height: 1.2; background: linear-gradient(135deg, #1f2937 0%, #374151 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"></h1>
                
                <!-- Rating Display -->
                <div class="rating-display" style="display:flex; align-items:center; gap:16px; margin-bottom:24px; padding: 16px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; border: 1px solid #f59e0b;">
                    <div class="stars" id="product-stars" style="font-size: 1.4em;"></div>
                    <span id="rating-text" style="color:#92400e; font-weight:700; font-size: 1.1em;"></span>
                    <div class="rating-badge" style="margin-left: auto; background: #f59e0b; color: white; padding: 4px 12px; border-radius: 16px; font-size: 0.8em; font-weight: 600;">⭐ Được yêu thích</div>
                </div>
                
                <p id="product-description" style="color:#4b5563; font-size:1.15em; line-height:1.7; margin-bottom:28px; padding: 20px; background: #f8fafc; border-radius: 12px; border-left: 4px solid #3b82f6;"></p>
                
                <!-- Product Features -->
                <div class="product-features" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 28px;">
                    <div class="feature" style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f0fdf4; border-radius: 8px; border: 1px solid #bbf7d0;">
                        <span style="color: #16a34a; font-size: 1.2em;">🚀</span>
                        <span style="color: #166534; font-weight: 600; font-size: 0.9em;">Giao hàng nhanh</span>
                    </div>
                    <div class="feature" style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #fef2f2; border-radius: 8px; border: 1px solid #fecaca;">
                        <span style="color: #dc2626; font-size: 1.2em;">🔥</span>
                        <span style="color: #991b1b; font-weight: 600; font-size: 0.9em;">Món hot</span>
                    </div>
                    <div class="feature" style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #eff6ff; border-radius: 8px; border: 1px solid #bfdbfe;">
                        <span style="color: #2563eb; font-size: 1.2em;">✨</span>
                        <span style="color: #1e40af; font-weight: 600; font-size: 0.9em;">Chất lượng cao</span>
                    </div>
                    <div class="feature" style="display: flex; align-items: center; gap: 8px; padding: 12px; background: #f5f3ff; border-radius: 8px; border: 1px solid #c4b5fd;">
                        <span style="color: #7c3aed; font-size: 1.2em;">💎</span>
                        <span style="color: #5b21b6; font-weight: 600; font-size: 0.9em;">Đặc biệt</span>
                    </div>
                </div>
                
                <div class="price-section" style="margin-bottom:36px; padding: 24px; background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border-radius: 16px; border: 1px solid #f87171; text-align: center;">
                    <div style="color: #7f1d1d; font-size: 0.9em; font-weight: 600; margin-bottom: 8px;">Giá đặc biệt</div>
                    <span id="product-price" style="font-size:2.5em; font-weight:900; color:#dc2626; text-shadow: 0 2px 4px rgba(220,38,38,0.2);"></span>
                    <div style="color: #7f1d1d; font-size: 0.85em; margin-top: 8px;">* Đã bao gồm VAT</div>
                </div>
                
                <div class="action-buttons" style="display:flex; gap:16px;">
                    <button id="add-to-cart-btn" class="btn btn-primary" style="flex:2; padding:18px 28px; font-size:1.15em; font-weight:700; border-radius:16px; background:linear-gradient(135deg, #ff6a00 0%, #ff944d 100%); color:white; border:none; cursor:pointer; transition:all 0.3s ease; display:flex; align-items:center; justify-content:center; gap:10px; box-shadow: 0 8px 25px rgba(255,106,0,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                        <span style="font-size: 1.2em;">🛒</span>
                        <span>Thêm vào giỏ</span>
                    </button>
                    <button onclick="window.location.href='menu.php'" class="btn btn-secondary" style="flex:1; padding:18px 24px; font-size:1.1em; font-weight:600; border-radius:16px; background:white; color:#374151; border:2px solid #e5e7eb; cursor:pointer; transition:all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        ← Menu
                    </button>
                </div>
                
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="reviews-section" style="background:white; border-radius:20px; padding:40px; box-shadow:0 20px 60px rgba(0,0,0,0.08); border: 1px solid rgba(255,255,255,0.8); backdrop-filter: blur(10px);">
            <div class="reviews-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; padding-bottom: 20px; border-bottom: 2px solid #f1f5f9;">
                <div>
                    <h2 style="font-size:2.2em; font-weight:800; color:#1f2937; display:flex; align-items:center; gap:16px; margin: 0;">
                        <span style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">⭐</span>
                        <span style="background: linear-gradient(135deg, #1f2937 0%, #374151 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Đánh giá sản phẩm</span>
                    </h2>
                    <p style="color: #6b7280; margin: 8px 0 0 0; font-size: 1.05em;">Chia sẻ trải nghiệm của bạn với món ăn này</p>
                </div>
                <button id="write-review-btn" class="btn btn-primary" style="padding:14px 28px; font-size:1.05em; font-weight:700; border-radius:12px; background:linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color:white; border:none; cursor:pointer; transition:all 0.3s ease; box-shadow: 0 8px 25px rgba(59,130,246,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                    ✍️ Viết đánh giá
                </button>
            </div>
            
            <!-- Rating Summary -->
            <div id="rating-summary" class="rating-summary" style="display:grid; grid-template-columns: 280px 1fr; gap:40px; margin-bottom:48px; padding:32px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius:16px; border: 1px solid #e2e8f0;">
                <div class="rating-overview" style="text-align:center; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <div style="color: #6b7280; font-size: 0.9em; font-weight: 600; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Điểm trung bình</div>
                    <div id="avg-rating" style="font-size:3.5em; font-weight:900; color:#dc2626; margin-bottom:12px; text-shadow: 0 2px 4px rgba(220,38,38,0.2);">0.0</div>
                    <div class="stars-large" id="avg-stars" style="font-size:1.8em; margin-bottom:12px;"></div>
                    <div id="total-reviews-text" style="color:#6b7280; font-weight:600; font-size: 1.05em;">0 đánh giá</div>
                    <div style="margin-top: 16px; padding: 12px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 8px; border: 1px solid #93c5fd;">
                        <div style="color: #1e40af; font-size: 0.85em; font-weight: 600;">📊 Chất lượng đánh giá cao</div>
                    </div>
                </div>
                
                <div class="rating-breakdown" id="rating-breakdown" style="padding: 20px;">
                    <div style="color: #374151; font-size: 1.1em; font-weight: 700; margin-bottom: 20px;">📈 Phân bố đánh giá</div>
                    <!-- Rating bars will be generated here -->
                </div>
            </div>
            
            <!-- Write Review Form -->
            <div id="review-form" class="review-form" style="display:none; background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border:2px solid #0ea5e9; border-radius:16px; padding:32px; margin-bottom:40px; box-shadow: 0 12px 40px rgba(14,165,233,0.15);">
                <div class="form-header" style="text-align: center; margin-bottom: 28px;">
                    <h3 style="font-size:1.6em; font-weight:800; color:#0c4a6e; margin-bottom:8px; display: flex; align-items: center; justify-content: center; gap: 12px;">
                        <span style="font-size: 1.2em;">✍️</span>
                        <span>Viết đánh giá của bạn</span>
                    </h3>
                    <p style="color: #0369a1; margin: 0; font-size: 1.05em;">Chia sẻ trải nghiệm để giúp khách hàng khác</p>
                </div>
                
                <div class="form-group" style="margin-bottom:28px;">
                    <label style="display:block; font-weight:700; color:#0c4a6e; margin-bottom:12px; font-size: 1.1em;">⭐ Đánh giá sao của bạn:</label>
                    <div class="star-rating-container" style="text-align: center; padding: 20px; background: white; border-radius: 12px; border: 1px solid #bae6fd;">
                        <div class="star-rating" id="star-rating" style="display:flex; gap:8px; font-size:2.5em; cursor:pointer; justify-content: center;">
                            <span data-rating="1" style="transition: all 0.2s ease; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">☆</span>
                            <span data-rating="2" style="transition: all 0.2s ease; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">☆</span>
                            <span data-rating="3" style="transition: all 0.2s ease; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">☆</span>
                            <span data-rating="4" style="transition: all 0.2s ease; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">☆</span>
                            <span data-rating="5" style="transition: all 0.2s ease; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));">☆</span>
                        </div>
                        <div id="rating-description" style="margin-top: 12px; color: #6b7280; font-size: 0.9em; font-weight: 600; min-height: 20px;">Chọn số sao để đánh giá</div>
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom:28px;">
                    <label for="review-comment" style="display:block; font-weight:700; color:#0c4a6e; margin-bottom:12px; font-size: 1.1em;">💬 Bình luận chi tiết (tùy chọn):</label>
                    <textarea id="review-comment" placeholder="Hãy chia sẻ chi tiết về trải nghiệm của bạn: vị món ăn, chất lượng phục vụ, thời gian giao hàng..." style="width:100%; padding:16px 20px; border:2px solid #bae6fd; border-radius:12px; font-size:16px; resize:vertical; min-height:120px; font-family:inherit; background: white; transition: all 0.3s ease; line-height: 1.6;"></textarea>
                    <div style="margin-top: 8px; color: #0369a1; font-size: 0.85em;">💡 Mẹo: Đánh giá chi tiết sẽ giúp khách hàng khác có quyết định tốt hơn</div>
                </div>
                
                <div class="form-actions" style="display:flex; gap:16px; justify-content: center;">
                    <button id="submit-review-btn" class="btn btn-primary" style="padding:16px 32px; font-size:1.1em; font-weight:700; border-radius:12px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border:none; cursor:pointer; transition:all 0.3s ease; box-shadow: 0 8px 25px rgba(16,185,129,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                        📝 Gửi đánh giá
                    </button>
                    <button id="cancel-review-btn" class="btn btn-secondary" style="padding:16px 32px; font-size:1.1em; font-weight:600; border-radius:12px; background:white; color:#374151; border:2px solid #e5e7eb; cursor:pointer; transition:all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                        ✕ Hủy bỏ
                    </button>
                </div>
            </div>
            
            <!-- Reviews List -->
            <div class="reviews-list-container">
                <div class="reviews-list-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb;">
                    <h3 style="font-size: 1.4em; font-weight: 700; color: #1f2937; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <span>💬</span>
                        <span>Đánh giá từ khách hàng</span>
                    </h3>
                    <div class="sort-options" style="display: flex; gap: 8px;">
                        <select id="review-sort" style="padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.85em; background: white;">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="highest">Điểm cao nhất</option>
                            <option value="lowest">Điểm thấp nhất</option>
                        </select>
                    </div>
                </div>
                
                <div id="reviews-list" class="reviews-list">
                    <div style="text-align:center; padding:60px 40px; color:#6b7280;">
                        <div style="font-size:3em; margin-bottom:20px; opacity: 0.6;">⏳</div>
                        <div style="font-size: 1.1em; font-weight: 600;">Đang tải đánh giá...</div>
                        <div style="font-size: 0.9em; margin-top: 8px; opacity: 0.8;">Vui lòng chờ trong giây lát</div>
                    </div>
                </div>
            </div>
            
            <!-- Load More Button -->
            <div id="load-more-container" style="text-align:center; margin-top:32px; display:none;">
                <button id="load-more-btn" class="btn btn-secondary" style="padding:14px 40px; font-size:1.05em; font-weight:600; border-radius:12px; background:white; color:#374151; border:2px solid #e5e7eb; cursor:pointer; transition:all 0.3s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <span style="margin-right: 8px;">👀</span>
                    <span>Xem thêm đánh giá</span>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Loading State -->
    <div id="loading" style="text-align:center; padding:80px;">
        <div style="font-size:3em; margin-bottom:20px;">⏳</div>
        <div style="font-size:1.2em; color:#6b7280;">Đang tải thông tin sản phẩm...</div>
    </div>
    
    <!-- Error State -->
    <div id="error" style="display:none; text-align:center; padding:80px;">
        <div style="font-size:3em; margin-bottom:20px;">❌</div>
        <div style="font-size:1.2em; color:#ef4444; margin-bottom:20px;">Không tìm thấy sản phẩm</div>
        <button onclick="window.location.href='menu.php'" class="btn btn-primary">Quay lại Menu</button>
    </div>
</section>

<?php include '../includes/footer.php'; ?>

<script>
(function(){
    const productId = <?php echo $product_id; ?>;
    let currentRating = 0;
    let reviewsOffset = 0;
    const reviewsLimit = 5;
    
    function formatCurrency(value) {
        try {
            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
        } catch (e) {
            return value + 'đ';
        }
    }
    
    function generateStars(rating, size = '1em') {
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        let stars = '';
        
        for (let i = 0; i < 5; i++) {
            if (i < fullStars) {
                stars += '<span style="color:#fbbf24; font-size:' + size + ';">★</span>';
            } else if (i === fullStars && hasHalfStar) {
                stars += '<span style="color:#fbbf24; font-size:' + size + ';">☆</span>';
            } else {
                stars += '<span style="color:#e5e7eb; font-size:' + size + ';">☆</span>';
            }
        }
        
        return stars;
    }
    
    function formatDate(dateStr) {
        try {
            return new Date(dateStr.replace(' ', 'T')).toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } catch (e) {
            return dateStr;
        }
    }
    
    // Biến lưu role của user
    let userRole = null;
    
    // Lấy thông tin user để biết role
    fetch('../../backend/auth_me.php', { cache: 'no-store' })
        .then(r => r.json())
        .then(auth => {
            userRole = auth.role || null;
        })
        .catch(() => {});
    
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
        } else if (type === 'info') {
            notification.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
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
    
    // Notification với nút đến giỏ hàng
    function showNotificationWithAction(message) {
        const notification = document.createElement('div');
        notification.style.cssText = 'position:fixed;top:20px;right:20px;padding:16px 20px;border-radius:12px;color:white;font-weight:600;z-index:1000;animation:slideIn 0.3s ease;max-width:400px;box-shadow:0 8px 24px rgba(0,0,0,0.2);background:linear-gradient(135deg, #10b981 0%, #059669 100%);';
        
        notification.innerHTML = `<div style="margin-bottom:12px;">${message}</div>
            <div style="display:flex;gap:8px;">
                <button onclick="window.location.href='cart.php'" style="flex:1;padding:8px 16px;background:white;color:#059669;border:none;border-radius:6px;font-weight:600;cursor:pointer;">🛒 Xem giỏ hàng</button>
                <button onclick="this.parentElement.parentElement.remove()" style="padding:8px 12px;background:rgba(255,255,255,0.2);color:white;border:none;border-radius:6px;font-weight:600;cursor:pointer;">✕</button>
            </div>`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (document.body.contains(notification)) {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
    }
    
    function loadProduct() {
        console.log('Loading product ID:', productId);
        
        fetch('../../backend/products_list.php', { cache: 'no-store' })
            .then(r => {
                console.log('API Response status:', r.status);
                if (!r.ok) {
                    throw new Error(`HTTP ${r.status}: ${r.statusText}`);
                }
                return r.json();
            })
            .then(data => {
                console.log('API Data:', data);
                const products = data.products || [];
                console.log('Products found:', products.length);
                
                const product = products.find(p => p.id == productId);
                console.log('Product found:', product);
                
                if (!product) {
                    console.error('Product not found for ID:', productId);
                    document.getElementById('loading').style.display = 'none';
                    document.getElementById('error').style.display = 'block';
                    return;
                }
                
                // Update product info
                document.getElementById('product-name').textContent = product.name;
                document.getElementById('product-description').textContent = product.description || 'Món ăn ngon tại Fast Food';
                document.getElementById('product-price').textContent = formatCurrency(product.price);
                document.getElementById('breadcrumb-product').textContent = product.name;
                
                // Update product image with hover effect
                const imageContainer = document.getElementById('product-image');
                if (product.image_url) {
                    imageContainer.innerHTML = `
                        <img src="../assets/images/${product.image_url}" 
                             alt="${product.name}" 
                             style="width:100%; height:100%; object-fit:cover; transition: transform 0.3s ease;"
                             onmouseover="this.style.transform='scale(1.05)'"
                             onmouseout="this.style.transform='scale(1)'">
                    `;
                    // Show image overlay on hover
                    const overlay = imageContainer.querySelector('.image-overlay');
                    if (overlay) {
                        imageContainer.addEventListener('mouseenter', () => {
                            overlay.style.opacity = '1';
                        });
                        imageContainer.addEventListener('mouseleave', () => {
                            overlay.style.opacity = '0';
                        });
                    }
                }
                
                // Update rating display
                const rating = parseFloat(product.average_rating) || 0;
                const totalReviews = parseInt(product.total_reviews) || 0;
                
                document.getElementById('product-stars').innerHTML = generateStars(rating);
                document.getElementById('rating-text').textContent = `${rating.toFixed(1)} (${totalReviews} đánh giá)`;
                
                // Add to cart functionality
                document.getElementById('add-to-cart-btn').addEventListener('click', () => {
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
                        product_id: product.id,
                        name: product.name,
                        price: product.price,
                        image_url: product.image_url,
                        quantity: 1
                    });
                    // Chuyển thẳng đến giỏ hàng
                    window.location.href = 'cart.php';
                });
                
                document.getElementById('loading').style.display = 'none';
                document.getElementById('product-detail').style.display = 'block';
                
                // Load reviews
                loadReviews();
                
                // Check if should focus on reviews
                const urlParams = new URLSearchParams(window.location.search);
                const focusReviews = urlParams.get('focus') === 'reviews';
                
                if (focusReviews) {
                    // Auto scroll to reviews section after a short delay
                    setTimeout(() => {
                        const reviewsSection = document.querySelector('.reviews-section');
                        if (reviewsSection) {
                            reviewsSection.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'start' 
                            });
                            
                            // Add highlight effect
                            reviewsSection.style.animation = 'highlight 2s ease';
                        }
                    }, 800);
                } else {
                    // Default: scroll to reviews after 2 seconds
                    setTimeout(() => {
                        const reviewsSection = document.querySelector('.reviews-section');
                        if (reviewsSection) {
                            reviewsSection.scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'center' 
                            });
                        }
                    }, 2000);
                }
            })
            .catch(err => {
                console.error('Error loading product:', err);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error').style.display = 'block';
            });
    }
    
    function loadReviews(offset = 0) {
        console.log('Loading reviews for product:', productId);
        
        fetch(`../../backend/reviews_list.php?product_id=${productId}&limit=${reviewsLimit}&offset=${offset}`, { cache: 'no-store' })
            .then(r => {
                console.log('Reviews API status:', r.status);
                return r.json();
            })
            .then(data => {
                console.log('Reviews data:', data);
                if (data.ok) {
                    updateRatingSummary(data.stats);
                    
                    if (offset === 0) {
                        renderReviews(data.reviews);
                    } else {
                        appendReviews(data.reviews);
                    }
                    
                    // Show/hide load more button
                    const loadMoreContainer = document.getElementById('load-more-container');
                    if (data.pagination.has_more) {
                        loadMoreContainer.style.display = 'block';
                    } else {
                        loadMoreContainer.style.display = 'none';
                    }
                }
            })
            .catch(err => {
                console.error('Error loading reviews:', err);
                document.getElementById('reviews-list').innerHTML = `
                    <div style="text-align:center; padding:40px; color:#ef4444;">
                        <div style="font-size:2em; margin-bottom:16px;">❌</div>
                        <div>Không thể tải đánh giá</div>
                    </div>
                `;
            });
    }
    
    function updateRatingSummary(stats) {
        document.getElementById('avg-rating').textContent = stats.average_rating.toFixed(1);
        document.getElementById('avg-stars').innerHTML = generateStars(stats.average_rating, '1.5em');
        document.getElementById('total-reviews-text').textContent = `${stats.total_reviews} đánh giá`;
        
        // Generate rating breakdown
        const breakdown = document.getElementById('rating-breakdown');
        let html = '';
        
        for (let i = 5; i >= 1; i--) {
            const count = stats.rating_distribution[i] || 0;
            const percentage = stats.total_reviews > 0 ? (count / stats.total_reviews * 100) : 0;
            
            html += `
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                    <span style="font-size:0.9em; color:#6b7280; min-width:20px;">${i}★</span>
                    <div style="flex:1; height:8px; background:#e5e7eb; border-radius:4px; overflow:hidden;">
                        <div style="height:100%; background:#fbbf24; width:${percentage}%; transition:width 0.3s ease;"></div>
                    </div>
                    <span style="font-size:0.9em; color:#6b7280; min-width:30px;">${count}</span>
                </div>
            `;
        }
        
        breakdown.innerHTML = html;
    }
    
    function renderReviews(reviews) {
        const container = document.getElementById('reviews-list');
        
        if (reviews.length === 0) {
            container.innerHTML = `
                <div style="text-align:center; padding:60px 40px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 16px; border: 2px dashed #e2e8f0;">
                    <div style="font-size:4em; margin-bottom:20px; opacity: 0.6;">💬</div>
                    <h3 style="color:#374151; margin-bottom:12px; font-size: 1.3em; font-weight: 700;">Chưa có đánh giá nào</h3>
                    <p style="color:#6b7280; font-size: 1.05em; margin-bottom: 24px;">Hãy là người đầu tiên chia sẻ trải nghiệm của bạn!</p>
                    <div style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 20px; color: #1e40af; font-weight: 600; font-size: 0.9em;">
                        <span>✨</span>
                        <span>Đánh giá đầu tiên sẽ được ưu tiên hiển thị</span>
                    </div>
                </div>
            `;
            return;
        }
        
        container.innerHTML = reviews.map((review, index) => {
            const rating = parseInt(review.rating) || 0;
            const isHighRating = rating >= 4;
            const avatarColor = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'][index % 5];
            const avatarInitial = review.user_name.charAt(0).toUpperCase();
            
            return `
                <div class="review-item" style="position: relative; margin-bottom: 20px; animation-delay: ${index * 0.1}s;">
                    <div class="review-header" style="display:flex; align-items:start; gap:16px; margin-bottom:16px;">
                        <div class="user-avatar" style="width: 48px; height: 48px; border-radius: 50%; background: ${avatarColor}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.2em; flex-shrink: 0; box-shadow: 0 4px 12px ${avatarColor}33;">
                            ${avatarInitial}
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <h4 style="font-weight:700; color:#1f2937; margin:0; font-size: 1.1em;">${review.user_name}</h4>
                                ${isHighRating ? '<span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.7em; font-weight: 600;">⭐ TOP REVIEWER</span>' : ''}
                            </div>
                            <div style="display:flex; align-items:center; gap:12px; margin-bottom: 12px;">
                                <div class="stars" style="font-size: 1.1em;">${generateStars(rating)}</div>
                                <span style="color:#6b7280; font-size:0.9em; font-weight: 500;">${formatDate(review.created_at)}</span>
                                <div class="rating-badge" style="background: ${isHighRating ? '#dcfce7' : '#fef3c7'}; color: ${isHighRating ? '#166534' : '#92400e'}; padding: 4px 8px; border-radius: 8px; font-size: 0.75em; font-weight: 600;">
                                    ${rating === 5 ? '🌟 Xuất sắc' : rating === 4 ? '👍 Tốt' : rating === 3 ? '👌 Ổn' : rating === 2 ? '👎 Tệ' : '😞 Rất tệ'}
                                </div>
                            </div>
                        </div>
                    </div>
                    ${review.comment ? `
                        <div class="review-content" style="margin-left: 64px; padding: 16px 20px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; border-left: 4px solid ${avatarColor}; position: relative;">
                            <div style="color:#374151; line-height:1.7; font-size: 1.05em;">${review.comment}</div>
                            <div class="review-actions" style="margin-top: 12px; display: flex; gap: 16px; align-items: center;">
                                <button class="helpful-btn" style="background: none; border: none; color: #6b7280; font-size: 0.85em; cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#f3f4f6'; this.style.color='#374151'" onmouseout="this.style.background='none'; this.style.color='#6b7280'">
                                    <span>👍</span>
                                    <span>Hữu ích</span>
                                    <span style="background: #e5e7eb; color: #6b7280; padding: 2px 6px; border-radius: 10px; font-size: 0.8em;">${Math.floor(Math.random() * 10) + 1}</span>
                                </button>
                                <button class="reply-btn" style="background: none; border: none; color: #6b7280; font-size: 0.85em; cursor: pointer; display: flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 6px; transition: all 0.2s ease;" onmouseover="this.style.background='#f3f4f6'; this.style.color='#374151'" onmouseout="this.style.background='none'; this.style.color='#6b7280'">
                                    <span>💬</span>
                                    <span>Trả lời</span>
                                </button>
                            </div>
                        </div>
                    ` : `
                        <div style="margin-left: 64px; padding: 12px 20px; background: #f9fafb; border-radius: 8px; border: 1px dashed #e5e7eb;">
                            <span style="color: #9ca3af; font-style: italic; font-size: 0.95em;">Người dùng chỉ đánh giá sao mà không để lại bình luận</span>
                        </div>
                    `}
                </div>
            `;
        }).join('');
        
        // Add click handlers for helpful and reply buttons
        container.querySelectorAll('.helpful-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const countSpan = this.querySelector('span:last-child');
                const currentCount = parseInt(countSpan.textContent);
                countSpan.textContent = currentCount + 1;
                this.style.color = '#10b981';
                this.querySelector('span:first-child').textContent = '👍';
                showNotification('Cảm ơn bạn đã đánh giá!', 'success');
            });
        });
        
        container.querySelectorAll('.reply-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                showNotification('Tính năng trả lời sẽ có trong phiên bản tiếp theo!', 'info');
            });
        });
    }
    
    function appendReviews(reviews) {
        const container = document.getElementById('reviews-list');
        const newReviewsHtml = reviews.map(review => `
            <div class="review-item" style="border-bottom:1px solid #f1f5f9; padding:24px 0;">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:12px;">
                    <div>
                        <div style="font-weight:600; color:#1f2937; margin-bottom:4px;">${review.user_name}</div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div class="stars">${generateStars(review.rating)}</div>
                            <span style="color:#6b7280; font-size:0.9em;">${formatDate(review.created_at)}</span>
                        </div>
                    </div>
                </div>
                ${review.comment ? `<div style="color:#374151; line-height:1.6; margin-top:12px;">${review.comment}</div>` : ''}
            </div>
        `).join('');
        
        container.innerHTML += newReviewsHtml;
    }
    
    // Check if user can review this product
    function checkReviewPermission() {
        // First check if user is logged in
        fetch('../../backend/auth_me.php', { cache: 'no-store' })
            .then(r => r.json())
            .then(auth => {
                if (!auth || !auth.user_id) {
                    // User not logged in - hide review button
                    document.getElementById('write-review-btn').style.display = 'none';
                    return;
                }
                
                // Check if user has purchased and completed this product
                fetch(`../../backend/user_order_check.php?product_id=${productId}`, { cache: 'no-store' })
                    .then(r => r.json())
                    .then(data => {
                        const writeBtn = document.getElementById('write-review-btn');
                        
                        if (data && data.can_review) {
                            // User can review - show button
                            writeBtn.style.display = 'block';
                            writeBtn.textContent = '✍️ Viết đánh giá';
                        } else if (data && data.already_reviewed) {
                            // User already reviewed - show different text
                            writeBtn.style.display = 'block';
                            writeBtn.textContent = '✅ Đã đánh giá';
                            writeBtn.disabled = true;
                            writeBtn.style.opacity = '0.6';
                            writeBtn.style.cursor = 'not-allowed';
                        } else if (data && data.not_purchased) {
                            // User hasn't purchased - hide button or show message
                            writeBtn.style.display = 'none';
                        } else if (data && data.order_not_completed) {
                            // Order not completed yet
                            writeBtn.style.display = 'block';
                            writeBtn.textContent = '⏳ Chờ hoàn thành đơn hàng';
                            writeBtn.disabled = true;
                            writeBtn.style.opacity = '0.6';
                            writeBtn.style.cursor = 'not-allowed';
                        } else {
                            // Default - hide button
                            writeBtn.style.display = 'none';
                        }
                    })
                    .catch(() => {
                        // Error checking - hide button
                        document.getElementById('write-review-btn').style.display = 'none';
                    });
            })
            .catch(() => {
                // Error checking auth - hide button
                document.getElementById('write-review-btn').style.display = 'none';
            });
    }

    // Review form functionality
    function initReviewForm() {
        const writeBtn = document.getElementById('write-review-btn');
        const reviewForm = document.getElementById('review-form');
        const cancelBtn = document.getElementById('cancel-review-btn');
        const submitBtn = document.getElementById('submit-review-btn');
        const starRating = document.getElementById('star-rating');
        const commentTextarea = document.getElementById('review-comment');
        
        // Show/hide review form
        writeBtn.addEventListener('click', () => {
            if (writeBtn.disabled) return;
            if (reviewForm.style.display === 'none' || !reviewForm.style.display) {
                reviewForm.style.display = 'block';
                reviewForm.scrollIntoView({ behavior: 'smooth' });
            } else {
                reviewForm.style.display = 'none';
            }
        });
        
        cancelBtn.addEventListener('click', () => {
            reviewForm.style.display = 'none';
            currentRating = 0;
            updateStarDisplay(0);
            commentTextarea.value = '';
        });
        
        // Star rating interaction
        const stars = starRating.querySelectorAll('span');
        stars.forEach((star, index) => {
            star.addEventListener('mouseover', () => {
                updateStarDisplay(index + 1);
            });
            
            star.addEventListener('click', () => {
                currentRating = index + 1;
                updateStarDisplay(currentRating);
            });
        });
        
        starRating.addEventListener('mouseleave', () => {
            updateStarDisplay(currentRating);
        });
        
        function updateStarDisplay(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.textContent = '★';
                    star.style.color = '#fbbf24';
                    star.style.transform = 'scale(1.1)';
                } else {
                    star.textContent = '☆';
                    star.style.color = '#e5e7eb';
                    star.style.transform = 'scale(1)';
                }
            });
            
            // Update rating description
            const descriptions = [
                'Chọn số sao để đánh giá',
                '⭐ Rất tệ - Không hài lòng',
                '⭐⭐ Tệ - Dưới mong đợi', 
                '⭐⭐⭐ Bình thường - Ổn',
                '⭐⭐⭐⭐ Tốt - Hài lòng',
                '⭐⭐⭐⭐⭐ Xuất sắc - Rất hài lòng'
            ];
            const descElement = document.getElementById('rating-description');
            if (descElement) {
                descElement.textContent = descriptions[rating] || descriptions[0];
                descElement.style.color = rating > 0 ? '#059669' : '#6b7280';
                descElement.style.fontWeight = rating > 0 ? '700' : '600';
            }
        }
        
        // Cancel review
        cancelBtn.addEventListener('click', () => {
            reviewForm.style.display = 'none';
            currentRating = 0;
            updateStarDisplay(0);
            commentTextarea.value = '';
        });
        
        // Submit review
        submitBtn.addEventListener('click', () => {
            if (currentRating === 0) {
                showNotification('Vui lòng chọn số sao đánh giá', 'error');
                return;
            }
            
            const comment = commentTextarea.value.trim();
            
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang gửi...';
            
            fetch('../../backend/reviews_create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    product_id: productId,
                    rating: currentRating,
                    comment: comment
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    showNotification(data.message);
                    reviewForm.style.display = 'none';
                    currentRating = 0;
                    updateStarDisplay(0);
                    commentTextarea.value = '';
                    
                    // Reload reviews
                    reviewsOffset = 0;
                    loadReviews();
                    
                    // Reload product to update rating
                    loadProduct();
                } else {
                    showNotification(data.error || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(err => {
                console.error('Error submitting review:', err);
                showNotification('Không thể gửi đánh giá', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = '📝 Gửi đánh giá';
            });
        });
    }
    
    // Load more reviews
    document.getElementById('load-more-btn').addEventListener('click', () => {
        reviewsOffset += reviewsLimit;
        loadReviews(reviewsOffset);
    });
    
    // Enhanced UI interactions
    function initEnhancedUI() {
        // Smooth scroll for internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Parallax effect for background elements
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.product-detail-container::before, .reviews-section::before');
            parallaxElements.forEach(el => {
                if (el) {
                    el.style.transform = `translateY(${scrolled * 0.1}px)`;
                }
            });
        });
        
        // Image gallery functionality
        const thumbnails = document.querySelectorAll('.thumb');
        thumbnails.forEach((thumb, index) => {
            thumb.addEventListener('click', () => {
                // Remove active class from all thumbnails
                thumbnails.forEach(t => {
                    t.classList.remove('active');
                    t.style.borderColor = 'transparent';
                });
                
                // Add active class to clicked thumbnail
                thumb.classList.add('active');
                thumb.style.borderColor = '#3b82f6';
                
                // Here you would typically change the main image
                // For now, just add a visual feedback
                const mainImage = document.getElementById('product-image');
                if (mainImage) {
                    mainImage.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        mainImage.style.transform = 'scale(1)';
                    }, 200);
                }
            });
        });
        
        // Social share functionality
        const shareButtons = document.querySelectorAll('.share-btn');
        shareButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const text = btn.textContent;
                if (text.includes('Facebook')) {
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`, '_blank');
                } else if (text.includes('WhatsApp')) {
                    window.open(`https://wa.me/?text=${encodeURIComponent(window.location.href)}`, '_blank');
                } else if (text.includes('Copy')) {
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        showNotification('✅ Đã copy link!', 'success');
                    });
                }
            });
        });
        
        // Enhanced textarea auto-resize
        const textarea = document.getElementById('review-comment');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 200) + 'px';
            });
        }
        
        // Keyboard navigation for star rating
        const starRating = document.getElementById('star-rating');
        if (starRating) {
            starRating.addEventListener('keydown', (e) => {
                const stars = starRating.querySelectorAll('span');
                const currentActive = Array.from(stars).findIndex(star => 
                    star.style.color === 'rgb(251, 191, 36)'
                );
                
                let newRating = currentRating;
                
                if (e.key === 'ArrowRight' && currentActive < 4) {
                    newRating = currentActive + 2;
                } else if (e.key === 'ArrowLeft' && currentActive > 0) {
                    newRating = currentActive;
                } else if (e.key >= '1' && e.key <= '5') {
                    newRating = parseInt(e.key);
                }
                
                if (newRating !== currentRating) {
                    currentRating = newRating;
                    updateStarDisplay(currentRating);
                }
            });
        }
        
        // Loading skeleton animation
        function showLoadingSkeleton() {
            const skeletonHTML = `
                <div class="skeleton-container" style="animation: pulse 1.5s ease-in-out infinite;">
                    <div style="height: 20px; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); border-radius: 4px; margin-bottom: 10px;"></div>
                    <div style="height: 20px; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); border-radius: 4px; width: 80%; margin-bottom: 10px;"></div>
                    <div style="height: 20px; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); border-radius: 4px; width: 60%;"></div>
                </div>
            `;
            return skeletonHTML;
        }
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease forwards';
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.querySelectorAll('.review-item, .feature, .rating-overview').forEach(el => {
            observer.observe(el);
        });
    }
    
    // Initialize everything
    loadProduct();
    loadReviews();
    initReviewForm();
    checkReviewPermission();
    initEnhancedUI();
    
    // Add loading state management
    window.addEventListener('beforeunload', () => {
        document.body.style.opacity = '0.7';
    });
})();
</script>

<style>
/* ===== GLOBAL STYLES ===== */
* {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: #1f2937;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    min-height: 100vh;
}

/* ===== BREADCRUMB STYLES ===== */
.breadcrumb a:hover {
    color: #1d4ed8 !important;
    text-decoration: underline;
    transform: translateX(2px);
    transition: all 0.2s ease;
}

/* ===== PRODUCT DETAIL CONTAINER ===== */
.product-detail-container {
    position: relative;
    overflow: hidden;
}

.product-detail-container::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(59,130,246,0.03) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
    pointer-events: none;
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ===== IMAGE STYLES ===== */
.image-container {
    position: relative;
    overflow: hidden;
}

.image-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, 
        rgba(59,130,246,0.1) 0%, 
        rgba(16,185,129,0.1) 25%,
        rgba(245,158,11,0.1) 50%,
        rgba(239,68,68,0.1) 75%,
        rgba(139,92,246,0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.image-container:hover::after {
    opacity: 1;
}

.product-thumbnails .thumb {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.product-thumbnails .thumb:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-color: #3b82f6 !important;
    opacity: 1 !important;
}

.product-thumbnails .thumb.active {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(59,130,246,0.3);
}

/* ===== PRODUCT INFO STYLES ===== */
.product-category span {
    position: relative;
    overflow: hidden;
}

.product-category span::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s ease;
}

.product-category span:hover::before {
    left: 100%;
}

/* ===== RATING DISPLAY STYLES ===== */
.rating-display {
    position: relative;
    overflow: hidden;
}

.rating-display::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s ease;
}

.rating-display:hover::before {
    left: 100%;
}

.rating-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* ===== PRODUCT FEATURES STYLES ===== */
.feature {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.feature::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s ease;
}

.feature:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.feature:hover::before {
    left: 100%;
}

/* ===== PRICE SECTION STYLES ===== */
.price-section {
    position: relative;
    overflow: hidden;
}

.price-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.8s ease;
}

.price-section:hover::before {
    left: 100%;
}

/* ===== BUTTON STYLES ===== */
.btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn:hover::before {
    left: 100%;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.btn:active {
    transform: translateY(-1px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

#add-to-cart-btn {
    background: linear-gradient(135deg, #ff6a00 0%, #ff944d 100%);
    position: relative;
}

#add-to-cart-btn::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #e55a00 0%, #ff6a00 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

#add-to-cart-btn:hover::after {
    opacity: 1;
}

#add-to-cart-btn span {
    position: relative;
    z-index: 1;
}

.btn-secondary {
    background: white;
    border: 2px solid #e5e7eb;
    color: #374151;
}

.btn-secondary:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #1f2937;
}

/* ===== SOCIAL SHARE STYLES ===== */
.share-btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.share-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.4s ease;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.share-btn:hover::before {
    left: 100%;
}

/* ===== REVIEWS SECTION STYLES ===== */
.reviews-section {
    position: relative;
    overflow: hidden;
}

.reviews-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(16,185,129,0.02) 0%, transparent 70%);
    animation: rotate-reverse 25s linear infinite;
    pointer-events: none;
}

@keyframes rotate-reverse {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(-360deg); }
}

/* ===== RATING SUMMARY STYLES ===== */
.rating-overview {
    transition: all 0.3s ease;
}

.rating-overview:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.rating-breakdown > div {
    transition: all 0.3s ease;
}

.rating-breakdown > div:hover {
    transform: translateX(4px);
    background: rgba(251,191,36,0.1);
    border-radius: 8px;
    padding: 8px;
    margin: -4px;
}

/* ===== STAR RATING STYLES ===== */
.star-rating span {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    position: relative;
}

.star-rating span:hover {
    transform: scale(1.2) rotate(10deg);
    filter: drop-shadow(0 4px 8px rgba(251,191,36,0.4));
}

.star-rating span:active {
    transform: scale(1.1) rotate(5deg);
}

/* ===== REVIEW FORM STYLES ===== */
.review-form {
    position: relative;
    overflow: hidden;
}

.review-form::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(14,165,233,0.05) 0%, transparent 70%);
    animation: rotate 30s linear infinite;
    pointer-events: none;
}

.star-rating-container {
    transition: all 0.3s ease;
}

.star-rating-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(14,165,233,0.15);
}

#review-comment {
    transition: all 0.3s ease;
}

#review-comment:focus {
    outline: none;
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14,165,233,0.1);
    transform: translateY(-2px);
}

/* ===== REVIEWS LIST STYLES ===== */
.review-item {
    transition: all 0.3s ease;
    position: relative;
    padding: 24px;
    margin: 16px 0;
    background: white;
    border-radius: 12px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.review-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
    border-radius: 2px;
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.review-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border-color: #e2e8f0;
}

.review-item:hover::before {
    transform: scaleY(1);
}

/* ===== LOADING STATES ===== */
#loading {
    animation: fadeIn 0.5s ease;
}

#loading > div:first-child {
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* ===== HIGHLIGHT ANIMATION ===== */
@keyframes highlight {
    0% { 
        box-shadow: 0 20px 60px rgba(0,0,0,0.08); 
        transform: scale(1);
        border-color: rgba(255,255,255,0.8);
    }
    50% { 
        box-shadow: 0 25px 80px rgba(59, 130, 246, 0.2); 
        transform: scale(1.01);
        border-color: rgba(59, 130, 246, 0.3);
    }
    100% { 
        box-shadow: 0 20px 60px rgba(0,0,0,0.08); 
        transform: scale(1);
        border-color: rgba(255,255,255,0.8);
    }
}

/* ===== NOTIFICATION ANIMATIONS ===== */
@keyframes slideIn {
    from {
        transform: translateX(100%) scale(0.8);
        opacity: 0;
    }
    to {
        transform: translateX(0) scale(1);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0) scale(1);
        opacity: 1;
    }
    to {
        transform: translateX(100%) scale(0.8);
        opacity: 0;
    }
}

/* ===== FADE IN ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* ===== SORT OPTIONS STYLES ===== */
#review-sort {
    transition: all 0.3s ease;
}

#review-sort:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1024px) {
    .product-detail-container {
        gap: 32px !important;
        padding: 32px !important;
    }
    
    .rating-summary {
        grid-template-columns: 240px 1fr !important;
        gap: 32px !important;
    }
}

@media (max-width: 768px) {
    .container {
        padding: 16px !important;
    }
    
    .product-detail-container {
        grid-template-columns: 1fr !important;
        gap: 24px !important;
        padding: 24px !important;
    }
    
    #product-image {
        height: 300px !important;
    }
    
    .rating-summary {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        text-align: center;
    }
    
    .action-buttons {
        flex-direction: column !important;
    }
    
    .reviews-section {
        padding: 24px 16px !important;
    }
    
    .product-features {
        grid-template-columns: 1fr !important;
    }
    
    .social-share > div:last-child {
        flex-wrap: wrap;
        gap: 8px !important;
    }
    
    .share-btn {
        flex: 1;
        min-width: 100px;
    }
    
    .star-rating {
        font-size: 2em !important;
        gap: 6px !important;
    }
    
    .review-form {
        padding: 24px 20px !important;
    }
    
    .form-actions {
        flex-direction: column !important;
        gap: 12px !important;
    }
    
    .form-actions .btn {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .breadcrumb {
        font-size: 0.8em;
    }
    
    .breadcrumb > div {
        flex-wrap: wrap;
        gap: 4px !important;
    }
    
    .product-thumbnails {
        gap: 8px !important;
    }
    
    .product-thumbnails .thumb {
        width: 50px !important;
        height: 50px !important;
    }
    
    .rating-display {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px !important;
    }
    
    .reviews-list-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
    }
    
    .review-item {
        padding: 16px !important;
        margin: 12px 0 !important;
    }
}

/* ===== DARK MODE SUPPORT ===== */
@media (prefers-color-scheme: dark) {
    body {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        color: #f9fafb;
    }
    
    .product-detail-container,
    .reviews-section {
        background: rgba(31, 41, 55, 0.8);
        border-color: rgba(75, 85, 99, 0.3);
    }
    
    .review-item {
        background: rgba(31, 41, 55, 0.6);
        border-color: rgba(75, 85, 99, 0.3);
    }
}

/* ===== ACCESSIBILITY ===== */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* ===== PRINT STYLES ===== */
@media print {
    .social-share,
    .action-buttons,
    .review-form,
    #write-review-btn,
    #load-more-container {
        display: none !important;
    }
    
    .product-detail-container,
    .reviews-section {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
}
</style>