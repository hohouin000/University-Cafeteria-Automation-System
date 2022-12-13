<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}
// File upload folder 
$uploadDir = '/img/store/';

// Allowed file types 
$allowTypes = array('png', 'PNG');
if (isset($_POST['store-name'], $_POST['store-location'], $_POST['store-openhour'], $_POST['store-closehour'], $_POST['store-status'])) {
    if (!empty($_POST['store-name']) && !empty($_POST['store-location']) && !empty($_POST['store-openhour']) && !empty($_POST['store-closehour']) && !empty($_POST['store-status'])) {
        $store_name = mysqli_real_escape_string($mysqli, $_POST['store-name']);
        $store_location = mysqli_real_escape_string($mysqli, $_POST['store-location']);
        $store_openhour = mysqli_real_escape_string($mysqli, $_POST['store-openhour']);
        $store_closehour = mysqli_real_escape_string($mysqli, $_POST['store-closehour']);
        $store_status = mysqli_real_escape_string($mysqli, $_POST['store-status']);

        $store_name = htmlspecialchars($store_name);
        $store_location = htmlspecialchars($store_location);
        $store_openhour = htmlspecialchars($store_openhour);
        $store_closehour = htmlspecialchars($store_closehour);
        $store_status = htmlspecialchars($store_status);

        // $queryValidate = "SELECT store_name, store_location FROM store WHERE store_name = '{$store_name}' OR store_location = '{$store_location}';";
        // $result = $mysqli->query($queryValidate);
        $queryValidateStore = $mysqli->prepare("SELECT store_name, store_location FROM store WHERE store_name =?;");
        $queryValidateStore->bind_param('s', $store_name);
        $queryValidateStore->execute();
        $resultStore = $queryValidateStore->get_result();
        if (mysqli_num_rows($resultStore)) {
            $response['server_status'] = -1;
            echo json_encode($response);
            exit(1);
        }

        if ($store_openhour > $store_closehour) {
            $response['server_status'] = -2;
            echo json_encode($response);
            exit(1);
        }

        $queryValidateLocation = $mysqli->prepare("SELECT store_name, store_location FROM store WHERE store_location =?;");
        $queryValidateLocation->bind_param('s', $store_location);
        $queryValidateLocation->execute();
        $resultLocation = $queryValidateLocation->get_result();
        if (mysqli_num_rows($resultLocation)) {
            $response['server_status'] = -3;
            echo json_encode($response);
            exit(1);
        }
        //     $insert_query = "INSERT INTO store (store_name,store_location,store_openhour,store_closehour,store_status)
        // VALUES ('{$store_name}','{$store_location}','{$store_openhour}','{$store_closehour}','{$store_status}');";
        //     $insert_result = $mysqli->query($insert_query);
        //$insert_result = $insert_query->execute();

        //Image upload
        $fileName = basename($_FILES["store-pic"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server
            //$store_id = $mysqli->insert_id;
            $rand = strtoupper(substr(uniqid(sha1(time())), 0, 3));
            $target_dir = '/img/store/';
            $temp = explode(".", $_FILES["store-pic"]["name"]);
            $target_newfilename = "store_pic_" . $rand  . "." . strtolower(end($temp));
            $target_file = $target_dir . $target_newfilename;
            if (move_uploaded_file($_FILES["store-pic"]["tmp_name"], SITE_ROOT . $target_file)) {
                // $insert_query = "UPDATE store SET store_pic = '{$target_newfilename}' WHERE store_id = {$store_id};";
                // $insert_result = $mysqli->query($insert_query);
                $insert_query = $mysqli->prepare("INSERT INTO store (store_name,store_location,store_openhour,store_closehour,store_status,store_pic) VALUES (?,?,?,?,?,?);");
                $insert_query->bind_param('ssssis', $store_name, $store_location, $store_openhour, $store_closehour, $store_status, $target_newfilename);
                // $insert_query = $mysqli->prepare("UPDATE store SET store_pic =? WHERE store_id =?;");
                // $insert_query->bind_param('si', $target_newfilename, $store_id);
                $insert_result = $insert_query->execute();
                if ($insert_result) {
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
