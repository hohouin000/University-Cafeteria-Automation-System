<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_POST["mitem_name"], $_POST["mitem_price"], $_POST['mitem_status'], $_POST['mitem_id'], $_SESSION["store_id"])) {
    if (!empty($_POST['mitem_name']) && !empty($_POST["mitem_price"]) && !empty($_POST['mitem_id']) && !empty($_SESSION["store_id"])) {
        $mitem_name = mysqli_real_escape_string($mysqli, $_POST['mitem_name']);
        $mitem_price = mysqli_real_escape_string($mysqli, $_POST['mitem_price']);
        $mitem_status = mysqli_real_escape_string($mysqli, $_POST['mitem_status']);
        $mitem_id = mysqli_real_escape_string($mysqli, $_POST['mitem_id']);
        $store_id = mysqli_real_escape_string($mysqli, $_SESSION["store_id"]);

        $mitem_name = htmlspecialchars($mitem_name);

        if (!filter_var($mitem_price, FILTER_VALIDATE_FLOAT)) {
            $response['server_status'] = 0;
            echo json_encode($response);
            exit(1);
        }

        if ($mitem_price <= 0) {
            $response['server_status'] = 0;
            echo json_encode($response);
            exit(1);
        }


        if ($mitem_status != 0 && $mitem_status != 1) {
            $response['server_status'] = 0;
            echo json_encode($response);
            exit(1);
        }

        $queryValidate =  $mysqli->prepare("SELECT mitem_name FROM mitem WHERE mitem_name =? AND user_id <> ?;");
        $queryValidate->bind_param('si', $mitem_name, $mitem_id);
        $queryValidate->execute();
        $result = $queryValidate->get_result();
        if (mysqli_num_rows($result)) {
            $response['server_status'] = -1;
            echo json_encode($response);
            exit(1);
        }
        // $query = "UPDATE mitem SET mitem_name = '{$mitem_name}', mitem_price = {$mitem_price}, mitem_status = {$mitem_status}
        // WHERE mitem_id = {$mitem_id} AND store_id = {$store_id}";
        // $result = $mysqli->query($query);
        $query = $mysqli->prepare("UPDATE mitem SET mitem_name =?, mitem_price =?, mitem_status =? WHERE mitem_id =? AND store_id =?");
        $query->bind_param('sdiii', $mitem_name, $mitem_price, $mitem_status, $mitem_id, $store_id);
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
