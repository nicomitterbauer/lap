<?php
require_once 'maininclude.inc.php';

if(isset($_POST['bt_create_brand'])){
    $brand_name = trim($_POST['brand_name']);

    if(empty($brand_name)){
        $errors[] = 'Markennamen einegeben';
    }else if ($dba->getBrandByName($brand_name) !== false){
        $errors[] = 'Bezeichnung bereits vorhanden';
    }

    if(count($errors) == 0){
        $dba->createBrand($brand_name);
        header('Location: brand.php');
        exit();
    }
}

if(isset($_POST['bt_delete_brand'])){
        $id = $_POST['id'];
        $dba->deleteBrand($id);
    
    header('Location: brand.php');
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
                <h2>Werkzeugwelt</h2>
                <?php include 'showerrors.inc.php'; ?>
                <h3>Marke Hinzufügen</h3>
                <form action="brand.php" method="post">
                    <label> Markenname</label><br>
                    <input type="text" name="brand_name" required><br>
                    <button name="bt_create_brand">Marke erstellen</button>
                </form>

                <h3>Marken</h3>
                <?php $brands = $dba->getAllBrands();?>
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
                        foreach($brands as $b){
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($b->id) . '</td>';
                            echo '<td>' . htmlspecialchars($b->name) . '</td>';
                            echo '<td>';
                            // Bearbeiten Link
                            echo '<a href="edit_brand.php?id='. htmlspecialchars($b->id).'">Bearbeiten</a> ';
                            echo '<form method="POST" action="brand.php">';
                            echo '<input type="hidden" name="id" value="' . htmlspecialchars($b->id) . '">';
                            echo '<button name="bt_delete_brand">Löschen</button>';
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