<?php
require_once 'maininclude.php';

if(isset($_POST['bt_register'])){
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $is_admin = 0;

    if(empty($firstname)){
        $errors[]='Vorname darf nicht leer sein!';
    }

    if(empty($lastname)){
        $errors[]='Nachname darf nicht leer sein!';
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[]='Bitte geben Sie eine gültige Email ein!';
    }  elseif ($dba->getUserByEmail($email)!==false){
        $errors[]='Email bereits vorhanden';
    }

    if(strlen($password)<8){
        $errors[]='Passwort muss mind. 8 Zeichen lang!';
    }

    if(!isset($_POST['AGB'])){
        $errors[]='Bitt AGB akzeptieren!';
    }

    if(count($errors)===0){
        $dba->createUser($firstname, $lastname, $email, $password, $is_admin);
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
    <title>Registrierung</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Registrierung!</h1>

            <form action="register.php" method="POST">
                <label>Vorname:</label><br>
                <input type="text" name="firstname"><br>

                <label>Nachname:</label><br>
                <input type="text" name="lastname"><br>

                <label>Email:</label><br>
                <input type="email" name="email"><br>

                <label>Passwort:</label><br>
                <input type="password" name="password"><br>

                <label>Ich akzeptiere die AGB:</label>
                <input type="checkbox" name="AGB"><br>

                <button type="submit" name="bt_register">Registrieren!</button>

            </form>

        </section>
    </main>
</body>

</html>