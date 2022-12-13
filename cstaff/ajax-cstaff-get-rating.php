<?php
session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}
if (isset($_SESSION["store_id"])) {
    $store_id = $_SESSION["store_id"];
    // $query = "SELECT r.*, o.odr_ref FROM odr o INNER JOIN rating r ON  o.rating_id = r.rating_id WHERE store_id = {$store_id}";
    // $result = $mysqli->query($query);
    // $rowcount = mysqli_num_rows($result);
    $query = $mysqli->prepare("SELECT r.*, o.odr_ref FROM odr o INNER JOIN rating r ON  o.rating_id = r.rating_id WHERE store_id =? ORDER BY rating_date DESC");
    $query->bind_param('i', $store_id);
    $query->execute();
    $result = $query->get_result();
    $rowcount = $result->num_rows;
    if ($rowcount > 0) {
        $i = 1;
        while ($row = $result->fetch_array()) {
            $rating_date = (new Datetime($row["rating_date"]))->format("F j, Y H:i");
            switch ($row['rating_value']) {
                case 1:
                    $rating_value = "☆";
                    break;
                case 2:
                    $rating_value = "☆☆";
                    break;
                case 3:
                    $rating_value = "☆☆☆";
                    break;
                case 4:
                    $rating_value = "☆☆☆☆";
                    break;
                case 5:
                    $rating_value = "☆☆☆☆☆";
                    break;
            }
            $data[] = [
                "row" => $i++,
                "odr_ref" => $row['odr_ref'],
                "rating_value" => $rating_value,
                "rating_comment" => $row['rating_comment'],
                "rating_date" =>   $rating_date
            ];
        }
    } else {
        $data[] = [
            "row" => '',
            "odr_ref" => '',
            "rating_value" => '',
            "rating_comment" => '',
            "rating_date" =>  ''
        ];
    }
    $return_array = array('data' => $data);
    $jsonData = json_encode($return_array);
    echo $jsonData . "\n";
} else {
    $data[] = [
        "row" => '',
        "odr_ref" => '',
        "rating_value" => '',
        "rating_comment" => '',
        "rating_date" =>  ''
    ];
    $return_array = array('data' => $data);
    $jsonData = json_encode($return_array);
    echo $jsonData . "\n";
}
