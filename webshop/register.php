<?php
require_once 'maininclude.inc.php';
if (isset($_POST['bt_register'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(!isset($_POST['tos'])) {
        $errors[] = 'Bitte AGB akzeptieren';
    }
    if(empty($firstname)){
        $errors[] = 'Vorname eingeben';
    }
    if(empty($lastname)){
        $errors[] = 'Nachname eingeben';
    }
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
        $errors[] = 'Ungültige E-Mail Adresse';
    }
    if(strlen($password) < 8){
        $errors[] = 'Passwort zu kurz, mindestens 8 Zeichen';
    }

    if(count($errors) == 0){
        
            $dba->registerUser($firstname, $lastname, $email, $password, false);
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
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <?php include 'header.inc.php'; ?>
            <section>
                <h2>Registrieren</h2>
                <?php include 'showerrors.inc.php'; ?>
                <form action="register.php" method="post">
                    <label>Vorname</label><br>
                    <input type="text" name="firstname" required><br>
                    <label>Nachname</label><br>
                    <input type="text" name="lastname" required><br>
                    <label>Email</label><br>
                    <input type="email" name="email" required><br>
                    <label>Passwort</label><br>
                    <input type="password" name="password" required><br>
                    <input type="checkbox" name="tos"> <label>Ich bestätige die AGB</label><br>
                    <button name="bt_register">Registrieren</button>
                    </form>
            </section>
    </main>



</body>
</html>