<?php
include_once 'header.php';
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
?>
<!-- .header-main-->
<div class="hero-wrap sub-header">
  <div class="container">
    <div class="hero-content text-center py-0">
      <h1 class="hero-title">Explore</h1>

    </div>
    <!-- hero-content -->
  </div>
  <!-- .container-->
</div>
<!-- end hero-wrap -->
</header>
<!-- end header-section -->
<section class="explore-section pt-lg-4 " style="margin-bottom: 70px">
  <div class="container">
    <div class="filter-box pb-5">
      <div class="filter-box-filter justify-content-between align-items-center">
        <!-- end filter-box-filter-item -->
        <div class="filter-box-filter-item quicksearch-wrap">
          <input type="text" placeholder="Search By Name" class="form-control form-control-s1 quicksearch" style="width: 500px" />
        </div>
        <!-- end filter-box-filter-item -->
        <div class="filter-box-filter-item ms-lg-auto filter-btn-wrap">
          <div class="filter-btn-group">
            <form method="post">
              <button class="btn filter-btn" name="recent">Recent</button>
              <button class="btn filter-btn" name="ascending">Price: Low</button>
              <button class="btn filter-btn" name="descending">Price: High</button>
            </form>
          </div>
        </div>
        <!-- end filter-box-filter-item -->
        <div class="filter-box-filter-item filter-mobile-action ms-lg-auto">
          <div class="filter-box-search-mobile dropdown me-2">
            <a class="icon-btn" href="#" data-bs-toggle="dropdown">
              <em class="ni ni-search"></em>
            </a>
            <div class="dropdown-menu dropdown-menu-end card-generic card-generic-s2 mt-2 p-3">
              <input type="text" placeholder="Search By Name" class="form-control form-control-s1 quicksearch" />
            </div>
          </div>
          <!-- end filter-box-search-mobile -->
          <div class="filter-box-btn-group-mobile dropdown">
            <a class="icon-btn" href="#" data-bs-toggle="dropdown">
              <em class="ni ni-filter"></em>
            </a>
            <div class="dropdown-menu dropdown-menu-end card-generic mt-2 p-3">
              <div class="filter-btn-group filter-btn-group-s1">
                <form method="post" class="text-center">
                  <button class="btn filter-btn" name="recent">Recent</button>
                  <button class="btn filter-btn" name="ascending">Price: Low</button>
                  <button class="btn filter-btn" name="descending">Price: High</button>

                </form>
              </div>
            </div>
          </div>
          <!-- end filter-box-btn-group-mobile -->
        </div>
        <!-- end filter-box-filter-item -->
      </div>
      <!-- end filter-box-filter -->
    </div>
    <!-- end filter-box -->
    <div class="filter-container row g-gs">
      <?php
      if (isset($_POST['ascending'])) {

        $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 ORDER BY `price` ASC";
        $stmt = $pdo->prepare($sql);
      } else if (isset($_POST['descending'])) {
        $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 ORDER BY `price` DESC";
        $stmt = $pdo->prepare($sql);
      } else {
        $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 ORDER BY `id` DESC";
        $stmt = $pdo->prepare($sql);
      }
      $stmt->execute();

      $all_nft = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($all_nft as $nft) {


      ?>


        <div class="col-md-4 my-3 overview__item filter-item artworks">
          <div class="card card-full card-s3">
            <div class="card-author d-flex align-items-center justify-content-between pb-3">
              <div class="d-flex align-items-center">
                <a class="avatar me-1">
                  <img src="<?= $userCl->getAuthorImage($nft->author_id) ?>" />
                </a>
                <div class="custom-tooltip-wrap card-author-by-wrap">
                  <span class="card-author-by card-author-by-2">Owned by</span>
                  <a class="custom-tooltip author-link" href="view_profile?idd=<?= $nft->owner_id ?>">@<?= getUserNameById($pdo, $nft->owner_id) ?><span><i class="mdi mdi-check-circle"></i> </span></a>
                  <!-- end dropdown-menu -->
                </div>
                <!-- end custom-tootltip-wrap -->
              </div>
            </div>
            <!-- end card-author -->
            <div class="card-image">
              <img src="<?= $nft->image ?>" class="card-img" alt="art image" />
            </div>
            <!-- end card-image -->
            <div class="card-body px-0 pb-0">
              <h5 class="card-title text-truncate">
                <a><?= $nft->title ?></a>
              </h5>
              <div class="card-price-wrap d-flex align-items-center justify-content-between pb-3">
                <div class="me-5 me-sm-2">
                  <span class="card-price-title">Price</span>
                  <span class="card-price-number"><?= $nft->price ?> ETH</span>
                </div>
                <div class="text-sm-end">
                  <span class="card-price-title">Current bid</span>
                  <span class="card-price-number d-block">
                    $<?= number_format($nft->price * $ethereumToUsdRate) ?>
                  </span>
                </div>
              </div>
              <!-- end card-price-wrap -->
              <div class="d-flex justify-content-center mt-3">
                <a class="btn btn-primary" href="sign-in">Place a Bid</a>
              </div>
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
    <!-- end filter-container -->
  </div>
  <!-- .container -->
</section>
<!-- end explore-section -->

<!-- end top-creator-section -->

<!-- end collection-section -->
<?php

require_once 'footer.php';

?>