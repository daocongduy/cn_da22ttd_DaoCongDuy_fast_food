<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/config.php';

// Khởi tạo session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra đăng nhập
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Vui lòng đăng nhập']);
    exit;
}

// Lấy email từ session hoặc database
$userEmail = $_SESSION['email'] ?? null;
if (!$userEmail) {
    try {
        $db = getDb();
        $stmt = $db->prepare('SELECT email FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        if ($row) {
            $userEmail = $row['email'];
            $_SESSION['email'] = $userEmail; // Lưu vào session cho lần sau
        }
    } catch (Throwable $e) {
        // ignore
    }
}

if (!$userEmail) {
    http_response_code(401);
    echo json_encode(['error' => 'Không tìm thấy thông tin email']);
    exit;
}

try {
    $contacts = [];
    $replies = [];
    
    // Đọc tin nhắn liên hệ
    $contactsFile = __DIR__ . '/contact_messages.log';
    if (file_exists($contactsFile)) {
        $lines = file($contactsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $index => $line) {
            // Parse: [2024-12-23 10:30:00] Name <email> (phone): message
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (.+?) <(.+?)> \((.+?)\): (.+)$/', $line, $matches)) {
                // Chỉ lấy tin nhắn của user hiện tại (theo email)
                if (strtolower($matches[3]) === strtolower($userEmail)) {
                    $contacts[] = [
                        'id' => $index + 1,
                        'type' => 'sent',
                        'created_at' => $matches[1],
                        'name' => $matches[2],
                        'email' => $matches[3],
                        'phone' => $matches[4] === 'no-phone' ? '' : $matches[4],
                        'message' => $matches[5]
                    ];
                }
            }
        }
    }
    
    // Đọc phản hồi từ admin
    $repliesFile = __DIR__ . '/contact_replies.log';
    if (file_exists($repliesFile)) {
        $lines = file($repliesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            // Parse: [2024-12-23 10:30:00] Reply to #1 (Name <email>) by Admin: message
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] Reply to #(\d+) \((.+?) <(.+?)>\) by (.+?): (.+)$/', $line, $matches)) {
                // Chỉ lấy phản hồi cho user hiện tại
                if (strtolower($matches[4]) === strtolower($userEmail)) {
                    $replies[] = [
                        'contact_id' => (int)$matches[2],
                        'type' => 'reply',
                        'created_at' => $matches[1],
                        'admin_name' => $matches[5],
                        'message' => $matches[6]
                    ];
                }
            }
        }
    }
    
    // Kết hợp và sắp xếp theo thời gian
    $allMessages = [];
    
    foreach ($contacts as $c) {
        $allMessages[] = [
            'id' => $c['id'],
            'type' => 'sent',
            'created_at' => $c['created_at'],
            'message' => $c['message']
        ];
        
        // Tìm phản hồi cho tin nhắn này
        foreach ($replies as $r) {
            if ($r['contact_id'] === $c['id']) {
                $allMessages[] = [
                    'id' => $c['id'],
                    'type' => 'reply',
                    'created_at' => $r['created_at'],
                    'admin_name' => $r['admin_name'],
                    'message' => $r['message']
                ];
            }
        }
    }
    
    // Sắp xếp theo thời gian mới nhất
    usort($allMessages, function($a, $b) {
        return strcmp($b['created_at'], $a['created_at']);
    });
    
    echo json_encode([
        'ok' => true,
        'messages' => $allMessages,
        'total_sent' => count($contacts),
        'total_replies' => count($replies)
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
