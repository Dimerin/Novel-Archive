CREATE DATABASE IF NOT EXISTS novel_archive;

USE novel_archive;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('non-premium', 'premium', 'admin') DEFAULT 'non-premium',
    token VARCHAR(255) DEFAULT NULL,
    token_expire DATETIME DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS files (
    id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        filetype VARCHAR(50) NOT NULL,
        filedata LONGBLOB NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_id INT NOT NULL REFERENCES users(id),
        visibility TINYINT(1) NOT NULL DEFAULT 0
);