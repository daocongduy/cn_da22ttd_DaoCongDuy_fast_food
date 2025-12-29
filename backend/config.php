<?php
// Kết nối PDO tới MySQL
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'fast_food');

function getDb() {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    ensureDefaultAdmin($pdo);
    return $pdo;
}

// Tạo tài khoản admin mặc định nếu chưa có (idempotent)
function ensureDefaultAdmin(PDO $pdo): void {
    try {
        $check = $pdo->query("SELECT id FROM users WHERE role='admin' LIMIT 1");
        if ($check->fetch()) { return; }
        $email = 'admin@example.com';
        $name = 'Admin';
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $ins = $pdo->prepare('INSERT INTO users(name, email, password_hash, role) VALUES(?, ?, ?, "admin")');
        $ins->execute([$name, $email, $hash]);
    } catch (Throwable $e) {
        // bỏ qua nếu bảng chưa tồn tại hoặc lỗi khác lúc khởi tạo
    }
}
?>

