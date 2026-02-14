<?php 

require_once 'maininclude.inc.php';

if(!$dba->isAdmin()){
    header('Location: index.php');
    exit();
}

if(!isset($_GET['id'])){
    exit('id fehlt');
}

if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    exit('id ungültig');
}

$id = $_GET['id'];

$brand = $dba->getBrandById($id);

if(!$brand){
    exit('Marke fehlt');
}



if(isset($_POST['bt_update'])){
    $newName = trim($_POST['name']);

    if(empty($newName)){
        $errors[] = 'Name eingeben!';
    }

    $existingBrand = $dba->getBrandByName($newName);


    if($existingBrand && $existingBrand->id == $id){
        $errors[] = 'Name bereits vorhanden!';
    }

    if(count($errors) != 0){
        $brand->name = $newName;
        $dba->updateBrand($brand);
        header('Location: brand.php');
    exit();
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
        <h2>Marke bearbeiten</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="edit_brand.php?id=<?= htmlspecialchars($id)  ?>" method="POST">
            <label>Name</label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($brand->name)  ?>"><br>

            <button name="bt_update">Aktualisieren</button>

        </form>


    </section>
    
</body>
</html>