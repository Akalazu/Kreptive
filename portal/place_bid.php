<?php
require_once 'portal_settings.php';


$url_str = $_SERVER['HTTP_REFERER'];
// if (!str_contains($url_str, 'dashboard') && !str_contains($url_str, 'item') && !str_contains($url_str, 'user_profile')) {
//     header('Location: logout');
// }


if (isset($_SESSION['nft_link'])) {
    $token = $_SESSION['nft_link'];
    $nftDetails = $userCl->getNFTDetailsByToken($token);

    if ($nftDetails) {
        $nft_id = $nftDetails->id;
        $_SESSION['nft_id'] = $nft_id; // Save to session for persistence
    } else {
        header('location: ./');
        exit();
    }
} else if (isset($_GET['nftqrs'])) {
    $nft_id = $_SESSION['nftqrs'] = $_GET['nftqrs'];
}

// Now check if the session has a valid NFT ID
if (!isset($_SESSION['nftqrs']) || empty($_SESSION['nft_id'])) {
    echo "Session has invalid";
    die();
}

$nft_details = $userCl->getNFTDetailsByToken($nft_id);

$bidRecords = $userCl->getAllBidsForArt($nft_details->id);

$userBidStatus = $userCl->checkIfUserPlacedBidForArt($currUser->id, $nft_details->id);

$owner = $userCl->getUserDetails($nft_details->owner_id);
$author = $userCl->getUserDetails($nft_details->author_id);

$recipient_full = $owner->first_name . ' ' . $owner->last_name;



if (isset($_POST['place_bid'])) {
    $nftt_id = $_POST['nft_id'];
    $nft_det = $userCl->getNFTDetailsById($nftt_id);

    $nft_price = $_POST['nft_price'];
    $bidder = $currUser->id;
    $bid_price = $_POST['bidding_value'];
    $owner_id = $_POST['owner_addr'];

    $owner_details = $userCl->getUserDetails($owner_id);

    if ($bid_price < $nft_price) {
        echo '
            <script>
                swal({
                    title: "Error",
                        text: "Bidding price must be higher than the current price" ,
                        icon: "warning",
                    button: "Ok",
                    })
            </script>
              ';
    } else if ($currUser->balance - $bid_price < 0) {
        echo '
                        <script>
            swal({
                   title: "Error",
                      text: "You have insufficient fund to place this bid" ,
                      icon: "warning",
                  button: "Ok",
                })
            </script>
              ';
    } else {
        $tyme = time();
        $bid_time = date("d-m-Y h:ia", $tyme);
        $status = 0;

        $query = "INSERT INTO `all_bids`(`bidder_id`, `recipient`, `recipient_old`, `bid`, `recipient_new`, `art_id`, `status`, `time`) VALUES (:bi, :rc, :rold, :bd, :rnew, :ai, :st, :tt) ";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':bi', $bidder);
        $statement->bindParam(':rc', $owner_id);
        $statement->bindParam(':rold', $owner_details->profit);
        $statement->bindParam(':bd', $bid_price);
        $statement->bindParam(':rnew', $status);
        $statement->bindParam(':ai', $nftt_id);
        $statement->bindParam(':st', $status);
        $statement->bindParam(':tt', $bid_time);

        // echo $nft_details->title;
        // die();
        if ($statement->execute() && $userCl->sendBidderMail($fullname, $currUser->email, $nft_det->title, $bid_price) && $userCl->sendRecipientMail($recipient_full, $owner->email, $nft_det->title, $bid_price)) {
            // if ($statement->execute()) {
            echo '

         <script>
            swal({
                   title: "Success",
                      text: "Your bid has been successully placed" ,
                      icon: "success",
                  button: "Ok",
                }).then(function() {
             window.location.href = "./";
            });
        </script>

              ';
        } else {
            echo '<script>
      swal({
            title: "Oops!",
            text: "An error occured, Please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      ';
        }
    }
}



?>

<div class="card py-4 px-2" style="  min-height: 100%;">
    <div class="content-body px-md-5">
        <div class="container">
            <div class="page-title">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-12">
                            <div class="page-title-content">
                                <h1 style="margin: 20px 0 40px 0; font-weight: 700">Item Overview</h1>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="item-single">
                <div class="container">
                    <div class="row">
                        <div class="col-xxl-12">
                            <div class="top-bid">
                                <div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <img src="../<?= $nft_details->image ?>" class="img-fluid img-thumbnail rounded mb-0 mb-sm-3" alt="..." />
                                        </div>
                                        <div class="col-md-6">
                                            <h2 class="mb-3" style="font-weight: 700;"><?= $nft_details->title ?></h2>

                                            <p class="my-3">
                                                <?= $nft_details->description ?>
                                            </p>
                                            <div class="d-flex justify-content-between mt-4 mb-4">
                                                <div class="text-start">
                                                    <h4 class="mb-2">Price</h4>
                                                    <h5 class="text-muted">$<?= number_format($nft_details->price * $ethereumToUsdRate) ?></h5>
                                                </div>

                                                <div class="text-end">
                                                    <h4 class="mb-2">
                                                        Floor Price
                                                    </h4>
                                                    <h5 class="text-muted"><?= $nft_details->price   ?> ETH</h5>
                                                </div>
                                            </div>
                                            <!-- <h4 class="card-title mb-3">Latest Bids</h4> -->
                                            <div class="bid mb-3 card">
                                                <div class="activity-content py-0">
                                                    <ul>
                                                        <li class="d-flex justify-content-between align-items-center">

                                                            <div class="d-flex align-items-center">
                                                                <img src="../<?= $author->image ?>" class="me-2" alt="" width="50" />

                                                                <div class="activity-info">
                                                                    <h5 class="mb-0"><?= $author->first_name . ' ' . $author->last_name ?></h5>
                                                                    <p class="mb-0">@<?= $author->username ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="text-muted">Creator</span>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex justify-content-between align-items-center mt-4">

                                                            <div class="d-flex align-items-center">

                                                                <img src="../<?= $owner->image ?>" class="me-2" alt="" width="50" />
                                                                <div class="activity-info">
                                                                    <h5 class="mb-0"><?= $owner->first_name . ' ' . $owner->last_name ?></h5>
                                                                    <p class="mb-0">@<?= $owner->username ?></p>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <span class="text-muted">Owner</span>
                                                            </div>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>

                                            <?php

                                            if (!$userBidStatus) {
                                            ?>
                                                <form method="post">
                                                    <label for="bidding_value">
                                                        <h5 class="mb-0">Place a Bid</h5>
                                                    </label>
                                                    <input type="number" name="nft_id" id="nft_id" class="form-control my-2" value="<?= $nft_details->id ?>" hidden>
                                                    <input type="number" name="nft_price" id="nft_price" class="form-control my-2" value="<?= $nft_details->price ?>" hidden>
                                                    <input type="number" name="owner_addr" id="owner_addr" class="form-control my-2" value="<?= $nft_details->owner_id ?>" hidden>
                                                    <input type="number" name="bidding_value" id="bidding_value" class="form-control my-2" placeholder="Price in ETH" step="0.00001" required>

                                                    <div class="row">
                                                        <div class="col-md-6">

                                                            <button class="btn btn-primary btn-block btn-lg mb-2" type="submit" name="place_bid">Place Bid</button>
                                                        </div>
                                                        <div class="col-md-6">

                                                            <button class="btn btn-dark btn-block btn-lg" type="button" onclick="goBack()">Go Back</button>
                                                        </div>


                                                    </div>

                                                </form>
                                            <?php
                                            }
                                            ?>


                                            <div class="col-12">
                                                <ul class="list-group">
                                                    <?php

                                                    if ($bidRecords && count($bidRecords) > 0) {
                                                        echo '
                                                        <h4 class="my-3">Live Aunctions</h4>
                                                        ';
                                                        foreach ($bidRecords as $record) {
                                                            $bidder = $userCl->getUserDetails($record->bidder_id);

                                                            $bidderName = $bidder->first_name . ' ' . $bidder->last_name;

                                                            echo '
                                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                    ' . $bidderName . '
                                                                    <span class="badge badge-primary badge-pill">' . $record->bid . ' ETH</span>
                                                                </li>
                                                            ';
                                                        }
                                                    }

                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>



<?php
require_once 'portal_footer.php';
?>