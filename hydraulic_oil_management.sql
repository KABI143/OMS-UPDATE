-- Create the database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS hydraulic_oil_management;
USE hydraulic_oil_management;


-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert a sample user (password is 'password123' hashed using PHP's password_hash function)
INSERT INTO users (username, password) 
VALUES ('admin', '123');

-- =========================================================
-- Table: oil_inventory
-- Purpose: Stores details of different oil types and current stock quantity
-- =========================================================

CREATE TABLE IF NOT EXISTS oil_inventory (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Primary Key (auto-incremented)
    oil_name VARCHAR(100) NOT NULL,    -- Name of the oil
    stock_quantity INT(11) DEFAULT 0,  -- Current stock quantity
    quantity_received INT(11) DEFAULT 0,  -- Quantity of oil received
    received_by VARCHAR(100) NOT NULL  -- Name of person who received the oil
);

ALTER TABLE oil_inventory ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- =========================================================
-- Table: oil_usage
-- Purpose: Tracks the usage of oil on different machines
-- =========================================================
CREATE TABLE IF NOT EXISTS oil_usage (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each record
    oil_id INT NOT NULL,  -- Foreign Key referencing oil_inventory (id)
    machine_name VARCHAR(255) NOT NULL, -- Machine where the oil is used
    date DATE NOT NULL,   -- Date of oil usage
    quantity_used INT NOT NULL CHECK (quantity_used > 0), -- Quantity of oil used (must be positive)
    used_by VARCHAR(255) NOT NULL,  -- Name of person who used the oil
    used_reason VARCHAR(255) NOT NULL, -- Reason for oil usage
    remarks VARCHAR(255),  -- Optional remarks about the usage
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date and time when the usage was recorded
    FOREIGN KEY (oil_id) REFERENCES oil_inventory(id) ON DELETE CASCADE -- Link oil_id to oil_inventory(id)
);

 ALTER TABLE oil_usage ADD COLUMN oil_name VARCHAR(255);
-- =========================================================
-- Table: dashboard
-- Purpose: Stores a summary of total oil stock
-- =========================================================
CREATE TABLE IF NOT EXISTS dashboard (
    id INT AUTO_INCREMENT PRIMARY KEY,
    total_oil DECIMAL(10, 2) DEFAULT 0 -- Total oil in stock (calculated from oil_inventory)
);

-- Insert initial total oil stock if not already present
INSERT INTO dashboard (total_oil) 
SELECT COALESCE(SUM(stock_quantity + quantity_received), 0) FROM oil_inventory 
WHERE NOT EXISTS (SELECT 1 FROM dashboard);

-- =========================================================
-- Delimiter change to support triggers
-- =========================================================
DELIMITER $$

-- =========================================================
-- Trigger: update_dashboard_after_insert
-- Purpose: Update total oil in the dashboard after an INSERT to oil_inventory
-- =========================================================
DROP TRIGGER IF EXISTS update_dashboard_after_insert$$
CREATE TRIGGER update_dashboard_after_insert 
AFTER INSERT 
ON oil_inventory 
FOR EACH ROW 
BEGIN
    -- Recalculate the total oil in stock and update the dashboard
    UPDATE dashboard SET total_oil = (SELECT COALESCE(SUM(stock_quantity + quantity_received), 0) FROM oil_inventory);
END$$

-- =========================================================
-- Trigger: update_dashboard_after_update
-- Purpose: Update total oil in the dashboard after an UPDATE to oil_inventory
-- =========================================================
DROP TRIGGER IF EXISTS update_dashboard_after_update$$
CREATE TRIGGER update_dashboard_after_update 
AFTER UPDATE 
ON oil_inventory 
FOR EACH ROW 
BEGIN
    -- Recalculate the total oil in stock and update the dashboard
    UPDATE dashboard SET total_oil = (SELECT COALESCE(SUM(stock_quantity + quantity_received), 0) FROM oil_inventory);
END$$

-- =========================================================
-- Trigger: update_dashboard_after_delete
-- Purpose: Update total oil in the dashboard after a DELETE from oil_inventory
-- =========================================================
DROP TRIGGER IF EXISTS update_dashboard_after_delete$$
CREATE TRIGGER update_dashboard_after_delete 
AFTER DELETE 
ON oil_inventory 
FOR EACH ROW 
BEGIN
    -- Recalculate the total oil in stock and update the dashboard
    UPDATE dashboard SET total_oil = (SELECT COALESCE(SUM(stock_quantity + quantity_received), 0) FROM oil_inventory);
END$$

-- =========================================================
-- Reset the delimiter back to semicolon
-- =========================================================
DELIMITER ;

-- =========================================================
-- Example Data for oil_inventory
-- =========================================================
INSERT INTO oil_inventory (oil_name, stock_quantity, quantity_received, received_by) VALUES
    ('Hydraulic Oil A', 100, 50, 'John Doe'), 
    ('Hydraulic Oil B', 200, 100, 'Jane Smith')
ON DUPLICATE KEY UPDATE stock_quantity = VALUES(stock_quantity), quantity_received = VALUES(quantity_received), received_by = VALUES(received_by);

-- =========================================================
-- Example Data for oil_usage
-- =========================================================
INSERT INTO oil_usage (oil_id, machine_name, date, quantity_used, used_by, used_reason, remarks) 
SELECT id, 'Machine 1', '2024-12-01', 10, 'John Doe', 'Routine maintenance', 'No issues' FROM oil_inventory WHERE oil_name = 'Hydraulic Oil A';

INSERT INTO oil_usage (oil_id, machine_name, date, quantity_used, used_by, used_reason, remarks) 
SELECT id, 'Machine 2', '2024-12-05', 20, 'Jane Smith', 'System upgrade', 'No issues' FROM oil_inventory WHERE oil_name = 'Hydraulic Oil B';

-- =========================================================
-- Update dashboard total_oil based on initial stock
-- =========================================================
UPDATE dashboard SET total_oil = (SELECT COALESCE(SUM(stock_quantity + quantity_received), 0) FROM oil_inventory);


