<?php
session_start();
include('../conn_db.php');
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_POST['store_id'])) {
    if (!empty($_POST['store_id'])) {
        $store_id = mysqli_real_escape_string($mysqli, $_POST['store_id']);
        // $query = "SELECT * FROM store  WHERE store_id = '{$store_id}';";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * FROM store  WHERE store_id =?;");
        $query->bind_param('i', $store_id);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            while ($row = $result->fetch_array()) {
                $array = [
                    "store_id" => $row['store_id'],
                    "store_name" => $row['store_name'],
                    "store_location" => $row['store_location'],
                    "store_openhour" => $row['store_openhour'],
                    "store_closehour" => $row['store_closehour'],
                    "store_status" => $row['store_status'],
                    "store_pic" => $row['store_pic'],
                    "server_status" => 1
                ];
            }
        } else {
            $array = [
                "store_id" => '',
                "store_name" => '',
                "store_location" => '',
                "store_openhour" => '',
                "store_closehour" => '',
                "store_status" => '',
                "store_pic" => '',
                "server_status" => 0
            ];
        }
        echo json_encode($array);
    } else {
        $array = [
            "store_id" => '',
            "store_name" => '',
            "store_location" => '',
            "store_openhour" => '',
            "store_closehour" => '',
            "store_status" => '',
            "store_pic" => '',
            "server_status" => 0
        ];
        echo json_encode($array);
    }
} else {
    $array = [
        "store_id" => '',
        "store_name" => '',
        "store_location" => '',
        "store_openhour" => '',
        "store_closehour" => '',
        "store_status" => '',
        "store_pic" => '',
        "server_status" => 0
    ];
    echo json_encode($array);
}
