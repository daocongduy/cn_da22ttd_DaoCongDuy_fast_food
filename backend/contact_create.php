<?php
// backend/contact_create.php
// API nhận liên hệ từ người dùng và lưu vào file log để admin xem.

session_start();
header('Content-Type: application/json; charset=utf-8');

// Chỉ cho phép POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Đọc dữ liệu JSON từ body
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'Dữ liệu không hợp lệ'
    ]);
    exit;
}

$name    = trim($data['name'] ?? '');
$email   = trim($data['email'] ?? '');
$phone   = trim($data['phone'] ?? '');
$message = trim($data['message'] ?? '');

// Validate đơn giản
$errors = [];
if ($name === '') {
    $errors['name'] = 'Vui lòng nhập họ tên';
}
if ($email === '') {
    $errors['email'] = 'Vui lòng nhập email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Email không hợp lệ';
}
if ($message === '') {
    $errors['message'] = 'Vui lòng nhập nội dung liên hệ';
}

if ($errors) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => 'Vui lòng kiểm tra lại thông tin',
        'errors' => $errors
    ]);
    exit;
}

// Lưu liên hệ vào file log (không cần chỉnh DB)
$logFile = __DIR__ . '/contact_messages.log';
$time    = date('Y-m-d H:i:s');

// Loại bỏ xuống dòng trong message để log gọn
$flatMessage = str_replace(["\r", "\n"], ' ', $message);
$line = sprintf(
    "[%s] %s <%s> (%s): %s%s",
    $time,
    $name,
    $email,
    $phone ?: 'no-phone',
    $flatMessage,
    PHP_EOL
);

try {
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => 'Không thể lưu liên hệ, vui lòng thử lại sau'
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'message' => 'Đã gửi liên hệ thành công! Admin sẽ phản hồi trong thời gian sớm nhất.'
]);
exit;

