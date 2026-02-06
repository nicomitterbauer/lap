<?php 
require_once 'maininclude.inc.php';

if($dba->isAdmin() === false){
    header('Location: index.php');
    exit();
}

if(!isset($_GET['id'])){
    exit('Fehler, Get Paramter');
}

if(!filter_var($_GET['id'], FILTER_VALIDATE_INT)){
    exit('Fehler, Get Filter');
}


$id = $_GET['id'];


$brand = $dba->getBrandById($id);

if($brand === false){
    exit('keine Marke vorhanden');
}



if(isset($_POST['bt_update_brand'])){
    $newName = trim($_POST['brand_name']);

    $brand_name = $dba->getBrandByName($newName);
    
    if(empty($newName)){
        $errors[] = 'Bitte Namen eingeben!';
    }

    if($brand_name && $brand_name->id == $id){
        $errors[] = 'Name bereits vorhanden';
    }

    $brand->name = $newName;


    if(count($errors) == 0) {
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
    <title>Document</title>
</head>
<body>
    <section>
        <?php include 'header.inc.php'; ?>
        <h2>Marke</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="edit_brand.php?id=<?php echo htmlspecialchars($brand->id)?>" method="POST">
            <label>Marken Bearbeiten</label>
            <input type="text" name="brand_name" value="<?php echo htmlspecialchars($brand->name)?>"><br>

            <button name="bt_update_brand">Marke aktualisieren</button>
        </form>


    </section> 
</body>
</html>