<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Niftlify - Portal</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/assets/vendors/css/vendor.bundle.base.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;800&family=Outfit:wght@600;@900&display=swap" rel="stylesheet">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <?php
    $url = $_SERVER['REQUEST_URI'];
    if (str_contains($url, 'deposit')) {
        echo '<link rel="stylesheet" type="text/css" href="../assets/css/app.css">';
    }

    ?>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../images/favicon.png" />

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />


    <!-- Scripts -->
    <script src="../assets/js/sweetalert.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>

    <!-- Smartsupp Live Chat script -->
    <!-- Smartsupp Live Chat script -->
    <!-- <script type="text/javascript">
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
    </script> -->
</head>


<body class="@@dashboard">

    <?php

    require_once '../includes/init.php';

    // if (isset($_SERVER['HTTP_REFERER'])) {
    //     $location = $_SERVER['HTTP_REFERER'];
    // } {
    //     $location = './';
    // }

    if (!isset($_SESSION['currid'])) {
        header('Location: logout');
    } else {
        $id = $_SESSION['currid'];
        // echo $id;
        $userD = $userCl->getUserDetails($id);
    }
    // Verify the entered password
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_details'])) {
        $entered_password = $_POST['password']; // Password entered by the user

        // Verify the password against the stored hash in your database
        if (password_verify($entered_password, $userD->password)) {
            // Password is correct, update last activity and redirect to the home page
            $_SESSION['last_activity'] = time();
            echo '
                <script>
              swal({
                     title: "Welcome ' . $userD->username . ' ",
                        text: "Login Successful" ,
                        icon: "success",
                    button: "Loading...",
                  });
              </script>
                ';
            header("refresh: 1; ./");
            // header("Location: dashboard");
            // exit();
        } else {
            // Password is incorrect, display an error message
            echo '
            <script>
          swal({
                 title: "Login Failed ",
                    text: "Password is Incorrect" ,
                    icon: "error",
                button: "Ok",
              });
          </script>
            ';
        }
    }





    ?>
    <style>
        .lock_span {
            border: 1px solid #ebedf2;
            padding: 20px;
            border-radius: 50px;
        }

        .logout_text {
            display: flex;
            justify-content: end;
        }

        .logout_text a {
            padding: 10px 20px;
            font-size: 13px;
            /* background: #ee4300; */
            border: 1px solid #ee4300;
            color: #dc3545;
            border-radius: 50px;
        }
    </style>
    <div class="authincation lock-padding">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <span class="logout_text my-3"><a href="logout">Logout</a></span>
                <div class="col-md-6">
                    <div class="user icon-menu active text-center my-3">
                        <span class="lock_span"><img src="../<?= $userD->image ?>" alt="user_image" width="30" />
                            <?= $userD->first_name . ' ' . $userD->last_name ?></span>
                    </div>
                    <h4 class="card-title my-4 text-center">Welcome Back</h4>
                    <div class="auth-form card">
                        <div class="card-body">
                            <form class="row g-3" method="POST">
                                <div class="col-12">
                                    <label class="form-label">Enter Password</label><input type="password" class="form-control" name="password" placeholder="***********" />
                                </div>
                                <div class="mt-4">
                                    <button type="submit" name="submit_details" class="btn btn-primary btn-block">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="../js/scripts.js"></script>
</body>

</html>