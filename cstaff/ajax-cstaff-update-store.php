<?php
session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_POST["store_id"]) && isset($_POST["store_openhour"]) && isset($_POST["store_closehour"]) && isset($_POST["store_status"])) {
    $store_openhour = $_POST['store_openhour'];
    $store_closehour = $_POST['store_closehour'];
    $store_status = $_POST['store_status'];
    $store_id = $_POST['store_id'];
    $query = "UPDATE store SET store_openhour = '{$store_openhour}', 
    store_closehour = '{$store_closehour}', store_status = {$store_status} WHERE store_id = {$store_id};";
    $result = $mysqli->query($query);
    if ($result) {
        $response['server_status'] = 1;
    } else {
        $response['server_status'] = 0;
    }
    echo json_encode($response);
}
