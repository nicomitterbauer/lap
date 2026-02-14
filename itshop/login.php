<?php 

require_once 'maininclude.inc.php';

if(isset($_POST['bt_login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($password)){
        $errors[] = 'Passwort eingeben';
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'Email ungültig';
    }

    $success = $dba->login($email, $password);

    if($success == true){
        header('location: index.php');
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
        <h2>Login</h2>
        <?php include 'showerrors.inc.php'; ?>


        <form action="login.php" method="POST">
            <label>Email</label><br>
            <input type="email" name="email"><br>

            <label>Passwort</label><br>
            <input type="password" name="password"><br>

            <button name="bt_login">Login</button>

        </form>




    </section>
    
</body>
</html>