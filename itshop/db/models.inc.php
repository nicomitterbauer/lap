<?php
class User{
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;
    public bool $is_admin;

}

class Brand{
    public int $id;
    public string $name;

}

class Category{
    public int $id;
    public string $name;

}

class Product{
    public int $id;
    public string $productnumber;
    public string $title;
    public string $description;
    public float $price;
    public int $brand_id;
    public int $category_id;
    public bool $is_available;
    public string $picture;
    public bool $is_removed;
    public int $stock;

}


?>