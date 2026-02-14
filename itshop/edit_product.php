<?php 

require_once 'maininclude.inc.php';

if(!$dba->isAdmin()){
    header('Location: index.php');
    exit();
}

if(!isset($_GET['id'])){
    exit('id fehlt');
}

if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    exit('id ungültig');
}

$id = $_GET['id'];

$product = $dba->getProductById($id);

if(!$product){
    exit('Produkt fehlt');
}



if(isset($_POST['bt_update'])){
    $newName = trim($_POST['p_number']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];
    $is_available = isset($_POST['is_available']);
    $stock = $_POST['stock'];

    if(empty($newName)){
        $errors[] = 'Artikel nummer eingeben!';
    }

    if(empty($title)){
        $errors[] = 'Titel eingeben!';
    }

    if(empty($description)){
        $errors[] = 'Beschreibung eingeben!';
    }

    if(empty($price)){
        $errors[] = 'Preis eingeben!';
    }

    if(empty($brand_id)){
        $errors[] = 'Marke auswöhlen!';
    }

    if(empty($category_id)){
        $errors[] = 'Kategorie auswöheln!';
    }

    $existingProduct = $dba->getProductByProductnumber($newName);


    if($existingProduct && $existingProduct->id != $id){
        $errors[] = 'Artikel nummer bereits vorhanden!';
    }

    if(count($errors) == 0){
        $product->productnumber = $newName;
        $product->title = $title;
        $product->description = $description;
        $product->price = $price;
        $product->brand_id = $brand_id;
        $product->category_id = $category_id;
        $product->is_available = $is_available;
        $product->stock = $stock;

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
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <section>
        <?php include 'header.inc.php'; ?>
        <h2>Marke bearbeiten</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="edit_product.php?id=<?= htmlspecialchars($id)  ?>" method="POST">
            <label>Artikel Nummer</label><br>
            <input type="text" name="p_number" value="<?= htmlspecialchars($product->productnumber)  ?>"><br>

            <label>Name</label><br>
            <input type="text" name="title" value="<?= htmlspecialchars($product->title)  ?>"><br>

            <label>Beschreibung</label><br>
            <input type="text" name="description" value="<?= htmlspecialchars($product->description)  ?>"><br>

            <label>POrei</label><br>
            <input type="text" name="price" value="<?= htmlspecialchars($product->price)  ?>"><br>

            <label>Marke</label><br>
            <select name="brand_id">
                <?php foreach($dba->getAllBrands() as $b){?>
                <option value="<?= htmlspecialchars($b->id)?>"><?= htmlspecialchars($b->name)?></option>
                <?php }?>
            </select>

            <label>Kategorie</label><br>
            <select name="category_id">
                <?php foreach($dba->getAllCategories() as $c){?>
                <option value="<?= htmlspecialchars($c->id)?>"><?= htmlspecialchars($c->name)?></option>
                <?php }?>
            </select>

            <label>Verfügbar</label><br>
            <input type="checkbox" name="is_available" value="<?= htmlspecialchars($product->is_available)  ?>"><br>

            <label>Lagerbestand</label><br>
            <input type="text" name="stock" value="<?= htmlspecialchars($product->stock)  ?>"><br>


            <button name="bt_update">Aktualisieren</button>

        </form>


    </section>
    
</body>
</html>