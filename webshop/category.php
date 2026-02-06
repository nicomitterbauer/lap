<?php
require_once 'maininclude.php';

if ($dba->isAdmin() === false) {
    header('Location: index.php');
}

if (isset($_POST['bt_category'])) {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $errors[] = 'Kategorie darf nicht leer sein!';
    }
    if ($dba->getCategoryByName($name) === true) {
        $errors[] = 'Kategorie bereits vorhanden!';
    }

    if (count($errors) === 0) {
        $dba->createCategory($name);
        header('Location: category.php');
        exit();
    }
}

if(isset($_POST['bt_delete'])){
    $dba->deleteCategory($_POST['id']);
    header('Location: category.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorie</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Kategorie hinzufügen!</h1>

            <form action="category.php" method="POST">
                <label>Kategoriename:</label><br>
                <input type="text" name="name"><br>

                <button type="submit" name="bt_category">Kategoriename anlegen!</button>

            </form>

            <h2>Bestehende Kategorien!</h2>
            <table border="1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $categories = $dba->getAllCategories();
                    foreach ($categories as $category) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($category->id) . '</td>';
                        echo '<td>' . htmlspecialchars($category->name) . '</td>';

                        // Bearbeiten
                        echo '<td>';
                        echo '<a href="edit_category.php?id=' . htmlspecialchars($category->id) . '">Bearbeiten</a>';

                        // Löschen
                        echo '<form action="category.php" method="POST">';
                        echo '<input type="hidden" name="id" value="'.$category->id.'">';
                        echo '<button name="bt_delete">Löschen!</button>';
                        echo '</form>';

                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>

        </section>
    </main>
</body>

</html>