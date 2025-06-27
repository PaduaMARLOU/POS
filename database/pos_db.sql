-- Create and use the database
CREATE DATABASE IF NOT EXISTS pos_db;
USE pos_db;

-- ====================
-- USERS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  account_type ENUM('admin', 'user', 'staff') NOT NULL DEFAULT 'user',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  last_login DATETIME DEFAULT NULL,
  login_attempts INT DEFAULT 0,
  is_locked BOOLEAN DEFAULT FALSE,
  is_active BOOLEAN DEFAULT TRUE,
  email VARCHAR(100) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL
);

-- ====================
-- CONFIGS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS configs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ====================
-- PRODUCTS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_code VARCHAR(50) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL,
  description TEXT DEFAULT NULL,
  category ENUM('Beverages', 'Snacks', 'Electronics', 'Groceries', 'Apparel', 'Cosmetics', 'Stationery', 'Others') DEFAULT 'Others',
  brand ENUM('Generic', 'Coca-Cola', 'Pepsi', 'Samsung', 'LG', 'Sony', 'Apple', 'Other') DEFAULT 'Generic',
  cost_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  selling_price DECIMAL(10,2) NOT NULL,
  quantity_in_stock INT NOT NULL DEFAULT 0,
  reorder_level INT DEFAULT 10,
  unit ENUM('pcs', 'kg', 'g', 'liters', 'ml', 'box', 'pack', 'set') DEFAULT 'pcs',
  is_active BOOLEAN DEFAULT TRUE,
  image_path VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ====================
-- CUSTOMERS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ====================
-- SALES TABLE
-- ====================
CREATE TABLE IF NOT EXISTS sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_number VARCHAR(20) NOT NULL UNIQUE,
  cashier_id INT NOT NULL,
  customer_id INT DEFAULT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  total_items INT NOT NULL,
  discount DECIMAL(10,2) DEFAULT 0.00,
  payment_method ENUM('cash', 'card', 'gcash', 'others') DEFAULT 'cash',
  amount_paid DECIMAL(10,2) NOT NULL,
  change_given DECIMAL(10,2) DEFAULT 0.00,
  is_credit BOOLEAN DEFAULT FALSE, -- Marks whether this is a debt-based sale
  sale_time DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cashier_id) REFERENCES users(id),
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- ====================
-- SALE ITEMS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS sale_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- ====================
-- PAYMENT LOGS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS payment_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sale_id INT NOT NULL,
  payment_method ENUM('cash', 'card', 'gcash', 'others') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  paid_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE
);

-- ====================
-- DEBTS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS debts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  sale_id INT NOT NULL,
  debt_amount DECIMAL(10,2) NOT NULL,
  amount_paid DECIMAL(10,2) DEFAULT 0.00,
  balance DECIMAL(10,2) GENERATED ALWAYS AS (debt_amount - amount_paid) STORED,
  due_date DATE DEFAULT NULL,
  is_settled BOOLEAN DEFAULT FALSE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (sale_id) REFERENCES sales(id)
);

-- ====================
-- DEBT PAYMENTS TABLE
-- ====================
CREATE TABLE IF NOT EXISTS debt_payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  debt_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  paid_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  received_by INT DEFAULT NULL,
  remarks TEXT DEFAULT NULL,
  FOREIGN KEY (debt_id) REFERENCES debts(id) ON DELETE CASCADE,
  FOREIGN KEY (received_by) REFERENCES users(id)
);
