DROP DATABASE IF EXISTS novel_archive;
CREATE DATABASE IF NOT EXISTS novel_archive;

USE novel_archive;

DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('free', 'pro', 'admin') DEFAULT 'free',
    active BOOLEAN DEFAULT FALSE
);

DROP TABLE IF EXISTS files;
CREATE TABLE IF NOT EXISTS files (
    id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        filetype VARCHAR(50) NOT NULL,
        filedata LONGBLOB NOT NULL,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        user_id INT NOT NULL REFERENCES users(id),
        visibility TINYINT(1) NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS tokens;
CREATE TABLE IF NOT EXISTS tokens (
    email VARCHAR(100) PRIMARY KEY,
    token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    purpose ENUM('register', 'reset') DEFAULT 'register'
);

DROP TABLE IF EXISTS login_attempts;
CREATE TABLE login_attempts (
    email VARCHAR(100) PRIMARY KEY,
    first_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_attempt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    attempts INT DEFAULT 0,
    timeouted BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (email) REFERENCES users(email) ON DELETE CASCADE
);