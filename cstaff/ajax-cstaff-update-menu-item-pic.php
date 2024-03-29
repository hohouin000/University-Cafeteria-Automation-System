<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}

// File upload folder 
$uploadDir = '/img/menu/';

// Allowed file types 
$allowTypes = array('png', 'PNG');
if (isset($_POST["mitem-id"])) {
    if (!empty($_POST['mitem-id'])) {
        $mitem_id = mysqli_real_escape_string($mysqli, $_POST['mitem-id']);
        $fileName = basename($_FILES["mitem-pic"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server
            $target_dir = '/img/menu/';
            $temp = explode(".", $_FILES["mitem-pic"]["name"]);
            $target_newfilename = "mitem_pic_" . $mitem_id . "." . strtolower(end($temp));
            $target_file = $target_dir . $target_newfilename;
            if (move_uploaded_file($_FILES["mitem-pic"]["tmp_name"], SITE_ROOT . $target_file)) {
                // $query = "UPDATE mitem SET mitem_pic = '{$target_newfilename}' WHERE mitem_id = {$mitem_id};";
                // $result = $mysqli->query($query);
                $query = $mysqli->prepare("UPDATE mitem SET mitem_pic =? WHERE mitem_id =?;");
                $query->bind_param('si', $target_newfilename, $mitem_id);
                $result = $query->execute();
                if ($result) {
                    $response['server_status'] = 1;
                    echo json_encode($response);
                    exit(0);
                } else {
                    $response['server_status'] = 0;
                    echo json_encode($response);
                    exit(1);
                }
            }
        } else {
            $response['server_status'] = -4;
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
