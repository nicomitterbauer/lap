CREATE DATABASE itshop;

CREATE TABLE users
(
    id INT AUTO_INCREMENT,
    firstname VARCHAR(300),
    lastname VARCHAR(300),
    email VARCHAR(300),
    password VARCHAR(300),
    is_admin TINYINT(1),
    PRIMARY KEY(id)
);

CREATE TABLE brands(
    id INT AUTO_INCREMENT,
    name VARCHAR(300),
    PRIMARY KEY(id)
);

CREATE TABLE categories(
    id INT AUTO_INCREMENT,
    name VARCHAR(300),
    PRIMARY KEY(id)
);

CREATE TABLE products(
    id INT AUTO_INCREMENT,
    productnumber VARCHAR(300),
    title VARCHAR(300),
    description VARCHAR(400),
    price DECIMAL(10,2),
    brand_id INT,
    category_id INT,
    is_available TINYINT(1),
    picture VARCHAR(2048),
    is_removed TINYINT(1),
    stock INT,
    PRIMARY KEY(id),
    FOREIGN KEY(brand_id) REFERENCES brands(id),
    FOREIGN KEY(category_id) REFERENCES categories(id)
);