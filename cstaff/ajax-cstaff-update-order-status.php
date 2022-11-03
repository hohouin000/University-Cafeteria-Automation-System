<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_SESSION["store_id"])) {
    $store_id = $_SESSION["store_id"];
}
$odr_id = $_POST['odr_id'];
$odr_status = $_POST['odr_status'];
if ($odr_status == "CMPLT") {
    $odr_compltime = date("Y-m-d\TH:i:s");
} else {
    $odr_compltime = NULL;
}
$query = "UPDATE odr SET odr_status = '{$odr_status}', odr_compltime ='{$odr_compltime}' WHERE odr_id = {$odr_id} AND store_id = {$store_id}";
$result = $mysqli->query($query);
if ($result) {
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
