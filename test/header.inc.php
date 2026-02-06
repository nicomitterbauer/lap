<header>
    <h1>Webshop</h1>
    <nav>
        <ul>
            <li><a href="./">Start</a></li>
            <?php if($dba->isLoggedIn()){ ?>

                <?php if($dba->isAdmin()){ ?>
                <li><a href="./brand.php">Marke</a></li>
                <li><a href="./category.php">Kategorie</a></li>
                <li><a href="./product.php">Produkt</a></li>
                <?php }?>

                <li><a href="./logout.php">Logout</a></li>
            

            <?php } else {  ?>
                <li><a href="./register.php">Registrieren</a></li>
                <li><a href="./login.php">Login</a></li>


              <?php } ?>
            
                
            
            

            

          

        </ul>
    </nav>
</header>