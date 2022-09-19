<?php
if(! isset($_SESSION)){
    session_start();
}

if(empty($_SESSION['admin'])){
    header('Location: add-to-cart-api.php');
    exit;
}