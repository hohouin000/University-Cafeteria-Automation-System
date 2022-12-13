<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
}

if (isset($_POST['store_id'], $_POST['user_fname'], $_POST['user_lname'], $_POST['user_pwd'], $_POST['user_username'], $_POST['user_role'], $_POST['user_email'], $_POST['user_id'])) {
    if (!empty($_POST['user_fname']) && !empty($_POST['user_lname']) && !empty($_POST['user_pwd']) && !empty($_POST['user_username']) && !empty($_POST['user_role']) && !empty($_POST['user_email']) && !empty($_POST['user_id'])) {
        $store_id = mysqli_real_escape_string($mysqli, $_POST['store_id']);
        $user_fname = mysqli_real_escape_string($mysqli, $_POST['user_fname']);
        $user_lname = mysqli_real_escape_string($mysqli, $_POST['user_lname']);
        $user_pwd = mysqli_real_escape_string($mysqli, $_POST['user_pwd']);
        $user_username = mysqli_real_escape_string($mysqli, $_POST['user_username']);
        $user_role = mysqli_real_escape_string($mysqli, $_POST['user_role']);
        $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
        $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);

        $user_fname = htmlspecialchars($user_fname);
        $user_lname = htmlspecialchars($user_lname);
        $user_pwd = htmlspecialchars($user_pwd);
        $user_username = htmlspecialchars($user_username);
        $user_role = htmlspecialchars($user_role);
        $store_id = htmlspecialchars($store_id);
        $user_email = htmlspecialchars($user_email);
        $user_id = htmlspecialchars($user_id);

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

        $queryValidate = $mysqli->prepare("SELECT * FROM user WHERE user_username =? AND user_id <> ?;");
        $queryValidate->bind_param('si', $user_username, $user_id);
        $queryValidate->execute();
        $result = $queryValidate->get_result();
        if (mysqli_num_rows($result)) {
            $response['server_status'] = -1;
            echo json_encode($response);
            exit(1);
        } else {
            if ($store_id == "NULL") {
                //             $query = "UPDATE user SET user_username = '{$user_username}', user_fname = '{$user_fname}', user_lname = '{$user_lname}',
                // user_pwd = '{$user_pwd}', user_role = '{$user_role}', user_email = '{$user_email}',store_id = NULL WHERE user_id = {$user_id};";
                $query = $mysqli->prepare("UPDATE user SET user_username =?, user_fname =?, user_lname =?, user_pwd =?, user_role =?, user_email =?, store_id = NULL WHERE user_id =?;");
                $query->bind_param('ssssssi', $user_username, $user_fname, $user_lname, $user_pwd, $user_role, $user_email, $user_id);
            } else {
                //         $query = "UPDATE user SET user_username = '{$user_username}', user_fname = '{$user_fname}', user_lname = '{$user_lname}',
                // user_pwd = '{$user_pwd}', user_role = '{$user_role}', user_email = '{$user_email}', store_id = '{$store_id}' WHERE user_id = {$user_id};";
                $query = $mysqli->prepare("UPDATE user SET user_username =?, user_fname =?, user_lname =?, user_pwd =?, user_role =?, user_email =?, store_id =? WHERE user_id =?;");
                $query->bind_param('ssssssii', $user_username, $user_fname, $user_lname, $user_pwd, $user_role, $user_email, $store_id, $user_id);
            }
            // $result = $mysqli->query($query);
            $result = $query->execute();
            if ($result) {
                $response['server_status'] = 1;
            } else {
                $response['server_status'] = 0;
            }
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
