<?php 
require_once 'maininclude.inc.php';

if($dba->isAdmin() === false){
    header('Location: index.php');
    exit();
}

if(isset($_POST['bt_add_category'])){
    $name = trim($_POST['name']);
    
    if(empty($name)){
        $errors[] = 'Bitte Namen eingeben!';
    }

    if(count($errors) == 0) {
        $dba->createCategory($name);
        header('Location: category.php');
        exit();
    }

    
}

if(isset($_POST['bt_delete_category'])){
    $id = trim($_POST['id']);

    if(count($errors) == 0) {
        $dba->deleteCategory($id);
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
        <h2>Kategorien</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="category.php" method="POST">
            <label>Kategorie Name</label>
            <input type="text" name="name"><br>

            <button name="bt_add_category">kategorie hinzufügen</button>
        </form>



        <table>

            
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kategorie</th>
                    <th>Aktionen</th>
                </tr>

            </thead>
            <tbody>
                <?php
                $categories = $dba->getAllCategories();
                foreach($categories as $c){
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($c->id) . '</td>';
                    echo '<td>' . htmlspecialchars(string: $c->name) . '</td>';
                    echo '<td>';
                    echo '<a href="edit_category.php?id=' . htmlspecialchars($c->id).'">Bearbeiten</a>';
                    echo '<form action="category.php" method="POST">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($c->id). '">';
                    echo '<button name="bt_delete_category">Löschen</button>';
                    echo '</form>';
                    echo '</td>';
                    echo'</tr>';

                } ?>

            </tbody>
        </table>



    </section> 
</body>
</html>