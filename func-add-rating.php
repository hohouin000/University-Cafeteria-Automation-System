<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
    header("location: login.php");
    exit(1);
}

if (isset($_POST["odr-id"]) && isset($_POST["rating"]) && isset($_POST["comment"]) && isset($_POST["store-id"])) {
    if (!empty($_POST['odr-id']) && !empty($_POST['rating']) && !empty($_POST['store-id'])) {
        $store_id = mysqli_real_escape_string($mysqli, $_POST["store-id"]);
        $odr_id = mysqli_real_escape_string($mysqli, $_POST["odr-id"]);
        // $query = "SELECT * FROM odr WHERE odr_id = {$odr_id};";
        //$result = $mysqli->query($query);
        //$rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * FROM odr WHERE odr_id =?;");
        $query->bind_param('i', $odr_id);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            // $arr = $mysqli->query($query)->fetch_array();
            $arr = $result->fetch_assoc();
            if ($arr['odr_rate_status'] == 0) {
                $rating = mysqli_real_escape_string($mysqli, $_POST["rating"]);
                $comment = mysqli_real_escape_string($mysqli, $_POST["comment"]);

                $comment = htmlspecialchars($comment);

                if (!filter_var($rating, FILTER_VALIDATE_INT)) {
                    header("location:order-history.php?response=0");
                    exit(1);
                }

                $date = date("Y-m-d\TH:i:s");
                // $query = "INSERT INTO rating (rating_value,rating_comment,rating_date) VALUES ({$rating},'{$comment}','{$date}');";
                // $result = $mysqli->query($query);
                $query = $mysqli->prepare("INSERT INTO rating (rating_value,rating_comment,rating_date) VALUES (?,?,?);");
                $query->bind_param('iss', $rating, $comment, $date);
                $query->execute();
                $rating_id = $mysqli->insert_id;

                // $query = "UPDATE odr SET odr_rate_status = 1, rating_id = {$rating_id} WHERE odr_id = {$odr_id};";
                // $result = $mysqli->query($query);
                $query = $mysqli->prepare("UPDATE odr SET odr_rate_status = 1, rating_id =? WHERE odr_id =?;");
                $query->bind_param('ii', $rating_id, $odr_id);
                $query->execute();

                // $query = "SELECT CAST(AVG(r.rating_value) AS DECIMAL(10,1)) as 'rating' FROM odr o INNER JOIN rating r ON o.rating_id = r.rating_id WHERE o.store_id = {$store_id};";
                // $result = $mysqli->query($query)->fetch_array();
                $query =  $mysqli->prepare("SELECT CAST(AVG(r.rating_value) AS DECIMAL(10,1)) as 'rating' FROM odr o INNER JOIN rating r ON o.rating_id = r.rating_id WHERE o.store_id =?;");
                $query->bind_param('i', $store_id);
                $query->execute();
                $result = $query->get_result()->fetch_assoc();

                // $query = "UPDATE store SET store_rating = {$result['rating']} WHERE store_id = {$store_id};";
                // $result = $mysqli->query($query);
                $query =  $mysqli->prepare("UPDATE store SET store_rating =? WHERE store_id =?;");
                $query->bind_param('di', $result['rating'], $store_id);
                $result = $query->execute();

                if ($result) {
                    $_SESSION["server_status"] = 1;
                    header("location:order-history.php");
                    exit(0);
                } else {
                    $_SESSION["server_status"] = 0;
                    header("location:order-history.php");
                    exit(1);
                }
            } else {
                $_SESSION["server_status"] = -1;
                header("location:order-history.php");
                exit(1);
            }
        }
    } else {
        $_SESSION["server_status"] = 0;
        header("location:order-history.php");
        exit(1);
    }
} else {
    $_SESSION["server_status"] = 0;
    header("location:order-history.php");
    exit(1);
}
