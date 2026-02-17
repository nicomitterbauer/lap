<?php
require_once 'models.inc.php';

class DbAccess{
    private PDO $conn;

     public function __construct()
    {
        $this->conn = new PDO('mysql:host=localhost; dbname=webshop', username: 'root', password: '');
        $this->conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    }

    public function getConn() : PDO {
        return $this->conn;
    }

    public function registerUser(string $firstname, string $lastname, string $email, string $password, bool $is_admin): int
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = '
        INSERT INTO users
        (firstname, lastname, email, password, is_admin)
        VALUES
        (:firstname, :lastname, :email, :password, :is_admin)
        ';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('firstname', $firstname);
        $ps->bindValue('lastname', $lastname);
        $ps->bindValue('email', $email);
        $ps->bindValue('password', $password);
        $ps->bindValue('is_admin', $is_admin, PDO::PARAM_BOOL);
        $ps->execute();
        return $this->conn->lastInsertId();

    }

    public function getUserByEmail(string $email) : User|false {
        $ps = $this->conn->prepare('
        SELECT *
        FROM users
        WHERE email = :email
        ');
        $ps->bindValue('email', $email);
        $ps->execute();
        return $ps->fetchObject(User::class);
    }

    public function login(string $email, string $password): bool
    {
        $user = $this->getUserByEmail($email);
        if ($user === false) {
            return false;
        }
        if(!password_verify($password, $user->password)) {
            return false;
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['is_admin'] = $user->is_admin;

        return true;
    }

    public function isLoggedIn(): bool
    {
        if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0) {
            return true;
        }
        return false;
    }

    public function isAdmin(): bool{
        return $this->isLoggedIn() && $_SESSION['is_admin'] === true;
    }


    public function createCategory(string $name) : int{

        $sql = '
        INSERT INTO categories
        (name)
        VALUES
        (:name)
        ';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $name);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getAllCategories(): array{
        $ps = $this->conn->prepare('
        SELECT *
        FROM categories
        ');
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_CLASS, Category::class);
    }

    public function getCategoryById(string $id): Category|false
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM categories
                WHERE id = :id
                ');
            $ps->bindValue('id', $id);
            $ps->execute();
            return $ps->fetchObject(Category::class);

        }
    
    public function getCategoryByName(string $name): Category|false
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM categories
                WHERE name = :name
                ');
            $ps->bindValue('name', $name);
            $ps->execute();
            return $ps->fetchObject(Category::class);

        }


    public function updateCategory(Category $c) :void {
        $ps = $this->conn->prepare('
            UPDATE categories
            SET name = :name
            WHERE id = :id');
        $ps->bindValue('id', $c->id);
        $ps->bindValue('name', $c->name);
        $ps->execute();
    }

    public function deleteCategory(string $id): void {
        $ps = $this->conn->prepare('
            DELETE FROM categories
            WHERE id = :id
        ');
        $ps->bindValue('id', $id);
        $ps->execute();
    }

    public function createBrand(string $name): int{
        $sql = '
        INSERT INTO brands
        
        (name)
        VALUES
        (:name)
        ';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $name);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getBrandByName(string $brand_name) : Brand|false {
        $ps = $this->conn->prepare('
        SELECT *
        FROM brands
        WHERE name = :name
        ');

        $ps->bindValue('name', $brand_name);
        $ps->execute();

        return $ps->fetchObject(Brand::class);
    }
    
    public function getAllBrands() : array {
        $ps = $this->conn->prepare('
        SELECT *
        FROM brands');
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_CLASS, Brand::class);
    }

    public function getBrandById(int $id) : Brand|false{
        $ps = $this->conn->prepare('
        SELECT *
        FROM brands
        WHERE id = :id
        ');

        $ps->bindValue('id', $id);
        $ps->execute();

        return $ps->fetchObject(Brand::class);
    }

    public function updateBrand(Brand $b) :void {
        $ps = $this->conn->prepare('
            UPDATE brands
            SET name = :name
            WHERE id = :id');
        $ps->bindValue('id', $b->id);
        $ps->bindValue('name', $b->name);
        $ps->execute();
    }

    public function deleteBrand(string $id): void {
        $ps = $this->conn->prepare('
            DELETE FROM brands
            WHERE id = :id
        ');
        $ps->bindValue('id', $id);
        $ps->execute();
    }


    // Produkte

    public function createProduct(string $productnumber, int $brand_id, int $category_id,
    float $price, string $description, string $title, bool $is_available, string $uploadpath, bool $is_removed) : int
    {
        $ps = $this->conn->prepare('
        INSERT INTO products
        (productnumber, brand_id, category_id, price, description, title, is_available, picture, is_removed)
        VALUES
        (:productnumber, :brand_id, :category_id, :price, :description, :title, :is_available, :picture, :is_removed)');
    
        $ps->bindValue('productnumber', $productnumber);
        $ps->bindValue('brand_id', $brand_id);
        $ps->bindValue('category_id', $category_id);
        $ps->bindValue('price', $price);
        $ps->bindValue('description', $description);
        $ps->bindValue('title', $title);
        $ps->bindValue('is_available', $is_available, PDO::PARAM_BOOL);
        $ps->bindValue('picture', $uploadpath);
        $ps->bindValue('is_removed', $is_removed, PDO::PARAM_BOOL);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getProductByProductnumber(string $productnumber) : Product|false{
        $ps = $this->conn->prepare('
        SELECT *
        FROM products
        WHERE productnumber = :productnumber
        ');

        $ps->bindValue('productnumber', $productnumber);
        $ps->execute();

        return $ps->fetchObject(Product::class);
    }

    public function getAllProducts(): array{
        $ps = $this->conn->prepare('
        SELECT *
        FROM products
        WHERE is_removed = 0
        ');
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_CLASS, Product::class);
    }

    public function deleteProduct(string $id): void {
        $ps = $this->conn->prepare('
            UPDATE products
            SET is_removed = 1, is_available = 0
            WHERE id = :id
        ');
        $ps->bindValue('id', $id);
        $ps->execute();
    }

    public function getProductById(int $id) : Product|false{
        $ps = $this->conn->prepare('
        SELECT *
        FROM products
        WHERE id = :id
        ');
        $ps->bindValue('id', $id);
        $ps->execute();

        return $ps->fetchObject(Product::class);
    }

    public function getProductByTitle(string $title): Product|false
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM products
                WHERE title = :title
                ');
            $ps->bindValue('title', $title);
            $ps->execute();
            return $ps->fetchObject(Product::class);

        }
        
    public function updateProduct(Product $p) :void {
        $ps = $this->conn->prepare('
            UPDATE products
            SET productnumber = :productnumber, brand_id = :brand_id, category_id = :category_id,
            price = :price, description = :description, title = :title, is_available = :is_available
            WHERE id = :id');
        $ps->bindValue('id', $p->id);
        $ps->bindValue('productnumber', $p->productnumber);
        $ps->bindValue('brand_id', $p->brand_id);
        $ps->bindValue('category_id', $p->category_id);
        $ps->bindValue('price', $p->price);
        $ps->bindValue('description', $p->description);
        $ps->bindValue('title', $p->title);
        $ps->bindValue('is_available', $p->is_available, PDO::PARAM_BOOL);
        $ps->execute();
    }

    public function updatePic(Product $p) :void {
        $ps = $this->conn->prepare('
            UPDATE products
            SET picture = :picture
            WHERE id = :id');
        $ps->bindValue('id', $p->id);
        $ps->bindValue('picture', $p->picture);
        $ps->execute();

    }

    public function createOrder(string $street, string $postcode, string $city, array $products){
        $userId = $_SESSION['user_id'];
        $ps = $this->conn->prepare('
        INSERT INTO orders
        (user_id, order_date, amount, street, postcode, city)
        VALUES
        (:user_id, :order_date, :amount, :street, :postcode, :city)
        ');

        $ps->bindValue('user_id', $userId);
        $ps->bindValue('order_date', (new DateTime())->format('Y-m-d H:i:s'));
        $ps->bindValue('amount', 0);
        $ps->bindValue('street', $street);
        $ps->bindValue('postcode', $postcode);
        $ps->bindValue('city', $city);
        $ps->execute();

        $orderId = $this->conn->lastInsertId();

        $amount = 0;

        // für jeden Eintrag in products --> order_items
        // p ist im Format ProductID:::Quantity
        foreach($products as $p){
            $pieces = explode(':::', $p);
            $productId = $pieces[0];
            $quantity = $pieces[1];

            $product = $this->getProductById($productId);
            $unitPrice = $product->price;

            $amount += $unitPrice * $quantity;

            $ps = $this->conn->prepare('
            INSERT INTO order_item
            (order_id, product_id, quantity, unit_price)
            VALUES
            (:order_id, :product_id, :quantity, :unit_price)
            ');
            $ps->bindValue('order_id', $orderId);
            $ps->bindValue('product_id', $productId);
            $ps->bindValue('quantity', $quantity);
            $ps->bindValue('unit_price', $unitPrice);
            $ps->execute();
        }

        $ps = $this->conn->prepare('
        UPDATE orders
        SET amount = :amount
        WHERE id = :orderId');

        $ps->bindValue('amount', $amount);
        $ps->bindValue('orderId', $orderId);
        $ps->execute();

        // Warenkorb löschen
        $_SESSION['cart'] = [];
    }

    public function getAllOrders():array {
        $ps = $this->conn->prepare('
        SELECT *
        FROM orders
        ');
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_CLASS, Order::class);
    }
}


?>