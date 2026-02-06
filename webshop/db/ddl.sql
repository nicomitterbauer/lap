CREATE TABLE users(
    id INT AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(300) NOT NULL,
    password VARCHAR(300) NOT NULL,
    is_admin TINYINT(1) NOT NULL,
    PRIMARY KEY(id)
);

CREATE TABLE brands(
    id INT AUTO_INCREMENT,
    name VARCHAR(100),
    PRIMARY KEY(id)
);

CREATE TABLE categories(
    id INT AUTO_INCREMENT,
    name VARCHAR(100),
    PRIMARY KEY(id)
);

CREATE TABLE products( 
    id INT AUTO_INCREMENT,
    category_id INT NOT NULL,
    brand_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    productnumber INT NOT NULL,
    description VARCHAR(300) NOT NULL,
    picture VARCHAR(1000) NOT NULL,
    stock INT NOT NULL,
    is_available TINYINT(1) NOT NULL,
    is_removed TINYINT(1) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(brand_id) REFERENCES brands(id),
    FOREIGN KEY(category_id) REFERENCES categories(id)
);