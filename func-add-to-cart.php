<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit(1);
}
if (isset($_POST['mitem-id'], $_POST['store-id'], $_POST['amount'], $_POST['remark'])) {
    if (!empty($_POST['mitem-id']) && !empty($_POST['store-id']) &&  !empty($_POST["amount"])) {

        $mitem_id = mysqli_real_escape_string($mysqli, $_POST["mitem-id"]);
        $store_id = mysqli_real_escape_string($mysqli, $_POST["store-id"]);
        $user_id = mysqli_real_escape_string($mysqli, $_SESSION["user_id"]);
        $amount = mysqli_real_escape_string($mysqli, $_POST["amount"]);
        $remark = mysqli_real_escape_string($mysqli, $_POST["remark"]);

        if (!filter_var($amount, FILTER_VALIDATE_FLOAT)) {
            header("location:store-menu.php?store_id={$store_id}&response=0");
            exit(1);
        }

        $remark = htmlspecialchars($remark);

        $query = "SELECT * FROM cart WHERE user_id = {$user_id} GROUP BY user_id";
        $result = $mysqli->query($query);
        $rowcount = mysqli_num_rows($result);

        //case 1 No item in cart
        if ($rowcount == 0) {
            //insert into db
            $query = "INSERT INTO cart (user_id, store_id, mitem_id, cart_amount, cart_remark) 
    VALUES ({$user_id},{$store_id},{$mitem_id},{$amount},'{$remark}')";
            $added_result = $mysqli->query($query);
        } else {
            //case 2 have item in cart
            $row = $result->fetch_array();
            $cart_store = $row["store_id"];

            //if items in same store
            if ($cart_store == $store_id) {
                $cart_query = "SELECT * FROM cart WHERE user_id = {$user_id} AND mitem_id = {$mitem_id}";
                $cart_result = $mysqli->query($cart_query);
                $cartrowcount = mysqli_num_rows($cart_result);

                //item is not in cart yet
                if ($cartrowcount == 0) {
                    $query = "INSERT INTO cart (user_id, store_id, mitem_id, cart_amount, cart_remark) 
            VALUES ({$user_id},{$store_id},{$mitem_id},{$amount},'{$remark}')";
                    $added_result = $mysqli->query($query);
                } else {
                    //item in cart already
                    $cart_row = $cart_result->fetch_array();
                    $cart_amount = $cart_row["cart_amount"];
                    $new_cart_amount = $cart_amount + $amount;
                    $query = "UPDATE cart SET cart_amount = {$new_cart_amount} WHERE user_id = {$user_id} AND mitem_id = {$mitem_id} AND store_id = {$store_id}";
                    $added_result = $mysqli->query($query);
                }
            } else {
                //different store (delete all and insert new item from different store)
                $query = "DELETE FROM cart WHERE user_id = {$user_id}";
                $delete_result = $mysqli->query($query);
                if ($delete_result) {
                    $query = "INSERT INTO cart (user_id, store_id, mitem_id, cart_amount, cart_remark) 
            VALUES ({$user_id},{$store_id},{$mitem_id},{$amount},'{$remark}')";
                    $added_result = $mysqli->query($query);
                } else {
                    $added_result = false;
                }
            }
        }
        if ($added_result) {
            header("location:store-menu.php?store_id={$store_id}&response=1");
            exit(0);
        } else {
            header("location:store-menu.php?store_id={$store_id}&response=0");
            exit(1);
        }
    } else {
        header("location:store-menu.php?store_id={$store_id}&response=0");
        exit(1);
    }
} else {
    header("location:store-menu.php?store_id={$store_id}&response=0");
    exit(1);
}
