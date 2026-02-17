<?php
require_once 'maininclude.inc.php';

if(!$dba->isAdmin()){
    header('Location: index.php');
    exit();
}

// hole die connection
$conn = $dba->getConn();


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
                <h2>Werkzeugwelt</h2>
                <?php include 'showerrors.inc.php'; ?>
                <h2>Statistik</h2>

                <h3> Wie viele Produkte gibt es?</h3>
                <?php
                $ps= $conn->prepare('
                SELECT COUNT(*) AS anzahl
                FROM products
                ');

                $ps->execute();
                // iteriere über jeden Datensatz den die Datenbank als Ergebnis zurücksendet
                while($row = $ps->fetch()){
                    echo '<p> Es gibt ' . $row['anzahl'] . ' Produkte </p>';
                }
                ?>


                <h3>Wie oft (Stückzahl) wurde jedes Produkt bestellt?</h3>
                <p> COALESCE nimmt den ersten nicht-NULL Wert</p>
                <?php
                $ps = $conn->prepare('
                SELECT COALESCE(SUM(oi.quantity), 0) AS anzahl, p.id, p.title AS title
                FROM products p
                LEFT JOIN order_item oi ON(p.id = oi.product_id)
                GROUP BY p.id
                ORDER BY anzahl DESC
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['title']) . ':' . $row['anzahl'] . '</p>';
                }
                ?>

                <h3>Welche zwei Produkte wurde am häufigsten bestellt</h3>
                <?php
                $ps = $conn->prepare('
                SELECT COALESCE(SUM(oi.quantity), 0) AS anzahl, p.id, p.title AS title
                FROM products p
                LEFT JOIN order_item oi ON(p.id = oi.product_id)
                GROUP BY p.id
                ORDER BY anzahl DESC
                LIMIT 2
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['title']) . ':' . $row['anzahl'] . '</p>';
                }
                ?>

                <h3>Welche zwei Produkte wurden am seltensten bestellt</h3>
                 <?php
                $ps = $conn->prepare('
                SELECT COALESCE(SUM(oi.quantity), 0) AS anzahl, p.id, p.title AS title
                FROM products p
                LEFT JOIN order_item oi ON(p.id = oi.product_id)
                GROUP BY p.id
                ORDER BY anzahl ASC
                LIMIT 2
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['title']) . ':' . $row['anzahl'] . '</p>';
                }
                ?>

                <h3>Welche Produkte wurde noch nie bestellt</h3>
                 <?php
                $ps = $conn->prepare('
                SELECT p.id, p.title AS title
                FROM products p
                LEFT JOIN order_item oi ON(p.id = oi.product_id)
                WHERE oi.id IS NULL
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['title']) . '</p>';
                }
                ?>

                <h3>Unter den mind. 1x verkauften Produkten, welche sind die 2 seltensten Produkte</h3>
                 <?php
                 // Where filter vor dem group by 
                 //Having filtert das Ergebnis von group by 
                $ps = $conn->prepare('
                SELECT COALESCE(SUM(oi.quantity), 0) AS anzahl, p.id, p.title AS title
                FROM products p
                LEFT JOIN order_item oi ON(p.id = oi.product_id)
                GROUP BY p.id
                HAVING anzahl > 0
                ORDER BY anzahl ASC
                LIMIT 2
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['title']) . ':' . $row['anzahl'] . '</p>';
                }
                ?>



                <h3>Welcher Kunde hat am häufigsten bestellt</h3>
                // Ermittle die Anzahl der Kunden
                // Sortiere nach Anzahl, nehme den ersten
                <?php
                $ps = $conn->prepare('
                SELECT COUNT(u.id) AS anzahl, u.firstname, u.lastname, u.email
                FROM users u
                LEFT JOIN orders o ON(u.id = o.user_id)
                GROUP BY u.id
                ORDER BY anzahl DESC
                LIMIT 1

                ');

                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['email']) . ':' . $row['anzahl'] . '</p>';
                }
                ?>


                <h3>Wie viel kostet ein Produkt im Durschnitt?</h3>
                <?php
                $ps = $conn->prepare('
                SELECT AVG(price) AS durchschnitt
                FROM products
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['durchschnitt']) . '</p>';
                }
                ?>

                <h3>Wie viele Kunden gibt es?</h3>
                <?php
                $ps = $conn->prepare('
                SELECT COUNT(*) AS anzahl
                FROM users
                WHERE is_admin = 0
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>Es gibt ' . htmlspecialchars($row['anzahl']) . ' Kunden</p>';
                }
                ?>




                <h3>Wie viele Administratioren gibt es?</h3>
                <?php
                $ps = $conn->prepare('
                SELECT COUNT(is_admin) AS anzahl
                FROM users
                WHERE is_admin = 1
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>Es gibt ' . htmlspecialchars($row['anzahl']) . ' Admins</p>';
                }
                ?>


                <h3>Wie heißt das teuerste Produkt, und wie viele kostet es?</h3>
                <?php
                $ps = $conn->prepare('
                SELECT title, price
                FROM products
                ORDER BY price DESC
                LIMIT 1
                ');
                $ps->execute();
                while($row = $ps->fetch()){
                    echo '<p>' . htmlspecialchars($row['title']) . ': €' . number_format($row['price'], 2, ',', '.') . '</p>';
                }
                ?>


            </section>
    </main>



</body>
</html>