<?php
require_once 'maininclude.inc.php';

if(isset($_POST['bt_create_category'])){
    $category_name = trim($_POST['category_name']);

    if(empty($category_name)){
        $errors[] = 'Kategoriename eingeben';
    }else if ($dba->getCategoryByName($category_name) !== false){
        $errors[] = 'Bezeichnung bereits vorhanden';
    }

    if(count($errors) == 0){
        $dba->createCategory($category_name);
        header('Location: category.php');
        exit();
    }
}

if(isset($_POST['delete_c'])){
        $id = $_POST['id'];
        $dba->deleteCategory($id);
    
    header('Location: category.php');
    exit();
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
                <h2>Kategorie</h2>
                <?php include 'showerrors.inc.php'; ?>

                <h3>Neue Kategorie erstellen</h3>
                <form action="category.php" method="post">
                    <label>Kategoriename</label><br>
                    <input type="text" name="category_name" required><br>
                    <button name="bt_create_category">Kategorie erstellen</button>
                </form>

                <h3>Liste</h3>

                 <?php $categories = $dba->getAllCategories();?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($categories as $c){
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($c->id) . '</td>';
                            echo '<td>' . htmlspecialchars($c->name) . '</td>';
                            echo '<td>';
                            // Bearbeiten Link
                            echo '<a href="edit_category.php?id='.$c->id.'">Bearbeiten</a> ';
                            // Lösch-Formular
                            echo '<form method="POST" action="category.php">';
                            echo '<input type="hidden" name="id" value="' . htmlspecialchars($c->id) . '">';
                            echo '<button name="delete_c">Löschen</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

            </section>
    </main>



</body>
</html>