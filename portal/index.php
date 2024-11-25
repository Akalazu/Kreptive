<?php

$pageName = 'dashboard';
require_once 'portal_settings.php';
$userCl->updateRoyalties();

$sql = "SELECT * FROM `all_nft` ";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$allcounts = $stmt->rowCount();
$all = $stmt->fetchAll(PDO::FETCH_OBJ);

foreach ($all as $alls) {
    if (str_contains($alls->link, 'mintartes')) {
        $new_link = str_replace('mintartes', 'kreptive', $alls->link);

        $sql = "UPDATE `all_nft` SET `link` = :ll";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':ll', $new_link);
        $statement->execute();
    }
}
// $userCl->updateNFTLinks();

?>
<style>
    .card-s3 {
        padding: 30px;
        justify-content: center;
    }

    .bal-cards {
        height: 170px;
        border: 2px solid rgba(13, 110, 253, 0.25);
        box-shadow: 0px 32px 90px 0px rgba(26, 64, 137, 0.12);
        /* font-family: "Inter", sans-serif; */
        margin-bottom: 40px;
    }

    @media (max-width: 650px) {
        .bal-cards {
            height: 230px;
        }

        .card-s3 {
            padding: 12px;
        }
    }

    .hero-action-wrap {
        flex-wrap: wrap;
        gap: 30px;
    }

    .button {
        padding: 15px 30px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Deposit Button */
    .button.deposit {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
    }

    .button.deposit:hover {
        background: linear-gradient(135deg, #0072ff, #00c6ff);
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 114, 255, 0.3);
        color: #fff;

    }

    /* Withdraw Button */
    .button.withdraw {
        background: linear-gradient(135deg, #9a55ff, #ff4b2b);
    }

    .button.withdraw:hover {
        background: linear-gradient(135deg, #ff4b2b, #ff416c);
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(255, 75, 43, 0.3);
        color: #fff;
    }

    span.coin_img img {
        width: 50px;
    }

    .crypto_name {
        background-color: #1b1b1b69;
        color: #f2f2f2;
        border-radius: 4px;
        padding: 3px 5px;
        font-size: 12px;
    }

    .custom-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        /* equivalent to bg-orange-300 */
    }

    .custom-dot-pending {
        background-color: #fdba74;
    }

    .custom-dot-success {
        background-color: #22c55e;
    }

    .custom-dot-failed {
        background-color: #c81e1e;
    }

    .mobile-font {
        font-size: 14px;
    }
</style>
<div class="row">
    <div class="col-12 grid-margin">

        <div class="card">
            <div class="container">
                <div class="row py-5">

                    <div class="col-md-12 mb-3 mb-md-0">
                        <div class="card card-full card-s3 bal-cards">
                            <div class="hero-action-wrap d-flex justify-content-between align-items-center">
                                <!-- <a href="account.html" class="btn btn-light">Profit</a> -->
                                <span>
                                    <p class="hero-author-username mb-1 ">Account Balance</p>
                                    <h2 class="amount__value d-flex align-items-center"><span class="curr_amount" data-val="<?= $currUser->balance * $ethereumToUsdRate + ($currUser->profit * $ethereumToUsdRate) ?>">-</span> </h2>
                                </span>

                                <div class="d-flex align-items-center gap-3">
                                    <a class="button deposit" href="deposit">Deposit</a>
                                    <a class="button withdraw" href="payout">Withdraw</a>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card card-full card-s3 bal-cards" style="height: 250px;">
                            <div class="hero-action-wrap ">

                                <h5 class="my-4" style="color: #c7dbff;font-weight: 800;">Crypto</h5>
                                <div class="coins d-flex justify-content-between align-items-center gap-3 mb-4">
                                    <span class="coin-info d-flex align-items-center gap-2">
                                        <span class="coin_img">
                                            <img src="../assets/images/ethereum.svg" alt="">
                                        </span>

                                        <span>
                                            <p class="mb-1">ETH <span class="crypto_name">Ethereum</span></p>
                                            <span class="dollar__equi">$<?= number_format($currUser->balance * $ethereumToUsdRate) ?></span>
                                        </span>
                                    </span>

                                    <div class="coin-price">
                                        <span class="dollar__equi"><?= $currUser->balance ?> ETH</span>
                                    </div>
                                </div>

                                <hr style="background-color: #c7dbff;">

                                <div class="coins d-flex justify-content-between align-items-center gap-3 mb-4">
                                    <span class="coin-info d-flex align-items-center gap-2">
                                        <span class="coin_img">
                                            <img src="../assets/images/arbi.svg" alt="">
                                        </span>

                                        <span>
                                            <p class="mb-1">ETH <span class="crypto_name">Arbitrum</span></p>
                                            <span class="dollar__equi">$<?= number_format($currUser->profit * $ethereumToUsdRate) ?></span>
                                        </span>
                                    </span>

                                    <div class="coin-price">
                                        <span class="dollar__equi"><?= $currUser->profit ?> ETH</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="card card-full card-s3 bal-cards" style="height: fit-content; padding: 0 22px">
                            <div class="hero-action-wrap ">

                                <span class="d-flex justify-content-between align-items-center">
                                    <h5 class="my-4" style="color: #c7dbff;font-weight: 800;">Recent Transactions</h5>

                                    <a href="activity" class="btn btn-link" style="font-size: 14px;">View All</a>
                                </span>

                                <?php


                                // print_r($userCl->getRecentTransactions($currUser->id));
                                $transactions = $userCl->getRecentTransactions($currUser->id);

                                if ($transactions) {

                                    foreach ($transactions as $transaction) {


                                        if ($transaction['type'] == 'withdraw' || $transaction['type'] == 'Brokeage Fee') {
                                            $sign = "-";
                                            $icon = "mdi-inbox-arrow-up";
                                            $color = "#e84f11";
                                        } else {
                                            $sign = "+";
                                            $icon = "mdi-inbox-arrow-down";
                                            $color = "green";
                                        }

                                        if (isset($transaction['date_created'])) {
                                            $date = $transaction['date_created'];
                                        } else if (isset($transaction['time_withdrawn'])) {
                                            $date = $transaction['time_withdrawn'];
                                        } else {
                                            $date = $transaction['time_created'];
                                        }

                                        if ($transaction['status'] == 0) {
                                            $dot = 'custom-dot-pending';
                                            $status = 'Pending';
                                        } else if ($transaction['status'] == 1) {
                                            $dot = 'custom-dot-success';
                                            $status = 'Success';
                                        } else {
                                            $dot = 'custom-dot-failed';
                                            $status = 'Failed';
                                        }

                                        $brokage = '<span class="badge badge-secondary ms-2 text-white" style="font-size: 10px"> Required </span>';

                                ?>
                                        <div class="coins d-flex justify-content-between align-items-center gap-3 mb-4">
                                            <span class="coin-info d-flex align-items-center gap-2">
                                                <span class="coin_img">
                                                    <span class="mdi <?= $icon ?>" style=" text-align: center;font-size: 22px;color: white;background: <?= $color ?>;padding: 5px 10px;border-radius: 10%;"></span>
                                                </span>



                                                <span>
                                                    <p class="mb-1 mobile-font"><?= ucfirst($transaction['type']) == 'Brokeage Fee' ? ucfirst($transaction['type']) . $brokage : ucfirst($transaction['type']) ?></p>
                                                    <span class="dollar__equi" style="font-size: 12px"><?= $date ?></span>
                                                </span>
                                            </span>

                                            <div class="coin-price">
                                                <span class="dollar__equi"><?= $sign . $transaction['amount'] ?> ETH</span>

                                                <span class="d-flex align-items-center gap-1 mobile-font">
                                                    <div class="custom-dot <?= $dot ?>"> </div><?= $status ?>
                                                </span>

                                            </div>
                                        </div>



                                        <hr style="background-color: #c7dbff;">

                                <?php
                                    }
                                } else {
                                    echo '<div class="alert alert-secondary" role="alert">No recent transactions found.</div>';
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="profile-setting-panel-wrap">
                <div class="tab-content mt-4" id="myTabContent">
                    <div class="tab-pane fade show active" id="offers-receive" role="tabpanel" aria-labelledby="offers-receive-tab">
                        <div class="profile-setting-panel" style="padding: 10px 30px;">
                            <!-- end alert -->
                            <!-- <h3 class="mb-1">Your Received Offers:</h3> -->
                            <!-- <div id="google_translate_element" style="margin-bottom: 10px; margin-top: -25px;"></div> -->

                            <p>The NFT Marketplace</p>
                            <div class="row">
                                <?php
                                $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 AND `owner_id` != :oi ORDER BY `id` DESC LIMIT 15";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':oi', $currUser->id);
                                $stmt->execute();

                                $all_nft = $stmt->fetchAll(PDO::FETCH_OBJ);

                                foreach ($all_nft as $nft) {


                                ?>
                                    <div class="col-md-4 my-3 overview__item">
                                        <div class="card card-full card-s3">
                                            <div class="card-author d-flex align-items-center justify-content-between pb-3">
                                                <div class="d-flex align-items-center">
                                                    <a class="avatar me-1">
                                                        <img src="../<?= $userCl->getAuthorImage($nft->author_id) ?>" />
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
                                                <img src="../<?= $nft->image ?>" class="card-img" alt="art image" />
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
                                                <form class="d-flex justify-content-center mt-3" method="GET" action="place_bid">
                                                    <input type="hidden" name="nftqrs" value="<?= $nft->link_id ?>">
                                                    <button class="btn btn-primary" type="submit">Place a Bid</button>
                                                </form>
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
                            <div class="d-flex justify-content-center my-4 mb-5">
                                <input type="hidden" id="row" value="0">
                                <input type="hidden" id="all" value="<?= $allcounts; ?>">
                                <input type="hidden" id="usdrate" value="<?= $ethereumToUsdRate ?? 1; ?>">
                                <button class="btn btn-dark load_more mb-2">
                                    <span class="text">
                                        Load More
                                    </span>
                                </button>
                            </div>
                        </div>


                    </div>
                    <!-- end tab-pane -->

                    <!-- end tab-pane -->
                </div>
                <!-- end tab-content -->
            </div>

        </div>
    </div>

</div>
<?php
include_once 'portal_footer.php';
?>