CREATE DATABASE IF NOT EXISTS krambl_store;
USE krambl_store;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    complete_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT NOT NULL,
    contact VARCHAR(30) NOT NULL,
    role ENUM('admin','buyer') NOT NULL DEFAULT 'buyer',
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    verification_token VARCHAR(100),
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    image VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    address TEXT NOT NULL,
    contact VARCHAR(30) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO categories (name) VALUES
('Klasik Pinoy'),
('Premium Merienda'),
('Seasonal Specials');

INSERT INTO products (category_id, name, description, price, stock, image) VALUES
(1, 'Ube Keso Krambl', 'Soft ube cookie with white chocolate and cheese.', 145.00, 30, 'assets/img/product-ube.svg'),
(1, 'Tablea Tsokolate', 'Chocolate cookie inspired by Filipino tablea.', 155.00, 22, 'assets/img/product-tablea.svg'),
(1, 'Buko Pandan Cloud', 'Pandan cookie with coconut and white chocolate.', 150.00, 18, 'assets/img/product-pandan.svg'),
(2, 'Mango Graham Melt', 'Mango cookie with graham crumble.', 165.00, 15, 'assets/img/product-mango.svg'),
(2, 'Leche Flan Brulee', 'Caramel cookie with custard-style topping.', 175.00, 12, 'assets/img/product-flan.svg'),
(3, 'Bibingka Holiday', 'Bibingka-inspired cookie with coconut and cheese.', 180.00, 8, 'assets/img/product-classic.svg');

-- Admin password: Admin123!
INSERT INTO users (complete_name, email, password, address, contact, role, is_verified, is_active) VALUES
('Krambl Administrator', 'admin@krambl.test', '$2y$12$jnG65GdQhkKkK9r/BYGK0ObonLvW4RYKUEAuJ8fzpEijwO8ve9cei', 'Krambl Office', '09171234567', 'admin', 1, 1);

-- Buyer password: Buyer123!
INSERT INTO users (complete_name, email, password, address, contact, role, is_verified, is_active) VALUES
('Sample Buyer', 'buyer@krambl.test', '$2y$12$kqalvBgI.HiPH7aeR923ougc8bhLNzrFZle.OxVfoaS6S8ixtzFB6', 'Quezon City, Philippines', '09181234567', 'buyer', 1, 1);
