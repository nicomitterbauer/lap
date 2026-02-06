<?php
require_once 'maininclude.php';

if ($dba->isAdmin() === false) {
    header('Location: index.php');
}

if(!isset($_GET['id'])){
    exit('Kein gültiger GET Parameter!');
}

// gibt es für diese ID überhaupt ein Produkt?
$category = $dba->getCategoryById($_GET['id']);
if($category === false){
    exit('Kategorie nicht gefunden!');
}

if (isset($_POST['bt_category'])) {
    $new_name = trim($_POST['new_name']);

    if (empty($new_name)) {
        $errors[] = 'Kategory darf nicht leer sein!';
    }

    $categoryByNewName = $dba->getCategoryByName($new_name);
    // ist diese Kategorie bereits vorhanden?
    if($categoryByNewName != false && $categoryByNewName->id != $category->id){
        $errors[]='Kategorie existiert bereits';
    }

    $category->name = $new_name;

    if (count($errors) === 0) {
        $dba->updateCategory($category);
        header('Location: category.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category-Edit</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Kategorie Bearbeiten!</h1>

            <form action="edit_category.php?id=<?php echo $category->id ?>" method="POST">
                <label>Neuer Kategoriename:</label><br>
                <input type="text" name="new_name" value="<?php echo $category->name ?>"><br>

                <button type="submit" name="bt_category">Speichern!</button>

            </form>

        </section>
    </main>
</body>

</html>