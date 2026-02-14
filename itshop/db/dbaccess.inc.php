<?php 

require_once 'models.inc.php';

class DbAccss{
    private PDO $conn;

    public function __construct(){
        $this->conn = new PDO('mysql:host=localhost; dbname=itshop', username:'root', password: '');
        $this->conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    }

    public function registerUser(string $firstname, string $lastname, string $email, string $password, bool $is_admin){
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql  = '
        INSERT INTO users
        (firstname, lastname, email, password, is_admin)
        VALUE
        (:firstname, :lastname, :email, :password, :is_admin)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('firstname', $firstname);
        $ps->bindValue('lastname', $lastname);
        $ps->bindValue('email', $email);
        $ps->bindValue('password', $password);
        $ps->bindValue('is_admin', $is_admin, PDO::PARAM_BOOL);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getUserByEmail(string $email) : User|bool {
        $sql  = '
        SELECT *
        FROM users
        WHERE email = :email';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('email', $email);
        $ps->execute();
        return $ps->fetchObject(User::class);
    }

    public function login(string $email, string $password){
        $user = $this->getUserByEmail($email);
        if($user === false){
            return false;
        }
        if(!password_verify($password, $user->password)){
            return false;
        }

        $_SESSION['user_id'] = $user->id;
        $_SESSION['is_admin'] = $user->is_admin;

        return true;
    }

    public function isLoggedIn(): bool{
        return !empty($_SESSION['user_id']);
    }

    public function isAdmin()  : bool {
        return $this->isLoggedIn() && !empty($_SESSION['is_admin']);
    }

    



    public function createBrand($brand_name) : int{
        $sql  = '
        INSERT INTO brands
        (name)
        VALUE
        (:name)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $brand_name);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getBrandByName($brand_name) : Brand|bool {
        $sql  = '
        SELECT *
        FROM brands
        WHERE name = :name';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $brand_name);
        $ps->execute();
        return $ps->fetchObject(Brand::class);
    }

    public function getAllBrands(){
        $sql  = '
        SELECT *
        FROM brands';

        $ps = $this->conn->prepare($sql);
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_CLASS, Brand::class);
    }

    public function deleteBrand($brand_id) {
        $sql  = '
        DELETE 
        FROM brands
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $brand_id);
        $ps->execute();
    }

    public function getBrandById($id) : Brand|bool {
        $sql  = '
        SELECT *
        FROM brands
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $id);
        $ps->execute();
        return $ps->fetchObject(Brand::class);
    }

    public function updateBrand(Brand $b) {
        $sql  = '
        UPDATE brands
        SET name = :name
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $b->name);
        $ps->bindValue('id', $b->id);
        $ps->execute();
    }






        public function createCategory($category_name) : int{
        $sql  = '
        INSERT INTO categories
        (name)
        VALUE
        (:name)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $category_name);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getCategoryByName($category_name) : Category|bool {
        $sql  = '
        SELECT *
        FROM categories
        WHERE name = :name';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $category_name);
        $ps->execute();
        return $ps->fetchObject(Category::class);
    }

    public function getAllCategories(){
        $sql  = '
        SELECT *
        FROM categories';

        $ps = $this->conn->prepare($sql);
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_CLASS, Category::class);
    }

    public function deleteCategory($category_id) {
        $sql  = '
        DELETE 
        FROM categories
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $category_id);
        $ps->execute();
    }

    public function getCategoryById($id) : Category|bool {
        $sql  = '
        SELECT *
        FROM categories
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $id);
        $ps->execute();
        return $ps->fetchObject(Category::class);
    }

    public function updateCategory(Category $c) {
        $sql  = '
        UPDATE categories
        SET name = :name
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('name', $c->name);
        $ps->bindValue('id', $c->id);
        $ps->execute();
    }




    public function createProduct(string $productnumber, string $title, string $description, float $price, int $brand_id, int $category_id,  bool $is_available, string $uploadpath, bool $is_removed, int $stock
) : int{
        $sql  = '
        INSERT INTO products
        (productnumber, title, description, price, brand_id, category_id, is_available, picture, is_removed, stock)
        VALUE
        (:productnumber, :title, :description, :price, :brand_id, :category_id, :is_available, :picture, :is_removed, :stock)';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('productnumber', $productnumber);
        $ps->bindValue('title', $title);
        $ps->bindValue('description', $description);
        $ps->bindValue('price', $price);
        $ps->bindValue('brand_id', $brand_id);
        $ps->bindValue('category_id', $category_id);
        $ps->bindValue('is_available', $is_available);
        $ps->bindValue('picture', $uploadpath);
        $ps->bindValue('is_removed', $is_removed);
        $ps->bindValue('stock', $stock);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getProductByProductnumber($productnumber) : Product|bool {
        $sql  = '
        SELECT *
        FROM products
        WHERE productnumber= :productnumber';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('productnumber', $productnumber);
        $ps->execute();
        return $ps->fetchObject(Product::class);
    }

    public function getAllProducts(){
        $sql  = '
        SELECT *
        FROM products';

        $ps = $this->conn->prepare($sql);
        $ps->execute();
        return $ps->fetchAll(PDO::FETCH_CLASS, Product::class);
    }

    public function deleteProduct($product_id) {
        $sql  = '
        DELETE 
        FROM products
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $product_id);
        $ps->execute();
    }

    public function getProductById($id) : Product|bool {
        $sql  = '
        SELECT *
        FROM products
        WHERE id = :id';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('id', $id);
        $ps->execute();
        return $ps->fetchObject(Product::class);
    }

    public function updateProduct(Product $p) : bool {

    $sql = "
        UPDATE products
        SET productnumber = :productnumber,
            title = :title,
            description = :description,
            price = :price,
            brand_id = :brand_id,
            category_id = :category_id,
            is_available = :is_available,
            stock = :stock
        WHERE id = :id
    ";

    $ps = $this->conn->prepare($sql);

    $ps->bindValue(':productnumber', $p->productnumber);
    $ps->bindValue(':title', $p->title);
    $ps->bindValue(':description', $p->description);
    $ps->bindValue(':price', $p->price);
    $ps->bindValue(':brand_id', $p->brand_id, PDO::PARAM_INT);
    $ps->bindValue(':category_id', $p->category_id, PDO::PARAM_INT);
    $ps->bindValue(':is_available', $p->is_available, PDO::PARAM_BOOL);
    $ps->bindValue(':stock', $p->stock, PDO::PARAM_INT);
    $ps->bindValue(':id', $p->id, PDO::PARAM_INT);

    return $ps->execute();
}




}


?>