<?php
require_once __DIR__ . '/config.php';

echo "<h1>🛠️ Khôi phục Admin Account</h1>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
.container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.success { color: #10b981; background: #d1fae5; padding: 15px; border-radius: 8px; margin: 10px 0; }
.error { color: #ef4444; background: #fee2e2; padding: 15px; border-radius: 8px; margin: 10px 0; }
.info { color: #3b82f6; background: #dbeafe; padding: 15px; border-radius: 8px; margin: 10px 0; }
.warning { color: #f59e0b; background: #fef3c7; padding: 15px; border-radius: 8px; margin: 10px 0; }
.btn { background: #3b82f6; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin: 5px; }
.btn:hover { background: #2563eb; }
</style>";

echo "<div class='container'>";

try {
    $db = getDb();
    
    // Kiểm tra xem đã có admin nào chưa (dựa trên cột role trong bảng users hiện tại)
    $stmt = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "admin"');
    $adminCount = $stmt->fetch()['count'] ?? 0;
    
    if ($adminCount > 0) {
        echo "<div class='warning'>⚠️ Đã có $adminCount admin trong hệ thống.</div>";
        
        // Hiển thị danh sách admin hiện tại (schema hiện tại không có cột username)
        $stmt = $db->query('SELECT id, name, email FROM users WHERE role = "admin"');
        $admins = $stmt->fetchAll();
        
        echo "<h3>👥 Danh sách Admin hiện tại:</h3>";
        echo "<ul>";
        foreach ($admins as $admin) {
            echo "<li>ID: {$admin['id']} - {$admin['name']} - {$admin['email']}</li>";
        }
        echo "</ul>";
    }
    
    // Thông tin admin mặc định (phù hợp với bảng users hiện tại)
    $adminData = [
        'name' => 'Administrator',
        // Có thể đăng nhập bằng email này
        'email' => 'admin@fastfood.com',
        'password' => 'admin123',
        'role' => 'admin',
        'phone' => '0123456789',
        'address' => 'Hệ thống quản trị'
    ];
    
    echo "<h3>📝 Thông tin admin sẽ được tạo:</h3>";
    echo "<ul>";
    echo "<li><strong>Tên:</strong> " . $adminData['name'] . "</li>";
    echo "<li><strong>Email:</strong> " . $adminData['email'] . "</li>";
    echo "<li><strong>Password:</strong> " . $adminData['password'] . "</li>";
    echo "<li><strong>Role:</strong> " . $adminData['role'] . "</li>";
    echo "<li><strong>Phone:</strong> " . $adminData['phone'] . "</li>";
    echo "<li><strong>Address:</strong> " . $adminData['address'] . "</li>";
    echo "</ul>";
    
    // Kiểm tra xem email đã tồn tại chưa (schema hiện tại không có cột username)
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$adminData['email']]);
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        echo "<div class='info'>ℹ️ Email đã tồn tại!</div>";
        echo "<div class='info'>ID user hiện tại: " . $existingUser['id'] . "</div>";
        
        // Cập nhật role thành admin
        $stmt = $db->prepare('UPDATE users SET role = "admin" WHERE id = ?');
        $stmt->execute([$existingUser['id']]);
        
        echo "<div class='success'>✅ Đã cập nhật user ID " . $existingUser['id'] . " thành admin!</div>";
    } else {
        // Tạo admin mới theo đúng cấu trúc bảng users (password_hash thay vì password, không có username)
        $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role, phone, address, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $result = $stmt->execute([
            $adminData['name'],
            $adminData['email'],
            password_hash($adminData['password'], PASSWORD_DEFAULT),
            $adminData['role'],
            $adminData['phone'],
            $adminData['address']
        ]);
        
        if ($result) {
            $newAdminId = $db->lastInsertId();
            echo "<div class='success'>✅ Đã tạo admin mới thành công!</div>";
            echo "<div class='info'>🆔 Admin ID: " . $newAdminId . "</div>";
        } else {
            echo "<div class='error'>❌ Lỗi khi tạo admin!</div>";
            exit;
        }
    }
    
    echo "<div class='success'>";
    echo "<h3>🎉 HOÀN THÀNH!</h3>";
    echo "<p>Bạn có thể đăng nhập với:</p>";
    echo "<ul>";
    echo "<li><strong>Email:</strong> " . $adminData['email'] . "</li>";
    echo "<li><strong>Password:</strong> " . $adminData['password'] . "</li>";
    echo "</ul>";
    echo "<div class='warning'>⚠️ LƯU Ý: Hãy đổi mật khẩu sau khi đăng nhập!</div>";
    echo "</div>";
    
    echo "<div style='margin-top: 20px;'>";
    echo "<a href='../frontend/pages/login.php' class='btn'>🔑 Đăng nhập ngay</a>";
    echo "<a href='../frontend/admin/' class='btn'>🏠 Vào Admin Panel</a>";
    echo "</div>";
    
} catch (Throwable $e) {
    echo "<div class='error'>❌ Lỗi: " . $e->getMessage() . "</div>";
}

echo "</div>";
?>

