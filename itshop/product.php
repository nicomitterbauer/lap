<?php 

require_once 'maininclude.inc.php';

if(!$dba->isAdmin()){
    header('Location: index.php');
    exit();
}

if(isset($_POST['bt_add'])){
    $productnumber = trim($_POST['productnumber']);
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];
    $is_available = isset($_POST['is_available']);
    $stock = $_POST['stock'];

    $is_removed = false;

    $originalFilename = $_FILES['picture']['name'];
    $uploaddir = 'uploads/';
    $uploadpath = $uploaddir . $originalFilename;

    if(move_uploaded_file($_FILES['picture']['tmp_name'], $uploadpath)){
        echo 'Erfolgreich hochgeladen!';
    } else {
        $errors[] = 'Fehler beim Hochladen';
    }


    if(empty($productnumber)){
        $errors[] = 'Artikelnummer eingeben!';
    }
    if($dba->getProductByProductnumber($productnumber) !== false){
        $errors[] = 'Artikelnummer bereits vorhanden!';
    }

    if(empty($title)){
        $errors[] = 'Titel eingeben!';
    }

    if(empty($description)){
        $errors[] = 'Beschreibung eingeben!';
    }

    if(empty($price)){
        $errors[] = 'Preis eingeben!';
    } ###

    if(empty($brand_id)){
        $errors[] = 'Marke auswöhlen!';
    }

    if(empty($category_id)){
        $errors[] = 'Kategorie auswöheln!';
    }


    if(count($errors) == 0){
        $dba->createProduct($productnumber, $title, $description, $price, $brand_id, $category_id, $is_available, $uploadpath, $is_removed, $stock);
        header('Location: product.php');
    exit();
    }


}

if(isset($_POST['bt_delete'])){
    $product_id = $_POST['product_id'];

    $dba->deleteProduct($product_id);
        header('Location: product.php');
        exit();
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
        <h2>Produkte</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="product.php" method="POST" enctype="multipart/form-data">
            <label>Aritkelnummer</label><br>
            <input type="text" name="productnumber"><br>

            <label>Name</label><br>
            <input type="text" name="title"><br>

            <label>Beschreibung</label><br>
            <input type="text" name="description"><br>

            <label>Preis</label><br>
            <input type="number" name="price"><br>

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

            <label>Bild</label><br>
            <input type="file" name="picture" accept=".jpg, .jpeg, .png, .pdf"><br>

            <label>Verfügbar</label><br>
            <input type="checkbox" name="is_available"><br>

            <label>Lagerbestand</label><br>
            <input type="numbers" name="stock"><br>
            

            <button name="bt_add">Hinzufügen</button>

        </form>

        <h2>Bestehende Produkte</h2>


        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Aritkelnummer</th>
                    <th>Name</th>
                    <th>Beschreibung</th>
                    <th>Preis</th>
                    <th>Marke</th>
                    <th>Kategorie</th>
                    <th>Verfügbar</th>
                    <th>Bild</th>
                    <th>gelöscht?</th>
                    <th>Lagerbestand</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($dba->getAllProducts() as $p) { 
                    $brand = $dba->getBrandById($p->brand_id);
                    $brand_name = '';


                    if($brand !== false){
                        $brand_name = $brand->name;
                    }

                    $category = $dba->getCategoryById($p->category_id);
                    $category_name = '';


                    if($category !== false){
                        $category_name = $category->name;
                    }
                    ?>

                <tr>

                    <td><?= htmlspecialchars($p->id) ?></td>
                    <td><?= htmlspecialchars($p->productnumber) ?></td>
                    <td><?= htmlspecialchars($p->title) ?></td>
                    <td><?= htmlspecialchars($p->description) ?></td>
                    <td><?= htmlspecialchars($p->price) ?></td>
                    <td><?= htmlspecialchars($brand_name) ?></td>
                    <td><?= htmlspecialchars($category_name) ?></td>
                    <td><?= htmlspecialchars($p->is_available ? 'ja' : 'Nein') ?></td>
                    <td><img src="<?= $p->picture ?>" alt="<?= htmlspecialchars($p->title) ?>" class="picture"></td>
                    <td><?= htmlspecialchars($p->is_removed ? 'ja' : 'Nein') ?></td>
                    <td><?= htmlspecialchars($p->stock) ?></td>


                    <td>
                        <a href="edit_product.php?id=<?= htmlspecialchars($p->id)  ?>">Bearbeiten</a>

                        <form action="product.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($p->id)  ?>">
                            <button name="bt_delete">Löschen</button>
                        </form>
                    </td>
                </tr>

                <?php } ?>
            </tbody>
        </table>


    </section>
    
</body>
</html>