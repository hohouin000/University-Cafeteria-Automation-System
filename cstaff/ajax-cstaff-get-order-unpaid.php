<?php
session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_SESSION["store_id"])) {
    $store_id = $_SESSION["store_id"];
    // $query = "SELECT * FROM odr WHERE store_id = {$store_id} AND odr_status = 'UNPD';";
    // $result = $mysqli->query($query);
    // $rowcount = mysqli_num_rows($result);
    $query = $mysqli->prepare("SELECT * FROM odr WHERE store_id =? AND odr_status = 'UNPD';");
    $query->bind_param('i', $store_id);
    $query->execute();
    $result = $query->get_result();
    $rowcount = $result->num_rows;
    if ($rowcount > 0) {
        while ($row = $result->fetch_array()) {
            $details = "";
            // $user_query = "SELECT user_fname,user_lname FROM user WHERE user_id = {$row['user_id']};";
            // $user_arr = $mysqli->query($user_query)->fetch_array();
            $user_query = $mysqli->prepare("SELECT user_fname,user_lname FROM user WHERE user_id =?;");
            $user_query->bind_param('i', $row['user_id']);
            $user_query->execute();
            $user_arr = $user_query->get_result()->fetch_array();
            $user_name = $user_arr['user_fname'] . " " . $user_arr['user_lname'];

            $odr_placedtime = (new Datetime($row["odr_placedtime"]))->format("F j, Y H:i");

            // $odr_query = "SELECT SUM(odr_detail_amount*odr_detail_price) AS total_price FROM odr_detail WHERE odr_id = {$row['odr_id']}";
            // $odr_arr = $mysqli->query($odr_query)->fetch_array();
            $odr_query = $mysqli->prepare("SELECT SUM(odr_detail_amount*odr_detail_price) AS total_price FROM odr_detail WHERE odr_id =?");
            $odr_query->bind_param('i', $row['odr_id']);
            $odr_query->execute();
            $odr_arr = $odr_query->get_result()->fetch_array();
            $total_price = "RM " . $odr_arr['total_price'];

            // $odr_detail_query = "SELECT m.mitem_name,od.odr_detail_amount,od.odr_detail_remark FROM odr_detail od INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE od.odr_id = {$row['odr_id']}";
            // $odr_detail_result = $mysqli->query($odr_detail_query);
            $odr_detail_query = $mysqli->prepare("SELECT m.mitem_name,od.odr_detail_amount,od.odr_detail_remark FROM odr_detail od INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE od.odr_id =?");
            $odr_detail_query->bind_param('i', $row['odr_id']);
            $odr_detail_query->execute();
            $odr_detail_result = $odr_detail_query->get_result();
            while ($odr_detail_row = $odr_detail_result->fetch_array()) {
                if ($odr_detail_row["odr_detail_remark"] != "") {
                    $details .= "<b>" . $odr_detail_row["odr_detail_amount"] . "</b>" . "<b>x</b> " . $odr_detail_row["mitem_name"] . " (" . $odr_detail_row["odr_detail_remark"] . ")" . "<br/>";
                } else {
                    $details .= "<b>" . $odr_detail_row["odr_detail_amount"] . "</b>"  . "<b>x</b> " . $odr_detail_row["mitem_name"] . "<br/>";
                }
            }

            $data[] = [
                "odr_id" => $row['odr_id'],
                "odr_ref" => $row['odr_ref'],
                "odr_placedtime" => $odr_placedtime,
                "user_name" => $user_name,
                "total_price" => $total_price,
                "odr_details" => $details
            ];
        }
    } else {
        $data[] = [
            "odr_id" => '',
            "odr_ref" => '',
            "odr_placedtime" => '',
            "user_name" => '',
            "total_price" => '',
            "odr_details" => ''
        ];
    }
    $return_array = array('data' => $data);
    $jsonData = json_encode($return_array);
    echo $jsonData . "\n";
} else {
    $data[] = [
        "odr_id" => '',
        "odr_ref" => '',
        "odr_placedtime" => '',
        "user_name" => '',
        "total_price" => '',
        "odr_details" => ''
    ];
    $return_array = array('data' => $data);
    $jsonData = json_encode($return_array);
    echo $jsonData . "\n";
}
