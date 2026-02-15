<?php

require_once 'maininclude.inc.php';

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_POST['bt_cart'])){
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if($product_id < 1){
        $errors[] = 'Ungültige id';
    } elseif (!filter_var($product_id, FILTER_VALIDATE_INT)){
        $errors[] = 'Ungültige id';
    }

if(count($errors) == 0){


    $found = false;

    for($i = 0; $i < count($_SESSION['cart']); $i++){
        if($_SESSION['cart']['$i']['product_id'] == $product_id){
            $_SESSION['cart']['$i']['quantity'] += $quantity;
            $found = true;
            break;
        } 
    }

    if($found === false){
        $entry = [];
        $entry['product_id'] = $product_id;
        $entry['quantity'] = $quantity;
        $_SESSION['cart'][] = $entry;
    }

    header('Location: cart.php');
    exit();
}
}

if(isset($_POST['bt_delete'])){
    $id = (int)$_POST['id'];

    for($i = 0; $i < count($_SESSION['cart']); $i++){
        if($_SESSION['cart']['$i']['product_id'] == $id){
            array_splice($_SESSION['cart'], $i, 1);
            header('Location: cart.php');
            exit();
        }
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
        <h2>Warenkorb</h2>
        <?php include 'showerrors.inc.php'; ?>


        <table>
            <thead>
                <tr>
                    <td>Bild</td>
                    <td>Titel</td>
                    <td>Einzelstück Preis</td>
                    <td>Stück</td>
                    <td>Löschen</td>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach($_SESSION['cart'] as $cartItem){ 
                    $quantity = $cartItem['quantity'];
                    $product_id = $cartItem['product_id'];

                    $prodcut = $dba->getProductById($product_id);

                    if(!$prodcut){
                        $errors[] = 'Kein Produkt vorhanden';
                        continue;
                    }

                    $total += $product->price * $quantity; ?>

                    <tr>
                        <td><img src="<?= $product->picture; ?>" alt=""></td>
                        <td><?=htmlspecialchars($prodcut->title);?></td>
                        <td><?=htmlspecialchars($prodcut->price);?></td>
                        <td><?=htmlspecialchars($quantity);?></td>
                        <td>
                            <form action="cart.php" method="POST">
                                <input type="hidden" name="$id" value="<?=htmlspecialchars($prodcut_id);?>">
                                <button name="bt_delete">Löschen</button>
                            </form>
                        </td>
                    </tr>
                 <?php }?>
            </tbody>
        </table>

        <p><strong>Gesamtpreis: <?= number_format($total, 2, ',', '.'); ?></strong></p>

        <p>
            <form action="order.php" method="GET">
                <button>Bestellen</button>
            </form>
        </p>


    </section>
    
</body>
</html>