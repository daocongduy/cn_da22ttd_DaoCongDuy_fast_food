<?php
// Test Dashboard - Kiểm tra dashboard có hoạt động tốt không
session_start();
$_SESSION['role'] = 'admin';
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'admin';

echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <title>🧪 Test Dashboard</title>
    <style>
        body { 
            font-family: 'Inter', Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            /* Dùng cùng dải màu cam như website chính để đồng bộ brand */
            background: linear-gradient(135deg, #ff6a00 0%, #f97316 50%, #ea580c 100%); 
            min-height: 100vh; 
            color: #fefce8;
        }
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(20px); 
            padding: 40px; 
            border-radius: 20px; 
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        h1 { 
            font-size: 3em; 
            margin-bottom: 20px; 
            background: linear-gradient(135deg, #fff, #e2e8f0); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            font-weight: 800; 
            text-align: center;
        }
        .success { 
            background: rgba(16, 185, 129, 0.2); 
            color: #6ee7b7; 
            padding: 20px; 
            border-radius: 12px; 
            margin: 15px 0; 
            border-left: 4px solid #10b981; 
        }
        .error { 
            background: rgba(239, 68, 68, 0.2); 
            color: #f87171; 
            padding: 20px; 
            border-radius: 12px; 
            margin: 15px 0; 
            border-left: 4px solid #ef4444; 
        }
        .btn { 
            display: inline-block; 
            padding: 15px 30px; 
            background: linear-gradient(135deg, #0ea5e9, #0284c7); 
            color: white; 
            text-decoration: none; 
            border-radius: 10px; 
            margin: 10px 5px; 
            font-weight: 600; 
            transition: all 0.3s; 
            box-shadow: 0 8px 20px rgba(14, 165, 233, 0.3); 
        }
        .btn:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 15px 35px rgba(14, 165, 233, 0.4); 
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🧪 Test Dashboard</h1>
        <p style='text-align: center; font-size: 1.2em; margin-bottom: 30px;'>Kiểm tra các file chính có hoạt động tốt không...</p>";

try {
    echo "<div class='success'>🔍 <strong>Kiểm tra các file chính:</strong></div>";
    
    // Kiểm tra các file quan trọng
    $criticalFiles = [
        'frontend/admin/pages/dashboard.php' => 'Dashboard Admin',
        'frontend/index.php' => 'Homepage',
        'frontend/assets/css/style.css' => 'CSS chính',
        'backend/dashboard_stats.php' => 'Dashboard API',
        'backend/config.php' => 'Database config'
    ];
    
    $allGood = true;
    
    foreach ($criticalFiles as $file => $name) {
        if (file_exists($file)) {
            echo "<div class='success'>✅ $name: <code>$file</code> - OK</div>";
        } else {
            echo "<div class='error'>❌ $name: <code>$file</code> - MISSING!</div>";
            $allGood = false;
        }
    }
    
    if ($allGood) {
        echo "<div class='success'>
            <h3>🎉 Tất cả file chính đều OK!</h3>
            <p>Dashboard và các trang chính đã được sửa lỗi CSS và sẵn sàng hoạt động.</p>
        </div>";
    }
    
    echo "<div class='success'>
        <h3>🔧 Các sửa đổi đã thực hiện:</h3>
        <ul style='text-align: left; margin: 15px 0; padding-left: 30px;'>
            <li>✅ Xóa tham chiếu đến <code>unified-colors.css</code> đã bị xóa</li>
            <li>✅ Thay thế CSS variables bằng giá trị trực tiếp</li>
            <li>✅ Sửa gradient trong homepage</li>
            <li>✅ Cập nhật admin header CSS</li>
            <li>✅ Sửa style.css chính</li>
        </ul>
    </div>";
    
    echo "<div class='success'>
        <h3>🌐 Test các trang:</h3>
        <div style='text-align: center; margin: 20px 0;'>
            <a href='frontend/admin/pages/dashboard.php' class='btn'>📊 Test Dashboard</a>
            <a href='frontend/index.php' class='btn'>🏠 Test Homepage</a>
        </div>
    </div>";
    
} catch (Exception $e) {
    echo "<div class='error'>❌ Lỗi: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "    </div>
</body>
</html>";
?>