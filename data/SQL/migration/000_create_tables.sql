DROP DATABASE IF EXISTS todo_server;
CREATE DATABASE IF NOT EXISTS todo_server;
USE todo_server;
CREATE TABLE todos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,       
    description TEXT,                        
    status      ENUM('open','done') DEFAULT 'open',
    created_at  DATETIME DEFAULT NOW(),      
    updated_at  DATETIME DEFAULT NOW() ON UPDATE NOW(),
    deleted_at  DATETIME DEFAULT NULL        -- Soft Delete
)ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

