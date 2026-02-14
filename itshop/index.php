<?php 

require_once 'maininclude.inc.php';


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


        <div class="product-list">
            <?php foreach($dba->getAllProducts() as $p){?>
            <div class="product-card">
                <p><img src="<?= $p->picture ?>" alt="" class="picture"></p>

                <h2><a href="info_product.php?id=<?= htmlspecialchars($p->id) ?>"><?= htmlspecialchars($p->title) ?></a></h2>
                
                <p><?= htmlspecialchars(number_format($p->price, 2, ',', '.')) ?> EUR</p>
            </div>
            <?php }?>
        </div>


    </section>
    
</body>
</html>