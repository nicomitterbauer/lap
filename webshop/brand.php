<?php
require_once 'maininclude.php';

if ($dba->isAdmin() === false) {
    header('Location: index.php');
}

if (isset($_POST['bt_brand'])) {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $errors[] = 'Marke darf nicht leer sein!';
    }
    if ($dba->getBrandByName($name) === true) {
        $errors[] = 'Marke bereits vorhanden!';
    }

    if (count($errors) === 0) {
        $dba->createBrand($name);
        header('Location: brand.php');
        exit();
    }
}

if(isset($_POST['bt_delete'])){
    $dba->deleteBrand($_POST['id']);
    header('Location: brand.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brand</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Marke hinzufügen!</h1>

            <form action="brand.php" method="POST">
                <label>Markenname:</label><br>
                <input type="text" name="name"><br>

                <button type="submit" name="bt_brand">Markennamen anlegen!</button>

            </form>

            <h2>Bestehende Marken!</h2>
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
                    $brands = $dba->getAllBrands();
                    foreach ($brands as $brand) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($brand->id) . '</td>';
                        echo '<td>' . htmlspecialchars($brand->name) . '</td>';

                        // Bearbeiten
                        echo '<td>';
                        echo '<a href="edit_brand.php?id=' . htmlspecialchars($brand->id) . '">Bearbeiten</a>';

                        // Löschen
                        echo '<form action="brand.php" method="POST">';
                        echo '<input type="hidden" name="id" value="'.$brand->id.'">';
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