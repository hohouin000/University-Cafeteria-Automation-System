<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
    header("location: login.php");
    exit(1);
}

if (isset($_POST["mitem-id"]) && isset($_POST["store-id"])) {
    $mitem_id = $_POST["mitem-id"];
    $store_id = $_POST["store-id"];
    $user_id = $_SESSION["user_id"];
    $query = "SELECT * from cart WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
    $result = $mysqli->query($query);
    $rowcount = mysqli_num_rows($result);
    if ($rowcount > 0) {

        $amount = $_POST["amount"];
        $remark = $_POST["remark"];

        $query = "DELETE FROM cart WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
        $result = $mysqli->query($query);

        if ($result) {
            header("location:cart.php?response=1");
            exit(1);
        } else {
            header("location:cart.php?response=0");
            exit(1);
        }
    } else {
        header("location:cart.php?response=0");
        exit(1);
    }
}
