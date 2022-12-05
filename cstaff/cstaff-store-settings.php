<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("../conn_db.php");
    include('../head.php');
    if ($_SESSION["user_role"] != "CSTAFF") {
        header("location:../restricted.php");
        exit(1);
    }
    ?>
    <link rel="stylesheet" href="../css/report.css">
    <title>Store Settings| Cafeteria Staff</title>
</head>

<body class="d-flex flex-column">
    <?php include('cstaff-nav.php');
    ?>
    <div class="container admin-dashboard p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 pb-5 display-6">Store Settings</h2>
        <div class="row g-2 mb-5  justify-content-md-end">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-store-setting" id="btn-change-setting">Change Settings</button>
            </div>
        </div>

        <!-- DASHBOARD -->
        <div class="row row-cols-1 row-cols-lg-2 align-items-stretch g-4 py-3" id="div-setting">
            <div class="col">
                <div class="card border-info p-2">
                    <div class="card-body">
                        <h4 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                            </svg>
                            Store Operating Hour
                        </h4>
                        <p class="card-text my-2">
                            <span class="h6">
                                <?php
                                $store_id = $_SESSION["store_id"];
                                $query = "SELECT * FROM store  WHERE store_id = '{$store_id}';";
                                $result = $mysqli->query($query);
                                $row = $result->fetch_array();
                                $formatted_open_hour = date("H:ia", strtotime($row['store_openhour']));
                                $formatted_close_hour = date("H:ia", strtotime($row['store_closehour']));
                                echo  $formatted_open_hour . " - " .  $formatted_close_hour;
                                ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-success p-2">
                    <div class="card-body">
                        <h4 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                                <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z" />
                                <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117zM11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5zM4 1.934V15h6V1.077l-6 .857z" />
                            </svg>
                            Store Status
                        </h4>
                        <p class="card-text my-2">
                            <span class="h6">
                                <?php
                                if ($row['store_status'] == 1) {
                                    echo "Open For Today";
                                } else {
                                    echo "Not Open For Today";
                                }
                                ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- DASHBOARD -->
    </div>
    <?php include('../footer.php'); ?>
    <?php include("../toast-message.php"); ?>

    <!-- Modal -->
    <?php $store_id = $_SESSION["store_id"];
    $query = "SELECT * FROM store  WHERE store_id = '{$store_id}';";
    $result = $mysqli->query($query);
    $row = $result->fetch_array();
    ?>
    <div class="modal fade" id="modal-store-setting" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Store Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-change-setting">
                        <div class="row row-cols-2 g-2 mb-2">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="form-store-setting-openhour" placeholder="Open Hour" value="<?php echo $row['store_openhour'] ?>" required>
                                    <label for="storeopenhour">Open Hour</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="form-store-setting-closehour" placeholder="Close Hour" value="<?php echo $row['store_closehour'] ?>" required>
                                    <label for="storeopenhour">Close Hour</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="form-store-setting-status" value="<?php echo $row['store_status'] ?>">
                                <label class="form-check-label" for="storestatus">Open For Today</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-store-setting">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-confrim-changes" name="btn-confrim-changes">Confirm Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Modal -->

    <script>
        $(document).ready(function() {
            //Set Store Status
            if ($('#form-store-setting-status').val() == 1) {
                $("#form-store-setting-status").prop('checked', true);
            } else {
                $("#form-store-setting-status").prop('checked', false);
            }

            //AJAX Call Starts Here
            $("#form-change-setting").on('submit', function(e) {
                // Get Selected Store Details in Modal
                e.preventDefault();
                store_id = "<?php echo $store_id ?>";
                console.log(store_id)
                var store_openhour = $('#form-store-setting-openhour').val()
                var store_closehour = $('#form-store-setting-closehour').val()
                var store_status;

                if (store_openhour > store_closehour) {
                    alertify.error('Open hour cannot be larger than close hour !');
                    return false;
                }

                if ($('#form-store-setting-status').is(':checked')) {
                    store_status = 1;
                } else {
                    store_status = 0;
                }

                // Update AJAX Call Starts here
                $.ajax({
                    url: "ajax-cstaff-update-store.php",
                    type: "POST",
                    data: {
                        "store_id": store_id,
                        "store_openhour": store_openhour,
                        "store_closehour": store_closehour,
                        "store_status": store_status,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.server_status == 1) {
                            alertify.success('Changes Made Successfully !');
                            // alertify.alert('Success', 'Changes Made Successfully !', function() {
                            //     location.reload();
                            // });
                            setTimeout(location.reload.bind(location), 700);
                        } else {
                            alertify.error('Fail to change settings !');
                        }
                    }
                })
            });
        });
    </script>
</body>

</html>