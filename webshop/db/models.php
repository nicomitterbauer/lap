<?php

class User{
    public INT $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;
    public bool $is_admin;

}

class Brand{
    public INT $id;
    public string $name;
}

class Category{
    public INT $id;
    public string $name;
}

class Product{
    public INT $id;
    public INT $category_id;
    public INT $brand_id;
    public STRING $title;
    public FLOAT $price;
    public INT $productnumber;
    public STRING $description;
    public STRING $picture;
    public INT $stock;
    public BOOL $is_available;
    public BOOL $is_removed;
}

?>