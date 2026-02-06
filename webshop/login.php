<?php
require_once 'maininclude.php';

if(isset($_POST['bt_login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if($dba->login($email, $password)===false){
        $errors[]='Email oder Passwort falsch!';
    } else {
        header('Location: index.php');
        exit();
    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Login!</h1>

            <form action="login.php" method="POST">
                <label>Email:</label><br>
                <input type="email" name="email"><br>

                <label>Passwort:</label><br>
                <input type="password" name="password"><br>

                <button type="submit" name="bt_login">Login!</button>

            </form>

        </section>
    </main>
</body>

</html>