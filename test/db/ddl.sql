CREATE TABLE users
(
    id INT AUTO_INCREMENT,
    firstname VARCHAR(30),
    lastname VARCHAR(30),
    email VARCHAR(100),
    password VARCHAR(300),
    is_admin TINYINT(1),
    PRIMARY KEY (id)
)

CREATE TABLE brands 
(
    id INT AUTO_INCREMENT,
    name VARCHAR(300),
    PRIMARY KEY (id)
)

CREATE TABLE categories 
(
    id INT AUTO_INCREMENT,
    name VARCHAR(300),
    PRIMARY KEY (id)
)

CREATE TABLE products
(
   id INT AUTO_INCREMENT,
   productnumber VARCHAR(300) NOT NULL,
   title VARCHAR(100) NOT NULL,
   description VARCHAR(400) NOT NULL,
   brand_id INT NOT NULL,
   category_id INT NOT NULL,
   unit_price DECIMAL(10,2) NOT NULL,
   is_available TINYINT(1) NOT NULL,
   picture VARCHAR(2048) NOT NULL,
   is_removed TINYINT(1) NOT NULL,
   stock INT NOT NULL,
   PRIMARY KEY (id),
   FOREIGN KEY (brand_id) REFERENCES brands(id),
   FOREIGN KEY (category_id) REFERENCES categories(id)
)

// Admin Passwort: Admin1234