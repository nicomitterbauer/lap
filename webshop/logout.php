<?php
require_once 'maininclude.php';

if(isset($_POST['bt_logout'])){
    session_destroy();
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>
    <main>
        <?php include 'header.php'; ?>
        <section>
            <?php include 'showerrors.php'; ?>
            <h1>Logout!</h1>

            <form action="Logout.php" method="POST">
                <button name="bt_logout">Logout</button>
            </form>

        </section>
    </main>
</body>

</html>