-- Create database if it does not exist
CREATE DATABASE IF NOT EXISTS ecommerce_db;

-- Select the database to use
USE ecommerce_db;

-- Create categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each category
    category_name VARCHAR(100) NOT NULL -- Name of the category
);

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each product
    name VARCHAR(255) NOT NULL,        -- Product name
    price DECIMAL(10,2) NOT NULL,      -- Product price with 2 decimal places
    category_id INT NULL,              -- Foreign key (can be NULL if no category)
    stock INT NOT NULL DEFAULT 0,      -- Stock quantity (default is 0)

    -- Define foreign key relationship with categories table
    -- If a category is deleted, set category_id to NULL instead of deleting product
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Insert sample data into categories table
INSERT INTO categories (category_name) VALUES ('Electronics'), ('Clothing'), ('Books');

-- Insert sample data into products table
INSERT INTO products (name, price, category_id, stock) VALUES 
('Laptop', 999.99, 1, 5),     -- Low stock (should be highlighted in red)
('T-Shirt', 19.99, 2, 50),    -- Normal stock
('Smartphone', 699.00, 1, 8), -- Low stock (should be highlighted in red)
('Novel', 14.50, 3, 20),      -- Normal stock
('Unbranded Desk', 45.00, NULL, 12); -- Product without category
