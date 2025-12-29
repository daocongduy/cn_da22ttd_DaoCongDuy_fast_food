# 🔌 Backend API Documentation

> API Backend cho hệ thống Fast Food

---

## 📋 Tổng quan

- **Ngôn ngữ**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Format**: JSON
- **Base URL**: `http://localhost/fast_food/backend/`

---

## 🔐 Authentication APIs

### POST `/auth_login.php`
Đăng nhập

```json
// Request
{
  "username": "admin",
  "password": "admin123",
  "role": "admin"  // "user" hoặc "admin"
}

// Response
{ "ok": true, "user_id": 1, "role": "admin" }
```

> **Lưu ý**: User chỉ đăng nhập được với role "user", Admin chỉ đăng nhập được với role "admin"

### POST `/auth_register.php`
Đăng ký tài khoản

```json
// Request
{
  "fullname": "Nguyễn Văn A",
  "email": "user@example.com",
  "username": "user123",
  "password": "password123"
}
```

### GET `/auth_me.php`
Lấy thông tin user hiện tại

### GET `/auth_logout.php`
Đăng xuất

---

## 🛒 User APIs

### GET `/products_list.php`
Danh sách sản phẩm (có rating)

### POST `/orders_create.php`
Tạo đơn hàng

```json
{
  "items": [{ "product_id": 1, "quantity": 2 }],
  "shipping": {
    "name": "Nguyễn Văn A",
    "phone": "0123456789",
    "address": "123 ABC"
  }
}
```

### GET `/orders_list.php`
Đơn hàng của user

### GET `/orders_detail.php?id=123`
Chi tiết đơn hàng

### DELETE `/user_order_delete.php`
Xóa đơn hàng (chỉ đơn pending)

```json
{ "order_id": 123 }
```

### GET `/user_order_check.php?order_id=123&product_id=1`
Kiểm tra user đã mua sản phẩm chưa (để cho phép đánh giá)

### POST `/reviews_create.php`
Tạo đánh giá sản phẩm

```json
{
  "product_id": 1,
  "order_id": 123,
  "rating": 5,
  "comment": "Rất ngon!"
}
```

### GET `/reviews_list.php?product_id=1`
Danh sách đánh giá sản phẩm

### POST `/contact_create.php`
Gửi tin nhắn liên hệ

### GET `/user_contacts.php`
Tin nhắn của user

### POST `/user_update.php`
Cập nhật hồ sơ

### POST `/user_change_password.php`
Đổi mật khẩu

---

## 👨‍💼 Admin APIs

### GET `/dashboard_stats.php`
Thống kê dashboard

```json
{
  "ok": true,
  "today": { "total_orders": 15, "revenue": 750000 },
  "total_orders": 150,
  "total_products": 20,
  "total_users": 100,
  "status_counts": { "pending": 5, "completed": 100 }
}
```

### GET `/admin/orders_list.php`
Tất cả đơn hàng

### POST `/admin/order_update_status.php`
Cập nhật trạng thái đơn

```json
{ "order_id": 123, "status": "confirmed" }
```

**Status**: pending → confirmed → preparing → delivering → completed / cancelled

### POST `/admin/order_delete.php`
Xóa đơn hàng (chỉ completed)

### GET `/admin/products_list.php`
Tất cả sản phẩm

### POST `/admin/product_create.php`
Tạo sản phẩm (multipart/form-data)

### POST `/admin/product_update.php`
Cập nhật sản phẩm

### POST `/admin/product_delete.php`
Xóa sản phẩm

### GET `/admin/reviews_list.php`
Tất cả đánh giá

### POST `/admin/reviews_update.php`
Duyệt/từ chối đánh giá

### GET `/admin/users_list.php`
Danh sách người dùng

### POST `/admin/user_delete.php`
Xóa người dùng

### GET `/admin/contacts_list.php`
Tất cả liên hệ

### POST `/admin/contact_reply.php`
Phản hồi liên hệ

### GET `/admin/top_products.php`
Top sản phẩm bán chạy

### GET `/admin/revenue_today.php`
Doanh thu hôm nay

---

## 📤 Response Format

### Success
```json
{ "ok": true, "data": {...} }
```

### Error
```json
{ "ok": false, "error": "Error message" }
```

---

## 🔒 Security

- Password hashing: `password_hash()` / `password_verify()`
- SQL Injection: PDO Prepared Statements
- Session-based authentication
- Role-based authorization (user/admin)

---

## 📝 Database Tables

- `users` - Người dùng
- `products` - Sản phẩm (có average_rating, total_reviews)
- `orders` - Đơn hàng
- `order_items` - Chi tiết đơn hàng
- `order_status_history` - Lịch sử trạng thái
- `product_reviews` - Đánh giá sản phẩm
- `contacts` - Liên hệ (file-based)
- `contact_replies` - Phản hồi liên hệ (file-based)

---

**[← Back to Main README](../README.md)**
