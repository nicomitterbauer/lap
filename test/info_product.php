<?php
require_once 'maininclude.inc.php';

if ($dba->isAdmin() === false) {
    header('Location: index.php');
}

// KEIN Admin-Redirect
if (!isset($_GET['id']) || empty($_GET['id'])) {
    exit('GET Parameter nicht gefunden!');
}

// Produkt zu dieser ID?
$product = $dba->getProductById($_GET['id']);
if ($product===false) {
    exit('Für diese ID existiert kein Produkt!');
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
        <?php include 'header.inc.php'; ?>
        <section>
            <?php include 'showerrors.inc.php'; ?>
            <h1>Ihr ausgewähltes Produkt:</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Beschreibung</th>
                        <th>Preis</th>
                        <th>Produktnummer</th>
                        <th>Marke</th>
                        <th>Kategorie</th>
                        <th>Verfügbar?</th>
                        <th>Bild</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $p = $dba->getProductById($_GET['id']);
                    
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
                        
                        echo '<td>' . htmlspecialchars($p->title) . '</td>';
                        echo '<td>' . htmlspecialchars($p->description) . '</td>';
                        echo '<td>' . number_format(htmlspecialchars($p->unit_price), 2, '.', ','). ' EUR</td>';
                        echo '<td>' . htmlspecialchars($p->productnumber) . '</td>';
                        echo '<td>' . htmlspecialchars($brand_name) . '</td>';
                        echo '<td>' . htmlspecialchars($category_name) . '</td>';
                        echo '<td>' . htmlspecialchars($p->is_available ? 'Ja' : 'Nein') . '</td>';
                        echo '<td> <img src="' . htmlspecialchars($p->picture) . '" class="picture" style="max-height: 10em; max-width: 10em;"></td>';
                    ?>
                </tbody>
            </table>
            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($p->id);?>">
                <label>Menge:</label>
                <input type="number" name="quantity" value="1">
                <button name="bt_cart">In den Warenkorb legen:</button>
            </form>

        </section>
    </main>
</body>

</html>