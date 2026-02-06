<?php
require_once 'maininclude.php';

if ($dba->isAdmin() === false) {
    header('Location: index.php');
}

if (isset($_POST['bt_product'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = (int) trim($_POST['category_id']);
    $brand_id = (int) trim($_POST['brand_id']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);
    $productnumber = trim($_POST['productnumber']);
    $is_available = isset($_POST['is_available']);
    $is_removed = 0;

    //Bild
    $filename = $_FILES['picture']['name'];
    $uploaddir = 'uploads/';
    $uploadpath = $uploaddir . $filename;

    if (move_uploaded_file($_FILES['picture']['tmp_name'], $uploadpath)) {
        echo 'Datei erfolgreich hochgeladen!';
    } else {
        $errors[] = 'Fehler beim Upload!';
    }

    $productByTitle = $dba->getProductByTitel($title);
    if (empty($title)) {
        $errors[] = 'Titel darf nicht leer sein!';
    } else if (!is_string($title)) {
        $errors[] = 'Titel muss ein Text sein!';
    } elseif ($productByTitle != false) {
        $errors[] = 'Titel bereits verwendet!';
    }

    if (empty($description)) {
        $errors[] = 'Beschreibung darf nicht leer sein!';
    } else if (!is_string($description)) {
        $errors[] = 'Titel muss ein Text sein!';
    }

    if (empty($category_id)) {
        $errors[] = 'Kategorie darf nicht leer sein!';
    }

    if (empty($brand_id)) {
        $errors[] = 'Brand ID darf nicht leer sein!';
    }

    if (empty($price)) {
        $errors[] = 'Preis darf nicht leer sein!';
    }

    $productByProductnumber = $dba->getProductByProductnumber($productnumber);
    if (empty($productnumber)) {
        $errors[] = 'Produktnummer darf nicht leer sein!';
    } else if ($productByProductnumber != false) {
        $errors[] = 'Produktnummer bereits verwendet!';
    }

    if (empty($stock)) {
        $errors[] = 'Lagerbestand darf nicht leer sein!';
    }

    if (count($errors) === 0) {
        $dba->createProduct($category_id, $brand_id, $title, $price, $productnumber, $description, $filename, $stock, $is_available, $is_removed);
        header('Location: product.php');
        exit();
    }

}

if(isset($_POST['bt_delete'])){
    $dba->deleteProduct();
    header('Location: product.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Produkt hinzufügen!</h1>

            <form action="product.php" method="POST" enctype="multipart/form-data">
                <label>Titel:</label><br>
                <input type="text" name="title"><br>

                <label>Beschreibung:</label><br>
                <input type="text" name="description"><br>

                <label>Kategorie:</label><br>
                <select name="category_id">
                    <?php
                    $categories = $dba->getAllCategories();
                    foreach ($categories as $c) {
                        echo '<option value="' . htmlspecialchars($c->id) . '">' . htmlspecialchars($c->name) . '</option>';
                    }
                    ?>
                </select><br>

                <label>Marke:</label><br>
                <select name="brand_id">
                    <?php
                    $brands = $dba->getAllBrands();
                    foreach ($brands as $b) {
                        echo '<option value="' . htmlspecialchars($b->id) . '">' . htmlspecialchars($b->name) . '</option>';
                    }

                    ?>
                </select><br>

                <label>Preis:</label><br>
                <input type="text" name="price"><br>


                <label>Produktnummer:</label><br>
                <input type="text" name="productnumber"><br>

                <label>Lagerbestand:</label><br>
                <input type="text" name="stock"><br>

                <label>Verfügbar?:</label><br>
                <input type="checkbox" name="is_available"><br>

                <label>Bild:</label><br>
                <input type="file" name="picture"><br>

                <button type="submit" name="bt_product">Produkt anlegen!</button>

            </form>

            <h2>Bestehende Produkte:</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Beschreibung</th>
                        <th>Kategorie</th>
                        <th>Marke</th>
                        <th>Preis</th>
                        <th>Produktnummer</th>
                        <th>Lagerbestand</th>
                        <th>Verfügbar?</th>
                        <th>Bild</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $products = $dba->getAllProducts();
                    foreach ($products as $p) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($p->id) . '</td>';
                        echo '<td>' . htmlspecialchars($p->title) . '</td>';
                        echo '<td>' . htmlspecialchars($p->description) . '</td>';
                        echo '<td>' . htmlspecialchars($dba->getCategoryNameById($p->category_id)) . '</td>';
                        echo '<td>' . htmlspecialchars($dba->getBrandNameById($p->brand_id)) . '</td>';
                        echo '<td>' . htmlspecialchars($p->price) . '</td>';
                        echo '<td>' . htmlspecialchars($p->productnumber) . '</td>';
                        echo '<td>' . htmlspecialchars($p->stock) . '</td>';
                        echo '<td>' . htmlspecialchars(($p->is_available) ? "Ja" : "Nein") . '</td>';
                        echo '<td><img src="uploads/' . $p->picture . '"class="picture"></td>';

                        //Bearbeiten
                        echo '<td>';

                        //Löschen
                        echo '<form action="product.php" method="POST">';
                        echo '<input type="hidden" name="id" value="">';
                        echo '<button name="bt_delete">Löschen</button>';
                        echo '</form>';

                        echo '</tr>';


                    }
                    ?>
                </tbody>




        </section>
    </main>


</body>

</html>