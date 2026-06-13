USE phpd_mvc_rest;
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,

    email VARCHAR(100) NOT NULL UNIQUE,
    username VARCHAR(50) DEFAULT NULL , -- wird später ergenzet

    password VARCHAR(255) NOT NULL,

    role VARCHAR(20) NOT NULL DEFAULT 'user',
    status ENUM('active','block') DEFAULT 'active', -- unbenutzt 

    erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at    DATETIME DEFAULT NULL  

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;