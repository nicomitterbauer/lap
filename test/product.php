<?php 
require_once 'maininclude.inc.php';

if($dba->isAdmin() === false){
    header('Location: index.php');
    exit();
}

if(isset($_POST['bt_add_product'])){
    $productnumber = trim($_POST['productnumber']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $brand_id = trim($_POST['brand_id']);
    $category_id = trim($_POST['category_id']);
    $unit_price = trim($_POST['unit_price']);
    $is_available = isset($_POST['is_available']);
    $stock = trim($_POST['stock']);

    $originaleFilename = $_FILES['picture']['name'];
    $uploaddir = 'uploads/';
    $uploadpath = $uploaddir . $originaleFilename;

    $is_removed = 0;

    if(move_uploaded_file($_FILES['picture']['tmp_name'], $uploadpath)){
        echo 'Datei erfolgreich hochgeladen';
    } else {
        $errors[] = 'Fehler beim hochladen';
    }
    
    if(empty($productnumber)){
        $errors[] = 'Bitte Produkt Nummer eingeben!';
    }
    
    if(empty($title)){
        $errors[] = 'Bitte Titel eingeben!';
    }else if (!is_string($title)){
        $errors[] = 'Einen Text eingeben';
    }

    if(empty($description)){
        $errors[] = 'Bitte Beschreibung eingeben!';
    }else if (!is_string($description)){
        $errors[] = 'Einen Text eingeben';
    }

    if(empty($brand_id)){
        $errors[] = 'Bitte Marke auswählen';
    }

    if(empty($category_id)){
        $errors[] = 'Bitte Kategorie auswählen';
    }

    if(empty($unit_price)){
        $errors[] = 'Bitte Preis eingeben';
    }

    if(empty($stock)){
        $errors[] = 'Bitte Lagerbestand eingeben';
    }


    if(count($errors) == 0) {
        $dba->createProduct($productnumber, $title, $description, $brand_id, $category_id, $unit_price, $is_available, $uploadpath, $is_removed, $stock);
        header('Location: product.php');
        exit();
    }

}

if(isset($_POST['bt_delete_product'])){
    $id = trim($_POST['id']);

    if(count($errors) == 0) {
        $dba->deleteProduct($id);
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
        <h2>Produkte</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="product.php" method="POST" enctype="multipart/form-data">
            <label>Produkt Nr:</label>
            <input type="text" name="productnumber"><br>

            <label>Titel</label>
            <input type="text" name="title"><br>

            <label>Beschreibung</label>
            <input type="text" name="description"><br>

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
            <input type="number" name="unit_price"><br>

            <label>Verfügbar</label>
            <input type="checkbox" name="is_available"><br>

            <label>Bild</label>
            <input type="file" name="picture"><br>

            <label>Lagerbestand</label>
            <input type="number" name="stock"><br>



            <button name="bt_add_product">Produkt hinzufügen</button>
        </form>



        <table>

            
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produktnummer</th>
                    <th>Titel</th>
                    <th>Beschreibung</th>
                    <th>Marken_id</th>
                    <th>Kategorie_id</th>
                    <th>Preis</th>
                    <th>Verfügbar</th>
                    <th>Bild</th>
                    <th>Gelöscht?</th>
                    <th>Lagerbestand</th>
                    <th>Aktionen</th>
                </tr>

            </thead>
            <tbody>
                <?php
                $products = $dba->getAllProducts();
                foreach($products as $p){
                    $brand = $dba->getBrandById($p->brand_id);
                    $brand_name = '';

                    if($brand != false){
                        $brand_name = $brand->name;
                    }

                    $category = $dba->getCategoryById($p->category_id);
                    $category_name = '';

                    if($category != false){
                        $category_name = $category->name;
                    }

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($p->id) . '</td>';
                    echo '<td>' . htmlspecialchars($p->productnumber) . '</td>';
                    echo '<td>' . htmlspecialchars($p->title) . '</td>';
                    echo '<td>' . htmlspecialchars($p->description) . '</td>';
                    echo '<td>' . htmlspecialchars($brand_name) . '</td>';
                    echo '<td>' . htmlspecialchars($category_name) . '</td>';
                    echo '<td>' . htmlspecialchars($p->unit_price) . '</td>';
                    echo '<td>' . htmlspecialchars(($p->is_available ? 'Ja' : 'Nein')) . '</td>';
                    echo '<td> <img src="' . $p->picture .'" alt="' . $p->title .'" style="with: 2em; height: 2em;"></td>';
                    echo '<td>' . htmlspecialchars(($p->is_removed ? 'Ja' : 'Nein')) . '</td>';
                    echo '<td>' . htmlspecialchars($p->stock) . '</td>';
                    echo '<td>';

                    echo '<a href="edit_product.php?id=' . htmlspecialchars($p->id).'">Bearbeiten</a>';
                    echo '<form action="product.php" method="POST">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($p->id). '">';
                    echo '<button name="bt_delete_product">Löschen</button>';
                    echo '</form>';
                    echo '</td>';
                    echo'</tr>';

                } ?>

            </tbody>
        
                
            
        </table>



    </section> 
</body>
</html>