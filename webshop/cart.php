<?php
require_once 'maininclude.inc.php';

print_r($_SESSION);

// Warenkorb als Array in der Session speichern
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_POST['bt_add_to_cart'])){
    $product = $_POST['product_id'];
    $quantity = trim($_POST['quantity']);

    if(filter_var($quantity, FILTER_VALIDATE_INT) === false){
        $errors[] = 'Ungültige Menge';
    } else if ($quantity < 1){
        $errors[] = 'Menge muss mind. 1 sein';
    }

    if(filter_var($product, FILTER_VALIDATE_INT) === false){
        $errors[] = 'Ungültiges Produkt';
    } 
    
    if(count($errors) == 0){
        // Produkt zum Warenkorb hinzufügen, oder Warenkorb aktulatisieren

        // gibt es das Produkt schon im Warenkorb?
        $found = false;

        for($i = 0; $i < count($_SESSION['cart']); $i++){
            if($_SESSION['cart'][$i]['product_id'] == $product){
                // Produkt schon im Warenkorb, nur Menge erhöhen
                $_SESSION['cart'][$i]['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if(!$found){
            // Produkt noch nicht im Warenkorb, neuen Eintrag hinzufügen
            $entry = [];
            $entry['product_id'] = $product;
            $entry['quantity'] = $quantity;
            // Füge Eintrag zum Warenkorb 
            // hinzu
            $_SESSION['cart'][] = $entry;
        }

        

        
        header('Location: cart.php');
        exit();
    }
}

if(isset($_POST['bt_delete_from_cart'])){
    $id= $_POST['id'];
    // iteriere über session cart array und suche nach der Produkt id
    for($i = 0; $i < count($_SESSION['cart']); $i++){
                if($_SESSION['cart'][$i]['product_id'] == $id){
                    // Produkt gefunden, entferne es
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
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php include 'header.inc.php'; ?>
            <section>
                <h2>Werkzeugwelt</h2>
                <?php include 'showerrors.inc.php'; ?>

                <h2>Warenkorb</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Bild</th>
                            <th>Titel</th>
                            <th>Einzelpreis</th>
                            <th>Stück</th>
                            <th>Löschen</th>
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

                            if(!$product){
                                $errors[]='Kein Produkt';
                                continue;
                            }
                            
                            $total += $product->price * $quantity;
                            echo '<tr>';
                            echo '<td><img src="' . htmlspecialchars($product->picture) . '" style="max-width:100px; max-height:100px;"></td>';
                            echo '<td>' . htmlspecialchars($product->title) . '</td>';
                            echo '<td>' . htmlspecialchars($product->price) . '</td>';
                            echo '<td>' . htmlspecialchars($quantity) . ' Stück</td>';
                            echo '<td>';
                            echo '<form method="post" action="cart.php">';
                            echo '<input type="hidden" name="id" value="' . $product->id . '">';
                            echo '<button name="bt_delete_from_cart">Löschen</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>

                    </tbody>
                </table>
                
                <p><strong>Gesamtpreis <?php echo $total; ?> EUR</strong></p>

                <p>
                    <form action="order.php" method="GET">
                        <button>Bestellen</button>
                    </form>
                </p

              

            </section>
    </main>



</body>
</html>