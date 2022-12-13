<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_POST['store_id'], $_POST['user_fname'], $_POST['user_lname'], $_POST['user_pwd'], $_POST['user_username'], $_POST['user_role'], $_POST['user_email'])) {
    if (!empty($_POST['store_id']) && !empty($_POST['user_fname']) && !empty($_POST['user_lname']) && !empty($_POST['user_pwd']) && !empty($_POST['user_username']) && !empty($_POST['user_role']) && !empty($_POST['user_email'])) {
        $store_id = mysqli_real_escape_string($mysqli, $_POST['store_id']);
        $user_fname = mysqli_real_escape_string($mysqli, $_POST['user_fname']);
        $user_lname = mysqli_real_escape_string($mysqli, $_POST['user_lname']);
        $user_pwd = mysqli_real_escape_string($mysqli, $_POST['user_pwd']);
        $user_username = mysqli_real_escape_string($mysqli, $_POST['user_username']);
        $user_role = mysqli_real_escape_string($mysqli, $_POST['user_role']);
        $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);

        $store_id = htmlspecialchars($store_id);
        $user_fname = htmlspecialchars($user_fname);
        $user_lname = htmlspecialchars($user_lname);
        $user_pwd = htmlspecialchars($user_pwd);
        $user_username = htmlspecialchars($user_username);
        $user_email = htmlspecialchars($user_email);
        $user_role = htmlspecialchars($user_role);

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $response['server_status'] = -2;
            echo json_encode($response);
            exit(1);
        }

        if (strlen($user_pwd) < 8 || strlen($user_pwd) > 12) {
            $response['server_status'] = -3;
            echo json_encode($response);
            exit(1);
        }

        if (strlen($user_username) < 5 || strlen($user_username) > 15) {
            $response['server_status'] = -4;
            echo json_encode($response);
            exit(1);
        }

        // $queryValidate = "SELECT * FROM user WHERE user_username = '{$user_username}';";
        // $result = $mysqli->query($queryValidate);
        $queryValidate = $mysqli->prepare("SELECT * FROM user WHERE user_username =?;");
        $queryValidate->bind_param('s', $user_username);
        $queryValidate->execute();
        $result = $queryValidate->get_result();
        if (mysqli_num_rows($result)) {
            $response['server_status'] = -1;
            echo json_encode($response);
            exit(1);
        } else {
            if ($store_id == "NULL") {
                //             $insert_query = "INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email) 
                // VALUES ('{$user_username}','{$user_fname}','{$user_lname}','{$user_pwd}','{$user_role}','{$user_email}');";
                //             $insert_result = $mysqli->query($insert_query);
                $insert_query = $mysqli->prepare("INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email) VALUES (?,?,?,?,?,?);");
                $insert_query->bind_param('ssssss', $user_username, $user_fname, $user_lname, $user_pwd, $user_role, $user_email);
                $insert_result = $insert_query->execute();
            } else {
                //             $insert_query = "INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email,store_id) 
                // VALUES ('{$user_username}','{$user_fname}','{$user_lname}','{$user_pwd}','{$user_role}','{$user_email}','{$store_id}');";
                //             $insert_result = $mysqli->query($insert_query);
                $insert_query = $mysqli->prepare("INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email,store_id) VALUES (?,?,?,?,?,?,?);");
                $insert_query->bind_param('ssssssi', $user_username, $user_fname, $user_lname, $user_pwd, $user_role, $user_email, $store_id);
                $insert_result = $insert_query->execute();
            }
            if ($insert_result) {
                $response['server_status'] = 1;
                echo json_encode($response);
                exit(0);
            }else{
                $response['server_status'] = 0;
                echo json_encode($response);
                exit(1);
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
