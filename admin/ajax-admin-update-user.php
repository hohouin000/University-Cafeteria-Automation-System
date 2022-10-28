<?php session_start();
include("../conn_db.php");

$store_id = $_POST['store_id'];
$user_fname = $_POST['user_fname'];
$user_lname = $_POST['user_lname'];
$user_pwd = $_POST['user_pwd'];
$user_username = $_POST['user_username'];
$user_role = $_POST['user_role'];
$user_email = $_POST['user_email'];
$user_id = $_POST['user_id'];

if ($store_id == "NULL") {
    $query = "UPDATE user SET user_username = '{$user_username}', user_fname = '{$user_fname}', user_lname = '{$user_lname}', 
user_pwd = '{$user_pwd}', user_role = '{$user_role}', user_email = '{$user_email}',store_id = NULL WHERE user_id = {$user_id};";
} else {
    $query = "UPDATE user SET user_username = '{$user_username}', user_fname = '{$user_fname}', user_lname = '{$user_lname}', 
    user_pwd = '{$user_pwd}', user_role = '{$user_role}', user_email = '{$user_email}', store_id = '{$store_id}' WHERE user_id = {$user_id};";
}
$result = $mysqli->query($query);
if ($result) {
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
