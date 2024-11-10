<?php
$pageName = 'Account Settings';

include_once "portal_settings.php";

if (isset($_GET['idd'])) {
    $idd = sanitizeText($_GET['idd']);

    $user = $userCl->getUserDetails($idd);

    $full_name = $user->first_name . ' ' . $user->last_name;
}
$stat = 0;

$sql = "SELECT * FROM `all_bids` WHERE `recipient` = :rp AND `status` = :st ORDER BY `id` DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':rp', $idd);
$stmt->bindParam(':st', $stat);
$stmt->execute();
$bidRecords = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="wallet__wrapper my-5">
    <div class="outer__inner">
        <div class="container">
            <div class="row">
                <!-- end col -->

                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body table__container">

                            <div class="tab-content mt-4" id="myTabContent">
                                <div class="tab-pane fade show active" id="account-information" role="tabpanel" aria-labelledby="account-information-tab">

                                    <div class="profile-setting-panel">
                                        <h3 class="mb-5">User Profile</h3>
                                        <div class="d-flex align-items-center">
                                            <div class="image-result-area avatar avatar-3 me-2">
                                                <img id="image-result" src="../<?= $user->image ?>" alt="" />
                                            </div>
                                            <h4>
                                                <?= $full_name ?>

                                                <p class="mb-0"><?= $user->username ?> <span><i class="mdi mdi-check-circle"></i> </span></p>
                                            </h4>
                                        </div>
                                        <hr class="my-4">

                                        <div class="row">
                                            <span class="col-md-3 col-4 mb-md-3 mb-0">
                                                <h4><?= $userCl->getTotalNftPriceByUser($idd) ?> ETH</h4>
                                                <p>Total Volume</p>
                                            </span>
                                            <span class="col-md-3 col-4">
                                                <h4><?= $userCl->getLowestNftPriceByUser($idd) ?> ETH</h4>
                                                <p>Floor Price</p>
                                            </span>

                                            <span class="col-md-3 col-4" class="text-center">
                                                <h4><?= count($userCl->getAllNftsOwnedByUser($idd)) ?></h4>
                                                <p>Total Items</p>
                                            </span>

                                            <span class="col-md-3 col-6">
                                                <h4>10%</h4>
                                                <p>Creator Earnings</p>
                                            </span>
                                            <span class="col-md-3 col-6 ">
                                                <h4>Ethereum</h4>
                                                <p>Chain</p>
                                            </span>

                                        </div>
                                        <p class="mt-4 p-3" style="border: 1px dashed #56328b;">
                                            <?= $user->bio ?>
                                        </p>

                                        <!-- end row -->

                                        <div class="profile-setting-panel-wrap pt-2">
                                            <ul class="nav nav-tabs-s3 mb-2" id="myTab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#items" type="button" role="tab" aria-controls="items" aria-selected="true">
                                                        Items
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="following-tab" data-bs-toggle="tab" data-bs-target="#offers" type="button" role="tab" aria-controls="offers" aria-selected="false">
                                                        Offers
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="bidding-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab" aria-controls="activity" aria-selected="false">
                                                        Activity
                                                    </button>
                                                </li>
                                            </ul>
                                            <!-- ADMIN DEPOSIT -->
                                            <div class="tab-content mt-4" id="myTabContent">
                                                <div class="tab-pane fade show active" id="items" role="tabpanel" aria-labelledby="all-tab">
                                                    <div class="row mt-4">
                                                        <?php

                                                        $sql = "SELECT * FROM `all_nft` WHERE `owner_id` = :idd";
                                                        $statement = $pdo->prepare($sql);
                                                        $statement->bindParam(':idd', $idd);
                                                        if ($statement->execute()) {
                                                            $user_nfts = $statement->fetchAll(PDO::FETCH_OBJ);
                                                        }

                                                        foreach ($user_nfts as $user_nft) {

                                                        ?>
                                                            <div class="col-md-4 my-3 overview__item">
                                                                <div class="card card-full card-s3">
                                                                    <div class="card-author d-flex align-items-center justify-content-between pb-3">
                                                                        <div class="d-flex align-items-center">
                                                                            <a class="avatar me-1">
                                                                                <img src="../<?= $user->image ?>" />
                                                                            </a>
                                                                            <div class="custom-tooltip-wrap card-author-by-wrap">
                                                                                <span class="card-author-by card-author-by-2">Owned by</span>
                                                                                <a class="custom-tooltip author-link" href="view_profile?idd=<?= $user_nft->owner_id ?>">@<?= getUserNameById($pdo, $user_nft->owner_id) ?><span><i class="mdi mdi-check-circle"></i> </span></a>
                                                                                <!-- end dropdown-menu -->
                                                                            </div>
                                                                            <!-- end custom-tootltip-wrap -->
                                                                        </div>
                                                                    </div>
                                                                    <!-- end card-author -->
                                                                    <div class="card-image">
                                                                        <img src="../<?= $user_nft->image ?>" class="card-img" alt="art image" />
                                                                    </div>
                                                                    <!-- end card-image -->
                                                                    <div class="card-body px-0 pb-0">
                                                                        <h5 class="card-title text-truncate">
                                                                            <a><?= $user_nft->title ?></a>
                                                                        </h5>
                                                                        <div class="card-price-wrap d-flex align-items-center justify-content-between pb-3">
                                                                            <div class="me-5 me-sm-2">
                                                                                <span class="card-price-title">Price</span>
                                                                                <span class="card-price-number"><?= $user_nft->price ?> ETH</span>
                                                                            </div>
                                                                            <div class="text-sm-end">
                                                                                <span class="card-price-title">Current bid</span>
                                                                                <span class="card-price-number d-block">
                                                                                    $<?= number_format($user_nft->price * $ethereumToUsdRate) ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <!-- end card-price-wrap -->
                                                                        <form class="d-flex justify-content-center mt-3" method="POST" action="place_bid">
                                                                            <input type="hidden" name="nftqrs" value="<?= $user_nft->id ?>">
                                                                            <button class="btn btn-primary" type="submit">Place a Bid</button>
                                                                        </form>
                                                                    </div>
                                                                    <!-- end card-body -->
                                                                </div>
                                                            </div>
                                                        <?php


                                                        }

                                                        ?>
                                                        <!-- end card -->

                                                    </div>
                                                    <!-- end activity-tab-wrap -->
                                                </div>
                                                <!-- end tab-pane -->
                                                <div class="tab-pane fade" id="offers" role="tabpanel" aria-labelledby="offers-tab">
                                                    <ul class="list-group">
                                                        <?php

                                                        if ($bidRecords && count($bidRecords) > 0) {
                                                            foreach ($bidRecords as $record) {
                                                                $art = $userCl->getNFTDetailsById($record->art_id);

                                                                echo '
                                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                ' . $art->title . '
                                                                                <h5 class="my-0">' . $record->bid . ' ETH</h5>
                                                                            </li>
                                                                        ';
                                                            }
                                                        }

                                                        ?>
                                                    </ul>
                                                    <!-- end activity-tab-wrap -->
                                                </div>
                                                <!-- end tab-pane -->
                                                <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                                                    <ul class="list-group">

                                                        <?php
                                                        $sql = "SELECT * FROM `activities_db` WHERE `created_by` = :cb AND `type` = 'sales' || `type` = 'purchase' ORDER BY `id` DESC";

                                                        $statement = $pdo->prepare($sql);
                                                        $statement->bindParam(':cb', $currUser->code);
                                                        $statement->execute();
                                                        $j = 1;
                                                        while ($activity = $statement->fetch(PDO::FETCH_OBJ)) {

                                                            if ($activity->type == 'sales') {
                                                                $icon = "
                                                                        <span class='mdi mdi-inbox-arrow-up' style='text-align: center;font-size: 30px;color: white;background: green;padding: 5px 10px;border-radius: 10%;'></span>
                                                                    ";
                                                            } else {
                                                                $icon = "
                                                                        <span class='mdi mdi-inbox-arrow-down' style='text-align: center;font-size: 30px;color: white;background: #c81e1e;padding: 5px 10px;border-radius: 10%;'></span>
                                                                    ";
                                                            }

                                                            echo '
                                                                <li class="list-group-item d-flex align-items-center gap-3">
                                                                    <span>
                                                                        ' . $icon . '
                                                                    </span>
                                                                    <span>
                                                                         <h6>' . $activity->activity . '</h6>
                                                                        <p class="my-0">' . $activity->reference_id . '</p>
                                                                    </span>

                                                                </li>
                                                                 <hr>
                                                                ';
                                                            $j++;
                                                        }

                                                        ?>




                                                    </ul>

                                                    <!-- end activity-tab-wrap -->
                                                </div>
                                                <!-- end tab-pane -->

                                                <!-- end tab-pane -->
                                            </div>
                                            <!-- end tab-content -->
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- end tab-content -->
                        </div>
                    </div>
                </div>
                <!-- end profile-setting-panel-wrap-->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
    <!-- </section> -->
    <!-- end profile-section -->

</div>
<!-- end col -->
</div>
<!-- end row -->
<?php require_once 'portal_footer.php' ?>