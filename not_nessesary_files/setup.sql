DROP DATABASE IF EXISTS stms_db;
CREATE DATABASE stms_db;
USE stms_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher', 'student') NOT NULL,
    address TEXT NULL,
    department VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Admin (Password: 123456)
-- Hash generated for '123456'
INSERT INTO users (full_name, email, password, role, address) VALUES 
('System Admin', 'admin@stms.com', '$2y$10$Thp.6a8.p9Zg5.e5r6q.5.6z7s8t9u0v1w2x3y4z', 'admin', 'Sylhet, Bangladesh');