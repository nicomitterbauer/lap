<?php 
require_once 'maininclude.inc.php';

if($dba->isAdmin() === false){
    header('Location: index.php');
    exit();
}

if(!isset($_GET['id'])){
    exit('Fehler, Get Paramter');
}

if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    exit('Fehler, Get Filter');
}


$id = $_GET['id'];


$product = $dba->getProductById($id);

if($product === false){
    exit('kein Produkt vorhanden');
}



if(isset($_POST['bt_update_product'])){
    $newProductnumber = trim($_POST['productnumber']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];
    $unit_price = $_POST['unit_price'];
    $is_available = isset($_POST['is_available']);
    $stock = $_POST['stock'];

    $existingProductnumber = $dba->getProductByProductnumber($newProductnumber);
    
    if(empty($newProductnumber)){
        $errors[] = 'Bitte Artikel Nummer eingeben!';
    }

    if($existingProductnumber && $existingProductnumber->id == $id){
        $errors[] = 'Artikelnummer bereits vorhanden';
    }

    if(empty($title)){
        $errors[] = 'Titel eingeben';
    }

    if(empty($description)){
            $errors[] = 'Beschreibung eingeben';
        }

    if(empty($brand_id)){
            $errors[] = 'Marke auswählen';
        }

    if(empty($category_id)){
            $errors[] = 'Kategorie auswählen';
        }

    $product->productnumber = $newProductnumber;
    $product->title = $title;
    $product->description = $description;
    $product->brand_id = $brand_id;
    $product->category_id = $category_id;
    $product->unit_price = $unit_price;
    $product->is_available = $is_available;
    $product->stock = $stock;



    if(count($errors) == 0) {
        $dba->updateProduct($product);
        header('Location: product.php');
        exit();
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <section>
        <?php include 'header.inc.php'; ?>
        <h2>Produkt bearbeiten</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="edit_product.php?id=<?php echo htmlspecialchars($product->id)?>" method="POST">
            <label>Artikel nummer Bearbeiten</label>
            <input type="text" name="productnumber" value="<?php echo htmlspecialchars($product->productnumber)?>"><br>

            <label>Titel</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($product->title)?>"><br>

            <label>Beschreibung</label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($product->description)?>"><br>

            <select name="brand_id">
                <?php 
                $brands = $dba->getAllBrands();

                foreach($brands as $b){
                    echo '<option value="' . htmlspecialchars($b->id) .'">' . htmlspecialchars($b->name) .'</option>';
                }
                ?>
            </select><br>

            <select name="category_id">
                <?php 
                $categories = $dba->getAllCategories();

                foreach($categories as $c){
                    echo '<option value="' . htmlspecialchars($c->id) .'">' . htmlspecialchars($c->name) .'</option>';
                }
                ?>
            </select><br>

            <label>Preis</label>
            <input type="number" name="unit_price" value="<?php echo htmlspecialchars($product->unit_price)?>"><br>

            <label>Verfügbar</label>
            <input type="checkbox" name="is_available" value="<?php echo htmlspecialchars($product->is_available)?>"><br>


            <label>Lagerbestand</label>
            <input type="number" name="stock" value="<?php echo htmlspecialchars($product->stock)?>"><br>

            <button name="bt_update_product">Product aktualisieren</button>
        </form>


    </section> 
</body>
</html>