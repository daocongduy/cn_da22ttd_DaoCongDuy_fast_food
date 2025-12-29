<?php
header('Content-Type: application/json; charset=UTF-8');
session_start();

// Kiểm tra quyền admin
$role = $_SESSION['role'] ?? '';
if ($role !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied']);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$contactId = isset($payload['contact_id']) ? (int)$payload['contact_id'] : 0;
$replyMessage = isset($payload['reply']) ? trim($payload['reply']) : '';
$userEmail = isset($payload['email']) ? trim($payload['email']) : '';
$userName = isset($payload['name']) ? trim($payload['name']) : '';

if (!$contactId || !$replyMessage || !$userEmail) {
    http_response_code(400);
    echo json_encode(['error' => 'Dữ liệu không hợp lệ']);
    exit;
}

try {
    // Lưu phản hồi vào file log riêng
    $replyLogFile = __DIR__ . '/../contact_replies.log';
    $time = date('Y-m-d H:i:s');
    $adminName = $_SESSION['name'] ?? 'Admin';
    
    $flatReply = str_replace(["\r", "\n"], ' ', $replyMessage);
    $line = sprintf(
        "[%s] Reply to #%d (%s <%s>) by %s: %s%s",
        $time,
        $contactId,
        $userName,
        $userEmail,
        $adminName,
        $flatReply,
        PHP_EOL
    );
    
    file_put_contents($replyLogFile, $line, FILE_APPEND | LOCK_EX);
    
    echo json_encode([
        'ok' => true,
        'message' => 'Đã gửi phản hồi thành công!'
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
