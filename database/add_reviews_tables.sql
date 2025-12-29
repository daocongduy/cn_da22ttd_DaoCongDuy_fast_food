-- Thêm bảng đánh giá món ăn
-- Chạy script này sau khi đã có database cơ bản

USE fast_food;

-- Bảng đánh giá sản phẩm
CREATE TABLE IF NOT EXISTS product_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_id INT NULL, -- Chỉ cho phép đánh giá nếu đã mua (optional)
    rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    is_approved TINYINT(1) DEFAULT 1, -- Admin có thể duyệt bình luận
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign keys
    CONSTRAINT fk_review_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_review_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_review_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    
    -- Một user chỉ đánh giá 1 lần cho 1 sản phẩm
    UNIQUE KEY unique_user_product (user_id, product_id)
) ENGINE=InnoDB;

-- Thêm cột rating trung bình vào bảng products
ALTER TABLE products 
ADD COLUMN average_rating DECIMAL(2,1) DEFAULT 0.0,
ADD COLUMN total_reviews INT DEFAULT 0;

-- Indexes để tối ưu performance
CREATE INDEX idx_reviews_product ON product_reviews(product_id);
CREATE INDEX idx_reviews_user ON product_reviews(user_id);
CREATE INDEX idx_reviews_approved ON product_reviews(is_approved);
CREATE INDEX idx_reviews_rating ON product_reviews(rating);

-- Trigger để tự động cập nhật rating trung bình khi có đánh giá mới
DELIMITER $$

CREATE TRIGGER update_product_rating_after_insert
AFTER INSERT ON product_reviews
FOR EACH ROW
BEGIN
    UPDATE products 
    SET 
        average_rating = (
            SELECT ROUND(AVG(rating), 1) 
            FROM product_reviews 
            WHERE product_id = NEW.product_id AND is_approved = 1
        ),
        total_reviews = (
            SELECT COUNT(*) 
            FROM product_reviews 
            WHERE product_id = NEW.product_id AND is_approved = 1
        )
    WHERE id = NEW.product_id;
END$$

CREATE TRIGGER update_product_rating_after_update
AFTER UPDATE ON product_reviews
FOR EACH ROW
BEGIN
    UPDATE products 
    SET 
        average_rating = (
            SELECT ROUND(AVG(rating), 1) 
            FROM product_reviews 
            WHERE product_id = NEW.product_id AND is_approved = 1
        ),
        total_reviews = (
            SELECT COUNT(*) 
            FROM product_reviews 
            WHERE product_id = NEW.product_id AND is_approved = 1
        )
    WHERE id = NEW.product_id;
END$$

CREATE TRIGGER update_product_rating_after_delete
AFTER DELETE ON product_reviews
FOR EACH ROW
BEGIN
    UPDATE products 
    SET 
        average_rating = COALESCE((
            SELECT ROUND(AVG(rating), 1) 
            FROM product_reviews 
            WHERE product_id = OLD.product_id AND is_approved = 1
        ), 0.0),
        total_reviews = (
            SELECT COUNT(*) 
            FROM product_reviews 
            WHERE product_id = OLD.product_id AND is_approved = 1
        )
    WHERE id = OLD.product_id;
END$$

DELIMITER ;

-- Dữ liệu mẫu (optional)
INSERT INTO product_reviews (product_id, user_id, rating, comment) VALUES
(1, 1, 5, 'Burger rất ngon, thịt bò tươi và phô mai tan chảy. Sẽ đặt lại!'),
(1, 2, 4, 'Ngon nhưng hơi mặn một chút. Nhìn chung vẫn ổn.'),
(2, 1, 5, 'Gà rán giòn tan, gia vị vừa phải. Rất hài lòng!'),
(3, 2, 3, 'Khoai tây hơi khô, cần cải thiện thêm.'),
(4, 1, 5, 'Coca-Cola luôn là lựa chọn tuyệt vời!');

-- Cập nhật rating cho các sản phẩm có sẵn
UPDATE products p 
SET 
    average_rating = (
        SELECT ROUND(AVG(rating), 1) 
        FROM product_reviews r 
        WHERE r.product_id = p.id AND r.is_approved = 1
    ),
    total_reviews = (
        SELECT COUNT(*) 
        FROM product_reviews r 
        WHERE r.product_id = p.id AND r.is_approved = 1
    );