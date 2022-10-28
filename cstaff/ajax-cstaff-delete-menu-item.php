<?php
session_start();
include('../conn_db.php');
if (!empty(($_POST['mitem_id']))) {
    $target_dir = '/img/menu/';
    $mitem_id = $_POST['mitem_id'];
    $query = "SELECT mitem_pic FROM mitem WHERE mitem_id = '{$mitem_id}';";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_array($result);
    $target_file = $target_dir . $row['mitem_pic'];
    unlink(SITE_ROOT . $target_file);

    $query = "DELETE FROM mitem WHERE mitem_id = '{$mitem_id}';";
    $result = $mysqli->query($query);
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
