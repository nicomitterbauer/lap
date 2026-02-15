<?php 

require_once 'maininclude.inc.php';

if(!isset($_GET['id'])){
    exit('id fehlt');
}

if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    exit('id ungültig');
}

$id = $_GET['id'];

$p = $dba->getProductById($id);

if(!$p){
    exit('Produkt existiert nicht');
}

$c = $dba->getCategoryById($p->category_id);
$b = $dba->getBrandById($p->brand_id);


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
        <h2>Übersicht</h2>
        <?php include 'showerrors.inc.php'; ?>

        <img src="<?= $p->picture ?>" alt="" class="picture">

        <Label>Kategorie:</Label>
        <?= htmlspecialchars($c->name) ?>

        <Label>Marke</Label>
        <?= htmlspecialchars($b->name) ?>

        <Label>Titel:</Label>
        <?= htmlspecialchars($p->title) ?>

        <Label>Artikelnr:</Label>
        <?= htmlspecialchars($p->productnumber) ?>

        <Label>Beschreibung:</Label>
        <?= htmlspecialchars($p->description) ?>

        <Label>Lagerbestand:</Label>
        <?= htmlspecialchars($p->stock) ?>

        <Label>Preis:</Label>
        <?= htmlspecialchars(number_format($p->price, 2, ',', '.')) ?> EUR
        

        <form action="cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($p->id) ?>">
            <label>Menge:</label>
            <input type="number" name="quantity"  value="1"><br>
            <button name="bt_cart">In den Warenkorb legen</button>
        </form>
       


    </section>
    
</body>
</html>