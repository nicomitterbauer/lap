<header>
    <?php if($dba->isLoggedIn()){ ?>
    <li><a href="index.php">Start</a></li>
    <li><a href="logout.php">Logout</a></li>
    <?php }?>

    <?php if($dba->isAdmin()){ ?>
    <li><a href="brand.php">Marke</a></li>
    <li><a href="category.php">Kategorie</a></li>
    <?php } ?>

    <?php if(!$dba->isLoggedIn()){ ?>
    <li><a href="login.php">Login</a></li>
    <li><a href="register.php">Registrierung</a></li>
<?php } ?>
</header>