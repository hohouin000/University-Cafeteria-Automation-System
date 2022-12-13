<?php
session_start();
include('../conn_db.php');
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_POST['store_id'])) {
    if (!empty(($_POST['store_id']))) {
        $target_dir = '/img/store/';
        $store_id = mysqli_real_escape_string($mysqli, $_POST['store_id']);
        // $query = "SELECT store_pic FROM store WHERE store_id = '{$store_id}';";
        // $result = $mysqli->query($query);
        $query = $mysqli->prepare("SELECT store_pic FROM store WHERE store_id =?;");
        $query->bind_param('i', $store_id);
        $query->execute();
        $result = $query->get_result();
        $row = mysqli_fetch_array($result);
        $target_file = $target_dir . $row['store_pic'];
        unlink(SITE_ROOT . $target_file);

        // $query = "DELETE FROM store WHERE store_id = '{$store_id}';";
        // $result = $mysqli->query($query);
        $query = $mysqli->prepare("DELETE FROM store WHERE store_id =?;");
        $query->bind_param('i', $store_id);
        $result = $query->execute();
        if ($result) {
            $response['server_status'] = 1;
        } else {
            $response['server_status'] = 0;
        }
    } else {
        $response['server_status'] = 0;
    }
    echo json_encode($response);
    exit(0);
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit(1);
}
