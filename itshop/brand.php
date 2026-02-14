<?php 

require_once 'maininclude.inc.php';

if(!$dba->isAdmin()){
    header('Location: index.php');
    exit();
}

if(isset($_POST['bt_add'])){
    $name = trim($_POST['name']);

    if(empty($name)){
        $errors[] = 'Name eingeben!';
    }
    if($dba->getBrandByName($name) !== false){
        $errors[] = 'Name bereits vorhanden!';
    }

    if(count($errors) == 0){
        $dba->createBrand($name);
        header('Location: brand.php');
    exit();
    }


}

if(isset($_POST['bt_delete'])){
    $brand_id = $_POST['brand_id'];

    $dba->deleteBrand($brand_id);
        header('Location: brand.php');
        exit();
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
        <h2>Marke</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="brand.php" method="POST">
            <label>Name</label><br>
            <input type="text" name="name"><br>

            <button name="bt_add">Hinzufügen</button>

        </form>


        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marke</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($dba->getAllBrands() as $b) { ?>
                <tr>
                    <td><?= htmlspecialchars($b->id) ?></td>
                    <td><?= htmlspecialchars($b->name) ?></td>

                    <td>
                        <a href="edit_brand.php?id=<?= htmlspecialchars($b->id)  ?>">Bearbeiten</a>

                        <form action="brand.php" method="POST">
                            <input type="hidden" name="brand_id" value="<?= htmlspecialchars($b->id)  ?>">
                            <button name="bt_delete">Löschen</button>
                        </form>
                    </td>
                </tr>

                <?php } ?>
            </tbody>
        </table>


    </section>
    
</body>
</html>