<?php
session_start();
include('../conn_db.php');
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_POST["mitem_id"])) {
    if (!empty(($_POST['mitem_id']))) {
        $mitem_id = mysqli_real_escape_string($mysqli,  $_POST['mitem_id']);
        // $query = "SELECT * FROM mitem WHERE mitem_id = '{$mitem_id}';";
        // $result = $mysqli->query($query);
        // $row = $result->fetch_array();
        $query = $mysqli->prepare("SELECT * FROM mitem WHERE mitem_id =?;");
        $query->bind_param('i', $mitem_id);
        $query->execute();
        $row = $query->get_result()->fetch_assoc();

        //Set pic name into variable instead of using mysql query;
        $mitem_pic = $row['mitem_pic'];
        // $query = "DELETE FROM mitem WHERE mitem_id = '{$mitem_id}';";
        // $result = $mysqli->query($query);
        $query = $mysqli->prepare("DELETE FROM mitem WHERE mitem_id =?;");
        $query->bind_param('i', $mitem_id);
        $result = $query->execute();
        if ($result) {
            $target_dir = '/img/menu/';
            $target_file = $target_dir . $mitem_pic;
            unlink(SITE_ROOT . $target_file);
            $response['server_status'] = 1;
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
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit(1);
}
