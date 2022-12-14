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
    <title>View Store Rating | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column">
    <?php include('cstaff-nav.php') ?>
    <div class="container p-2 pb-0 mt-5 mb-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 display-6">View Store Rating </h2>
        <div class="row g-2 justify-content-md-end">
        </div>
    </div>

    <div class="container pt-2">
        <div class="table-responsive">
            <table id="rating-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Order Ref</th>
                        <th>Rating</th>
                        <th>Comment</th>
                        <th>Date of Rating</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <?php include('../footer.php'); ?>
    <script>
        $(document).ready(function() {
            // Datatable Starts here
            var table = $('#rating-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-rating.php",
                },
                'columns': [{
                        data: 'row'
                    },
                    {
                        data: 'odr_ref',
                    },
                    {
                        data: 'rating_value'
                    },
                    {
                        data: 'rating_comment'
                    },
                    {
                        data: 'rating_date'
                    }
                ],
                columnDefs: [{
                    render: function(data, type, full, meta) {
                        return "<span style='width:100px; word-wrap:break-word; display:inline-block;'> " + data + "</span>";
                    },
                    width: '20%',
                    targets: 3
                }],
            });
        });
    </script>
</body>

</html>