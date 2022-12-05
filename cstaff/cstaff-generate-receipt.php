<!DOCTYPE html>
<html lang="en">
<!-- Template used from https://codepen.io/garrettbear/details/JzMmqg -->

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt</title>
  <link rel="stylesheet" href="../css/receipt.css">
  <?php
  session_start();
  include("../conn_db.php");
  if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
  }

  if (!empty(($_GET['odr_id']))) {
    $odr_id = $_GET['odr_id'];
    if ((isset($_SESSION["user_id"])) && (isset($_SESSION["store_id"]))) {
      $user_id = $_SESSION["user_id"];
      $store_id = $_SESSION["store_id"];
    }
  }
  ?>
</head>

<body>
  <div id="showScroll" class="container">
    <div class="receipt">
      <?php
      $query = "SELECT s.store_name, s.store_location, u.user_fname, u.user_lname FROM store s INNER JOIN user u ON s.store_id = u.store_id WHERE s.store_id = {$store_id} AND u.user_id = {$user_id}";
      $arr = $mysqli->query($query)->fetch_array();
      ?>
      <h1 class="logo">Store:<?php echo " " . $arr['store_name'] ?></h1>
      <div class="address">
        <?php echo $arr['store_location'] ?>
      </div>
      <div class="transactionDetails">
        <div class="detail"><?php echo "Receipt Generated: " . date("jS-M-Y H:ia") ?></div>
      </div>
      <div class="transactionDetails">
        <?php echo "Served By: " . $arr['user_fname'] . " " . $arr['user_lname'] ?>
      </div>
      <div class="break">
        ********************************
      </div>
      <?php
      $query = "SELECT m.mitem_name,od.odr_detail_amount,od.odr_detail_price,od.odr_detail_remark FROM odr_detail od INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE od.odr_id = {$odr_id}";
      $result = $mysqli->query($query);
      $rowcount = mysqli_num_rows($result);

      if ($rowcount > 0) {
        while ($row = $result->fetch_array()) {
      ?>
          <div class="transactionDetails">
            <div class="detail"><?php echo $row["odr_detail_amount"] . "X" ?></div>
            <div class="detail">
              <?php
              if ($row["odr_detail_remark"] != "") {
                echo $row["mitem_name"] . " (" . $row["odr_detail_remark"] . ") ";
              } else {
                echo $row["mitem_name"] . " (No Remark)";
              }
              ?>
            </div>
            <div class="detail"><?php echo "RM " . $row["odr_detail_amount"] * $row["odr_detail_price"] ?></div>
          </div>
      <?php
        }
      }
      ?>
      <div class="break">
        ********************************
      </div>
      <?php
      $query = "SELECT SUM(odr_detail_amount*odr_detail_price) AS total_price FROM odr_detail WHERE odr_id = {$odr_id}";
      $arr = $mysqli->query($query)->fetch_array();
      $total_price = "RM " . $arr['total_price'];
      ?>
      <div class="paymentDetails bold">
        <div class="detail">TOTAL</div>
        <div class="detail"><?php echo $total_price ?></div>
      </div>
      <div class="paymentDetails">
        <div class="detail">CHARGE</div>
        <div class="detail"><?php echo $total_price ?></div>
      </div>
      <div class="feedback">
        <div class="break">
          **************************
        </div>
        <p class="center">
          All goods sold are not refundable !
        </p>
        <h4 class="web">Thank You for Ordering</h4>
        <div class="break">
          **************************
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    window.print();
  </script>

</body>

</html>