<?php 
require_once 'maininclude.inc.php';

if($dba->isAdmin() === false){
    header('Location: index.php');
    exit();
}

if(isset($_POST['bt_add_brand'])){
    $name = trim($_POST['name']);
    
    if(empty($name)){
        $errors[] = 'Bitte Namen eingeben!';
    }

    if(count($errors) == 0) {
        $dba->createBrand($name);
        header('Location: brand.php');
        exit();
    }

}

if(isset($_POST['bt_delete_brand'])){
    $id = trim($_POST['id']);

    if(count($errors) == 0) {
        $dba->deleteBrand($id);
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
</head>
<body>
    <section>
        <?php include 'header.inc.php'; ?>
        <h2>Marke</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="brand.php" method="POST">
            <label>Marken Name</label>
            <input type="text" name="name"><br>

            <button name="bt_add_brand">Marke hinzufügen</button>
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
                <?php
                $brand = $dba->getAllBrands();
                foreach($brand as $b){
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($b->id) . '</td>';
                    echo '<td>' . htmlspecialchars(string: $b->name) . '</td>';
                    echo '<td>';
                    echo '<a href="edit_brand.php?id=' . htmlspecialchars($b->id).'">Bearbeiten</a>';
                    
                    echo '<form action="brand.php" method="POST">';
                    echo '<input type="hidden" name="id" value="' . htmlspecialchars($b->id). '">';
                    echo '<button name="bt_delete_brand">Löschen</button>';
                    echo '</form>';

                    echo '</td>';
                    echo'</tr>';

                } ?>

            </tbody>
        
                
            
        </table>



    </section> 
</body>
</html>