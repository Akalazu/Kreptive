<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="author" content="Sortnio" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width,  initial-scale=1.0" />
  <meta name="description" content="Niftlify - NFT Marketplace" />
  <meta name="keywords" content="nft, crypto" />
  <title>Niftlify - Portal</title>
  <!-- Favicon -->
  <link rel="icon" sizes="16x16" href="images/favicon.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="images/favicon.png">
  <!-- Stylesheets -->
  <link rel="stylesheet" href="assets/css/vendor.bundle.css?ver=100" />
  <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">

  <!-- <link rel="stylesheet" href="assets/css/app.css"> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <script src="assets/js/sweetalert.min.js"></script>

  <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">

  <!-- Style -->
  <link rel="stylesheet" href="assets/css/login-style.css">
  <link rel="stylesheet" href="assets/css/style.css?ver=100" />

  <!-- Smartsupp Live Chat script -->
  <!-- Smartsupp Live Chat script -->
  <script type="text/javascript">
    var _smartsupp = _smartsupp || {};
    _smartsupp.key = '5cb72bfceac0248ce873cd52fd5f375ac09b1cdc';
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
  </script>
  <noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>
  <noscript> Powered by <a href=“https://www.smartsupp.com” target=“_blank”>Smartsupp</a></noscript>

</head>
<style>
  * {
    font-family: "Onest", sans-serif;
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
  include_once "./includes/init.php";
  ?>
  <div class="page-wrap">
    <?php




    if (isset($_POST['submit_btn'])) {
      $email = sanitizeText(sanitizeMail($_POST['email']));

      $password = sanitizeText($_POST['password']);

      if (!(empty($email) || empty($password))) {
        if (doesEmailExist($pdo, $email)) {
          $sql = "SELECT * FROM `reg_details` WHERE `email` = :em";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':em', $email);
          $stmt->execute();
          $reg_det = $stmt->fetch(PDO::FETCH_OBJ);
          $full_details = $reg_det->first_name . ' ' . $reg_det->last_name;

          if (password_verify($password, $reg_det->password)) {

            $time_date_of_login =  date('l, jS \O\f F Y \a\t H:ia');
            //  if ($reg_det->verified == 1) {
            // if ($reg_det->verified == 1 && $userCl->sendLoggedInMail($full_details, $time_date_of_login, $reg_det->email)) {
            if ($reg_det->verified == 1) {

              $activityCl->userLoggedIn($reg_det->id, 'login');
              $_SESSION['currcode'] = $reg_det->code;
              $_SESSION['currid'] = $reg_det->id;
              $_SESSION['last_activity'] = time();

              echo '
                <script>
              swal({
                    title: "Welcome ' . $reg_det->username . ' ",
                        text: "Login Successful" ,
                        icon: "success",
                    button: "Loading...",
                  });
              </script>
              ';
              if (isset($_SESSION['nft_link'])) {
                header('refresh: 2; portal/place_bid');
              } else {
                header('refresh: 2; portal/');
              }
            } else {
              echo '
            <script>
          swal({
                 title: "Account not verified",
                    text: "Kindly contact our customer service" ,
                    icon: "error",
                button: "Ok",
              });
          </script>
            ';
            }
          } else {
            echo '
          <script>
        swal({
               title: "Login Failed ",
                  text: "Invalid Email or Password" ,
                  icon: "error",
              button: "Ok",
            });
        </script>
          ';
          }
        } else {
          echo '
          <script>
        swal({
               title: "Login Failed ",
                  text: "Invalid Email or Password" ,
                  icon: "error",
              button: "Ok",
            });
        </script>
          ';
        }
      } else {
        echo '
            
        <script>
        swal({
               title: "Error!",
                  text: "Input fields cannot be empty",
                  icon: "warning",
              button: "Ok",
            });
        </script>
        ';
      }
    }
    ?>


    <div class="d-lg-flex half">

      <div class="bg order-1 order-md-2" style="background-image: url('https://images.pexels.com/photos/17485846/pexels-photo-17485846/free-photo-of-an-artist-s-illustration-of-artificial-intelligence-ai-this-image-represents-the-ways-in-which-ai-can-help-compress-videos-and-increase-efficiency-for-users-it-was-created-by-vincent-s.png?auto=compress&cs=tinysrgb&w=600');"></div>
      <div class="contents order-2 order-md-1">

        <div class="container">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-7">
              <!-- <img src="images/logo__dark.png" class="img-fluid" alt="" style="width: 100px;"> -->
              <a href="./" style="position: relative;bottom: 24px;"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="width:25px">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                </svg>
                Go Back </a>
              <h2>Login to <strong>Niftlify</strong></h2>
              <p>Input your details to access endless possibilities</p>

              <form action="#" method="post" class="mt-5">
                <div class="form-group first">
                  <label for="username">Email</label>
                  <input type="email" class="form-control" placeholder="email@gmail.com" name="email" required>
                </div>
                <div class="form-group last mb-3">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password__field" placeholder="********" name="password" required>
                  <div class="field__view">
                    <i class="fa-regular fa-eye" id="eye__icon"></i>
                  </div>
                </div>

                <div class="d-flex mb-5 align-items-center">

                  <span><a href="sign-up" class="forgot-pass">Create an account </a></span>
                  <span class="ml-auto"><a href="forgot-password" class="forgot-pass">Forgot Password</a></span>
                </div>

                <input type="submit" value="Log In" class="btn btn-block btn-primary" name="submit_btn">

              </form>
            </div>
          </div>
        </div>
      </div>

      <?php
      include_once 'portal/portal_footer.php';
      ?>