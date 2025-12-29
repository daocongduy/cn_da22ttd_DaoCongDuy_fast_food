-- fast_food schema & seed
CREATE DATABASE IF NOT EXISTS fast_food CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE fast_food;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(20),
  address VARCHAR(255),
  email VARCHAR(190) UNIQUE,
  password_hash VARCHAR(255),
  role ENUM('user','admin') DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(160) NOT NULL,
  price INT NOT NULL,
  image_url VARCHAR(255),
  is_active TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  status ENUM('pending','confirmed','preparing','delivering','completed','cancelled') DEFAULT 'pending',
  total_amount INT NOT NULL DEFAULT 0,
  shipping_name VARCHAR(120),
  shipping_phone VARCHAR(20),
  shipping_address VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price INT NOT NULL,
  total_price INT NOT NULL,
  CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_items_product FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_status_history (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  status ENUM('pending','confirmed','preparing','delivering','completed','cancelled') NOT NULL,
  note VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_hist_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX IF NOT EXISTS idx_orders_user ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status);
CREATE INDEX IF NOT EXISTS idx_items_order ON order_items(order_id);

-- Seed
INSERT INTO users(name, email, role) VALUES
('User Demo', 'user@example.com', 'user'),
('Admin Demo', 'admin@example.com', 'admin');

INSERT INTO products (name, price, image_url) VALUES
('Burger Bò Phô Mai', 55000, 'burger.jpg'),
('Gà Rán 2 Miếng', 69000, 'fried-chicken.jpg'),
('Khoai Tây Chiên', 29000, 'fries.jpg'),
('Coca-Cola 330ml', 15000, 'coke.jpg');

