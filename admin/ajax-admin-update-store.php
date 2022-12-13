<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_POST['store_name'], $_POST['store_location'], $_POST['store_openhour'], $_POST['store_closehour'], $_POST['store_status'], $_POST['store_id'])) {
    if (!empty($_POST['store_name']) && !empty($_POST['store_location']) && !empty($_POST['store_openhour']) && !empty($_POST['store_closehour']) && !empty($_POST['store_id'])) {
        $store_name = mysqli_real_escape_string($mysqli, $_POST['store_name']);
        $store_location = mysqli_real_escape_string($mysqli, $_POST['store_location']);
        $store_openhour = mysqli_real_escape_string($mysqli, $_POST['store_openhour']);
        $store_closehour = mysqli_real_escape_string($mysqli, $_POST['store_closehour']);
        $store_status = mysqli_real_escape_string($mysqli, $_POST['store_status']);
        $store_id = mysqli_real_escape_string($mysqli, $_POST['store_id']);

        $store_name = htmlspecialchars($store_name);
        $store_location = htmlspecialchars($store_location);
        $store_openhour = htmlspecialchars($store_openhour);
        $store_closehour = htmlspecialchars($store_closehour);
        $store_status = htmlspecialchars($store_status);
        $store_id = htmlspecialchars($store_id);


        $queryValidate = $mysqli->prepare("SELECT store_name, store_location FROM store WHERE store_name =? AND store_id <>? ;");
        $queryValidate->bind_param('si', $store_name, $store_id);
        $queryValidate->execute();
        $result = $queryValidate->get_result();
        if (mysqli_num_rows($result)) {
            $response['server_status'] = -1;
            echo json_encode($response);
            exit(1);
        }

        if ($store_openhour > $store_closehour) {
            $response['server_status'] = -2;
            echo json_encode($response);
            exit(1);
        }

        $queryValidate = $mysqli->prepare("SELECT store_name, store_location FROM store WHERE store_location =? AND store_id <>? ;");
        $queryValidate->bind_param('si', $store_location, $store_id);
        $queryValidate->execute();
        $result = $queryValidate->get_result();
        if (mysqli_num_rows($result)) {
            $response['server_status'] = -3;
            echo json_encode($response);
            exit(1);
        }
        //         $query = "UPDATE store SET store_name = '{$store_name}', store_location = '{$store_location}', store_openhour = '{$store_openhour}',
        // store_closehour = '{$store_closehour}', store_status = {$store_status} WHERE store_id = {$store_id};";
        //         $result = $mysqli->query($query);
        $query = $mysqli->prepare("UPDATE store SET store_name =?, store_location =?, store_openhour =?, store_closehour =?, store_status =? WHERE store_id =?;");
        $query->bind_param('ssssii', $store_name, $store_location, $store_openhour, $store_closehour, $store_status, $store_id);
        $result = $query->execute();
        if ($result) {
            $response['server_status'] = 1;
        } else {
            $response['server_status'] = 0;
        }
        echo json_encode($response);
    } else {
        $response['server_status'] = 0;
        echo json_encode($response);
    }
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
}
