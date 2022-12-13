<?php
session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_SESSION["store_id"])) {
    $store_id = $_SESSION["store_id"];
    // $query = "SELECT * FROM mitem WHERE store_id = {$store_id}";
    // $result = $mysqli->query($query);
    // $rowcount = mysqli_num_rows($result);
    $query = $mysqli->prepare("SELECT * FROM mitem WHERE store_id =?");
    $query->bind_param('i', $store_id);
    $query->execute();
    $result = $query->get_result();
    $rowcount = $result->num_rows;

    if ($rowcount > 0) {
        $i = 1;
        while ($row = $result->fetch_array()) {
            if ($row['mitem_status'] == 1) {
                $mitem_status = "Available";
            } else {
                $mitem_status = "Not Available";
            }
            $mitem_price = "RM " . $row['mitem_price'];
            $data[] = [
                "row" => $i++,
                "mitem_id" => $row['mitem_id'],
                "mitem_name" => $row['mitem_name'],
                "mitem_price" => $mitem_price,
                "mitem_status" => $mitem_status,
                "mitem_pic" => $row['mitem_pic']
            ];
        }
    } else {
        $data[] = [
            "row" => '',
            "mitem_id" => '',
            "mitem_name" => '',
            "mitem_price" => '',
            "mitem_status" => '',
            "mitem_pic" => ''
        ];
    }
    $return_array = array('data' => $data);
    $jsonData = json_encode($return_array);
    echo $jsonData . "\n";
} else {
    $data[] = [
        "row" => '',
        "mitem_id" => '',
        "mitem_name" => '',
        "mitem_price" => '',
        "mitem_status" => '',
        "mitem_pic" => ''
    ];
    $return_array = array('data' => $data);
    $jsonData = json_encode($return_array);
    echo $jsonData . "\n";
}
