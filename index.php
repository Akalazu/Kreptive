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
    <div class="row flex-lg-row-reverse justify-content-between align-items-center align-items-xl-start" style="flex-direction: column-reverse;
    gap: 5rem;">
      <div class="col-lg-5" data-aos="fade-left">
        <div class="swiper swiper-carousel swiper-button-s1" data-breakpoints='{
                                "0":{"slidesPerView":1,"slidesPerGroup":1}
                            }' data-loop="true" navigation="true" data-autoplay="false" data-speed="500" data-centeredslides="false">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
              <a class="card">
                <img src="https://images.unsplash.com/photo-1672911640817-d2902754be5a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTkwfHxuZnR8ZW58MHwwfDB8fHww" alt="" class="card-img-top">
                <div class="card-body d-flex align-items-center">
                  <div class="avatar flex-shrink-0">
                    <img src="images/user_icon.png" alt="">
                  </div>
                  <div class="ms-2">
                    <h5 class="card-title-effect">Space Currency</h5>
                    <span class="fs-13 text-secondary">By Kevin Martin</span>
                  </div>
                </div><!-- end card-body -->
              </a><!-- end card -->
            </div><!-- end swiper-slider -->
            <div class="swiper-slide">
              <a class="card">
                <img src="https://plus.unsplash.com/premium_photo-1672912348671-9a9ec5541b7f?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTd8fG5mdHxlbnwwfDB8MHx8fDA%3D " alt="" class="card-img-top">
                <div class="card-body d-flex align-items-center">
                  <div class="avatar flex-shrink-0">
                    <img src="images/user_icon.png" alt="">
                  </div>
                  <div class="ms-2">
                    <h5 class="card-title-effect">Sebastian Rainbow</h5>
                    <span class="fs-13 text-secondary">By Alex Smith</span>
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
          <h1 class="hero-title hero-title-s1 mb-3" style="font-family: 'Rubik', sans-serif;">Discover rare, <br> world class artworks</h1>
          <p class="hero-text mb-4 pb-1">With Kreptive' top-notch platform, launching your own white-label NFT store or NFT Marketplace is a breeze.
          </p>
          <ul class="hero-btns btns-group">
            <li><a href="explore" class="btn btn-lg btn-primary">Explore</a></li>
            <li><a href="sign-in" class="btn btn-lg btn-outline-dark">Mint an Art</a></li>
          </ul>
        </div><!-- hero-content -->
      </div><!-- col-lg-6 -->
    </div><!-- end row -->
  </div><!-- .container-->
</div>
<!-- end hero-wrap -->
</header>
<!-- end header-section -->
<section class="section-space trending-section bg-gray">
  <div class="container">
    <div class="section-head text-center">
      <h2>Latest Auctions</h2>
    </div>
    <!-- end section-head -->
    <div class="row g-gs">
      <?php
      $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 ORDER BY `id` DESC LIMIT 15";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();

      $all_nft = $stmt->fetchAll(PDO::FETCH_OBJ);

      foreach ($all_nft as $nft) {


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
                  <a class="custom-tooltip author-link" href="user-profile?usrp=<?= $nft->owner_id ?>">@<?= getUserNameById($pdo, $nft->owner_id) ?><span><i class="mdi mdi-check-circle"></i> </span></a>
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
      <small class="mt-2">Get on-the-go with kreptive by doing the following</small>
    </div>
    <!-- end section-head -->
    <!-- <div class="row g-gs">
      <div class="col-lg-4">
        <div class="card card-hiw card-hiw-s3 card-features">
          <div class="card-body">
    <em class="icon icon-circle icon-wbg shadow-sm me-2" style="background-color: #1c2b46; height: 70px; width: 70px; ">
      <svg xmlns=" http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4" style="color: #fff; height: 50px; width: 50px">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
      </svg>

    </em> <br>
    </h4>
    <h3 class="my-3">Seamless UI/UX</h3>
    <p class="card-text">
      Our web app prioritizes a seamless and intuitive user experience (UX) along with a visually appealing user interface (UI) becomes a sought-after destination for our visitors
    </p>
  </div>
  </div>
  </div>
  <div class="col-lg-4">
    <div class="card card-hiw card-hiw-s3 card-features">
      <div class="card-body">
        <h4 class="mb-3 card-title">
          <em class="icon icon-circle icon-wbg shadow-sm me-2" style="background-color: #1c2b46; height: 70px; width: 70px; ">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6" style="color: #fff; height: 50px; width: 50px">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
            </svg>


          </em> <br>
        </h4>
        <h3 class="my-3">Buy & Sell NFT with Ease</h3>
        <p class="card-text">
          Step into the world of non-fungible tokens (NFTs) like never before with Kreptive. We've streamlined the process, making it simpler than ever to buy and sell NFTs, unlocking a world of unique digital assets and creative possibilities.
        </p>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card card-hiw card-hiw-s3 card-features">
      <div class="card-body">
        <h4 class="mb-3 card-title">
          <em class="icon icon-circle icon-wbg shadow-sm me-2" style="background-color: #1c2b46; height: 70px; width: 70px; ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 style=" style="color: #fff; height: 50px; width: 50px">
              <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
            </svg>

          </em> <br>
        </h4>
        <h3 class="my-3">24/7 Customer Service</h3>
        <p class="card-text">
          At Kreptive, we take pride in offering you a unique advantage that sets us apart from the competition: our 24/7 customer support. This means that no matter when you need assistance, day or night, our dedicated support team is here to help
        </p>
      </div>

    </div>
  </div>

  </div> -->
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
                What is Kreptive NFT Marketplace?
              </h4>
              <p class="card-text">
                Artists, creators, and cryptocurrency enthusiasts come together on Kreptive NFT Marketplace to collectively create and trade premium NFTs.
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