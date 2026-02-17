CREATE DATABASE webshop;
USE webshop;

CREATE TABLE users
(
    id INT AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(320) NOT NULL,
    password VARCHAR(300) NOT NULL,
    is_admin BOOL NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY(email)
)

// statt BOOL richtig wäre: TINYINT(1)

CREATE TABLE categories
(
    id INT AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    UNIQUE KEY(name),
    PRIMARY KEY (id)
);

CREATE TABLE brands
(
    id INT AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    UNIQUE KEY(name),
    PRIMARY KEY (id)
);

CREATE TABLE products(
    id INT AUTO_INCREMENT,
    productnumber VARCHAR(300),
    brand_id INT NOT NULL,
    category_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    title VARCHAR(100) NOT NULL,
    is_available TINYINT(1) NOT NULL,
    picture VARCHAR(2048) NOT NULL,
    is_removed TINYINT(1) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(brand_id) REFERENCES brands(id),
    FOREIGN KEY(category_id) REFERENCES categories(id)
)

CREATE TABLE orders(
    id INT AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_date DATETIME NOT NULL,
    amount INT NOT NULL,
    street VARCHAR(255) NOT NULL,
    postcode VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE order_item(
    id INT AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(order_id) REFERENCES orders(id),
    FOREIGN KEY(product_id) REFERENCES products(id)
);