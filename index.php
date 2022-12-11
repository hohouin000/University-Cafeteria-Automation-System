<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    ?>
    <title>Home | Index</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('nav.php');
    include('func-recommendation.php');
    ?>
    <div class="container p-5" id="recommend-dashboard" style="margin-top:5%;">
        <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
            </svg> Recommended Stores </h3>

        <!-- DASHBOARD -->
        <div class="row row-cols-1 row-cols-lg-4 align-items-stretch g-4 py-3">
            <?php
            if (isset($_SESSION['user_id'])) {
                $matrix = array();
                $query = "SELECT CAST(AVG(r.rating_value) AS DECIMAL(10,1)) as 'rating', u.user_id, s.store_id FROM odr o INNER JOIN rating r ON o.rating_id = r.rating_id INNER JOIN store s ON o.store_id = s.store_id INNER JOIN user u ON o.user_id = u.user_id GROUP BY s.store_id, u.user_id ORDER BY rating_value;
            ";
                $result = $mysqli->query($query);
                $rowcount = mysqli_num_rows($result);
                if ($rowcount > 0) {
                    while ($row = $result->fetch_array()) {
                        $matrix[$row['user_id']][$row['store_id']] = $row['rating'];
                    }
                    $i = 0;
                    $recommendation = array();
                    $recommendation = func_recommendation($matrix, $_SESSION['user_id']);
                    arsort($recommendation);
                    if (!empty($recommendation)) {
                        foreach ($recommendation as $store_id => $rating) {
                            if ($i <= 5) {
                                $query = "SELECT * FROM store WHERE store_id = {$store_id};";
                                $arr = $mysqli->query($query)->fetch_array();
            ?>
                                <div class="col">
                                    <div class="card border-info p-25">
                                        <div class="card-body">
                                            <img <?php
                                                    echo "src=\"img/store/{$arr['store_pic']}\"";
                                                    ?> style="width:100%; height:175px; object-fit:cover;" class="card-img-top rounded-25 img-fluid" alt="<?php echo $arr["store_name"] ?>">
                                            <h5 class="card-title" style="margin-top:5%;">
                                                <?php echo $arr["store_name"] ?>
                                            </h5>
                                            <p class="card-subtitle">
                                                <?php
                                                $now = date('H:i:s');
                                                $openhour = explode(":", $arr["store_openhour"]);
                                                $closehour = explode(":", $arr["store_closehour"]);
                                                if ((($now < $arr["store_openhour"]) || ($now > $arr["store_closehour"])) || ($arr["store_status"] == 0)) {
                                                ?>
                                                    <span class="badge rounded-pill bg-warning">Closed</span>
                                                <?php } else { ?>
                                                    <span class="badge rounded-pill bg-success">Open</span>
                                                <?php }
                                                ?>

                                            </p>
                                            <p class="card-text my-2">
                                                <span class="h6">
                                                    Operating hours:
                                                    <?php echo $openhour[0] . ":" . $openhour[1] . " - " . $closehour[0] . ":" . $closehour[1]; ?>
                                            </p>
                                            </span>
                                            </p>
                                            <div class="text-end">
                                                <a href="<?php echo "store-menu.php?store_id=" . $arr["store_id"] ?>" class="btn btn-sm btn-outline-dark">Go to Store</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            $i++;
                        }
                    } else {
                        // Display when there's insufficient data for recommendation engine
                        $query = "SELECT * FROM store WHERE store_rating >= 4 LIMIT 5";
                        $result = $mysqli->query($query);
                        $rowcount = mysqli_num_rows($result);
                        if ($rowcount > 0) {
                            while ($row = $result->fetch_array()) {
                            ?>
                                <div class="col">
                                    <div class="card border-info p-25">
                                        <div class="card-body">
                                            <img <?php
                                                    echo "src=\"img/store/{$row['store_pic']}\"";
                                                    ?> style="width:100%; height:175px; object-fit:cover;" class="card-img-top rounded-25 img-fluid" alt="<?php echo $row["store_name"] ?>">
                                            <h5 class="card-title" style="margin-top:5%;">
                                                <?php echo $row["store_name"] ?>
                                            </h5>
                                            <p class="card-subtitle">
                                                <?php
                                                $now = date('H:i:s');
                                                $openhour = explode(":", $row["store_openhour"]);
                                                $closehour = explode(":", $row["store_closehour"]);
                                                if ((($now < $row["store_openhour"]) || ($now > $row["store_closehour"])) || ($row["store_status"] == 0)) {
                                                ?>
                                                    <span class="badge rounded-pill bg-warning">Closed</span>
                                                <?php } else { ?>
                                                    <span class="badge rounded-pill bg-success">Open</span>
                                                <?php }
                                                ?>

                                            </p>
                                            <p class="card-text my-2">
                                                <span class="h6">
                                                    Operating hours:
                                                    <?php echo $openhour[0] . ":" . $openhour[1] . " - " . $closehour[0] . ":" . $closehour[1]; ?>
                                            </p>
                                            </span>
                                            </p>
                                            <div class="text-end">
                                                <a href="<?php echo "store-menu.php?store_id=" . $row["store_id"] ?>" class="btn btn-sm btn-outline-dark">Go to Store</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </symbol>
                            </svg>
                            <div class="alert alert-warning d-flex align-items-center w-100" role="alert">
                                <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:" width="24" height="24">
                                    <use xlink:href="#exclamation-triangle-fill" />
                                </svg>
                                <div>
                                    No recommended stores are available currently.
                                </div>
                            </div>
                        <?php
                        }
                    }
                }
            } else {
                // Display for customer without account
                $query = "SELECT * FROM store WHERE store_rating >= 4 LIMIT 5";
                $result = $mysqli->query($query);
                $rowcount = mysqli_num_rows($result);
                if ($rowcount > 0) {
                    while ($row = $result->fetch_array()) {
                        ?>
                        <div class="col">
                            <div class="card border-info p-25">
                                <div class="card-body">
                                    <img <?php
                                            echo "src=\"img/store/{$row['store_pic']}\"";
                                            ?> style="width:100%; height:175px; object-fit:cover;" class="card-img-top rounded-25 img-fluid" alt="<?php echo $row["store_name"] ?>">
                                    <h5 class="card-title" style="margin-top:5%;">
                                        <?php echo $row["store_name"] ?>
                                    </h5>
                                    <p class="card-subtitle">
                                        <?php
                                        $now = date('H:i:s');
                                        $openhour = explode(":", $row["store_openhour"]);
                                        $closehour = explode(":", $row["store_closehour"]);
                                        if ((($now < $row["store_openhour"]) || ($now > $row["store_closehour"])) || ($row["store_status"] == 0)) {
                                        ?>
                                            <span class="badge rounded-pill bg-warning">Closed</span>
                                        <?php } else { ?>
                                            <span class="badge rounded-pill bg-success">Open</span>
                                        <?php }
                                        ?>

                                    </p>
                                    <p class="card-text my-2">
                                        <span class="h6">
                                            Operating hours:
                                            <?php echo $openhour[0] . ":" . $openhour[1] . " - " . $closehour[0] . ":" . $closehour[1]; ?>
                                    </p>
                                    </span>
                                    </p>
                                    <div class="text-end">
                                        <a href="<?php echo "store-menu.php?store_id=" . $row["store_id"] ?>" class="btn btn-sm btn-outline-dark">Go to Store</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                        </symbol>
                    </svg>
                    <div class="alert alert-warning d-flex align-items-center w-100" role="alert">
                        <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:" width="24" height="24">
                            <use xlink:href="#exclamation-triangle-fill" />
                        </svg>
                        <div>
                            No recommended stores are available currently.
                        </div>
                    </div>
            <?php }
            }
            ?>
        </div>
        <!-- DASHBOARD -->
    </div>
    <?php include('footer.php'); ?>
</body>

</html>