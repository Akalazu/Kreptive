<?php
$pageName = 'dashboard';
include_once 'portal_settings.php';

?>
<!-- .header-main-->
<div class="hero-wrap sub-header" style="
            background-image: url('./assets/images/sunrise1.webp');
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            background-color: rgba(0, 0, 0, 0.7);
            background-blend-mode: overlay;
          ">
  <div class="overlay"></div>
  <div class="container">
    <div class="hero-content py-0 pt-2 d-flex align-items-center">
      <!-- end avatar -->
      <div class="author-hero-content-wrap d-flex flex-wrap justify-content-between mx-4 flex-grow-1">
        <div class="author-hero-content me-3">
          <p class="hero-author-username mb-1 text-white text-center">Balance</p>
          <h2 class="hero-author-title mb-1 text-white d-flex align-items-center"><?= $currUser->balance ?><small style="font-size: 18px; background-color: white; color: black; padding: 5px; border-radius: 5px; margin-left: 5px">ETH</small></h2>
        </div>
        <!-- end author-hero-content -->

      </div>
      <!-- end author-hero-content-wrap -->
    </div>
    <!-- hero-content -->
  </div>
  <!-- .container-->
</div>
<!-- end hero-wrap -->
</header>
<!-- end header-section -->
<section class="profile-section section-space">
  <div class="container">
    <div class="row">
      <?php include_once 'portal_sidebar.php' ?>
      <!-- end col -->
      <div class="col-lg-9 ps-xl-5">
        <div class="user-panel-title-box">
          <h3>NFT Dashboard</h3>
        </div>
        <!-- end user-panel-title-box -->
        <div class="profile-setting-panel-wrap">
          <div class="tab-content mt-4" id="myTabContent">
            <div class="tab-pane fade show active" id="offers-receive" role="tabpanel" aria-labelledby="offers-receive-tab">
              <div class="profile-setting-panel">
                <!-- end alert -->
                <!-- <h3 class="mb-1">Your Received Offers:</h3> -->
                <div id="google_translate_element" style="margin-bottom: 10px; margin-top: -25px;"></div>

                <p class="mb-4 fs-14">The NFT Marketplace</p>
                <div class="row g-gs">
                  <?php
                  $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 ORDER BY `id` DESC";
                  $stmt = $pdo->prepare($sql);
                  $stmt->execute();

                  $all_nft = $stmt->fetchAll(PDO::FETCH_OBJ);

                  foreach ($all_nft as $nft) {


                  ?>
                    <div class="col-md-6">
                      <div class="card card-full card-s3">
                        <div class="card-author d-flex align-items-center justify-content-between pb-3">
                          <div class="d-flex align-items-center">
                            <a class="avatar me-1">
                              <img src="../<?= $userCl->getAuthorImage($nft->author_id) ?>" />
                            </a>
                            <div class="custom-tooltip-wrap card-author-by-wrap">
                              <span class="card-author-by card-author-by-2">Owned by</span>
                              <a class="custom-tooltip author-link">@<?= getUserNameById($pdo, $nft->author_id) ?></a>
                              <!-- end dropdown-menu -->
                            </div>
                            <!-- end custom-tootltip-wrap -->
                          </div>
                        </div>
                        <!-- end card-author -->
                        <div class="card-image">
                          <img src="../<?= $nft->image ?>" class="card-img" alt="art image" />
                        </div>
                        <!-- end card-image -->
                        <div class="card-body px-0 pb-0">
                          <h5 class="card-title text-truncate">
                            <a><?= $nft->title ?></a>
                          </h5>
                          <div class="card-price-wrap d-flex align-items-center justify-content-sm-between pb-3">
                            <div class="me-5 me-sm-2">
                              <span class="card-price-title">Price</span>
                              <span class="card-price-number"><?= $nft->price ?> ETH</span>
                            </div>
                            <div class="text-sm-end">
                              <span class="card-price-title">Current bid</span>
                              <span class="card-price-number d-block">
                                $<?= number_format($nft->price * 1700) ?>
                              </span>
                            </div>
                          </div>
                          <!-- end card-price-wrap -->
                          <a href="purchase_nft" class="btn btn-sm btn-dark">Buy NFT</a>
                        </div>
                        <!-- end card-body -->
                      </div>
                      <!-- end card -->
                    </div>

                  <?php
                  }
                  ?>
                  <!-- end col -->
                </div>
                <!-- end row -->
              </div>
              <!-- end profile-setting-panel -->
            </div>
            <!-- end tab-pane -->

            <!-- end tab-pane -->
          </div>
          <!-- end tab-content -->
        </div>
        <!-- end profile-setting-panel-wrap-->
      </div>
      <!-- end col -->
    </div>
    <!-- end row -->
  </div>
  <!-- end container -->
</section>
<!-- end profile-section -->
<footer class="footer-section bg-dark on-dark mt-5">
  <div class="container">

    <!-- end section-space-sm -->
    <hr class="bg-white-slim my-0" />
    <div class="copyright-wrap d-flex flex-wrap py-3 align-items-center justify-content-between">
      <p class="footer-copy-text py-2">
        &copy; <?= date('Y') ?> Niffiti.
      </p>
      <p>Developed by <a href="https://mygagnerapp.com" class="text-white"><b>The Gagner Team</b></a></p>

    </div>
    <!-- end d-flex -->
  </div>
  <!-- .container -->
</footer>
<?php require_once 'portal_footer.php' ?>