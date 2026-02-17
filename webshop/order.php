<?php
require_once 'maininclude.inc.php';

// User muss eingelogt sein
if(!$dba->isLoggedIn()){
    header('location: login.php');
    exit();
}

if(empty($_SESSION['cart'])){
    header('location: cart.php');
    exit();
}
if(isset($_POST['bt_order'])){
    $street = trim($_POST['street']);
    $postcode = trim($_POST['postcode']);
    $city = trim($_POST['city']);


    if(empty($street)){
        $errors[] = 'Straße eingeben';
    }

    if(empty($postcode)){
        $errors[] = 'PLZ eingeben';
    }
    if(empty($city)){
        $errors[] = 'Stadt eingeben';
    }

    $products = $_POST['products'];

    if(count($errors) == 0){
        $dba->createOrder($street, $postcode, $city, $products);
        header('Location: success.php');
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
                <h2>Bestellung</h2>
                <?php include 'showerrors.inc.php'; ?>

                <form action="order.php" method="POST">
                    <h3>Produkte</h3>

                    <table>
                    <thead>
                        <tr>
                            <th>Bild</th>
                            <th>Titel</th>
                            <th>Einzelpreis</th>
                            <th>Stück</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach($_SESSION['cart'] as $cartItem){
                            $productId = $cartItem['product_id'];
                            $quantity = $cartItem['quantity'];
                            // Lade Das Produkt anhand der Produkt ID
                            $product = $dba->getProductById($productId);
                            
                            $total += $product->price * $quantity;
                            echo '<tr>';
                            echo '<td><img src="' . htmlspecialchars($product->picture) . '" style="max-width:100px; max-height:100px;"></td>';
                            echo '<td>' . htmlspecialchars($product->title) . '</td>';
                            echo '<td>' . htmlspecialchars($product->price) . '</td>';
                            echo '<td>' . htmlspecialchars($quantity) . ' Stück</td>';
                            

                            // Die Kombination aus ProductID|Quantity im Form einfügen
                            // ProductID:::Quantity
                            echo '<input type="hidden" name="products[]" value="'. $productId . ':::' . $quantity.'">';


                            echo '</tr>';
                        }
                        ?>

                    </tbody>
                </table>
                
                <p><strong>Gesamtpreis <?php echo $total; ?> EUR</strong></p>

                    <h3>Lieferadresse</h3>
                    <label>Straße</label>
                    <input type="text" name="street"> <br>

                    <label>PLZ</label>
                    <input type="text" name="postcode"> <br>

                    <label>Ort</label>
                    <input type="text" name="city"> <br>

                    <button name="bt_order">Jetzt Kostenpflichtig Bestellen</button>

                </form>
                
            </section>
    </main>



</body>
</html>