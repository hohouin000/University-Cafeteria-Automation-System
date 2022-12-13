<?php
session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_POST["store_id"], $_POST["store_openhour"], $_POST["store_closehour"], $_POST["store_status"])) {
    if (!empty($_POST['store_id']) && !empty($_POST['store_openhour']) && !empty($_POST['store_closehour'])) {
        $store_openhour =  mysqli_real_escape_string($mysqli, $_POST['store_openhour']);
        $store_closehour =  mysqli_real_escape_string($mysqli, $_POST['store_closehour']);
        $store_status =  mysqli_real_escape_string($mysqli, $_POST['store_status']);
        $store_id =  mysqli_real_escape_string($mysqli, $_POST['store_id']);

        if ($store_status != 0 && $store_status != 1) {
            $response['server_status'] = 0;
            echo json_encode($response);
            exit(1);
        }

        //     $query = "UPDATE store SET store_openhour = '{$store_openhour}', 
        // store_closehour = '{$store_closehour}', store_status = {$store_status} WHERE store_id = {$store_id};";
        //     $result = $mysqli->query($query);
        $query = $mysqli->prepare("UPDATE store SET store_openhour =?, store_closehour =?, store_status =? WHERE store_id =?;");
        $query->bind_param('ssii', $store_openhour, $store_closehour, $store_status, $store_id);
        $result = $query->execute();
        if ($result) {
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
