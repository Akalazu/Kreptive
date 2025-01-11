<?php
include_once 'header.php';
?>
<style>
  /*@import url("https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;700;800&display=swap");*/
  /** {*/
  /*  margin: 0;*/
  /*  padding: 0;*/
  /*  box-sizing: border-box;*/
  /*  font-family: "Rubik", sans-serif;*/
  /*}*/

  /*body {*/
  /*  display: flex;*/
  /*  justify-content: center;*/
  /*  align-items: center;*/
  /*  flex-wrap: wrap;*/
  /*  min-height: 100vh;*/
  /*  background: #232427;*/
  /*}*/

  /*body .container-cards {*/
  /*  display: flex;*/
  /*  justify-content: center;*/
  /*  align-items: center;*/
  /*  flex-wrap: wrap;*/
  /*max-width: 1200px;*/
  /*  margin: 40px 0;*/
  /*}*/

  body .container-cards .card {
    position: relative;
    min-width: 320px;
    height: 440px;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
    border-radius: 15px;
    /*margin: 30px;*/
    transition: 0.5s;
  }

  body .container-cards .card .box {
    position: absolute;
    top: 20px;
    left: 20px;
    right: 20px;
    bottom: 20px;
    background: #2a2b2f;
    border-radius: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    transition: 0.5s;
  }


  /*body .container-cards .card .box:before {*/
  /*  content: "";*/
  /*  position: absolute;*/
  /*  top: 0;*/
  /*  left: 0;*/
  /*  width: 50%;*/
  /*  height: 100%;*/
  /*  background: rgba(255, 255, 255, 0.03);*/
  /*}*/

  body .container-cards .card .box .content {
    padding: 20px;
    text-align: center;
  }

  body .container-cards .card .box .content h2 {
    position: absolute;
    top: -10px;
    right: 30px;
    font-size: 8rem;
    color: rgba(255, 255, 255, 0.1);
  }

  body .container-cards .card .box .content h3 {
    font-size: 1.8rem;
    color: #fff;
    z-index: 1;
    transition: 0.5s;
    margin-bottom: 15px;
  }

  body .container-cards .card .box .content p {
    font-size: 1rem;
    font-weight: 300;
    color: rgba(255, 255, 255, 0.9);
    z-index: 1;
    transition: 0.5s;
  }
</style>

<!-- end fun-fact-section -->
<section class="story-section section-space-b my-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-5 mb-md-1">
        <img src="https://img.freepik.com/free-vector/bitcoin-digital-financial-currency-with-rocket_24877-56129.jpg?size=626&ext=jpg&ga=GA1.1.964983529.1695819598&semt=ais" />
      </div>
      <!-- end col-lg-6 -->
      <div class="col-lg-6 ps-xl-5 mt-5 mt-md-1">
        <div class="about-content-wrap">
          <h2 class="mb-3">About Niftlify</h2>
          <p>
            At Niftlify, we are passionate about bringing the world of digital art to life through blockchain technology. Our platform serves as a gateway for artists, collectors, and enthusiasts to explore, create, and trade unique digital assets known as Non-Fungible Tokens (NFTs).
          </p>
          <p>
            Whether you're an artist looking to showcase your talent, a collector seeking exclusive digital assets, or simply an enthusiast eager to explore the world of NFTs, Niftlify welcomes you to join our growing community.
            <br />

            <br />
          <h2 class="mb-3">Our Mission</h2>

          Our mission is to empower artists and creators, fostering a decentralized and inclusive ecosystem for digital art. We believe in the transformative power of NFTs, providing artists with new avenues for expression and audiences with unprecedented access to authentic, limited-edition artworks.
          </p>
        </div>
      </div>
      <!-- end col-lg-6 -->
    </div>
    <!-- end row -->
  </div>
  <!-- end contaiern -->
</section>


<!-- end cta-section -->
<?php

require_once 'footer.php';

?>