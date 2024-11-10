<?php
require_once 'header.php';


if (isset($_GET['address'])) {
    $token = sanitizeText($_GET['address']);
    if ($nft_details = $userCl->getNFTDetailsByToken($token)) {
        $owner = $userCl->getUserDetails($nft_details->owner_id);
        $author = $userCl->getUserDetails($nft_details->author_id);
    } else {
        header('Location: ./');
    }
}


if (isset($_POST['place_bid'])) {
    $nftt_link = sanitizeName($_POST['link_id']);

    $_SESSION['nft_link'] = $nftt_link;

    if ($_SESSION['nft_link']) {
        header('Location: sign-in');
    }
}
?>
<div class="card py-4 px-2" style="  min-height: 100%;">
    <div class="content-body px-md-5 mb-5 mt-4">
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
                                        <div class="col-md-6">
                                            <img src="<?= $nft_details->image ?>" class="img-fluid img-thumbnail rounded" alt="..." />
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
                                                        Current Bid
                                                    </h4>
                                                    <h5 class="text-muted"><?= $nft_details->price   ?> ETH</h5>
                                                </div>
                                            </div>
                                            <!-- <h4 class="card-title mb-3">Latest Bids</h4> -->
                                            <div class="bid mb-3 card">
                                                <div class="activity-content py-0">
                                                    <ul>
                                                        <li class="d-flex justify-content-between align-items-center px-4 pt-4">

                                                            <div class="d-flex align-items-center">
                                                                <img src="<?= $author->image ?>" alt="" width="50" />

                                                                <div class="activity-info ml-2">
                                                                    <h6 class="mb-0"><?= $author->first_name . ' ' . $author->last_name ?></h5>
                                                                        <p class="mb-0">@<?= $author->username ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="text-muted">Creator</span>
                                                            </div>
                                                        </li>
                                                        <li class="d-flex justify-content-between align-items-center mt-4 px-4 pb-5 pt-3">

                                                            <div class="d-flex align-items-center">

                                                                <img src="<?= $owner->image ?>" alt="" width="50" />
                                                                <div class="activity-info ml-2">

                                                                    <h6 class="mb-0"><?= $owner->first_name . ' ' . $owner->last_name ?></h6>
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
                                            <form method="post">
                                                <label for="bidding_value">
                                                    <h5 class="mb-0">NFT Link</h5>
                                                </label>
                                                <input type="number" name="nft_id" id="nft_id" class="form-control my-2" value="<?= $nft_details->id ?>" hidden>
                                                <div style="position:relative">
                                                    <input type="text" name="nft_link" id="nft_link" class="form-control my-2 p-4" value="<?= $nft_details->link ?>" style="font-size: 14px;" readonly>

                                                    <input type="text" name="link_id" id="link_id" class="form-control my-2 p-4" value="<?= $nft_details->link_id ?>" style="font-size: 14px;" hidden>

                                                    <i class="fa fa-copy" style="    bottom: 20px;position: absolute;top: 10px;right: 13px;background: white;height: 30px;width: 30px;text-align: center;display: flex;justify-content: center;align-items: center;border-radius: 50%;" onclick="copyText()"></i>
                                                </div>

                                                <div class=" row mt-4">
                                                    <div class="col-md-6">
                                                        <button class="btn btn-primary btn-block btn-lg mb-2 p_bid" type="submit" name="place_bid" id="<?= $nft_details->link_id ?>">Place Bid</button>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <a class="btn btn-dark btn-block btn-lg" type="button" href="./">Go Back</a>
                                                    </div>
                                                </div>

                                            </form>
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

require_once 'footer.php';

?>