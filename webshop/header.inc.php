<header>
            <h1>Webshop</h1>
            <nav>
                <ul>
                    <li><a href="./">Start</a></li>
                    <?php if($dba->isLoggedIn()){ ?>
                        
                        <?php if($dba->isAdmin()){ ?>
                            <li><a href="./category.php">Kategorie</a></li>
                            <li><a href="./brand.php">Marke</a></li>
                            <li><a href="./product.php">Produkte</a></li>
                            <li><a href="./admin_orders.php">Ansicht Bestellung</a></li>
                            <li><a href="./admin_statistics.php">Statistik</a></li>
                        <?php } ?>

                    <li><a href="./logout.php">Logout</a></li>
                    <li><a href="./cart.php">Warenkorb</a></li>

                    <?php } else { ?>

                    <li><a href="./login.php">Login</a></li>
                    <li><a href="./register.php">Registrieren</a></li>

                    <?php } ?>
                </ul>
            </nav>
        </header>