<?php

require_once 'maininclude.inc.php';

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_POST['bt_cart'])){
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if($product_id < 1){
        $errors[] = 'Ungültige id';
    } elseif (!filter_var($product_id, FILTER_VALIDATE_INT)){
        $errors[] = 'Ungültige id';
    }


    $found = false;

    for($i = 0; $i < count($_SESSION['cart']); $i++){
        if($_SESSION['cart']['$i']['product_id'] == $product_id){
            $_SESSION['cart']['$i']['quantity'] += $quantity;
            $found = true;
            break;
        } 
    }

    if($found === false){
        $entry = [];
        $entry['product_id'] = $product_id;
        $entry['quantity'] = $quantity;
        $_SESSION['cart'][] = $entry;
    }

    header('Location: cart.php');
    exit();
}

if(isset())




?>