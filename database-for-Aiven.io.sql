-- Tắt kiểm tra khóa ngoại tạm thời để có thể xóa/tạo bảng an toàn
SET FOREIGN_KEY_CHECKS = 0;

-- Xóa các bảng cũ nếu đã tồn tại (Đảm bảo database trên Aiven sạch sẽ 100% trước khi tạo mới)
DROP TABLE IF EXISTS order_details;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

-- 1. Tạo bảng Danh mục (Categories)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- 2. Tạo bảng Sản phẩm (Products)
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category_id INT,
    brand VARCHAR(50) NOT NULL,
    price INT NOT NULL,
    old_price INT DEFAULT NULL,
    image_url VARCHAR(255) NOT NULL,
    details TEXT,
    sales_count INT DEFAULT 0,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- 3. Tạo bảng Đơn hàng (Orders)
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    notes TEXT,
    total_amount INT NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending', -- Các trạng thái: Pending, Delivering, Completed, Cancelled
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tạo bảng Chi tiết Đơn hàng (Order Details)
CREATE TABLE order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price INT NOT NULL, 
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Bật lại kiểm tra khóa ngoại
SET FOREIGN_KEY_CHECKS = 1;

-- 5. Thêm các danh mục
INSERT INTO categories (name) VALUES 
('Điện thoại'), ('Laptop'), ('Tablet'), ('Phụ kiện'), ('Đồng hồ');

-- 6. Thêm sản phẩm mẫu (category_id = 1 là 'Điện thoại')
INSERT INTO products (name, category_id, brand, price, old_price, image_url, details, sales_count, is_featured) VALUES 
('Samsung Galaxy S24 Ultra 5G', 1, 'SAMSUNG', 31990000, 33990000, 'public/images/Phone-card-image-1.jpg', 'Cấu hình mạnh mẽ...', 150, TRUE),
('iPhone 15 Pro Max 256GB', 1, 'iPhone', 34990000, 36990000, 'public/images/Phone-card-image-2.jpg', 'Siêu phẩm Apple...', 500, TRUE),
('Xiaomi 14 5G', 1, 'xiaomi', 22990000, 24490000, 'public/images/Phone-card-image-3.jpg', 'Công nghệ hàng đầu...', 120, TRUE);