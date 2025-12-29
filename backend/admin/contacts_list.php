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

$logFile = __DIR__ . '/../contact_messages.log';

try {
    $contacts = [];
    
    if (file_exists($logFile)) {
        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $index => $line) {
            // Parse format: [2024-12-23 10:30:00] Name <email> (phone): message
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (.+?) <(.+?)> \((.+?)\): (.+)$/', $line, $matches)) {
                $contacts[] = [
                    'id' => $index + 1,
                    'created_at' => $matches[1],
                    'name' => $matches[2],
                    'email' => $matches[3],
                    'phone' => $matches[4] === 'no-phone' ? '' : $matches[4],
                    'message' => $matches[5]
                ];
            }
        }
        
        // Sắp xếp mới nhất lên đầu
        $contacts = array_reverse($contacts);
    }
    
    echo json_encode([
        'ok' => true,
        'contacts' => $contacts,
        'total' => count($contacts)
    ], JSON_UNESCAPED_UNICODE);
    
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
