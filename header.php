<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Sortnio" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,  initial-scale=1.0" />
    <meta name="description" content="Niffiti - NFT Marketplace" />
    <meta name="keywords" content="nft, trading" />
    <title>Niffiti - Homepage</title>
    <meta name="description" content="Niffiti is a marketplace of world-class NFTs" />
    <link rel="icon" href="https://niffiti.com/images/favicon.png" type="image/png">

    <?php
    if ($_SERVER['SERVER_NAME'] == 'localhost') {
        echo '<base href="http://localhost/artcribs/">';
    } else {
        echo '<base href="https://niffiti.com/">';
    }
    ?>



    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="https://niffiti.com/images/favicon.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png" />
    <meta property="og:image" content="https://niffiti.com/images/favicon.png" />
    <meta property="og:title" content="Niffiti - Home of Quality NFTs" />
    <!-- Favicon -->
    <link rel="icon" sizes="16x16" href="images/favicon.png" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon.png">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/vendor.bundle.css?ver=100" />
    <!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&family=Nunito+Sans:opsz,wght@6..12,900;6..12,1000&family=Outfit:wght@900&display=swap" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="assets/css/app.css"> -->
    <link rel="stylesheet" href="assets/assets/vendors/mdi/css/materialdesignicons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">



    <script src="assets/js/sweetalert.min.js"></script>

    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Style -->
    <link rel="stylesheet" href="assets/css/login-style.css">
    <link rel="stylesheet" href="assets/css/style.css?ver=100" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>


    <!-- <script type="text/javascript">
        var _smartsupp = _smartsupp || {};
        _smartsupp.key = 'c8f9076e067ae286a1c08521c1e1b536514e189b';
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
</style>




<body style="top: 0">
    <?php
    require_once "includes/init.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['newsletter_sub'])) {
        $email = sanitizeMail($_POST['email']);
        //   print_r($_POST);
        //   echo 'Na Here e end';
        //   die();

        /**
         * Code for this would come here later! 😉💪
         */
        if (!is_array($email)) {

            echo '
            <script>
          swal({
                 title: "Congratulations",
                    text: "You just subscribed for our newsletter ✅" ,
                    icon: "success",
               
              })
          </script>
            ';
        } else {
            echo '
            <script>
          swal({
                 title: "Erorr!",
                    text: "Kindly provide a valid email address" ,
                    icon: "error",
           \
              })
          </script>
            ';
        }
    }
    ?>
    <div class="page-wrap">
        <header class="header-section has-header-main">
            <div class="header-main is-sticky">
                <div class="container">
                    <div class="header-wrap">
                        <div class="header-logo">
                            <a href="./" class="logo-link">
                                <img class="logo-dark logo-img" src="images/logo__dark.png" alt="logo" style="width: 200px; height: 100%" />
                                <img class="logo-light logo-img" src="images/logo.png" alt=" logo" style="width:200px; height: 100%" />
                            </a>
                        </div>
                        <!-- .header-logo -->
                        <div class="header-mobile-action">

                            <!-- end header-search-mobile -->

                            <!-- end hheader-mobile-wallet -->
                            <div class="header-toggle">
                                <button class="menu-toggler">
                                    <em class="menu-on menu-icon ni ni-menu"></em>
                                    <em class="menu-off menu-icon ni ni-cross"></em>
                                </button>
                            </div>
                            <!-- .header-toggle -->
                        </div>
                        <!-- end header-mobile-action -->
                        <nav class="header-menu menu nav">
                            <ul class="menu-list ms-lg-auto">
                                <li class="menu-item has-sub">
                                    <a href="./" class="menu-link">Home</a>
                                </li>
                                <li class="menu-item has-sub">
                                    <a href="explore" class="menu-link">Explore</a>
                                </li>
                                <li class="menu-item has-sub">
                                    <a href="about-us" class="menu-link">About Us</a>
                                </li>
                                <!-- <li class="menu-item has-sub">
                                    <a href="portal/eMessage/" class="menu-link">Community</a>
                                </li> -->
                            </ul>
                            <ul class="menu-btns">
                                <li>
                                    <a href="sign-in" class="btn btn-lg btn-primary">Sign In</a>
                                </li>
                                <li>
                                    <a href="sign-up" class="btn btn_other">Sign Up</a>
                                </li>
                                <li>
                                    <a href="#" class="theme-toggler" id="theme-toggle" title="Toggle Dark/Light mode">
                                        <span>
                                            <em class="ni ni-moon icon theme-toggler-show"></em>
                                            <em class="ni ni-sun icon theme-toggler-hide"></em>
                                        </span>
                                        <span class="theme-toggler-text">Light/Dark Mode</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <!-- .header-menu -->
                        <div class="header-overlay"></div>
                    </div>
                    <!-- .header-warp-->
                </div>
                <!-- .container-->
            </div>