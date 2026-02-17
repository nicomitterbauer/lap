<?php
require_once 'maininclude.inc.php';

// gibt es den GET-Parameter für die ID?
if(!isset($_GET['id']) || empty($_GET['id'])){
    exit('GET-Parameter id fehlt!');
}

// Lade das gesamte Objekt der Kategorie (was bearbeitet werden soll)
$category = $dba->getCategoryById($_GET['id']);

if($category === false ){
    exit('Kategorie nicht gefunden');
}

if(isset($_POST['bt_update'])){
    $name = trim($_POST['name']);

    if(empty($name)){
        $errors[] = 'Bitte Namen eingeben';
    }

    // Doppelte Namen vermeiden
    // Lade Kategorie anhand des neuen Namens

    $categoryByNewName = $dba->getCategoryByName($name);
    // Wenn es eine andere Kategorie mit dem gleichen Namen schon gibt --> Fehler
    if($categoryByNewName !== FALSE && $categoryByNewName->id != $category->id){
        $errors[] = 'Es gibt bereits eine Kategorie mit diesem Name';
    }

    // Aktualisiere die Werte im Objekt
    // schreibe die Usereingaben in das Objekt
    $category->name = $name;

    if(count($errors) == 0){
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php include 'header.inc.php'; ?>
            <section>
                <h2>Werkzeugwelt</h2>
                <?php include 'showerrors.inc.php'; ?>

                <h3>Kategorie Bearbeiten</h3>
                <form action="edit_category.php?id=<?php echo $category->id; ?>" method="POST">
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($category->name); ?>"><br>
                    <button name="bt_update">bearbeiten</button>
                </form>
              
            </section>
    </main>



</body>
</html>