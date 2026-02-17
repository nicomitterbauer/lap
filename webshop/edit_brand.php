<?php
require_once 'maininclude.inc.php';

if(!isset($_GET['id'])){
    exit('Get-Parameter id fehlt');
}
if(filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
    exit('Get-Parameter id ungültig');
}

$id = $_GET['id'];

$brand = $dba->getBrandById($id);

if($brand === false){
    exit('Keine Marke mit der ID gefunden');
}

if(isset($_POST['bt_update_brand'])){
    $brand_name = trim($_POST['brand_name']);

    if(empty($brand_name)){
        $errors[]= 'Kategoriename eingeben';
    }

    if($brand !== false && $brand->id != $id){
        $errors[] = 'Name bereits vorhanden';
    }

    $brand->name = $brand_name;

    if(count($errors) === 0){
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php include 'header.inc.php'; ?>
            <section>
                <h2>Werkzeugwelt</h2>
                <?php include 'showerrors.inc.php'; ?>
                
                <h3>Marke Bearbeiten</h3>
                <form action="edit_brand.php?id=<?php echo htmlspecialchars($brand->id); ?>" method="POST">
                    <label>Name:</label><br>
                    <input type="text" name="brand_name" value="<?php echo htmlspecialchars($brand->name); ?>"><br>
                    <button name="bt_update_brand">Bearbeiten</button>
                </form>
            </section>
    </main>



</body>
</html>