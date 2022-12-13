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
if (isset($_POST['store-id'])) {
    if (!empty($_POST['store-id'])) {
        $store_id = mysqli_real_escape_string($mysqli, $_POST['store-id']);
        $fileName = basename($_FILES["store-pic"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server
            $target_dir = '/img/store/';
            $temp = explode(".", $_FILES["store-pic"]["name"]);
            $target_newfilename = "store_pic_" . $store_id . "." . strtolower(end($temp));
            $target_newfilename = htmlspecialchars($target_newfilename);
            $target_file = $target_dir . $target_newfilename;
            if (move_uploaded_file($_FILES["store-pic"]["tmp_name"], SITE_ROOT . $target_file)) {
                // $query = "UPDATE store SET store_pic = '{$target_newfilename}' WHERE store_id = {$store_id};";
                // $result = $mysqli->query($query);
                $query = $mysqli->prepare("UPDATE store SET store_pic =? WHERE store_id =?;");
                $query->bind_param('si', $target_newfilename, $store_id);
                $result = $query->execute();
                if ($result) {
                    $response['server_status'] = 1;
                } else {
                    $response['server_status'] = 0;
                }
                echo json_encode($response);
            }
        } else {
            $response['server_status'] = -4;
            echo json_encode($response);
        }
    } else {
        $response['server_status'] = 0;
        echo json_encode($response);
    }
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
}
