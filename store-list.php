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
    <div class="container p-5" id="store-dashboard" style="margin-top:5%;">
        <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z" />
                <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117zM11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5zM4 1.934V15h6V1.077l-6 .857z" />
            </svg> Store List </h3>

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
                        No stores are available currently.
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>