<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    if (isset($_GET["odr_id"])) {
        if (!empty($_GET["odr_id"])) {
            $odr_id = mysqli_real_escape_string($mysqli, $_GET["odr_id"]);
        } else {
            header("location: order-history.php");
            exit(1);
        }
    } else {
        header("location: order-history.php");
        exit(1);
    }

    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
        header("location: login.php");
        exit(1);
    }
    ?>
    <link href="css/rate.css" rel="stylesheet">
    <title>Rate Experience</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('nav.php') ?>
    <div class="container px-5 py-4">
        <div class="container p-2 pb-0 mt-5 pt-3">
            <div class="row my-3">
                <a href="order-history.php" class="nav nav-item text-decoration-none text-muted">
                    <i class="fa-solid fa-caret-left"> Go back</i></a>
            </div>
            <h2 class="pt-3 display-6">Rate Experience</h2>
        </div>
        <?php
        // $query = "SELECT o.*, s.store_name, s.store_id FROM odr o INNER JOIN store s ON o.store_id = s.store_id WHERE o.odr_id = {$odr_id};";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT o.*, s.store_name, s.store_id FROM odr o INNER JOIN store s ON o.store_id = s.store_id WHERE o.odr_id =?;");
        $query->bind_param('i', $odr_id);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            // $arr = $mysqli->query($query)->fetch_array();
            $arr = $result->fetch_array();
            if ($arr['odr_rate_status'] == 0) {
        ?>
                <div class="container d-flex justify-content-center mt-5">
                    <div class="card text-center" style="border: none;">
                        <div class="card-body">
                            <h5 class="card-title">Order Ref: <?php echo $arr['odr_ref']; ?></h5>
                            <p class="card-text">Store Name: <?php echo $arr['store_name']; ?></p>
                            <div class="rate py-3 mt-3">
                                <form action="func-add-rating.php" method="POST">
                                    <h6 class="mb-0">Rate your experience</h6>
                                    <div class="rating"> <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label> <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label> <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label> <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label> <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <label for="comment" class="form-label">Comment (Optional)</label>
                                        <textarea class="form-control" id="comment" name="comment" rows="5" maxlength="250"></textarea>
                                    </div>
                                    <div class="buttons px-4 mb-3">
                                        <button type="submit" class="btn btn-outline-primary btn-block">Submit</button>
                                    </div>
                                    <input type="hidden" name="odr-id" value="<?php echo $odr_id; ?>">
                                    <input type="hidden" name="store-id" value="<?php echo $arr['store_id']; ?>">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </symbol>
                </svg>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:" width="24" height="24">
                        <use xlink:href="#exclamation-triangle-fill" />
                    </svg>
                    <div>
                        Order has been rated !
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>