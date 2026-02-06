<?php
require_once 'models.php';


class DbAccess{

private PDO $conn;

public function __construct(){
    $this->conn = new PDO('mysql:host=localhost;dbname=webshop', username:'root', password:'');
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

public function createUser($firstname, $lastname, $email, $password, $is_admin){
    $ps = $this->conn->prepare('
    INSERT INTO users
    (firstname, lastname, email, password, is_admin)
    VALUES
    (:f, :l, :email, :password, :admin)
    ');
    $ps->bindValue('f', $firstname);
    $ps->bindValue('l', $lastname);
    $ps->bindValue('email', $email);
    $ps->bindValue('password', password_hash($password, PASSWORD_DEFAULT));
    $ps->bindValue('admin', $is_admin, PDO::PARAM_BOOL);
    $ps->execute();
    return $this->conn->lastInsertId();

}

public function getUserByEmail($email):User|false{
    $ps = $this->conn->prepare('
    SELECT * FROM
    users
    WHERE email = :email
    ');
    $ps->bindValue('email', $email);
    $ps->execute();
    return $ps->fetchObject(User::class);
}

public function login($email, $password){
    $user = $this->getUserByEmail($email);
    if($user == false){
        return false;
    }

    if(!password_verify($password, $user->password)){
        return false;
    }

    $_SESSION['user_id'] = $user->id;
    $_SESSION['is_admin'] = $user->is_admin;
    return true;
}

public function isLoggedIn(): bool {
    return !empty($_SESSION['user_id']);
}

public function isAdmin(): bool {
    return $this->isLoggedIn() && !empty($_SESSION['is_admin']);
}



// Brand
public function getBrandByName($name):Brand|false{
    $ps = $this->conn->prepare('
    SELECT *
    FROM brands
    WHERE name = :name
    ');
    $ps->bindValue('name', $name);
    $ps->execute();
    return $ps->fetchObject(Brand::class);
    }

    public function createBrand($name):int{
    $ps = $this->conn->prepare('
    INSERT INTO brands
    (name)
    VALUES
    (:name)
    ');
    $ps->bindValue('name', $name);
    $ps->execute();
    return $this->conn->lastInsertId();

}

public function getAllBrands():array{
    $ps = $this->conn->prepare('
    SELECT *
    FROM brands
    ');
    $ps->execute();
    return $ps->fetchAll(PDO::FETCH_CLASS,Brand::class);
}

public function deleteBrand($id):int{
    $ps = $this->conn->prepare('
    DELETE
    FROM brands
    WHERE id = :id
    ');
    $ps->bindValue('id', $id);
    $ps->execute();
    return $this->conn->lastInsertId();
}

public function getBrandById($id):Brand|false{
    $ps = $this->conn->prepare('
    SELECT *
    FROM brands
    WHERE id = :id
    ');
    $ps->bindValue('id', $id);
    $ps->execute();
    return $ps->fetchObject(Brand::class);
}

public function updateBrand(Brand $b){
    $ps = $this->conn->prepare('
    Update brands
    SET name = :name
    WHERE id = :id
    ');
    $ps->bindValue('name', $b->name);
    $ps->bindValue('id', $b->id);
    $ps->execute();
}

// Kategorien
public function getCategoryById($id):Product|false{
    $ps = $this->conn->prepare('
    SELECT *
    FROM categories
    WHERE id = :id
    ');
    $ps->bindValue('id', $id);
    $ps->execute();
    return $ps->fetchObject(Product::class);
}

public function updateCategory(Category $c){
    $ps = $this->conn->prepare('
    Update categories
    SET name = :name
    WHERE id = :id
    ');
    $ps->bindValue('name', $c->name);
    $ps->bindValue('id', $c->id);
    $ps->execute();
}

public function getAllCategories():array{
    $ps = $this->conn->prepare('
    SELECT *
    FROM categories
    ');
    $ps->execute();
    return $ps->fetchAll(PDO::FETCH_CLASS,Category::class);
}

public function getCategoryByName($name){
     $ps = $this->conn->prepare('
    SELECT *
    FROM categories
    WHERE name = :name
    ');
    $ps->bindValue('name', $name);
    $ps->execute();
    return $ps->fetchObject(Category::class);
}

public function createCategory($name):int{
    $ps = $this->conn->prepare('
    INSERT INTO categories
    (name)
    VALUES
    (:name)
    ');
    $ps->bindValue('name', $name);
    $ps->execute();
    return $this->conn->lastInsertId();

}

public function deleteCategory($id):int{
    $ps = $this->conn->prepare('
    DELETE
    FROM categories
    WHERE id = :id
    ');
    $ps->bindValue('id', $id);
    $ps->execute();
    return $this->conn->lastInsertId();
}


// Produkt
public function getProductByProductnumber($productnumber):Product|false{
    $ps = $this->conn->prepare('
    SELECT *
    FROM products
    WHERE productnumber = :productnumber
    ');
    $ps->bindValue('productnumber', $productnumber);
    $ps->execute();
    return $ps->fetchObject(Product::class);
}

public function getProductByTitel($title):Product|false{
$ps = $this->conn->prepare('
    SELECT *
    FROM products
    WHERE title = :title
    ');
    $ps->bindValue('title', $title);
    $ps->execute();
    return $ps->fetchObject(Product::class);
}

public function createProduct(int $category_id, int $brand_id, string $title, float $price, int $productnumber, string $description, string $picture, int $stock, bool $is_available, bool $is_removed):int{
    $ps = $this->conn->prepare('
    INSERT INTO products
    (category_id, brand_id, title, price ,productnumber, description, picture, stock, is_available, is_removed)
    VALUES
    (:category_id, :brand_id, :title, :price, :productnumber, :description, :picture, :stock, :is_available, :is_removed)
    ');
    $ps->bindValue('category_id', $category_id);
    $ps->bindValue('brand_id', $brand_id);
    $ps->bindValue('title', $title);
    $ps->bindValue('price', $price);
    $ps->bindValue('productnumber', $productnumber);
    $ps->bindValue('description', $description);
    $ps->bindValue('picture', $picture);
    $ps->bindValue('stock', $stock);
    $ps->bindValue('is_available', $is_available, PDO::PARAM_BOOL);
    $ps->bindValue('is_removed', $is_removed, PDO::PARAM_BOOL);
    $ps->execute();
    return $this->conn->lastInsertId();
}

public function getAllProducts():array{
    $ps = $this->conn->prepare('
    SELECT *
    FROM products
    WHERE is_removed = 0;
    ');
    $ps->execute();
    return $ps->fetchAll(PDO::FETCH_CLASS, Product::class);
}

public function getCategoryNameById($id){
    $ps = $this->conn->prepare('
    SELECT name
    FROM categories
    WHERE id = :id
    ');
    $ps->bindValue('id', $id);
    $ps->execute();
    return $ps->fetchColumn();
}

public function getBrandNameById($id){
    $ps = $this->conn->prepare('
    SELECT name
    FROM brands
    WHERE id = :id
    ');
    $ps->bindValue('id', $id);
    $ps->execute();
    return $ps->fetchColumn();
}

public function deleteProduct(){
    $ps = $this->conn->prepare('
    UPDATE products
    SET is_removed = 1
    ');
    $ps->execute();
}

}

?>