<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
        header("location: login.php");
        exit(1);
    }
    ?>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <link href="css/success.css" rel="stylesheet" />
    <title>Cart</title>
</head>

<body>
    <div class="card">
        <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
            <i class="checkmark">✓</i>
        </div>
        <h1>Success</h1>
        <p>Order request received</p>
        <div style=" text-align: center; padding-top:20px;">
            <a href="index.php" button type="button" class="btn btn-outline-success">Return to Home Page</a></button>
        </div>
    </div>
</body>

<?php
require 'func-send-digital-receipt.php';
?>

</html>