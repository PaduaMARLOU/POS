CREATE DATABASE IF NOT EXISTS pos_db;
USE pos_db;

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


-- Configs table
CREATE TABLE IF NOT EXISTS configs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type VARCHAR(100) NOT NULL,
  content TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_code VARCHAR(50) NOT NULL UNIQUE,     -- Unique SKU or Barcode
  name VARCHAR(100) NOT NULL,                   -- Product name/title
  description TEXT DEFAULT NULL,                -- Optional product description

  category ENUM(                                -- Fixed list of common categories
    'Beverages', 'Snacks', 'Electronics', 'Groceries',
    'Apparel', 'Cosmetics', 'Stationery', 'Others'
  ) DEFAULT 'Others',

  brand ENUM(                                   -- Fixed list of brands (can be expanded)
    'Generic', 'Coca-Cola', 'Pepsi', 'Samsung',
    'LG', 'Sony', 'Apple', 'Other'
  ) DEFAULT 'Generic',

  cost_price DECIMAL(10,2) NOT NULL DEFAULT 0,  -- Purchase price from supplier
  selling_price DECIMAL(10,2) NOT NULL,         -- Selling price to customer
  quantity_in_stock INT NOT NULL DEFAULT 0,     -- Available stock quantity
  reorder_level INT DEFAULT 10,                 -- Threshold for low stock alert

  unit ENUM(                                    -- Unit of measure
    'pcs', 'kg', 'g', 'liters', 'ml', 'box', 'pack', 'set'
  ) DEFAULT 'pcs',

  is_active BOOLEAN DEFAULT TRUE,               -- Toggle for active/inactive listing
  image_path VARCHAR(255) DEFAULT NULL,         -- Optional path to product image

  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,                     -- Creation timestamp
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP -- Last updated
);
