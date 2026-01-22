-- =========================
-- CREATE DATABASE
-- =========================
CREATE DATABASE IF NOT EXISTS bottleneck_db;
USE bottleneck_db;

-- =========================
-- OPERATORS TABLE (username only)
-- =========================
CREATE TABLE operators (
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(50) NOT NULL
);

-- =========================
-- MACHINES TABLE
-- =========================
CREATE TABLE machines (
    machine_id VARCHAR(20) PRIMARY KEY,
    machine_name VARCHAR(100) NOT NULL
);

-- =========================
-- SHIFTS TABLE
-- =========================
CREATE TABLE shifts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shift_name VARCHAR(50) NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL
);

-- =========================
-- ALLOCATIONS TABLE
-- =========================
CREATE TABLE allocations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operator_username VARCHAR(50),
    machine_id VARCHAR(20),
    shift_id INT
);
CREATE TABLE machine_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    operator_username VARCHAR(50),
    machine_id VARCHAR(20),
    shift_id INT,
    status ENUM('ON','OFF') NOT NULL,
    reason VARCHAR(255),
    production INT DEFAULT 0,
    log_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    delay_seconds INT DEFAULT 0
);
