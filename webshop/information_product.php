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

// Lade die Kategorie für das Produkt
$category = $dba->getCategoryById($product->category_id);
$brand = $dba->getBrandById($product->brand_id);


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
                
                    <label>Titel:</label>
                    <?php echo htmlspecialchars($product->title); ?><br>

                    <label>Beschreibung:</label>
                    <?php echo htmlspecialchars($product->description); ?><br>

                    <label>Artikelnummer:</label>
                    <?php echo htmlspecialchars($product->productnumber); ?><br>

                    <label>Marke:</label>
                    <?php echo $brand->name; ?><br>

                    <label>Kategorie:</label>
                    <?php echo $category->name; ?><br>

                    <label>Preis:</label>
                    <?php echo htmlspecialchars($product->price); ?><br>

                    <label>Bild:</label><br>
                    <img src="<?php echo htmlspecialchars($product->picture); ?>" style="max-width:100px; max-height:100px;">
                
                    
                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product->id); ?>">
                        <label>Menge</label>
                        <input type="text" name="quantity" value="1">
                        <button name="bt_add_to_cart">In den Warenkorb</button>
                    </form>
            </section>
    </main>



</body>
</html>