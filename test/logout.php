<?php 
require_once 'maininclude.inc.php';

if(isset($_POST['bt_logout'])){
    session_destroy();
    header('Location: index.php');
    exit();
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
        <h2>Logout</h2>
        <?php include 'showerrors.inc.php'; ?>
        
        <form action="logout.php" method="POST">
            <label>Ausloggen?</label>
            <button name="bt_logout">Logout</button>
        </form>

    </section> 
</body>
</html>