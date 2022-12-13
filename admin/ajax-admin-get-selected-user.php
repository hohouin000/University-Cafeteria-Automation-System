<?php
session_start();
include('../conn_db.php');
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_POST['user_id'])) {
    if (!empty($_POST['user_id'])) {
        $user_id = mysqli_real_escape_string($mysqli,$_POST['user_id']);
        // $query = "SELECT * FROM user  WHERE user_id = '{$user_id}';";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * FROM user  WHERE user_id =?;");
        $query->bind_param('i', $user_id);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            while ($row = $result->fetch_array()) {
                $array = [
                    "store_id" => $row['store_id'],
                    "user_username" => $row['user_username'],
                    "user_fname" => $row['user_fname'],
                    "user_lname" => $row['user_lname'],
                    "user_email" => $row['user_email'],
                    "user_role" => $row['user_role'],
                    "user_pwd" => $row['user_pwd'],
                    "server_status" => 1
                ];
            }
        } else {
            $array = [
                "store_id" => '',
                "user_username" => '',
                "user_fname" => '',
                "user_lname" => '',
                "user_email" => '',
                "user_role" => '',
                "user_pwd" => '',
                "server_status" => 0
            ];
        }
        echo json_encode($array);
    } else {
        $array = [
            "store_id" => '',
            "user_username" => '',
            "user_fname" => '',
            "user_lname" => '',
            "user_email" => '',
            "user_role" => '',
            "user_pwd" => '',
            "server_status" => 0
        ];
        echo json_encode($array);
    }
} else {
    $array = [
        "store_id" => '',
        "user_username" => '',
        "user_fname" => '',
        "user_lname" => '',
        "user_email" => '',
        "user_role" => '',
        "user_pwd" => '',
        "server_status" => 0
    ];
    echo json_encode($array);
}
