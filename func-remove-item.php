<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
    header("location: login.php");
    exit(1);
}

if (isset($_POST["mitem-id"]) && isset($_POST["store-id"])) {
    if (!empty($_POST['mitem-id']) && !empty($_POST['store-id'])) {
        $mitem_id = mysqli_real_escape_string($mysqli, $_POST["mitem-id"]);
        $store_id = mysqli_real_escape_string($mysqli, $_POST["store-id"]);
        $user_id = mysqli_real_escape_string($mysqli, $_SESSION["user_id"]);

        $query = "SELECT * from cart WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
        $result = $mysqli->query($query);
        $rowcount = mysqli_num_rows($result);
        if ($rowcount > 0) {
            $query = "DELETE FROM cart WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
            $result = $mysqli->query($query);

            if ($result) {
                header("location:cart.php?response=1");
                exit(0);
            } else {
                header("location:cart.php?response=0");
                exit(1);
            }
        } else {
            header("location:cart.php?response=0");
            exit(1);
        }
    } else {
        header("location:cart.php?response=0");
        exit(1);
    }
}
