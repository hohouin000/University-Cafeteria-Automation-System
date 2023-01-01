<!DOCTYPE html>
<html lang="en">

<head>
  <?php session_start();
  include("conn_db.php");
  if (isset($_SESSION["user_role"]) && !empty($_SESSION["user_role"]) && $_SESSION["user_role"] == "CUST") {
    header("location: index.php");
    exit(1);
  }
  ?>
  <?php include('head.php'); ?>
  <title>Login</title>
</head>

<body class="d-flex flex-column h-100">
  <div class="container p-5" id="login-dashboard" style="margin-top:5%;">
    <a class="nav nav-item text-decoration-none text-muted" href="index.php">
      <i class="bi bi-arrow-left-square me-2"></i>Go back</a>
    <!-- Login Form -->
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-lg-4 col-md-6 col-sm-6">

          <div class="card shadow">
            <div class="card-title text-center border-bottom">
              <h2 class="p-3">Login</h2>
            </div>
            <div class="card-body">
              <form id="form-login">
                <div class="mb-4">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" required />
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" required />
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-outline-primary">Login</button>
                </div>
              </form>
              <div class="alert alert-dark mt-3" role="alert">
                Click <a href="cstaff/cstaff-login.php" class="alert-link">here</a> to login as cafeteria staff.
              </div>
            </div>
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
            </symbol>
          </svg>
          <div class="alert alert-primary d-flex align-items-center mt-3" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
              <use xlink:href="#info-fill" />
            </svg>
            <div>
              Please contact the administrator via email (ucastest2000@gmail.com) in case of forget password.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>

  <script>
    $(document).ready(function() {
      $("#form-login").on('submit', function(e) {
        e.preventDefault();
        var user_username = $('#username').val()
        var user_pwd = $('#password').val()
        $.ajax({
          url: "ajax-login-validation.php",
          type: "POST",
          data: {
            "user_username": user_username,
            "user_pwd": user_pwd,
          },
          dataType: 'json',
          success: function(response) {
            if (response.server_status == 1) {
              window.location.href = "index.php";
            } else {
              $('#login-fail-toast').toast('show')
            }
          }
        });
      });
    });
  </script>
  <?php include("toast-message.php"); ?>
</body>

</html>