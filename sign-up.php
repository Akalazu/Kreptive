<?php
include_once "./includes/init.php";

// ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="author" content="Sortnio" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width,  initial-scale=1.0" />
  <meta name="description" content="Kreptive - NFT Marketplace" />
  <meta name="keywords" content="nft, crypto" />
  <title>Kreptive - Portal</title>
  <!-- Favicon -->
  <link rel="icon" sizes="16x16" href="images/favicon.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon.png">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="assets/css/vendor.bundle.css?ver=100" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
  <!-- <link rel="stylesheet" href="assets/css/app.css"> -->

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="assets/js/sweetalert.min.js"></script>

  <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">

  <!-- Style -->
  <link rel="stylesheet" href="assets/css/login-style.css">
  <!-- <link rel="stylesheet" href="assets/css/app.css" /> -->
  <link rel="stylesheet" href="assets/assets/css/style.css">
  <!-- Smartsupp Live Chat script -->
  <!-- <script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '07e8f4cfbd525e6f4e3c33265f93c811bea2424e';
    window.smartsupp || (function(d) {
      var s, c, o = smartsupp = function() {
        o._.push(arguments)
      };
      o._ = [];
      s = d.getElementsByTagName('script')[0];
      c = d.createElement('script');
      c.type = 'text/javascript';
      c.charset = 'utf-8';
      c.async = true;
      c.src = 'https://www.smartsuppchat.com/loader.js?';
      s.parentNode.insertBefore(c, s);
    })(document);
  </script> -->
  <noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>


</head>
<style>
  * {
    font-family: "Rubik", sans-serif;
  }

  body {
    top: 0 !important;
  }


  .goog-logo-link,
  .goog-te-gadget span {
    display: none !important;
  }

  .goog-te-gadget {
    color: transparent !important;
    font-size: 0;
    height: 40px;
  }

  .goog-te-banner-frame {
    display: none !important;
  }

  body>.skiptranslate {
    display: none;
  }

  #goog-gt-tt,
  .goog-te-balloon-frame {
    display: none !important;
  }

  .goog-text-highlight {
    background: none !important;
    box-shadow: none !important;
  }

  #google_translate_element select {
    background: #fff;
    /* border: 1px solid #777e90; */
    border-radius: 5px;
    font-family: "Rubik", sans-serif;
    padding: 7px;
    width: fit-content;
  }

  #eye__icon {
    display: inline;
    position: relative;
    top: -40px;
    left: 90%
  }
</style>




<body>
  <?php





  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!(empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['userName']) || empty($_POST['email']) || empty($_POST['password']))) {
      $fname = sanitizeText($_POST['firstName']);
      $lname = sanitizeText($_POST['lastName']);
      $username = sanitizeText($_POST['userName']);
      $email = sanitizeMail($_POST['email']);
      $password = sanitizeText($_POST['password']);
      $randomNumbers = generateFourRandomNumbers();
      $verify_code = implode('', $randomNumbers);
      $fullName = $fname . ' ' . $lname;
      $withdrawal_limit = $userCl->getCurrLimit();
      $address = $userCl->getUserAddress();

      if (is_array($email)) {
        echo '
        <script>
        swal({
              title: "Error!",
              text: "Invalid email address provided",
              icon: "warning",
              button: "Ok",
            });
        </script>
      
      ';
      } else if (doesEmailExist($pdo, $email)) {
        echo '
        <script>
        swal({
              title: "Error!",
              text: "Email address already exist",
              icon: "warning",
              button: "Ok",
            });
        </script>
      
      ';
      } else if (strlen($password) < 6) {
        echo '
        <script>
        swal({
              title: "Error!",
              text: "Password must be at least 6 characters.",
              icon: "warning",
              button: "Ok",
            });
        </script>
      
      ';
      } else if (strlen($username) < 5) {
        echo '
        <script>
        swal({
              title: "Error!",
              text: "Username must be at least 5 characters.",
              icon: "warning",
              button: "Ok",
            });
        </script>
      
      ';
      } else if (strpos($fname, ' ') !== false || strpos($lname, ' ') !== false) {
        echo '
        <script>
        swal({
              title: "Error!",
              text: "First name or Last name cannot spaces",
              icon: "warning",
              button: "Ok",
            });
        </script>
      ';
      } else {
        $code = genid($pdo);
        $_SESSION['regMail'] = $email;
        $verified = 1;
        $mint_fee = $userCl->getMintFee();

        // $linkk = "https://www.mygagnerapp.com/portal/register?ref_code=$code";


        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO `reg_details`(`first_name`, `last_name`, `code`,`username`, `address`, `mint_fee`,`email`, `password`, `verify_code`, `verified`, `withdraw_limit`) VALUES ( :aa , :bb , :cc, :uu , :wa, :mf, :ee , :pp, :vc, :vv, :wl) ";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':aa', $fname);
        $statement->bindParam(':bb', $lname);
        $statement->bindParam(':cc', $code);
        $statement->bindParam(':uu', $username);
        $statement->bindParam(':wa', $address);
        $statement->bindParam(':ee', $email);
        $statement->bindParam(':mf', $mint_fee);
        $statement->bindParam(':pp', $passwordHash);
        $statement->bindParam(':vc', $verify_code);
        $statement->bindParam(':vv', $verified);
        $statement->bindParam(':wl', $withdrawal_limit);


        if ($statement->execute()) {
          //   if ($statement->execute()) {
          echo '
          <script>
        swal({
               title: "Registration Successful",
                  text: "Your Account has been Successfully Created" ,
                  icon: "success",
              button: "Ok",
            }).then(function() {
             window.location.href = "sign-in";
        });
        </script>
          ';
        } else {
          echo '
         <script>
        swal({
               title: "Error!",
                  text: "An error occurred" ,
                  icon: "warning",
              button: "Ok",
            });
        </script>';
        }
      }
    } else {
      echo '
         <script>
        swal({
               title: "Error!",
                  text: "Fields cannot be empty" ,
                  icon: "warning",
              button: "Ok",
            });
        </script>';
      // header('refresh: 3; index');
    }
  }



  ?>
  <!-- end header-section -->
  <div class="d-lg-flex half">

    <div class="bg order-1 order-md-2" style="background-image: url('https://images.unsplash.com/photo-1634696684126-462b1a767e22?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mzd8fDNkJTIwd2FsbGV0fGVufDB8MXwwfHx8Mg%3D%3D'); background-size: cover; height: 100%"></div>
    <div class="contents order-2 order-md-1">

      <div class="container vh-100">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7 py-5">
            <!-- <img src="images/logo__dark.png" class="img-fluid" alt="" style="width: 100px;"> -->
            <h3 style="margin-top: -10px; margin-bottom: 10px; text-align: center;">Sign up with <strong>Kreptive</strong></h3>

            <form action="#" method="post">
              <div class="form-group">

                <label for="fname">First Name</label>
                <input type="text" class="form-control" value="<?= getInputValue('firstName') ?>" name="firstName" required>
              </div>
              <div class="form-group">
                <label for="username">Last Name</label>
                <input type="text" class="form-control" value="<?= getInputValue('lastName') ?>" name="lastName" required>
              </div>
              <div class="form-group first">
                <label for="username">Username</label>
                <input type="text" class="form-control"=value="<?= getInputValue('userName') ?>" name="userName" required>
              </div>
              <div class="form-group first">
                <label for="username">Email</label>
                <input type="email" class="form-control" value="<?= getInputValue('email') ?>" name="email" required>
              </div>
              <div class="form-group last mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" placeholder="********" name="password" id="password__field" required>
                <div class="field__view">
                  <i class="fa-regular fa-eye" id="eye__icon"></i>
                </div>
              </div>

              <div class="d-flex mb-3 align-items-center">

                <span><a href="./" class="forgot-pass">Return to Homepage </a></span>
                <span class="ml-auto"><a href="sign-in" class="forgot-pass">Already have an account?</a></span>
              </div>

              <input type="submit" value="Create Account" class="btn btn-block btn-primary">

            </form>
          </div>
        </div>
      </div>
    </div>


  </div>

  <!-- end register-section -->
  <?php
  include_once 'portal/portal_footer.php';
  ?>