<?php 

require_once 'db/models.inc.php';

class DbAccess{
    private PDO $conn;

    public function __construct(){
        $this->conn = new PDO('mysql:host=localhost; dbname=webshop', username: 'root', password: '');
        $this->conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    }

    public function registerUser(string $firstname, string $lastname, string $email, string $password, bool $isAdmin) : int{
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = '
        INSERT INTO users
        (firstname, lastname, email, password, is_admin)
        VALUES
        (:firstname, :lastname, :email, :password, :is_admin)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('firstname', $firstname);
        $ps->bindValue('lastname', $lastname);
        $ps->bindValue('email', $email);
        $ps->bindValue('password', $password);
        $ps->bindValue('is_admin', $isAdmin, PDO::PARAM_BOOL);
        $ps->execute();

        return $this->conn->lastInsertId();

    }
 
    public function getUserByEmail(string $email) : User|false{

        $sql = '
        SELECT *
        FROM users
        WHERE email = :email';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('email', $email);
        $ps->execute();

        return $ps->fetchObject(User::class);
    }

    public function login(string $email, string $password) : bool{
        $user = $this->getUserByEmail($email);

        if($user == false){
            return false;
        }
        if(!password_verify( $password, $user->password)){
            return false;
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['is_admin'] = $user->is_admin;

        return true;
    }

    public function isLoggedIn() : bool {
    return !empty($_SESSION['user_id']);
    }

    public function isAdmin(){
        return $this->isLoggedIn() && !empty($_SESSION['is_admin']);
    }



    public function createBrand(string $name) : int {
        $sql = '
        INSERT INTO brands
        (name)
        VALUES
        (:name)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $name);
        $ps->execute();

        return $this->conn->lastInsertId();
    }


    public function getAllBrands(){
        $sql = '
        SELECT *
        FROM brands';

        $ps = $this->conn->prepare($sql);
        $ps->execute();

        return $ps->fetchAll(PDO::FETCH_CLASS, Brand::class);
    }

    public function getBrandById(int $id) : Brand|bool {
        $sql = '
        SELECT *
        FROM brands
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $id);
        $ps->execute();

        return $ps->fetchObject(Brand::class);
    }

    public function getBrandByName(string $newName): Brand|bool {
        $sql = '
        SELECT *
        FROM brands
        WHERE name = :name';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $newName);
        $ps->execute();

        return $ps->fetchObject(Brand::class);
    }

    public function updateBrand(Brand $b) {
        $sql = '
        UPDATE brands
        SET name = :name
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $b->name);
        $ps->bindValue('id', $b->id);
        $ps->execute();

    }

     public function deleteBrand(int $id) {
        $sql = '
        DELETE FROM brands
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $id);
        $ps->execute();

    }





    public function createCategory(string $name) : int {
        $sql = '
        INSERT INTO categories
        (name)
        VALUES
        (:name)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $name);
        $ps->execute();

        return $this->conn->lastInsertId();
    }


        public function getAllCategories(){
        $sql = '
        SELECT *
        FROM categories';

        $ps = $this->conn->prepare($sql);
        $ps->execute();

        return $ps->fetchAll(PDO::FETCH_CLASS, Category::class);
    }

    public function getCategoryById(int $id) : Category|bool {
        $sql = '
        SELECT *
        FROM categories
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $id);
        $ps->execute();

        return $ps->fetchObject(Category::class);
    }

    public function getCategoryByName(string $newName): Category|bool {
        $sql = '
        SELECT *
        FROM categories
        WHERE name = :name';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $newName);
        $ps->execute();

        return $ps->fetchObject(Category::class);
    }

    public function updateCategory(Category $c) {
        $sql = '
        UPDATE categories
        SET name = :name
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $c->name);
        $ps->bindValue('id', $c->id);
        $ps->execute();

    }

     public function deleteCategory(int $id) {
        $sql = '
        DELETE FROM categories
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $id);
        $ps->execute();

    }



    public function createProduct(string $productnumber, string $title, string $description, int $brand_id, int $category_id, float $unit_price, bool $is_available, string $uploadpath, bool $is_removed, int $stock){

        $sql = '
        INSERT INTO products
        (productnumber, title, description, brand_id, category_id, unit_price, is_available, picture, is_removed, stock)
        VALUES
        (:productnumber, :title, :description, :brand_id, :category_id, :unit_price, :is_available, :picture, :is_removed, :stock)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('productnumber', $productnumber);
        $ps->bindValue('title', $title);
        $ps->bindValue('description', $description);
        $ps->bindValue('brand_id', $brand_id);
        $ps->bindValue('category_id', $category_id);
        $ps->bindValue('unit_price', $unit_price);
        $ps->bindValue('is_available', $is_available);
        $ps->bindValue('picture', $uploadpath);
        $ps->bindValue('is_removed', $is_removed);
        $ps->bindValue('stock', $stock);
        $ps->execute();

        return $this->conn->lastInsertId();

    }

    public function getAllProducts(){
        $sql = '
        SELECT *
        FROM products';

        $ps = $this->conn->prepare($sql);
        $ps->execute();

        return $ps->fetchAll(PDO::FETCH_CLASS, Product::class);
    }
}



?>