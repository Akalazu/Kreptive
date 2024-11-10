<?php
include_once 'header.php';




  if (isset($_POST['recover_btn'])) {
    $user__email = $_POST['email'];
    // echo $user__email;
    if (doesEmailExist($pdo, $user__email) && madeRecoverRequest($pdo, $user__email)) {
      $user_det = $userCl->getUserDetailsByEmail($user__email); //get user details by email
      $name = $user_det->first_name . ' ' . $user_det->last_name; //get full name of user with that email address
      $userCl->sendForgotPwrdMail($name, $user__email, $user_det->id); //send email to the user
      echo '
               <script>
        swal({
              title: "Request Success",
              text: "If the email exist, a mail would be sent to the provided email address",
              icon: "success",
              button: "Ok",
            }).then(function() {
               window.location.href = "sign-in";
          });
        </script>
              ';
    }
  }
?>
<section class="login-section section-space-b pt-5 pt-md-5 mt-md-3 ">
  <div class="container">
    <div class="row align-items-center justify-content-center ">
      <div class="col-lg-6 mb-5 mb-lg-0 d-none d-lg-block ">
        <img src="assets/images/forgot-password.jpg" alt="" class="img-fluid" />
      </div>
      <!-- end col-lg-6 -->
      <div class="col-lg-6 col-md-9">
        <div class="section-head-sm mb-4 mt-3">
          <h2 class="mb-2 text-center">Forgot Password</h2>
          
        </div>
        <form method="POST">
          <div class="form-floating mb-4">
            <input type="email" class="form-control" id="emailAddress" name="email" placeholder="name@example.com" required />
            <label for="emailAddress">Email address</label>
     
              <em class="password-shown ni envelope-o "></em>
          
          </div>
          <!-- end form-floating -->
          
          <!-- end form-floating -->
          <center>
              <button class="btn btn-dark text-center " type="submit" name="recover_btn">
            Recover Password
          </button>
          <p class="mt-3 form-text">
            <a href="sign-up" class="btn-link">Go Back</a>
          </p>
          </center>
        </form>
      </div>
      <!-- end col-lg-6 -->
    </div>
    <!-- end row -->
  </div>
  <!-- end container -->
</section>
<!-- end login-section -->
<?php
require_once 'footer.php'
?>