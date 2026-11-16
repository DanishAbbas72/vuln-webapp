CREATE DATABASE IF NOT EXISTS vulnapp;
USE vulnapp;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password VARCHAR(64),   -- MD5 hashes, unsalted
  email VARCHAR(100),
  role VARCHAR(20) DEFAULT 'user',
  ssn VARCHAR(20)         -- sensitive data stored in plaintext
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  price DECIMAL(10,2)
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author VARCHAR(100),
  body TEXT
);

CREATE TABLE files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255)
);

-- admin / admin123, alice / password
INSERT INTO users (username, password, email, role, ssn) VALUES
('admin', '0192023a7bbd73250516f069df18b500', 'admin@vulnapp.local', 'admin', '123-45-6789'),
('alice', '5f4dcc3b5aa765d61d8327deb882cf99', 'alice@vulnapp.local', 'user',  '987-65-4321');

INSERT INTO products (name, price) VALUES ('Laptop', 999.99), ('Mouse', 19.99), ('Keyboard', 49.99);
