<?php
require_once 'maininclude.inc.php';




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
                

                <h2>Bestehende Produkte</h2>
            <h1>Unsere Produkte</h1>

            <div class="product-list">
                <?php foreach ($dba->getAllProducts() as $product): ?>
                    <div class="product-card">
                        <h2>
                            <a href="product.php?id=<?= $product->id ?>">
                                <?= htmlspecialchars($product->title) ?>
                            </a>
                        </h2>

                        <p>Preis: <?= number_format($product->price, 2, ',', '.') ?> €</p>
                    </div>
                <?php endforeach; ?>
            </div>

            </section>
    </main>



</body>
</html>