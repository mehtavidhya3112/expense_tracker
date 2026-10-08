-- ============================================================
-- Expense Tracker - Database Schema
-- Import this file in phpMyAdmin (or via `mysql -u root -p < schema.sql`)
-- before running the application.
-- ============================================================

CREATE DATABASE IF NOT EXISTS expense_tracker
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE expense_tracker;

CREATE TABLE IF NOT EXISTS expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    expense_date DATE NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Sample data (optional) - gives the dashboard something to
-- display immediately after setup. Safe to delete anytime.
-- ------------------------------------------------------------
INSERT INTO expenses (title, category, amount, expense_date, notes) VALUES
('Grocery Shopping',   'Food & Dining',      850.00, CURDATE(),                    'Weekly groceries from supermarket'),
('Bus Pass',           'Transportation',     500.00, CURDATE(),                    'Monthly bus pass renewal'),
('Movie Night',        'Entertainment',      400.00, CURDATE() - INTERVAL 2 DAY,   'Watched a movie with friends'),
('Electricity Bill',   'Bills & Utilities', 1200.00, CURDATE() - INTERVAL 5 DAY,   'Monthly electricity bill'),
('New Textbook',       'Education',          650.00, CURDATE() - INTERVAL 7 DAY,   'Data Structures textbook'),
('Doctor Visit',       'Health & Fitness',   300.00, CURDATE() - INTERVAL 10 DAY,  'Routine checkup');
