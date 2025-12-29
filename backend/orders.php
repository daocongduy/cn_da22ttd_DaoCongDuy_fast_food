<?php
header('Content-Type: application/json; charset=UTF-8');

// Mock dữ liệu đơn hàng cho mục đích demo giao diện
$orders = [
    [
        'id' => 2001,
        'date' => '2025-10-14 10:15',
        'total' => 35000,
        'status' => 'Chờ xác nhận',
        'items' => [
            ['name' => 'Burger Bò', 'qty' => 1, 'price' => 35000],
        ],
    ],
    [
        'id' => 2002,
        'date' => '2025-10-14 11:40',
        'total' => 89000,
        'status' => 'Đang giao',
        'items' => [
            ['name' => 'Gà rán phần', 'qty' => 1, 'price' => 45000],
            ['name' => 'Khoai tây', 'qty' => 1, 'price' => 22000],
            ['name' => 'Coca', 'qty' => 1, 'price' => 22000],
        ],
    ],
];

echo json_encode(['orders' => $orders], JSON_UNESCAPED_UNICODE);
?>

