<?php
require_once 'maininclude.inc.php';

if(isset($_POST['bt_create'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $productnumber = trim($_POST['productnumber']);
    $brand_id = trim($_POST['brand_id']);
    $category_id = trim($_POST['category_id']);
    $is_available = isset($_POST['is_available']);
    $price = trim($_POST['price']);
    $is_removed = 0;


    $originalFilename = $_FILES['picture']['name'];
    // Pfad, wo die Datei gespeichert werden soll
    $uploaddir = 'uploads/';
    $uploadpath = $uploaddir . $originalFilename;

    // Verschiebe die hochgeladene Datei vom temporären Speicherort in unseren uploads-Ordner
    if(move_uploaded_file($_FILES['picture']['tmp_name'], $uploadpath)){
        // Alles oky
        echo "Datei erfolgreich hochgeladen.\n";
    } else {
        $errors[] = 'Upload Fehlgeschlagen! Keine Date? Fehlende Rechte am Uploads-Ordner?';

    }

if(empty($title)){
        $errors[] = 'Titel eingeben';
    }

if(empty($description)){
        $errors[] = 'Beschreibung eingeben';
    }

if(empty($productnumber)){
        $errors[] = 'Aritkelnummer eingeben';
    }else if ($dba->getProductByProductnumber($productnumber) !== false){
        $errors[] = 'Bezeichnung bereits vorhanden';
    }

if(empty($brand_id)){
        $errors[] = 'Marke auswählen';
    }

if(empty($category_id)){
        $errors[] = 'Kategorie auswählen';
    }

if(count($errors) == 0){
        $dba->createProduct($productnumber, $brand_id, $category_id, $price, $description, $title, $is_available, $uploadpath, $is_removed);
        header('Location: product.php');
        exit();
    }

}

if(isset($_POST['bt_delete_product'])){
        $id = $_POST['id'];
        $dba->deleteProduct($id);
    
    header('Location: product.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php include 'header.inc.php'; ?>
            <section>
                <h2>Werkzeugwelt</h2>
                <?php include 'showerrors.inc.php'; ?>

                <h3>Neues Produkt</h3>

                <form action="product.php" method="POST" enctype="multipart/form-data">
                    <label>Titel:</label><br>
                    <input type="text" name="title"><br>

                    <label>Beschreibung:</label><br>
                    <input type="text" name="description"><br>

                    <label>Artikelnummer:</label><br>
                    <input type="text" name="productnumber"><br>

                    <label>Marke</label><br>
                    <select name="brand_id">
                        <?php
                        // Lade alle Marken
                        $brands = $dba->getAllBrands();
                        // gebe jede Marke mit <option> aus
                        // $b Datentyp: Marke
                        foreach($brands as $b){
                            echo '<option value="' . htmlspecialchars($b->id) . '">' . htmlspecialchars($b->name) . '</option>';
                        }
                        ?>
                    </select><br>

                    <label>Kategorie</label><br>
                    <select name="category_id">
                        <?php       
                        $categories = $dba->getAllCategories();
                        
                        foreach($categories as $c){
                            echo '<option value="' . $c->id . '">' . htmlspecialchars($c->name) . '</option>';
                        }
                        ?>
                    </select><br>

                    <label>Preis:</label><br>
                    <input type="text" name="price"><br>

                    <label>Verfügbar:</label><br>
                    <input type="checkbox" name="is_available"><br>

                    <label>Bild </label><br>
                    <input type="file" name="picture"><br>

                    <button name="bt_create">Produkt erstellen</button>
                </form>

                <h2>Bestehende Produkte</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TITEL</th>
                        <th>MARKE</th>
                        <th>KATEGORIE_ID</th>
                        <th>PRODUKTNUMMER</th>
                        <th>PREIS</th>
                        <th>BESCHREIBUNG</th>
                        <th>VERFÜGBAR</th>
                        <th>GELÖSCHT</th>
                        <th>BILD</th>
                        <th>AKTIONEN</th>
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
                        
                        // Category gehöhrt auch 

                        echo '<tr>';
                        echo '<td>' . $p->id . '</td>';
                        echo '<td>' . $p->title . '</td>';
                        echo '<td>' . $brand_name . '</td>';
                        echo '<td>' . $p->category_id . '</td>';
                        echo '<td>' . $p->productnumber . '</td>';
                        echo '<td>' . $p->price . '</td>';
                        echo '<td>' . $p->description . '</td>';
                        echo '<td>' . ($p->is_available ? 'Ja' : 'Nein') . '</td>';
                        echo '<td>' . ($p->is_removed ? 'Ja' : 'Nein') . '</td>';
                        echo '<td><img src="' . $p->picture . '" alt="' . $p->title . '" style="max-width:100px; max-height:100px;"></td>';
                        echo '<td>';
                        echo '<a href="edit_product.php?id='. htmlspecialchars($p->id).'">Bearbeiten</a> ';
                        echo '<form method="POST" action="product.php">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($p->id) . '">';
                        echo '<button name="bt_delete_product">Löschen</button>';
                        echo '</form>';
                        echo '</td>';
                    }
                    ?>
                </tbody>
                </table>

            </section>
    </main>



</body>
</html>