CREATE DATABASE novel_archive;

USE novel_archive;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('non-premium', 'premium') DEFAULT 'non-premium',
    token VARCHAR(255) DEFAULT NULL,
    token_expire DATETIME DEFAULT NULL
);
