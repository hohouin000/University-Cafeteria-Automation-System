<?php
session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    // $query = "SELECT * FROM user WHERE user_id <>'{$user_id}';";
    // $result = $mysqli->query($query);
    // $rowcount = mysqli_num_rows($result);
    $query = $mysqli->prepare("SELECT * FROM user WHERE user_id <>?;");
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    $rowcount = $result->num_rows;
    if ($rowcount > 0) {
        $i = 1;
        while ($row = $result->fetch_array()) {
            if ($row['store_id'] == null) {
                $store_name = "None";
            } else {
                $query_2 = "SELECT store_name FROM store WHERE store_id = '{$row['store_id']}';";
                $result_2 = $mysqli->query($query_2);
                while ($row_2 = $result_2->fetch_array()) {
                    $store_name = $row_2['store_name'];
                }
            }
            $data[] = [
                "row" => $i++,
                "user_username" => $row['user_username'],
                "user_fname" => $row['user_fname'],
                "user_lname" => $row['user_lname'],
                "user_email" => $row['user_email'],
                "user_role" => $row['user_role'],
                "user_pwd" => $row['user_pwd'],
                "store_name" =>  $store_name,
                "user_id" => $row['user_id']
            ];
        }
    } else {
        $data[] = [
            "row" => "",
            "user_username" => "",
            "user_fname" => "",
            "user_lname" => "",
            "user_email" => "",
            "user_role" => "",
            "user_pwd" => "",
            "store_name" => "",
            "user_id" => ""
        ];
    }
    $return_array = array('data' => $data);
    $jsonData = json_encode($return_array);
    echo $jsonData . "\n";
} else {
    header("location:../restricted.php");
    exit(1);
}
