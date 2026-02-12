<?php
require_once 'maininclude.inc.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['bt_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

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

if (isset($_POST['bt_delete'])) {
    $id = $_POST['id'];
    for ($i = 0; $i < count($_SESSION['cart']); $i++) {
        if ($_SESSION['cart'][$i]['product_id'] == $id) {

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
    <title>Cart</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.inc.php'; ?>
        <section>
            <?php include 'showerrors.inc.php'; ?>
            <h1>Willkommen in deinem Warenkorb!</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Preis</th>
                        <th>Menge</th>
                        <th>Bild</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $cartItem) {
                        $product_id = $cartItem['product_id'];
                        $quantity = $cartItem['quantity'];

                        $product = $dba->getProductById($product_id);

                        if (!$product){
                            continue;
                        }

                      

                        $total += $quantity * $product->unit_price;

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($product->title) . '</td>';
                        echo '<td>' . htmlspecialchars($product->unit_price) . '</td>';
                        echo '<td>' . htmlspecialchars($quantity) . '</td>';
                        echo '<td> <img src="' . htmlspecialchars($product->picture) . '" class="picture" style="max-width:10em; max-height: 10em;"></td>';

                        //Löschen
                        echo '<td>';
                        echo '<form action="cart.php" method="POST">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($product->id) . '">';
                        echo '<button name="bt_delete">Löschen!</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }



                    ?>
                </tbody>
            </table>
            <p><strong>Gesamtpreis: <?php echo number_format($total, 2, '.', ','); ?> EUR</strong></p>

            <form action="order.php" method="POST">
                <button>Jetzt bestellen!</button>
            </form>
        </section>
    </main>
</body>

</html>