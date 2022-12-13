<?php
session_start();
include('../conn_db.php');
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_POST['user_id'])) {
    if (!empty(($_POST['user_id']))) {
        $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);
        // $query = "DELETE FROM user WHERE user_id = '{$user_id}';";
        // $result = $mysqli->query($query);
        $query = $mysqli->prepare("DELETE FROM user WHERE user_id =?;");
        $query->bind_param('i', $user_id);
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
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
}
