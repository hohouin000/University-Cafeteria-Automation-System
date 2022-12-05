<?php
    $mysqli = new mysqli("localhost","root","","ucas");

    if($mysqli -> connect_errno){
        header("location: db_error.php");
        exit(1);
    }

    define('SITE_ROOT',realpath(dirname(__FILE__)));
    define('ADD_URL','https://localhost/fyp/func-add-order.php');
    define('FAILED_URL','https://localhost/fyp/order-failed.php');
    date_default_timezone_set('Asia/Kuala_Lumpur');
