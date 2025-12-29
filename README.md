# 🍔 FAST FOOD - Hệ thống Đặt Đồ Ăn Nhanh Online

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6+-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

**Website đặt đồ ăn nhanh hoàn chỉnh với giao diện hiện đại**

---

## 🚀 Tính năng chính

### 👤 Khách hàng
- 🏠 Trang chủ với sản phẩm nổi bật
- 🍔 Menu món ăn với tìm kiếm realtime
- 🛒 Giỏ hàng (lưu localStorage)
- 💳 Đặt hàng & thanh toán
- 📦 Theo dõi trạng thái đơn hàng
- ⭐ Đánh giá sản phẩm (1-5 sao)
- 👤 Quản lý hồ sơ cá nhân
- 📬 Gửi tin nhắn liên hệ
- 🔐 Đăng nhập / Đăng ký

### 👨‍💼 Quản trị viên (Admin)
- 📊 Dashboard thống kê (doanh thu, đơn hàng, biểu đồ)
- 📦 Quản lý đơn hàng (xem, cập nhật trạng thái, xóa)
- 🍕 Quản lý sản phẩm (CRUD, upload ảnh)
- ⭐ Quản lý đánh giá (duyệt, từ chối)
- 👥 Quản lý người dùng
- 📬 Quản lý & phản hồi liên hệ

---

## 📁 Cấu trúc dự án

```
fast_food/
├── frontend/                    # Giao diện
│   ├── index.php               # Trang chủ (router)
│   ├── pages/                  # Trang công khai
│   │   ├── home.php            # Trang chủ nội dung
│   │   ├── menu.php            # Danh sách món ăn
│   │   ├── product_detail.php  # Chi tiết sản phẩm
│   │   ├── cart.php            # Giỏ hàng
│   │   ├── contact.php         # Liên hệ
│   │   └── login.php           # Đăng nhập/Đăng ký
│   ├── user/pages/             # Trang người dùng (yêu cầu đăng nhập)
│   │   ├── checkout.php        # Thanh toán
│   │   ├── orders.php          # Danh sách đơn hàng
│   │   ├── order_status.php    # Theo dõi đơn hàng
│   │   ├── detail.php          # Chi tiết đơn hàng
│   │   ├── profile.php         # Hồ sơ cá nhân
│   │   ├── my_contacts.php     # Tin nhắn của tôi
│   │   └── review_order.php    # Đánh giá đơn hàng
│   ├── admin/pages/            # Trang quản trị (yêu cầu admin)
│   │   ├── dashboard.php       # Bảng điều khiển
│   │   ├── orders.php          # Quản lý đơn hàng
│   │   ├── products.php        # Quản lý sản phẩm
│   │   ├── reviews.php         # Quản lý đánh giá
│   │   ├── users.php           # Quản lý người dùng
│   │   └── contacts.php        # Quản lý liên hệ
│   ├── includes/               # Header, Footer
│   └── assets/                 # CSS, JS, Images
│
├── backend/                    # API Backend
│   ├── config.php              # Cấu hình database
│   ├── auth_*.php              # API xác thực
│   ├── products_list.php       # API sản phẩm
│   ├── orders_*.php            # API đơn hàng
│   ├── reviews_*.php           # API đánh giá
│   ├── contact_*.php           # API liên hệ
│   ├── user_*.php              # API người dùng
│   └── admin/                  # API quản trị
│
└── database/                   # SQL Scripts
    └── fast_food.sql           # Database schema
```

---

## 🛠️ Cài đặt

### Yêu cầu
- PHP 7.4+
- MySQL 5.7+
- XAMPP / WAMP / LAMP

### Các bước

1. **Clone dự án vào htdocs**
   ```bash
   cd C:\xampp\htdocs
   git clone <repo-url> fast_food
   ```

2. **Tạo database**
   - Mở phpMyAdmin: `http://localhost/phpmyadmin`
   - Tạo database: `fast_food`
   - Import file: `database/fast_food.sql`

3. **Cấu hình database** (nếu cần)
   ```php
   // backend/config.php
   define('DB_HOST', '127.0.0.1');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'fast_food');
   ```

4. **Tạo tài khoản Admin**
   ```
   http://localhost/fast_food/backend/bootstrap_admin_web.php
   ```

5. **Truy cập website**
   ```
   http://localhost/fast_food/
   ```

---

## 🔑 Tài khoản Demo

| Vai trò | Username | Password |
|---------|----------|----------|
| Admin | admin | admin123 |
| User | (tự đăng ký) | - |

> ⚠️ Đổi mật khẩu admin sau khi cài đặt!

---

## 📱 Screenshots

### Trang chủ
- Hero section với CTA
- Sản phẩm nổi bật
- Responsive design

### Menu
- Grid sản phẩm 3 cột
- Tìm kiếm realtime
- Quick search tags

### Admin Dashboard
- Thống kê KPI
- Biểu đồ doanh thu 7 ngày
- Top sản phẩm bán chạy
- Đơn hàng gần đây

---

## 🔧 Công nghệ

- **Backend**: PHP thuần, PDO, Session
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Styling**: CSS Grid, Flexbox, Gradient
- **Storage**: LocalStorage (giỏ hàng)

---

## 📝 Ghi chú

- Phân quyền đăng nhập: User chỉ đăng nhập vai trò User, Admin chỉ đăng nhập vai trò Admin
- Admin không thể thêm giỏ hàng, không thấy nút Liên hệ
- Giỏ hàng lưu localStorage, tự động chuyển đến trang giỏ hàng khi thêm sản phẩm
- Auto-refresh dữ liệu mỗi 30 giây

---

## 📄 License

MIT License - Sử dụng tự do cho mục đích học tập và thương mại.

---

**Made with ❤️ by Fast Food Team**
