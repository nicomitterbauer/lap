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
    if($dba->getCategoryByName($name) !== false){
        $errors[] = 'Name bereits vorhanden!';
    }

    if(count($errors) == 0){
        $dba->createCategory($name);
        header('Location: category.php');
    exit();
    }


}

if(isset($_POST['bt_delete'])){
    $category_id = $_POST['category_id'];

    $dba->deleteCategory($category_id);
        header('Location: category.php');
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
        <h2>Kategorie</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="category.php" method="POST">
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
                <?php foreach($dba->getAllcategories() as $c) { ?>
                <tr>
                    <td><?= htmlspecialchars($c->id) ?></td>
                    <td><?= htmlspecialchars($c->name) ?></td>

                    <td>
                        <a href="edit_category.php?id=<?= htmlspecialchars($c->id)  ?>">Bearbeiten</a>

                        <form action="category.php" method="POST">
                            <input type="hidden" name="category_id" value="<?= htmlspecialchars($c->id)  ?>">
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