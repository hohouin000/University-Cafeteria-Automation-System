<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    if (!isset($_GET["store_id"])) {
        header("location: restricted.php");
        exit(1);
    }
    ?>
    <title>Store Menu</title>
</head>

<body class="d-flex flex-column h-100">
    <?php
    include('nav.php');
    $store_id = $_GET["store_id"];
    $query = "SELECT * FROM store WHERE store_id = {$store_id}";
    $result = $mysqli->query($query);
    $row = $result->fetch_array();
    $openhour = explode(":", $row["store_openhour"]);
    $closehour = explode(":", $row["store_closehour"]);
    ?>
    <div class="container p-5" id="menu-dashboard" style="margin-top:5%;">
        <div class="row my-3">
            <a class="nav nav-item text-decoration-none text-muted" href="store-list.php">
                <i class="fa-solid fa-caret-left"> Go back</i></a>
        </div>
        <h2 class="border-bottom pb-2"> <?php echo $row['store_name'] ?></h2>
        <p class="card-text my-2">
            <span class="h6">
                Location:
                <?php echo $row['store_location']; ?>
        </p>
        <p class="card-text my-2">
            <span class="h6">
                Operating hours:
                <?php echo $openhour[0] . ":" . $openhour[1] . " - " . $closehour[0] . ":" . $closehour[1]; ?>
        </p>

        <div class="container p-5" id="bestseller-dashboard" style="margin-top:2%;">
            <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-menu-app" viewBox="0 0 16 16">
                    <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h2A1.5 1.5 0 0 1 5 1.5v2A1.5 1.5 0 0 1 3.5 5h-2A1.5 1.5 0 0 1 0 3.5v-2zM1.5 1a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2h14zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
                </svg> Best Seller </h3>

            <div class="row row-cols-1 row-cols-lg-4 align-items-stretch g-4 py-3">
                <?php
                $result->free_result();
                $query = "SELECT m.mitem_name, m.mitem_id, m.mitem_price, m.mitem_pic, SUM(od.odr_detail_amount) AS total_volume FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON m.mitem_id = od.mitem_id WHERE o.store_id = '{$store_id}'  AND o.odr_status = 'CMPLT' AND NOT(m.mitem_status = 0 ) GROUP BY od.mitem_id ORDER BY Total_Volume DESC LIMIT 5;";
                $result = $mysqli->query($query);
                $rowcount = mysqli_num_rows($result);
                if ($rowcount > 0) {
                    while ($row = $result->fetch_array()) {
                ?>
                        <div class="col">
                            <div class="card border-info p-25">
                                <div class="card-body">
                                    <img <?php
                                            echo "src=\"img/menu/{$row['mitem_pic']}\"";
                                            ?> style="width:100%; height:175px; object-fit:cover;" class="card-img-top rounded-25 img-fluid" alt="<?php echo $row["mitem_name"] ?>">
                                    <h5 class="card-title" style="margin-top:5%;">
                                        <?php echo $row["mitem_name"] ?>
                                    </h5>
                                    <p class="card-text my-2">
                                        <span class="h6">
                                            Price:
                                            <?php echo " " . "RM" . " " . $row["mitem_price"]; ?>
                                    </p>
                                    </span>
                                    </p>
                                    <div class="text-end">
                                        <a href="menu-item.php?<?php echo "store_id=" . $store_id . "&mitem_id=" . $row["mitem_id"] ?>" class="btn btn-sm btn-outline-dark">Add to Cart</a>
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

        <div class="container p-5" id="menu-dashboard" style="margin-top:3px; margin-bottom:12%;">
            <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-menu-app" viewBox="0 0 16 16">
                    <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0h2A1.5 1.5 0 0 1 5 1.5v2A1.5 1.5 0 0 1 3.5 5h-2A1.5 1.5 0 0 1 0 3.5v-2zM1.5 1a.5.5 0 0 0-.5.5v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5h-2zM0 8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V8zm1 3v2a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2H1zm14-1V8a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2h14zM2 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z" />
                </svg> Available Menu Items</h3>

            <div class="row row-cols-1 row-cols-lg-4 align-items-stretch g-4 py-3">
                <?php
                $result->free_result();
                $query = "SELECT * FROM mitem WHERE store_id = {$store_id} AND NOT(mitem_status = 0 )";
                $result = $mysqli->query($query);
                $rowcount = mysqli_num_rows($result);
                if ($rowcount > 0) {
                    while ($row = $result->fetch_array()) {
                ?>
                        <div class="col">
                            <div class="card border-info p-25">
                                <div class="card-body">
                                    <img <?php
                                            echo "src=\"img/menu/{$row['mitem_pic']}\"";
                                            ?> style="width:100%; height:175px; object-fit:cover;" class="card-img-top rounded-25 img-fluid" alt="<?php echo $row["mitem_name"] ?>">
                                    <h5 class="card-title" style="margin-top:5%;">
                                        <?php echo $row["mitem_name"] ?>
                                    </h5>
                                    <p class="card-text my-2">
                                        <span class="h6">
                                            Price:
                                            <?php echo " " . "RM" . " " . $row["mitem_price"]; ?>
                                    </p>
                                    </span>
                                    </p>
                                    <div class="text-end">
                                        <a href="menu-item.php?<?php echo "store_id=" . $store_id . "&mitem_id=" . $row["mitem_id"] ?>" class="btn btn-sm btn-outline-dark">Add to Cart</a>
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
    </div>
    <?php include('footer.php'); ?>
    <?php include("toast-message.php"); ?>
    <?php
    if (isset($_GET["response"])) {
        $response = $_GET["response"];
    }
    ?>
    <script>
        $(document).ready(function() {
            var response = "<?php echo $response; ?>";
            if (response == 1) {
                $('#atc-success-toast').toast('show')
            } else {
                $('#atc-fail-toast').toast('show')
            }
        });
    </script>
</body>

</html>