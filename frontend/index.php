<?php 
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-left">
                <div class="hero-badge">
                    <span>⚡</span>
                    <span>Giao nhanh, đồ ăn luôn nóng hổi</span>
                </div>

                <h1 class="hero-title">
                    Ăn ngon mỗi ngày cùng<br>
                    <span class="highlight">Fast Food</span>
                </h1>

                <p class="hero-subtitle">
                    Trải nghiệm ẩm thực chất lượng cao với những món ăn được chế biến từ
                    nguyên liệu tươi ngon, giao tận nơi chỉ trong vài phút.
                </p>

                <div class="hero-metadata">
                    <div class="hero-metadata-item">
                        <span class="emoji">🍔</span>
                        <span>Menu đa dạng hơn 100 món</span>
                    </div>
                    <div class="hero-metadata-item">
                        <span class="emoji">⭐</span>
                        <span>Được yêu thích bởi hàng nghìn khách hàng</span>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="pages/menu.php" class="btn-hero-primary">
                        <span>🍔 Đặt hàng ngay</span>
                        <span>→</span>
                    </a>
                    <a href="pages/menu.php?focus=reviews" class="btn-hero-secondary">
                        <span>⭐ Xem đánh giá thực tế</span>
                    </a>
                </div>

                <p class="hero-secondary-text">
                    <strong>Không phụ phí ẩn</strong> · Combo ưu đãi mỗi ngày · Hỗ trợ khách hàng 24/7
                </p>
            </div>

            <div class="hero-right">
                <div class="hero-badge-main">🍔</div>
                <div class="hero-right-card">
                    <div class="hero-right-card-title">
                        <span>🔥 Đơn đang xử lý</span>
                    </div>
                    <div class="hero-right-card-metrics">
                        <span>Hôm nay</span>
                        <span><strong>+120</strong> đơn</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="featured-products">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div class="section-header">
            <h2>Sản phẩm nổi bật</h2>
            <p>Những món ăn được yêu thích nhất tại Fast Food</p>
        </div>
        
        <!-- Products Grid -->
        <div class="products-grid" id="home-products" style="max-width: 900px; margin: 0 auto;">
            <!-- Loading state -->
            <div class="loading-products" style="grid-column: 1 / -1; text-align: center; padding: 60px;">
                <div style="font-size: 3em; margin-bottom: 20px;">⏳</div>
                <div style="font-size: 1.2em; color: #6b7280;">Đang tải sản phẩm...</div>
            </div>
        </div>
        
        <!-- View All Button -->
        <div style="text-align: center; margin-top: 50px;">
            <a href="pages/menu.php" class="btn-outline" style="display: inline-flex; align-items: center; gap: 12px;">
                <span>📋</span>
                <span>Xem tất cả sản phẩm</span>
                <span>→</span>
            </a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon">🚀</div>
                <h3>Giao hàng siêu tốc</h3>
                <p>Thời gian giao trung bình dưới 30 phút, luôn ưu tiên giữ món ăn nóng hổi và tươi ngon.</p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">🌟</div>
                <h3>Chất lượng chuẩn nhà hàng</h3>
                <p>Đội ngũ bếp chuyên nghiệp, quy trình tiêu chuẩn giúp từng phần ăn đều đồng đều và sạch sẽ.</p>
            </div>
            
            <div class="feature-item">
                <div class="feature-icon">💰</div>
                <h3>Giá tốt & nhiều ưu đãi</h3>
                <p>Combo tiết kiệm, voucher theo tuần và các chương trình tri ân khách hàng thân thiết.</p>
            </div>
        </div>
    </div>
</section>

<script>
console.log('🚀 Homepage script starting...');

(function(){
    const container = document.getElementById('home-products');
    if (!container) {
        console.error('❌ Products container not found');
        return;
    }
    
    console.log('✅ Products container found');

    function formatPrice(price) {
        try {
            return new Intl.NumberFormat('vi-VN').format(price) + 'đ';
        } catch(e) {
            return price + 'đ';
        }
    }

    function generateStars(rating) {
        const fullStars = Math.floor(rating || 0);
        let stars = '';
        for (let i = 0; i < 5; i++) {
            stars += i < fullStars ? '⭐' : '☆';
        }
        return stars;
    }

    function renderProducts(products) {
        console.log('🎨 Rendering', products.length, 'products');
        
        // Remove loading state
        const loadingElement = container.querySelector('.loading-products');
        if (loadingElement) {
            loadingElement.remove();
        }
        
        if (!Array.isArray(products) || products.length === 0) {
            container.innerHTML = '<div style="grid-column: 1 / -1; text-align:center; padding: 60px; color:#6b7280; background: white; border-radius: 16px; border: 2px dashed #e5e7eb;"><div style="font-size: 3em; margin-bottom: 16px; opacity: 0.5;">🍽️</div><h3 style="color: #374151; margin-bottom: 8px;">Chưa có sản phẩm</h3><p>Vui lòng <a href="create_test_data.php" style="color:#007bff;">tạo dữ liệu test</a></p></div>';
            return;
        }
        
        // Show only first 6 products for homepage
        const featuredProducts = products.slice(0, 6);
        
        container.innerHTML = featuredProducts.map(function(product, index){
            const img = product.image_url ? 
                (product.image_url.startsWith('http') ? product.image_url : 'assets/images/' + product.image_url) : '';
            const rating = parseFloat(product.average_rating) || 0;
            const totalReviews = parseInt(product.total_reviews) || 0;
            const name = (product.name || '').replace(/'/g, '&#39;');
            const description = product.description || '';
            
            return `
                <div class="product-card" style="
                    background: white; 
                    border-radius: 12px; 
                    overflow: hidden; 
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1); 
                    transition: all 0.3s ease;
                    border: 1px solid #e5e7eb;
                    position: relative;
                    max-width: 280px;
                    margin: 0 auto;
                ">
                    <!-- Product Image -->
                    <div class="product-image" style="
                        height: 200px; 
                        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
                        position: relative;
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        ${img ? `
                            <img src="${img}" alt="${name}" style="
                                width: 100%;
                                height: 100%;
                                object-fit: contain;
                                object-position: center;
                                padding: 10px;
                            ">
                        ` : `
                            <div style="
                                font-size: 4em; 
                                color: #cbd5e1; 
                                opacity: 0.6;
                            ">🍽️</div>
                        `}
                    </div>
                    
                    <!-- Product Info -->
                    <div style="padding: 16px 20px 20px;">
                        <!-- Product Name -->
                        <h3 style="
                            font-size: 1.1em; 
                            font-weight: 700; 
                            color: #1f2937; 
                            margin-bottom: 8px; 
                            line-height: 1.3;
                            text-align: center;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            min-height: 2.2em;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">${name}</h3>
                        
                        <!-- Description -->
                        <p style="
                            color: #6b7280; 
                            font-size: 0.85em; 
                            line-height: 1.4; 
                            margin-bottom: 12px;
                            text-align: center;
                            min-height: 2.8em;
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                        ">${description || 'Sản phẩm chất lượng cao'}</p>
                        
                        <!-- Price -->
                        <div style="
                            text-align: center;
                            margin-bottom: 16px;
                        ">
                            <div style="
                                font-size: 1.3em; 
                                font-weight: 800; 
                                color: #dc2626;
                            ">${formatPrice(product.price || 0)}</div>
                        </div>
                        
                        <!-- Action Button -->
                        <div style="text-align: center;">
                            <button onclick="window.location.href='pages/menu.php'" style="
                                background: #ff6a00; 
                                color: white; 
                                border: none; 
                                padding: 10px 20px; 
                                border-radius: 6px; 
                                font-weight: 600; 
                                cursor: pointer; 
                                transition: all 0.3s ease;
                                font-size: 0.85em;
                                text-transform: uppercase;
                                letter-spacing: 0.5px;
                                width: 100%;
                                max-width: 180px;
                            ">
                                XEM THỰC ĐƠN
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
        
        console.log('✅ Products rendered successfully');
    }

    // Add to cart function
    window.addToCart = function(id, name, price) {
        console.log('🛒 Adding to cart:', {id, name, price});
        alert('Đã thêm ' + name + ' vào giỏ hàng!');
    };

    // Load products with fallback URLs
    const apiUrls = [
        'backend/products_list.php',
        '../backend/products_list.php',
        '/fast_food/backend/products_list.php'
    ];
    
    let currentUrlIndex = 0;
    
    function tryLoadProducts() {
        if (currentUrlIndex >= apiUrls.length) {
            console.error('❌ All API URLs failed');
            const loadingElement = container.querySelector('.loading-products');
            if (loadingElement) {
                loadingElement.innerHTML = '<div style="text-align:center; color:#ef4444; padding: 40px;"><div style="font-size: 3em; margin-bottom: 16px;">❌</div><h3>Không thể tải sản phẩm</h3><p><a href="create_test_data.php" style="color:#007bff;">Tạo dữ liệu test</a></p></div>';
            }
            return;
        }
        
        const apiUrl = apiUrls[currentUrlIndex];
        console.log('🌐 Trying API:', apiUrl);
        
        fetch(apiUrl, { 
            cache: 'no-store',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(function(response) {
            console.log('📡 Response status:', response.status);
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(function(data) {
            console.log('📊 Data received:', data);
            if (data && data.products) {
                renderProducts(data.products);
            } else {
                throw new Error('No products in response');
            }
        })
        .catch(function(error) {
            console.error('❌ Error for', apiUrl, ':', error);
            currentUrlIndex++;
            if (currentUrlIndex < apiUrls.length) {
                console.log('🔄 Trying next URL...');
                setTimeout(tryLoadProducts, 1000);
            } else {
                const loadingElement = container.querySelector('.loading-products');
                if (loadingElement) {
                    loadingElement.innerHTML = '<div style="text-align:center; color:#ef4444; padding: 40px;"><div style="font-size: 3em; margin-bottom: 16px;">❌</div><h3>Lỗi tải sản phẩm</h3><p>' + error.message + '</p><p><a href="create_test_data.php" style="color:#007bff;">Tạo dữ liệu test</a></p></div>';
                }
            }
        });
    }
    
    // Start loading
    tryLoadProducts();
})();

console.log('✅ Homepage script completed');
</script>

<style>
/* Product Card Styles cho trang chủ */
#home-products {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    align-items: stretch;
}

#home-products .product-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

#home-products .product-image {
    height: 200px !important;
    min-height: 200px !important;
    max-height: 200px !important;
}

#home-products .product-image img {
    width: 100% !important;
    height: 100% !important;
    object-fit: contain !important;
    object-position: center !important;
    padding: 10px !important;
}

#home-products .product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

@media (max-width: 768px) {
    #home-products {
        grid-template-columns: 1fr !important;
        max-width: 320px !important;
    }
}

@media (max-width: 1024px) and (min-width: 769px) {
    #home-products {
        grid-template-columns: repeat(2, 1fr) !important;
        max-width: 600px !important;
    }
}
</style>

<?php 
include 'includes/footer.php';
?>