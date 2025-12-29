<?php
require_once __DIR__ . '/config.php';

echo "=== TẠO LẠI ADMIN ACCOUNT ===\n\n";

try {
    $db = getDb();
    
    // Kiểm tra xem đã có admin nào chưa (dựa trên cột role trong bảng users hiện tại)
    $stmt = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "admin"');
    $adminCount = $stmt->fetch()['count'] ?? 0;
    
    if ($adminCount > 0) {
        echo "⚠️  Đã có $adminCount admin trong hệ thống.\n";
        echo "Bạn có muốn tạo thêm admin mới không? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);
        
        if (trim(strtolower($line)) !== 'y') {
        echo "❌ Hủy tạo admin.\n";
            exit;
        }
    }
    
    // Thông tin admin mặc định (sử dụng schema users hiện tại: name, email, password_hash, role, phone, address)
    $adminData = [
        'name' => 'Administrator',
        // Có thể đăng nhập bằng email này hoặc nhập trực tiếp trong ô "Email / Tài khoản"
        'email' => 'admin@fastfood.com',
        'password' => 'admin123',
        'role' => 'admin',
        'phone' => '0123456789',
        'address' => 'Hệ thống quản trị'
    ];
    
    echo "📝 Thông tin admin sẽ được tạo:\n";
    echo "- Tên: " . $adminData['name'] . "\n";
    echo "- Email: " . $adminData['email'] . "\n";
    echo "- Password: " . $adminData['password'] . "\n";
    echo "- Role: " . $adminData['role'] . "\n";
    echo "- Phone: " . $adminData['phone'] . "\n";
    echo "- Address: " . $adminData['address'] . "\n\n";
    
    // Kiểm tra xem email đã tồn tại chưa (schema hiện tại không có cột username)
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$adminData['email']]);
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        echo "❌ Email đã tồn tại!\n";
        echo "ID user hiện tại: " . $existingUser['id'] . "\n";
        
        // Cập nhật role thành admin
        $stmt = $db->prepare('UPDATE users SET role = "admin" WHERE id = ?');
        $stmt->execute([$existingUser['id']]);
        
        echo "✅ Đã cập nhật user ID " . $existingUser['id'] . " thành admin!\n";
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
            echo "✅ Đã tạo admin mới thành công!\n";
            echo "🆔 Admin ID: " . $newAdminId . "\n";
        } else {
            echo "❌ Lỗi khi tạo admin!\n";
            exit;
        }
    }
    
    echo "\n🎉 HOÀN THÀNH!\n";
    echo "Bạn có thể đăng nhập với:\n";
    echo "- Email: " . $adminData['email'] . "\n";
    echo "- Password: " . $adminData['password'] . "\n";
    echo "\n⚠️  LƯU Ý: Hãy đổi mật khẩu sau khi đăng nhập!\n";
    
} catch (Throwable $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?>
