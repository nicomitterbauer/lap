<?php
require_once 'maininclude.php';

if ($dba->isAdmin() === false) {
    header('Location: index.php');
}

if(!isset($_GET['id'])){
    exit('Kein gültiger GET Parameter!');
}

// gibt es für diese ID überhaupt ein Produkt?
$brand = $dba->getBrandById($_GET['id']);
if($brand === false){
    exit('Marke nicht gefunden!');
}

if (isset($_POST['bt_brand'])) {
    $new_name = trim($_POST['new_name']);

    if (empty($new_name)) {
        $errors[] = 'Marke darf nicht leer sein!';
    }

    $brandByNewName = $dba->getBrandByName($new_name);
    // ist diese Marke bereits vorhanden?
    if($brandByNewName != false && $brandByNewName->id != $brand->id){
        $errors[]='Produkt existiert bereits';
    }

    $brand->name = $new_name;

    if (count($errors) === 0) {
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
    <title>Brand-Edit</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Marke Bearbeiten!</h1>

            <form action="edit_brand.php?id=<?php echo $brand->id ?>" method="POST">
                <label>Neuer Markenname:</label><br>
                <input type="text" name="new_name" value="<?php echo $brand->name ?>"><br>

                <button type="submit" name="bt_brand">Speichern!</button>

            </form>

        </section>
    </main>
</body>

</html>