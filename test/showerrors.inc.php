<?php

if(isset($errors) && count($errors) > 0) {
    echo '<ul>';
    foreach($errors as $error){
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul>';
}



?>