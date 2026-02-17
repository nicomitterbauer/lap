<?php
require_once 'maininclude.inc.php';

// gibt es den GET-Parameter für die ID?
if(!isset($_GET['id']) || empty($_GET['id'])){
    exit('GET-Parameter id fehlt!');
}

// Lade das gesamte Objekt der Kategorie (was bearbeitet werden soll)
$product = $dba->getProductById($_GET['id']);

if($product === false ){
    exit('Produkt nicht gefunden');
}

if(isset($_POST['bt_update'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $productnumber = trim($_POST['productnumber']);
    $brand_id = trim($_POST['brand_id']);
    $category_id = trim($_POST['category_id']);
    $price = trim($_POST['price']);
    $is_available = isset($_POST['is_available']);

    if(empty($title)){
        $errors[] = 'Titel eingeben';
    }

    if(empty($description)){
            $errors[] = 'Beschreibung eingeben';
        }

    if(empty($productnumber)){
            $errors[] = 'Aritkelnummer eingeben';
        }

    if(empty($brand_id)){
            $errors[] = 'Marke auswählen';
        }

    if(empty($category_id)){
            $errors[] = 'Kategorie auswählen';
        }


    $productByNewTitle = $dba->getProductByTitle($title);
    // Wenn es eine andere Produkt mit dem gleichen Namen schon gibt --> Fehler
    if($productByNewTitle !== FALSE && $productByNewTitle->id != $product->id){
        $errors[] = 'Es gibt bereits eine Produkt mit diesem Titel';
    }

    $productByNewProductnumber = $dba->getProductByProductnumber($productnumber);
    // Wenn es eine andere Produkt mit dem gleichen Namen schon gibt --> Fehler
    if($productByNewProductnumber !== FALSE && $productByNewProductnumber->productnumber != $product->productnumber){
        $errors[] = 'Es gibt bereits eine Produkt mit dieser Artikelnummer';
    }

    // Aktualisiere die Werte im Objekt
    // schreibe die Usereingaben in das Objekt
    $product->title = $title;
    $product->description = $description;
    $product->productnumber = $productnumber;
    $product->brand_id = $brand_id;
    $product->category_id = $category_id;
    $product->price = $price;
    $product->is_available = $is_available;

    if(count($errors) == 0){
        $dba->updateProduct($product);
        header('Location: product.php');
        exit();
    }


}


if(isset($_POST['bt_update_pic'])){
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

    if(empty($originalFilename)){
        $errors[] = 'Bitte eine Bilddatei auswählen';
    }

    $product->picture = $uploadpath;


    if(count($errors) == 0){
        $dba->updatePic($product);
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php include 'header.inc.php'; ?>
            <section>
                <h2>Werkzeugwelt</h2>
                <?php include 'showerrors.inc.php'; ?>

                <h3>Produkt Bearbeiten</h3>
                <form action="edit_product.php?id=<?php echo $product->id; ?>" method="POST">
                    <label>Titel:</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($product->title); ?>"><br>

                    <label>Beschreibung:</label>
                    <input type="text" name="description" value="<?php echo htmlspecialchars($product->description); ?>"><br>

                    <label>Artikelnummer:</label>
                    <input type="text" name="productnumber" value="<?php echo htmlspecialchars($product->productnumber); ?>"><br>

                    <label>Marke</label><br>
                    <select name="brand_id">
                        <?php
                        // Lade alle Marken
                        $brands = $dba->getAllBrands();
                        // gebe jede Marke mit <option> aus
                        // $b Datentyp: Marke
                        foreach($brands as $b){
                            $selected = ($b->id == $product->brand_id) ? 'selected' : '';
                            echo '<option value="' . $b->id . '" ' . $selected . '>' . htmlspecialchars($b->name) . '</option>';
                        }
                        ?>
                    </select><br>

                    <label>Kategorie</label><br>
                    <select name="category_id">
                        <?php       
                        $categories = $dba->getAllCategories();
                        
                        foreach($categories as $c){
                            $selected = ($c->id == $product->category_id) ? 'selected' : '';
                            echo '<option value="' . $c->id . '" ' . $selected . '>' . htmlspecialchars($c->name) . '</option>';
                        }
                        ?>
                    </select><br>

                    <label>Preis:</label>
                    <input type="text" name="price" value="<?php echo htmlspecialchars($product->price); ?>"><br>

                    <label>Verfügbar:</label>
                    <input type="checkbox" name="is_available"
                    <?php if($product->is_available){
                        echo 'checked';
                        } ?>><br>

                    <button name="bt_update">bearbeiten</button>
                </form>




                <form action="edit_product.php?id=<?php echo $product->id; ?>" method="POST" enctype="multipart/form-data">
                    

                    <label>Aktuelles Bild:</label><br>
                    <img src="<?php echo htmlspecialchars($product->picture); ?>" style="max-width:100px; max-height:100px;"><br>

                    <label>Änderung </label><br>
                    <input type="file" name="picture"><br>
                    

                    <button name="bt_update_pic">bearbeiten</button>
                </form>
              
            </section>
    </main>



</body>
</html>