<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    ?>
    <title>Store List</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('nav.php') ?>
    <div class="container p-5" id="store-dashboard" style="margin-top:5%; margin-bottom:12%;">
        <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
            </svg> Store Available </h3>

        <div class="row row-cols-1 row-cols-lg-4 align-items-stretch g-4 py-3">
            <?php
            $query = "SELECT * FROM store";
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
            }
            ?>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>