<?php 
require_once 'maininclude.inc.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <section>
        <?php include 'header.inc.php'; ?>
        <h2>Startseite</h2>
        <?php include 'showerrors.inc.php'; ?>
    </section> 

    <div class="product-list">
        <?php foreach($dba->getAllProducts() as $p){ ?>
            <div class="product-card">
                <p><img src="<?= $p->picture?>" alt="" style="max-width: 10em; max-height: 10em;"></p>
                <h2>
                    <a href="info_product.php?id=<?= htmlspecialchars($p->id)?>"><?= htmlspecialchars($p->title)?></a>
                </h2>

                <p>Preis: <?= htmlspecialchars($p->unit_price)?></p>
            </div>

        <?php } ?>

    </div>
</body>
</html>