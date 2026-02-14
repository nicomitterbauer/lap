<?php 

require_once 'maininclude.inc.php';

if(isset($_POST['bt_register'])){
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $is_admin = false;

    if(!isset($_POST['tos'])){
        $errors[] = 'Akzeptiere die AGBs';
    }

    if(empty($firstname)){
        $errors[] = 'Vorname eingeben';
    }

    if(empty($lastname)){
        $errors[] = 'Nachname eingeben';
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Email ungültig';
    } else if ($dba->getUserByEmail($email) !== false){
        $errors[] = 'Email bereits vorhanden';
    }

    if(strlen($password) < 8){
        $errors[] = 'passwort muss mindestens 8 Zeichen haben';
    }

    if(count($errors) == 0){
        $dba->registerUser($firstname, $lastname, $email, $password, $is_admin);
        header('Location: login.php');
        exit();
    }

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
        <h2>Registrieren</h2>
        <?php include 'showerrors.inc.php'; ?>


        <form action="register.php" method="POST">
            <label>Vorname</label><br>
            <input type="text" name="firstname"><br>

            <label>Nachname</label><br>
            <input type="text" name="lastname"><br>

            <label>Email</label><br>
            <input type="email" name="email"><br>

            <label>Passwort</label><br>
            <input type="password" name="password"><br>

            <label>Ich Akzeptiere die AGBs</label>
            <input type="checkbox" name="tos"><br>

            <button name="bt_register">Registrieren</button>

        </form>




    </section>
    
</body>
</html>