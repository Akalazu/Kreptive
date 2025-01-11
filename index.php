<?php
require_once 'header.php';

?>
<style>
  .btn-primary {
    background-color: #3e80ff;
    border-color: #3e80ff;
  }
</style>
<!-- .header-main-->
<div class="hero-wrap hero-wrap-2 section-space">
  <div class="container">
    <div class="row flex-lg-row-reverse justify-content-between align-items-center" style="flex-direction: column-reverse;
    gap: 5rem;">
      <div class="col-lg-5" data-aos="fade-left">
        <div class="swiper swiper-carousel swiper-button-s1" data-breakpoints='{
                                "0":{"slidesPerView":1,"slidesPerGroup":1}
                            }' data-loop="true" navigation="true" data-autoplay="false" data-speed="500" data-centeredslides="false">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
              <a class="card" href="what-is-nft">
                <img src="assets/images/what-is-nft.webp" alt="" class="card-img-top">
                <div class="card-body d-flex align-items-center">

                  <div class="ms-2">
                    <h5 class="card-title-effect">What is NFT?</h5>
                  </div>
                </div><!-- end card-body -->
              </a><!-- end card -->
            </div><!-- end swiper-slider -->
            <div class="swiper-slide">
              <a class="card" href="what-is-minting">
                <img src="assets/images/what-is-minting.webp" alt="" class="card-img-top">
                <div class="card-body d-flex align-items-center">

                  <div class="ms-2">
                    <h5 class="card-title-effect">What is Minting?</h5>

                  </div>
                </div><!-- end card-body -->
              </a><!-- end card -->
            </div><!-- end swiper-slider -->

          </div>
          <!-- If we need navigation buttons -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div><!-- end swiper-container -->
      </div><!-- end col-lg-5 -->
      <div class="col-lg-6" data-aos="fade-right">
        <div class="hero-content pt-xl-3 pt-lg-0 pb-0">
          <h1 class="hero-title hero-title-s1 mb-3 text-white" style="font-family: 'Rubik', sans-serif;" id="app">NFTs made simple,<br> secure, and accessible.</h1>

          <p class="hero-text mb-4 pb-1" style="color: #e9ecef;">Launch your own white-label NFT store or marketplace effortlessly with Niftlify's top-tier platform. Start now and redefine the future of digital ownership!
          </p>
          <ul class="hero-btns btns-group">
            <li><a href="sign-in" class="btn btn-md btn-dark">Mint an Art</a></li>
            <li><a href="explore" class="btn btn-md btn-outline-light text-white">Explore</a></li>
          </ul>
        </div><!-- hero-content -->
      </div><!-- col-lg-6 -->
    </div><!-- end row -->
  </div><!-- .container-->
  <svg class="shape1" viewBox="0 0 198 342" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M77.0989 212.77C4.94983 240.593 -3.65619 310.516 1.05944 342L199.558 342L200 -8.74228e-06C138.549 -6.05619e-06 149.16 97.8604 144.739 127.866C142.263 176.694 95.6667 204.359 77.0989 212.77Z" fill="#fff"></path>
  </svg>
  <svg class="shape2" viewBox="0 0 198 342" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M77.0989 212.77C4.94983 240.593 -3.65619 310.516 1.05944 342L199.558 342L200 -8.74228e-06C138.549 -6.05619e-06 149.16 97.8604 144.739 127.866C142.263 176.694 95.6667 204.359 77.0989 212.77Z" fill="#fff"></path>
  </svg>
  <svg class="shape3" viewBox="0 0 93 78" fill="none" xmlns="http://www.w3.org/2000/svg">
    <path d="M31.7243 33.8972C9.47383 37.2 1.30374 12.6752 0 0H93V77.5724C78.8762 75.8341 74.7477 66.7079 71.271 58.0164C67.7944 49.3248 59.5374 29.7687 31.7243 33.8972Z" fill="#fff"></path>
  </svg>
</div>
<!-- end hero-wrap -->
</header>
<!-- end header-section -->
<section class="section-space trending-section bg-gray">
  <div class="container">
    <div class="section-head text-center">
      <h2>Hot Auctions 🔥</h2>
    </div>
    <!-- end section-head -->
    <div class="row g-gs">
      <?php
      $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 ORDER BY `id` DESC LIMIT 15";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();

      $all_nft = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($all_nft as $nft) {

        $ownerDetails = $userCl->getUserDetails($nft->owner_id);


      ?>

        <div class="col-md-4 my-3 overview__item">
          <div class="card card-full card-s3">
            <div class="card-author d-flex align-items-center justify-content-between pb-3">
              <div class="d-flex align-items-center">
                <a class="avatar me-1">
                  <img src="<?= $userCl->getAuthorImage($nft->author_id) ?>" />
                </a>
                <div class="custom-tooltip-wrap card-author-by-wrap">
                  <span class="card-author-by card-author-by-2">Owned by</span>
                  <a class="custom-tooltip author-link" href="https://niftlify.com/<?= $ownerDetails->first_name ?>/<?= $ownerDetails->address ?>">@<?= getUserNameById($pdo, $nft->owner_id) ?><span><i class="mdi mdi-check-circle"></i> </span></a>
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
                  <span class="card-price-title">Floor Price</span>
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

      <!-- end col -->
    </div>
    <!-- end row -->
    <div class="text-center mt-4 mt-md-5">
      <a href="explore" class="btn-link btn-link-s1">View all</a>
    </div>
  </div>
  <!-- end container -->
</section>

<section class="top-creator-section section-space">
  <div class="container">
    <div class="section-head text-center">
      <h2>How to get started?</h2>
      <small class="mt-2">Get on-the-go with niftlify by doing the following</small>
    </div>
    <!-- end section-head -->
    <section class="section-space-b how-it-work-section pt-2" data-aos="zoom-in-up">
      <div class="container">
        <div class="row g-gs">
          <div class="col-sm-6 col-lg-4">
            <div class="card-hiw text-center card-hiw-s2">
              <span class="card-hiw-number">1</span>
              <span class="icon ni ni-user icon-md icon-rounded icon-wbg mx-auto mb-4 text-white bg-blue"></span>
              <h5 class="pb-2">Setup your account</h5>
              <p class="card-text-s1">Click on the sign-up button, fill the required fields and verify your account</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4">
            <div class="card-hiw text-center card-hiw-s2">
              <span class="card-hiw-number">2</span>
              <span class="icon ni ni-wallet icon-md icon-rounded icon-wbg mx-auto mb-4 text-white bg-purple"></span>
              <h5 class="pb-2">Fund your wallet</h5>
              <p class="card-text-s1">Go to the <b>Deposit</b> section and fund your account with your desired amount</p>
            </div>
          </div>
          <div class="col-sm-6 col-lg-4">
            <div class="card-hiw text-center card-hiw-s2">
              <span class="card-hiw-number">3</span>
              <span class="icon ni ni-camera icon-md icon-rounded icon-wbg mx-auto mb-4 text-white bg-pink"></span>
              <h5 class="pb-2">Add your NFTs</h5>
              <p class="card-text-s1">Upload your work, give it a price tag and then add a title and description.</p>
            </div>
          </div>

        </div><!-- end row -->
      </div><!-- end container -->
    </section><!-- end how-it-work-section -->
    <!-- end col-lg-4 -->
  </div>
  <!-- end row -->
  </div>
  <!-- end container -->
</section>
<!-- end top-creator-section -->
<section class="section-space-b how-it-work-section mt-5" id="faq">
  <div class="container">
    <div class="section-head text-center">
      <h2>Frequently Asked Questions</h2>
    </div>
    <!-- end section-head -->
    <div class="swiper swiper-carousel swiper-pagination-space" data-breakpoints='{
                    "0":{"slidesPerView":1,"slidesPerGroup":1},
                    "768":{"slidesPerView":2,"slidesPerGroup":2}
                    }' data-loop="true" navigation="true" data-autoplay="false" data-speed="500" data-centeredslides="false">
      <div class="swiper-wrapper">
        <div class="swiper-slide h-auto">
          <div class="card card-hiw card-hiw-s3 card-full">
            <div class="card-body">
              <h4 class="mb-3 card-title">
                <em class="ni ni-question icon icon-sm text-orange icon-circle icon-wbg shadow-sm me-2"></em>
                What is a non-fungible token (NFT)?
              </h4>
              <p class="card-text">
                A non-fungible token (NFT) is a cryptographic token that
                represents a unique asset. They function as verifiable
                proofs of authenticity and ownership within a blockchain
                network.
              </p>
            </div>
            <!-- end card-body -->
          </div>
        </div>
        <!-- end swiper-slide -->
        <div class="swiper-slide h-auto">
          <div class="card card-hiw card-hiw-s3 card-full">
            <div class="card-body">
              <h4 class="mb-3 card-title">
                <em class="ni ni-question icon icon-sm text-orange icon-circle icon-wbg shadow-sm me-2"></em>
                What is Niftlify NFT Marketplace?
              </h4>
              <p class="card-text">
                Artists, creators, and cryptocurrency enthusiasts come together on Niftlify NFT Marketplace to collectively create and trade premium NFTs.
              </p>
            </div>
            <!-- end card-body -->
          </div>
        </div>
        <!-- end swiper-slide -->
        <div class="swiper-slide h-auto">
          <div class="card card-hiw card-hiw-s3 card-full">
            <div class="card-body">
              <h4 class="mb-3 card-title">
                <em class="ni ni-question icon icon-sm text-orange icon-circle icon-wbg shadow-sm me-2"></em>
                How do I create an NFT?
              </h4>
              <p class="card-text">
                In order to upload your file, click [Create]. Right now, we support the following file formats: JPG, PNG. Note that you are unable to modify your NFT.

              </p>
            </div>
            <!-- end card-body -->
          </div>
        </div>
        <!-- end swiper-slide -->
        <div class="swiper-slide h-auto">
          <div class="card card-hiw card-hiw-s3 card-full">
            <div class="card-body">
              <h4 class="mb-3 card-title">
                <em class="ni ni-question icon icon-sm text-orange icon-circle icon-wbg shadow-sm me-2"></em>
                How do I buy an NFT?
              </h4>
              <p class="card-text">
                Select the Buy NFT option, look for the suggested artwork, click the [Buy] button to finish the transaction on the product page. The NFT will be transferred to your wallet as soon as the transaction is completed successfully.

              </p>
            </div>
            <!-- end card-body -->
          </div>
        </div>
        <!-- end swiper-slide -->
        <div class="swiper-slide h-auto">
          <div class="card card-hiw card-hiw-s3 card-full">
            <div class="card-body">
              <h4 class="mb-3 card-title">
                <em class="ni ni-question icon icon-sm text-orange icon-circle icon-wbg shadow-sm me-2"></em>
                How do I sell an NFT?
              </h4>
              <p class="card-text">
                Our staff will first verify that the content is suitable for listing before approving the listing of an NFT for sale. Usually, this process takes one to three hours. after a successful approval process.
              </p>
            </div>
            <!-- end card-body -->
          </div>
        </div>
        <!-- end swiper-slide -->
      </div>
      <!-- end swiper-wrapper -->
      <!-- If we need pagination buttons -->
      <div class="swiper-pagination"></div>
    </div>
    <!-- end swiper -->
  </div>
  <!-- end container -->
</section>
<!-- end how-it-work-section -->
<?php

require_once 'footer.php';

?>