-- DELETE & CREATE FRESH DATABASE
DROP DATABASE IF EXISTS travel_booking;
CREATE DATABASE travel_booking;
USE travel_booking;

-- PASSENGERS TABLE (Stores all bookings)
CREATE TABLE passengers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    address TEXT,
    email VARCHAR(100),
    journey_date DATE NOT NULL,
    from_location VARCHAR(100) NOT NULL,
    to_location VARCHAR(100) NOT NULL,
    travel_mode VARCHAR(20) NOT NULL,
    coach_type VARCHAR(20),          -- ADDED HERE
    seat_number VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ADMIN TABLE (Simple login)
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- DEFAULT ADMIN LOGIN (admin / admin123)
INSERT INTO admin_users (username, password) VALUES ('admin', MD5('admin123'));

-- SUCCESS MESSAGE
SELECT 'Database created successfully!' as status;
