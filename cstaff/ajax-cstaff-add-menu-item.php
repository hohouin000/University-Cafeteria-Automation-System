<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}
// File upload folder 
$uploadDir = '/img/menu/';

// Allowed file types 
$allowTypes = array('png','PNG');

if (isset($_POST["mitem-name"], $_POST["mitem-price"], $_POST['mitem-status'])) {
    if (!empty($_POST['mitem-name']) && !empty($_POST["mitem-price"]) && !empty($_POST['mitem-status'])) {
        $mitem_name = mysqli_real_escape_string($mysqli, $_POST['mitem-name']);
        $mitem_price = mysqli_real_escape_string($mysqli, $_POST['mitem-price']);
        $mitem_status = mysqli_real_escape_string($mysqli, $_POST['mitem-status']);

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

        if (isset($_SESSION["store_id"])) {
            $store_id = $_SESSION["store_id"];
        }

        // $queryValidate = "SELECT mitem_name FROM mitem WHERE mitem_name = '{$mitem_name}';";
        // $result = $mysqli->query($queryValidate);
        $queryValidate =  $mysqli->prepare("SELECT mitem_name FROM mitem WHERE mitem_name =?;");
        $queryValidate->bind_param('s', $mitem_name);
        $queryValidate->execute();
        $result = $queryValidate->get_result();
        if (mysqli_num_rows($result)) {
            $response['server_status'] = 0;
            echo json_encode($response);
            exit(1);
        } else {
            // $insert_query = "INSERT INTO mitem (mitem_name,mitem_price,mitem_status,store_id) 
            // VALUES ('{$mitem_name}','{$mitem_price}','{$mitem_status}','{$store_id}');";
            // $insert_result = $mysqli->query($insert_query);

            //Image upload
            $fileName = basename($_FILES["mitem-pic"]["name"]);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                // Upload file to the server
                $rand = strtoupper(substr(uniqid(sha1(time())), 0, 3));
                $target_dir = '/img/menu/';
                $temp = explode(".", $_FILES["mitem-pic"]["name"]);
                $target_newfilename = "mitem_pic_" . $rand . "." . strtolower(end($temp));
                $target_file = $target_dir . $target_newfilename;
                if (move_uploaded_file($_FILES["mitem-pic"]["tmp_name"], SITE_ROOT . $target_file)) {
                    // $insert_query = "UPDATE mitem SET mitem_pic = '{$target_newfilename}' WHERE mitem_id = {$mitem_id} AND store_id = {$store_id} ";
                    // $insert_result = $mysqli->query($insert_query);
                    $insert_query =  $mysqli->prepare("INSERT INTO mitem (mitem_name,mitem_price,mitem_status,store_id,mitem_pic) 
                    VALUES (?,?,?,?,?);");
                    $insert_query->bind_param('sdiis', $mitem_name, $mitem_price, $mitem_status, $store_id, $target_newfilename);
                    $insert_result = $insert_query->execute();
                    // $insert_query = $mysqli->prepare("UPDATE mitem SET mitem_pic =? WHERE mitem_id =? AND store_id =?");
                    // $insert_query->bind_param('sii', $target_newfilename, $mitem_id, $store_id);
                    // $insert_result = $insert_query->execute();
                } else {
                    $insert_result = false;
                }

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
                exit(0);
            }
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
