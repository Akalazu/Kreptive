<?php
include_once 'header.php';

//   echo $_SERVER['HTTP_REFERER'];
if (!isset($_GET['query_id']) || !isset($_SESSION['timeRecovered'])) {
  header('Location: ../');
} else {
  $id = $_GET['query_id'];
  $user_det = $userCl->getUserDetails($id);
  // echo $user_det->recover_request;
  // die();
  if ($user_det->recover_request != 1) {
    header('Location: ../');
  }

  if (time() > $_SESSION['timeRecovered'] + 60 * 10) {
    if ($userCl->updateRecoverRequest($id)) {
      unset($_SESSION['timeRecovered']);
      exit('Session expired, Kindly refresh this page and try again');
    }
    // header('Location: ./');
  } else {
    $_SESSION['lastaccess'] = time();
  }
}

if (isset($_POST['update_pass'])) {

  $newpass = sanitizeText($_POST['new-password']);
  $conpass = sanitizeText($_POST['confirm-password']);

  if (empty($newpass) or empty($conpass)) {
    $error = '
        <script>
      swal({
            title: "Error!",
            text: "Fields cannot be empty",
            icon: "warning",
            button: "Ok",
          });
      </script>
        
        ';
    echo $error;
  } elseif ($conpass !== $newpass) {
    $error = '
        <script>
      swal({
            title: "Password Not Updated",
            text: "Passwords do not match",
            icon: "warning",
            button: "Ok",
          });
      </script>
        
        ';
    echo $error;
  } elseif (strlen($newpass) < 6 or strlen($conpass) < 6) {
    $error = '
        <script>
      swal({
            title: "Password Not Updated",
            text: "Passwords must be at least 6 characters",
            icon: "warning",
            button: "Ok",
          });
      </script>
        
        ';
    echo $error;
  } else {

    $ref_id = $activityCl->genRefId();
    $activityCl->updatedPass($user_det->id, $ref_id);
    $passwordHash = password_hash($conpass, PASSWORD_BCRYPT);

    $sql = "UPDATE `reg_details` SET `password` = :obb WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':obb', $passwordHash);
    $statement->bindValue(':idd', $user_det->id);
    if ($statement->execute()) {
      echo '
             <script>
      swal({
            title: "Update Successful",
            text: "Password has been changed",
            icon: "success",
            button: "Ok",
          }).then(function() {
             window.location.href = "sign-in";
        });
      </script>
            ';
    }
  }
}
?>
<!-- outer-->
<section class="login-section section-space-b pt-5 pt-md-5 mt-md-3 ">
  <div class="container">
    <div class="row align-items-center justify-content-center ">
      <div class="col-lg-6 mb-5 mb-lg-0 d-none d-lg-block ">
        <img src="assets/images/forgot-password.jpg" alt="" class="img-fluid" />
      </div>
      <!-- end col-lg-6 -->
      <div class="col-lg-6 col-md-9">
        <div class="section-head-sm mb-4 mt-3">
          <h2 class="mb-2 text-center">Reset Password</h2>

        </div>
        <form method="POST">
          <div class="form-floating mb-4">
            <input type="email" class="form-control" id="emailAddress" name="email" placeholder="name@example.com" value="<?= $user_det->email ?>" readonly required />
            <label for="emailAddress">Email address</label>
          </div>
          <div class="form-floating mb-4">
            <input type="password" class="form-control password" id="password" name="new-password" placeholder="New Password" required />
            <label for="password">New Password</label>
            <a href="password" class="password-toggle" title="Toggle show/hide pasword">
              <em class="password-shown ni ni-eye-off"></em>
              <em class="password-hidden ni ni-eye"></em>
            </a>
          </div>
          <div class="form-floating mb-4">
            <input type="password" class="form-control password" id="confirm-password" name="confirm-password" placeholder="Confirm New Password" required />
            <label for="password">Confirm New Password</label>
            <a href="confirm_password" class="password-toggle" title="Toggle show/hide pasword">
              <em class="password-shown ni ni-eye-off"></em>
              <em class="password-hidden ni ni-eye"></em>
            </a>
          </div>
          <!-- end form-floating -->

          <!-- end form-floating -->
          <center>
            <button class="btn btn-dark text-center " type="submit" name="update_pass">
              Save Changes
            </button>
          </center>
        </form>
      </div>
      <!-- end col-lg-6 -->
    </div>
    <!-- end row -->
  </div>
  <!-- end container -->
</section>

<?php
require_once 'footer.php'
?>