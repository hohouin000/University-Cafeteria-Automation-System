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
    if (isset($_SESSION["store_id"])) {
        $store_id = $_SESSION["store_id"];
    }
    ?>

    <title>Order Management | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column">
    <?php include('cstaff-nav.php') ?>
    <div class="container p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 pb-5 display-6">Order Management</h2>
        <nav>
            <div class="nav nav-pills flex-wrap mb-3" id="pills-tab" role="tablist">
                <button class="nav-link active px-5" id="unpd-tab" data-bs-toggle="tab" data-bs-target="#nav-unpd" type="button" role="tab" aria-controls="nav-unpd" aria-selected="true">Unpaid</button>
                <button class="nav-link px-5" id="odrrcv-tab" data-bs-toggle="tab" data-bs-target="#nav-odrrcv" type="button" role="tab" aria-controls="nav-odrrcv" aria-selected="true">Order Received</button>
                <button class="nav-link px-5" id="prep-tab" data-bs-toggle="tab" data-bs-target="#nav-prep" type="button" role="tab" aria-controls="nav-prep" aria-selected="true">Preparing</button>
                <button class="nav-link px-5" id="rdfk-tab" data-bs-toggle="tab" data-bs-target="#nav-rdfk" type="button" role="tab" aria-controls="nav-rdfk" aria-selected="true">Ready for pick-up</button>
                <button class="nav-link px-5" id="cmplt-tab" data-bs-toggle="tab" data-bs-target="#nav-cmplt" type="button" role="tab" aria-controls="nav-cmplt" aria-selected="false">Completed</button>
                <button class="nav-link px-5" id="cxld-tab" data-bs-toggle="tab" data-bs-target="#nav-cxld" type="button" role="tab" aria-controls="nav-cxld" aria-selected="false">Cancelled</button>
            </div>
        </nav>
    </div>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-unpd" role="tabpanel" aria-labelledby="unpd-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="unpaid-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order Reference</th>
                                <th>Customer Name</th>
                                <th>Order Placement Time</th>
                                <th>Total Price</th>
                                <th>Order Details</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-odrrcv" role="tabpanel" aria-labelledby="odrrcv-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="order-received-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>2</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-prep" role="tabpanel" aria-labelledby="prep-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="preparing-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>3</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-rdfk" role="tabpanel" aria-labelledby="rdfk-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="rdfk-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>4</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-cmplt" role="tabpanel" aria-labelledby="cmplt-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="completed-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>5</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-cxld" role="tabpanel" aria-labelledby="cxld-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="cancelled-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>6</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('../footer.php'); ?>

    <script>
        $(document).ready(function() {
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            // Datatable Starts here
            var unpd_table = $('#unpaid-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-order.php",
                },
                'columns': [{
                        data: 'odr_ref'
                    },
                    {
                        data: 'user_name',
                    },
                    {
                        data: 'odr_placedtime'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'odr_details'
                    },
                    {
                        data: 'odr_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-warning btn-sm btn-received" data-id="' + data + '"> Received </a><a class="btn btn-outline-danger btn-sm btn-cancel" data-id="' + data + '"> Cancel </a></div>'
                            } else {
                                return ''
                            }
                        }

                    },
                ],
                scrollY: 200,
                scrollCollapse: true,

            });

            var odrrcv_table = $('#order-received-table').DataTable({
                scrollY: 200,
                scrollCollapse: true,
            });

            var prep_table = $('#preparing-table').DataTable({
                scrollY: 200,
                scrollCollapse: true,
            });

            var rdfk_table = $('#rdfk-table').DataTable({
                scrollY: 200,
                scrollCollapse: true,
            });

            var cmplt_table = $('#completed-table').DataTable({
                scrollY: 200,
                scrollCollapse: true,
            });

            var cxld_table = $('#cancelled-table').DataTable({
                scrollY: 200,
                scrollCollapse: true,
            });
        });
    </script>

    <?php include("../toast-message.php"); ?>
</body>

</html>