<?php
class User{
    public int $id;
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $password;
    public bool $is_admin;
}

class Category{
    public int $id;
    public string $name;
}

class Brand{
    public int $id;
    public string $name;

}

class Product{
    public int $id;
    public string $productnumber;
    public int $brand_id;
    public int $category_id;
    public float $price;
    public string $description;
    public string $title;
    public bool $is_available;
    public string $picture;
    public bool $is_removed;
}

class Order{
    public int $id;
    public int $user_id;
    public DateTime $orderDate;
    public float $amount;
    public string $street;
    public string $postcode;
    public string $city;

    public function __set($property, $value)
        {
            if($property === 'order_date'){
                $this->orderDate = DateTime::createFromFormat('Y-m-d H:i:s', $value);
                
            } else {
                $this->$property = $value;
            }
        }
}

class OrderItem{
    public int $id;
    public int $order_id;
    public int $product_id;
    public int $quantity;
    public float $unit_price;
}

  

?>