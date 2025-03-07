<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Niffiti - Portal</title>
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

    <script type='text/javascript'>
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
    </script>


</head>
<style>
    .art__title {
        font-size: 18px;
        font-weight: bold;
    }

    .amount__value {
        /* font-family: 'Outfit', sans-serif; */
        font-family: "Inter", sans-serif;
        font-size: 35px;
        font-weight: 900;
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
        display: none !important;
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
        margin-bottom: 10px;
    }

    .table__container {
        width: 100%;
        overflow-x: auto;
    }

    .table__container table {
        width: 100%;
        border-collapse: collapse;
    }

    .table__container table tr {
        font-family: "Rubik", sans-serif;
        text-align: center;
    }

    .dollar__equi {
        font-size: 14px;
        color: #cfcece;
    }
</style>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <!-- <a class="navbar-brand brand-logo" href="./"><img src="../images/logo__dark.png" alt="logo" /></a> -->
                <a class="navbar-brand brand-logo-mini" href="./"><img src="../images/favicon.png" /></a>
                <img class="navbar-brand brand-logo logo-dark logo-img" src="../images/logo__dark.png" alt="logo" style="width: 250px; height: 100%; object-fit: contain;" />
                <img class=" navbar-brand brand-logo logo-light logo-img" src="../images/logo.png" alt=" logo" style="width:250px; height: 100%; object-fit: contain;" />
            </div>
            <div class="navbar-menu-wrapper d-flex align-items-stretch">
                <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                    <span class="mdi mdi-menu"></span>
                </button>

                <ul class="navbar-nav navbar-nav-right">
                    <li class="nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="nav-profile-img">
                                <img src="../<?= $currUser->image ?>" alt="image">
                                <span class="availability-status online"></span>
                            </div>
                            <div class="nav-profile-text">
                                <p class="mb-1 text-black"><?= $currUser->first_name . ' ' . $currUser->last_name ?></p>
                            </div>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            <a class="dropdown-item" href="account">
                                <i class="mdi mdi-account me-2 text-success"></i> Account </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logout
                            ">
                                <i class="mdi mdi-logout me-2 text-primary"></i> Signout </a>
                        </div>
                    </li>

                </ul>
                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item nav-profile">
                        <a href="#" class="nav-link">
                            <div class="nav-profile-image" style="display: flex;justify-content: center;align-items: flex-end;">
                                <img src="../<?= $currUser->image ?>" alt="profile"> <!--change to offline or busy as needed-->
                            </div>
                            <div class="nav-profile-text d-flex flex-column ms-2">
                                <span class="font-weight-bold mb-2"><?= $currUser->first_name . ' ' . $currUser->last_name ?></span>
                                <span class="text-secondary text-small d-flex">Verified <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                                </span>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./">
                            <span class="menu-title">Dashboard</span>
                            <i class="mdi mdi-home menu-icon"></i>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="eMessage/">
                            <span class="menu-title">Community</span>
                            <i class="mdi mdi-forum menu-icon"></i>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="deposit_logs">
                            <span class="menu-title">Fund Account</span>
                            <i class="mdi mdi-cash menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mint_nft">
                            <span class="menu-title">Create NFT</span>
                            <i class="mdi mdi-diamond menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="purchase_nft">
                            <span class="menu-title">Buy NFT</span>
                            <i class="mdi mdi-cart-plus menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_collections">
                            <span class="menu-title">My Collections</span>
                            <i class="mdi mdi-tab menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="all_bids">
                            <span class="menu-title">All Bids</span>
                            <i class="mdi mdi-tab menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="payout">
                            <span class="menu-title">Withdrawal</span>
                            <i class="mdi mdi-cash-multiple menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account">
                            <span class="menu-title">Account</span>
                            <i class="mdi mdi-account menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="activity">
                            <span class="menu-title">Activity Log</span>
                            <i class="mdi mdi-sync menu-icon"></i>
                        </a>
                        <?php

                        if ($currUser->role == 'admin') {
                            echo '</li>
                    <li class="nav-item">
                        <a class="nav-link" href="superadmin">
                            <span class="menu-title">Admin</span>
                            <i class="mdi mdi-lock menu-icon"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users">
                            <span class="menu-title">Manage Users</span>
                            <i class="mdi mdi-account-multiple menu-icon"></i>
                        </a>
                    </li>';
                        }
                        ?>

                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">