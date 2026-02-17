<?php
require_once 'maininclude.inc.php';
if(isset($_POST['bt_login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $login_success = $dba->login($email, $password);
    if($login_success) {
        header('Location: index.php');
        exit();
    } else {
        $errors[] = 'Ungültige E-Mail oder Passwort';
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
    <main>
        <?php include 'header.inc.php';  ?>
        <section>
            <h2>Login</h2>
            <?php include 'showerrors.inc.php'; ?>
            <form action="login.php" method="post">
                <label>E-Mail</label><br>
                <input type="email" name="email" required><br>
                <label>Passwort</label><br>
                <input type="password" name="password" required><br>
                <button name="bt_login">Login</button>
            </form>
        </section>
    </main>
    
</body>
</html>