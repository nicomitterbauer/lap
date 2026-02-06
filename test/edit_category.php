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


$category = $dba->getCategoryById($id);

if($category === false){
    exit('keine Kategorie vorhanden');
}



if(isset($_POST['bt_update_category'])){
    $newName = trim($_POST['category_name']);

    $category_name = $dba->getCategoryByName($newName);
    
    if(empty($newName)){
        $errors[] = 'Bitte Namen eingeben!';
    }

    if($category_name && $category_name->id == $id){
        $errors[] = 'Name bereits vorhanden';
    }

    $category->name = $newName;


    if(count($errors) == 0) {
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
    <title>Document</title>
</head>
<body>
    <section>
        <?php include 'header.inc.php'; ?>
        <h2>Marke</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="edit_category.php?id=<?php echo htmlspecialchars($category->id)?>" method="POST">
            <label>Kategorie Bearbeiten</label>
            <input type="text" name="category_name" value="<?php echo htmlspecialchars($category->name)?>"><br>

            <button name="bt_update_category">Marke aktualisieren</button>
        </form>


    </section> 
</body>
</html>