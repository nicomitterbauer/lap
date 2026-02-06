<?php 
require_once 'maininclude.inc.php';

if(isset($_POST['bt_login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
        $errors[] = 'Ungültige Email!';
    }

    if(empty($password)){
        $errors[] = 'Password eingeben';
    }

    $success = $dba->login($email, $password);



    if($success = true) {
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
    <title>Document</title>
</head>
<body>
    <section>
        <?php include 'header.inc.php'; ?>
        <h2>Login</h2>
        <?php include 'showerrors.inc.php'; ?>

        <form action="login.php" method="POST">
            <label>Email</label>
            <input type="email" name="email"><br>

            <label>Passwort</label>
            <input type="password" name="password"><br>

            <button name="bt_login">Login</button>
        </form>



    </section> 
</body>
</html>