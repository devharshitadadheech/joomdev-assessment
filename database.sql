CREATE DATABASE IF NOT EXISTS joomdev;
CREATE DATABASE IF NOT EXISTS joomdev;

USE joomdev;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_password_reset TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT username_unique UNIQUE (username),
    password_change_required int(10) NOT NULL DEFAULT 1
);

INSERT INTO users (username, password, first_name, last_name, email, phone, role, password_change_required)
VALUES ('admin', '$2y$10$3tZo3FvH9R/.pGw9AeJuLeIBRyY.WXCGcChjvhTcyyEWh9S2F5xuO', 'Admin', 'User', 'admin@example.com', '123456789', 'admin', 0);

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    start_time TIMESTAMP NOT NULL,
    stop_time TIMESTAMP NOT NULL,
    notes TEXT,
    description VARCHAR(255) NOT NULL
);