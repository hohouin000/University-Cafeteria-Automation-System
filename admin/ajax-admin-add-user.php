<?php session_start();
include("../conn_db.php");


$store_id = $_POST['store_id'];
$user_fname = $_POST['user_fname'];
$user_lname = $_POST['user_lname'];
$user_pwd = $_POST['user_pwd'];
$user_username = $_POST['user_username'];
$user_role = $_POST['user_role'];
$user_email = $_POST['user_email'];

$queryValidate = "SELECT * FROM user WHERE user_username = '{$user_username}';";
$result = $mysqli->query($queryValidate);
if (mysqli_num_rows($result)) {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit();
} else {
    if ($store_id == "NULL") {
        $insert_query = "INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email) 
    VALUES ('{$user_username}','{$user_fname}','{$user_lname}','{$user_pwd}','{$user_role}','{$user_email}');";
        $insert_result = $mysqli->query($insert_query);
    } else {
        $insert_query = "INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email,store_id) 
    VALUES ('{$user_username}','{$user_fname}','{$user_lname}','{$user_pwd}','{$user_role}','{$user_email}','{$store_id}');";
        $insert_result = $mysqli->query($insert_query);
    }
    $response['server_status'] = 1;
    echo json_encode($response);
    exit();
}
