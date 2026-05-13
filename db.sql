CREATE DATABASE IF NOT EXISTS technova_db;
USE technova_db;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    category VARCHAR(50) DEFAULT 'general',
    in_stock BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Initial admin (Username: admin, Password: admin1212)
-- Note: In a real app, always use hashed passwords.
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$vO.Nl2I5X9vH3ez0F6Q0tY6J8P7F3I2G1A5B4C3D2E1F'); 
-- Note: The above hash is a placeholder for 'admin1212'. The login script will handle updating this correctly on first login.

-- Initial items from index.html
INSERT INTO products (title, price, image, link, category) VALUES 
('AMD Ryzen 7 9800X3D (Up to 5.2 GHz 8-Cores 16-Treads ) 96M Cache', 499.00, 'image/0sIFk0MI5W2JSmrooRxpsCkERe6uoqn7.png', 'link/link.html', 'processor'),
('AORUS GeForce RTX™ 5090 MASTER 32GB GDDR7 512bit', 1999.00, 'image/GeForce-RTX-5090-WINDFORCE-OC-32.png', 'link/link1.html', 'gpu'),
('GIGABYTE Radeon™ RX 9060 XT OC Edition 16GB GDDR6', 899.00, 'image/Gigabyte-Radeon-RX-9060-XT-GAMIN.png', 'link/link2.html', 'gpu'),
('ASUS Prime Gaming Radeon™ RX 9070 XT OC Edition 16GB GDDR6', 299.00, 'image/LwteIH3VEIJTxozpY6uAEzIawd91YvAF.png', 'link/link3.html', 'gpu'),
('Intel 13th Gen i7', 149.00, 'image/YRx84j0nsS5GPdsfuY3cqrFW9E8WrIyT.png', 'link/link4.html', 'processor'),
('ASUS Prime Gaming Geforce RTX 5080 16GB GDDR7', 189.00, 'image/aDY9t7cRhhgW2OzoWzgrIICSdgfrDR23.png', 'link/link5.html', 'gpu'),
('Intel® Core Ultra 9 Processor 285K', 249.00, 'image/qWLGRyQhfhNP2v6f368GPIqtIHje430V.png', 'link/link6.html', 'processor'),
('INTEL CORE I7 14700F (33M CACHE, UP TO 5.40 GHZ)', 229.00, 'image/y022zuJlr2CZzqp48LeLMsyzlGSPRFD9.png', 'link/link7.html', 'processor'),
('SAMSUNG 990 PRO PCIe 4.0 NVMe SSD 1TB', 249.00, 'image/samsungm2.png', 'link/link8.html', 'storage'),
('CORSAIR VENGEANCE 32GB (2x16GB) DDR5 5200MHz Kit', 249.00, 'image/oJj7XFo9BCNkdBnqTuciiw7oz5cbGMnZ.png', 'link/link9.html', 'ram'),
('MSI Pro B760M-A WIFI DDR5', 199.00, 'image/abxdDm5HrrojfYmyq66JEizKVWaQ5tk6.png', 'link/link10.html', 'motherboard'),
('ASUS ROG STRIX X870E-H GAMING WIFI 7', 299.00, 'image/5ywcIqXX1rKqPLhtMfVztFUsdXNc1cf1.png', 'link/link11.html', 'motherboard');
