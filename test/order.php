<?php
require_once 'maininclude.inc.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['bt_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if ($product_id < 1) {
        $errors[] = 'Produkt ID falsch!';
    } elseif (!filter_var($product_id, FILTER_VALIDATE_INT)) {
        $errors[] = 'Produkt ID muss eine Zahl sein!';
    }

    if ($quantity < 1) {
        $errors[] = 'Menge muss mind. 1 sein!';
    } elseif (!filter_var($quantity, FILTER_VALIDATE_INT)) {
        $errors[] = 'Menge muss eine Zahl sein!';
    }

    // schauen ob Produkt bereits im Warenkorb ist
    $found = false;
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['product_id'] == $product_id) {
            $_SESSION['cart'][$i]['quantity'] += $quantity;
            $found = true;
            break;
        }
    }

    if ($found === false) {
        $entry = [];
        $entry['product_id'] = $product_id;
        $entry['quantity'] = $quantity;
        $_SESSION['cart'][] = $entry;
    }
    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.inc.php'; ?>
        <section>
            <?php include 'showerrors.inc.php'; ?>
            <h1>Bestellübersicht!</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Preis</th>
                        <th>Menge</th>
                        <th>Bild</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $cartItem) {
                        $product_id = $cartItem['product_id'];
                        $quantity = $cartItem['quantity'];

                        $product = $dba->getProductById($product_id);

                        if (!$product) {
                            $errors[] = 'Produkt nicht gefunden!';
                        }

                        if(!$product){
                            continue;
                        }

                        $total += $quantity * $product->unit_price;

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($product->title) . '</td>';
                        echo '<td>' . htmlspecialchars($product->unit_price) . '</td>';
                        echo '<td>' . htmlspecialchars($quantity) . '</td>';
                        echo '<td> <img src="' . htmlspecialchars($product->picture) . '" class="picture" style="max-width:10em; max-height: 10em;"></td>';
                    }



                    ?>
                </tbody>
            </table>
            <p><strong>Gesamtpreis: <?php echo number_format($total, 2, '.', ','); ?> EUR</strong></p>
            <h2>Lieferadresse</h2>
            <form action="order.php" method="POST">
                <label>Straße:</label><br>
                <input type="text" name="street"><br>

                <label>PLZ:</label><br>
                <input type="text" name="postcode"><br>

                <label>Ort:</label><br>
                <input type="text" name="city"><br>

                <label>Zahlungsmethode:</label><br>
                <select>
                    <option value="Kreditkarte">Kreditkarte</option>
                    <option value="Vorkassa">Vorkassa</option>
                </select><br>

                <button name="bt_order">Jetzt kostenpflichtig bestellen!</button>
            </form>
        </section>
    </main>
</body>

</html>