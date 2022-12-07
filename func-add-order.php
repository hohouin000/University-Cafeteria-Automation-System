<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
    header("location: login.php");
    exit(1);
}
if (isset($_GET["response"])) {
    if ($_GET["response"] == 1) {
        $sum_query = "SELECT SUM(c.cart_amount*m.mitem_price) AS total_price FROM cart c INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id = {$_SESSION['user_id']}";
        $sum_arr = $mysqli->query($sum_query)->fetch_array();
        $total_amount = $sum_arr['total_price'];
        $payment_type = "ONLINE";
        $odr_status = "PREP";
        $session_id = $_GET["session_id"];
    }
} else {
    $total_amount = $_POST["total-amount"];
    $payment_type = "PAC";
    $odr_status = "UNPD";
}
$store_query = "SELECT * FROM store WHERE store_id = (SELECT store_id FROM cart WHERE user_id = {$_SESSION['user_id']} GROUP BY user_id)";
$store_arr = $mysqli->query($store_query)->fetch_array();
$store_id = $store_arr["store_id"];
$store_open = $store_arr["store_openhour"];
$store_close = $store_arr["store_closehour"];
$curr_time = date("H:i");
$store_open_arr = explode(":", $store_open);
$new_store_open = $store_open_arr[0] . ":" . $store_open_arr[1];
$store_close_arr = explode(":", $store_close);
$new_store_close = $store_close_arr[0] . ":" . $store_close_arr[1];
if (($store_arr["store_status"] == 1) && ($curr_time >= $new_store_open) && ($curr_time < $new_store_close)) {
    $store_closed = 0;
} else {
    $store_closed = 1;
}

if ($store_closed == 0) {
    $query = "INSERT INTO payment (user_id,payment_type,payment_amount) VALUES ({$_SESSION['user_id']},'{$payment_type}',{$total_amount});";
    $result = $mysqli->query($query);
    $payment_id = $mysqli->insert_id;

    $query = "INSERT INTO odr (user_id,store_id,payment_id,odr_status) VALUES ({$_SESSION['user_id']},{$store_id},{$payment_id},'{$odr_status}');";
    $result = $mysqli->query($query);
    $odr_id = $mysqli->insert_id;

    $curr = date("Ymd");
    $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
    $ref = $curr . $rand . "OID" . $odr_id;

    $query = "UPDATE odr SET odr_ref = '{$ref}' WHERE odr_id = {$odr_id};";
    $result = $mysqli->query($query);

    $query = "SELECT c.*, m.* FROM cart c INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id = {$_SESSION['user_id']} AND c.store_id = {$store_id};";
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array()) {
        $odr_detail_query = "INSERT INTO odr_detail (odr_id,mitem_id,odr_detail_amount,odr_detail_price,odr_detail_remark) VALUES ({$odr_id},{$row['mitem_id']},{$row['cart_amount']},{$row['mitem_price']},'{$row['cart_remark']}')";
        $odr_detail_result = $mysqli->query($odr_detail_query);
    }

    if ($odr_detail_result) {
        $query = "DELETE FROM cart WHERE user_id = {$_SESSION['user_id']} AND store_id = {$store_id};";
        $result = $mysqli->query($query);
        if (isset($_GET["response"])) {
            if ($_GET["response"] == 1) {
                header("location: order-success.php?odr={$odr_id}&response=1");
            }
        } else {
            header("location: order-success.php?odr={$odr_id}");
        }
        exit(1);
    } else {
        header("location: order-failed.php?response={$mysqli->errno}&odr={$odr_id}");
        exit(1);
    }
} else {
    header("location: order-failed.php?response=0");
    exit(1);
}
