<?php
// Quick setup script để tạo dữ liệu test ngay lập tức
require_once 'backend/config.php';

echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <title>🚀 Quick Setup</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 12px; border-radius: 8px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; border-radius: 8px; margin: 10px 0; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; padding: 12px; border-radius: 8px; margin: 10px 0; }
        h1 { color: #1f2937; text-align: center; margin-bottom: 30px; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; margin: 10px 5px; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🚀 Quick Setup - Tạo dữ liệu test</h1>";

try {
    $db = getDb();
    
    // 1. Tạo bảng product_reviews nếu chưa có
    echo "<div class='info'>📋 Bước 1: Tạo bảng product_reviews...</div>";
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS product_reviews (
                id INT PRIMARY KEY AUTO_INCREMENT,
                product_id INT NOT NULL,
                user_id INT NOT NULL,
                rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
                comment TEXT,
                is_approved TINYINT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_product_id (product_id),
                INDEX idx_user_id (user_id),
                UNIQUE KEY unique_user_product (user_id, product_id)
            )
        ");
        echo "<div class='success'>✅ Bảng product_reviews đã tạo</div>";
    } catch (Exception $e) {
        echo "<div class='info'>ℹ️ Bảng product_reviews đã tồn tại</div>";
    }
    
    // 2. Thêm cột rating vào bảng products
    echo "<div class='info'>📋 Bước 2: Thêm cột rating vào bảng products...</div>";
    try {
        $db->exec("ALTER TABLE products 
                   ADD COLUMN IF NOT EXISTS average_rating DECIMAL(3,2) DEFAULT 0.00,
                   ADD COLUMN IF NOT EXISTS total_reviews INT DEFAULT 0");
        echo "<div class='success'>✅ Đã thêm cột rating</div>";
    } catch (Exception $e) {
        echo "<div class='info'>ℹ️ Cột rating đã tồn tại</div>";
    }
    
    // 3. Tạo sản phẩm test
    echo "<div class='info'>📋 Bước 3: Tạo sản phẩm test...</div>";
    $testProducts = [
        [1, 'Burger Bò Phô Mai Deluxe', 85000, 'Burger bò Wagyu nướng hoàn hảo với phô mai cheddar cao cấp, rau xanh hữu cơ, cà chua tươi và sốt đặc biệt của nhà hàng. Kèm theo khoai tây chiên giòn.', 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=400&h=300&fit=crop&crop=center'],
        [2, 'Pizza Hải Sản Cao Cấp', 150000, 'Pizza đế bánh mỏng giòn với tôm tươi, mực baby, cua Alaska và phô mai mozzarella nhập khẩu. Nướng trong lò đá truyền thống.', 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=400&h=300&fit=crop&crop=center'],
        [3, 'Gà Rán Giòn Cay', 95000, 'Gà rán giòn tan với lớp bột tẩm gia vị cay nồng đặc trưng, ăn kèm khoai tây chiên và coleslaw tươi mát.', 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?w=400&h=300&fit=crop&crop=center'],
        [4, 'Coca Cola Premium', 25000, 'Coca Cola nguyên chất 330ml được phục vụ trong chai thủy tinh, mát lạnh với đá viên tươi.', 'https://images.unsplash.com/photo-1581636625402-29b2a704ef13?w=400&h=300&fit=crop&crop=center'],
        [5, 'Khoai Tây Chiên Truffle', 45000, 'Khoai tây chiên giòn rụm với muối truffle cao cấp, phô mai parmesan và herbs tươi. Kèm 3 loại sốt đặc biệt.', 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=400&h=300&fit=crop&crop=center'],
        [6, 'Sandwich Gà Nướng Avocado', 65000, 'Sandwich với ức gà nướng tẩm gia vị, bơ tươi, rau xanh hữu cơ và sốt aioli tự làm trên bánh mì sourdough.', 'https://images.unsplash.com/photo-1553909489-cd47e0ef937f?w=400&h=300&fit=crop&crop=center'],
        [7, 'Hot Dog Phô Mai Nướng', 55000, 'Hot dog với xúc xích bò cao cấp, phô mai cheddar nướng chảy, hành tây caramel và sốt mustard Dijon.', 'https://images.unsplash.com/photo-1612392062798-2facb8c4e3e2?w=400&h=300&fit=crop&crop=center'],
        [8, 'Salad Caesar Tôm Nướng', 75000, 'Salad Caesar truyền thống với tôm nướng tỏi ớt, rau xà lách romaine tươi, phô mai parmesan và crouton giòn.', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&h=300&fit=crop&crop=center']
    ];
    
    $created = 0;
    foreach ($testProducts as $product) {
        try {
            $insert = $db->prepare('
                INSERT INTO products (id, name, price, description, image_url, is_active, average_rating, total_reviews) 
                VALUES (?, ?, ?, ?, ?, 1, 0.00, 0)
                ON DUPLICATE KEY UPDATE 
                name = VALUES(name),
                price = VALUES(price),
                description = VALUES(description),
                image_url = VALUES(image_url),
                is_active = 1
            ');
            $insert->execute($product);
            $created++;
        } catch (Exception $e) {
            // Product already exists, update it
        }
    }
    echo "<div class='success'>✅ Đã tạo/cập nhật {$created} sản phẩm</div>";
    
    // 4. Tạo user test (phù hợp với cấu trúc bảng users hiện tại)
    echo "<div class='info'>📋 Bước 4: Tạo user test...</div>";
    // [tên hiển thị, email, mật khẩu]
    $testUsers = [
        ['Nguyễn Văn A', 'user1@test.com', '123456'],
        ['Trần Thị B', 'user2@test.com', '123456'],
        ['Lê Văn C', 'user3@test.com', '123456']
    ];
    
    $userCreated = 0;
    foreach ($testUsers as $user) {
        try {
            // Bảng users có các cột: name, phone, address, email, password_hash, role, created_at
            $insert = $db->prepare('
                INSERT INTO users (name, email, password_hash, role) 
                VALUES (?, ?, ?, ?)
            ');
            $insert->execute([
                $user[0],
                $user[1],
                password_hash($user[2], PASSWORD_DEFAULT),
                'user'
            ]);
            $userCreated++;
        } catch (Exception $e) {
            // User already exists
        }
    }
    echo "<div class='success'>✅ Đã tạo {$userCreated} user test</div>";
    
    // 5. Tạo đánh giá test
    echo "<div class='info'>📋 Bước 5: Tạo đánh giá test...</div>";
    
    // Get user IDs
    $users = $db->query('SELECT id FROM users WHERE role = "user" LIMIT 3')->fetchAll();
    
    if (count($users) > 0) {
        $testReviews = [
            [1, $users[0]['id'], 5, 'Burger rất ngon! Thịt bò tươi, phô mai tan chảy. Sẽ đặt lại!'],
            [1, $users[1]['id'], 4, 'Ngon nhưng hơi mặn một chút. Nhìn chung vẫn ổn.'],
            [2, $users[0]['id'], 4, 'Pizza hải sản tươi ngon, đế bánh giòn. Giá hơi cao.'],
            [2, $users[2]['id'], 5, 'Rất hài lòng! Topping nhiều, phô mai thơm.'],
            [3, $users[1]['id'], 5, 'Gà rán giòn tan, gia vị đậm đà. Recommend!'],
            [3, $users[2]['id'], 4, 'Ngon nhưng hơi nhiều dầu. Vẫn sẽ đặt lại.'],
            [4, $users[0]['id'], 5, 'Coca mát lạnh, giao nhanh. Perfect!'],
            [5, $users[1]['id'], 4, 'Khoai tây giòn, muối vừa phải. Ổn!'],
            [6, $users[2]['id'], 5, 'Sandwich gà nướng thơm ngon, rau tươi.'],
            [7, $users[0]['id'], 3, 'Hot dog bình thường, không có gì đặc biệt.']
        ];
        
        $reviewCreated = 0;
        foreach ($testReviews as $review) {
            try {
                $insert = $db->prepare('
                    INSERT INTO product_reviews (product_id, user_id, rating, comment) 
                    VALUES (?, ?, ?, ?)
                ');
                $insert->execute($review);
                $reviewCreated++;
            } catch (Exception $e) {
                // Review already exists
            }
        }
        echo "<div class='success'>✅ Đã tạo {$reviewCreated} đánh giá test</div>";
    }
    
    // 6. Cập nhật rating sản phẩm
    echo "<div class='info'>📋 Bước 6: Cập nhật rating sản phẩm...</div>";
    $products = $db->query('SELECT id FROM products')->fetchAll();
    $updated = 0;
    
    foreach ($products as $product) {
        $stats = $db->prepare('
            SELECT 
                AVG(rating) as avg_rating,
                COUNT(*) as total_reviews
            FROM product_reviews 
            WHERE product_id = ? AND is_approved = 1
        ');
        $stats->execute([$product['id']]);
        $result = $stats->fetch();
        
        $avgRating = $result['avg_rating'] ? round($result['avg_rating'], 2) : 0.00;
        $totalReviews = $result['total_reviews'] ?: 0;
        
        $update = $db->prepare('
            UPDATE products 
            SET average_rating = ?, total_reviews = ? 
            WHERE id = ?
        ');
        $update->execute([$avgRating, $totalReviews, $product['id']]);
        $updated++;
    }
    echo "<div class='success'>✅ Đã cập nhật rating cho {$updated} sản phẩm</div>";
    
    // Test API
    echo "<div class='info'>📋 Bước 7: Test API...</div>";
    $apiUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/backend/products_list.php';
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $response = @file_get_contents($apiUrl, false, $context);
    
    if ($response) {
        $data = json_decode($response, true);
        if ($data && isset($data['products'])) {
            echo "<div class='success'>✅ API products_list.php hoạt động tốt - Có " . count($data['products']) . " sản phẩm</div>";
        } else {
            echo "<div class='error'>❌ API trả về dữ liệu không hợp lệ</div>";
        }
    } else {
        echo "<div class='error'>❌ Không thể kết nối đến API</div>";
    }
    
    echo "<div class='success'>
        <h3>🎉 Setup hoàn tất!</h3>
        <p><strong>Test ngay:</strong></p>
        <a href='frontend/pages/menu.php' class='btn'>📋 Xem Menu</a>
        <a href='frontend/pages/product_detail.php?id=1&focus=reviews' class='btn'>⭐ Chi tiết sản phẩm #1</a>
        <a href='frontend/pages/product_detail.php?id=2&focus=reviews' class='btn'>⭐ Chi tiết sản phẩm #2</a>
        <a href='backend/products_list.php' class='btn'>🔧 Test API</a>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Lỗi: " . htmlspecialchars($e->getMessage()) . "</div>";
    echo "<div class='info'>
        <h4>Hướng dẫn khắc phục:</h4>
        <ol>
            <li>Đảm bảo MySQL/XAMPP đang chạy</li>
            <li>Tạo database 'fast_food'</li>
            <li>Import file database/food_store_db.sql</li>
            <li>Chạy lại script này</li>
        </ol>
    </div>";
}

echo "    </div>
</body>
</html>";
?>