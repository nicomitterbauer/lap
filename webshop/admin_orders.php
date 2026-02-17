<?php

require_once 'maininclude.inc.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Alle Bestellungen</h1>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User-ID</th>
                <th>Bestelldatum</th>
                <th>Summe</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $orders = $dba->getAllOrders();
            foreach($orders as $o){
                echo '<tr>';
                echo '<td>' . $o->id . '</td>';
                echo '<td>' . $o->user_id . '</td>';
                echo '<td>' . $o->orderDate->format('Y-m-d H:i:s') . '</td>';
                echo '<td>' . $o->amount . '</td>';
                echo '<tr>';
            }
           


?>

        </tbody>
    </table>
    
</body>
</html>