<?php
require_once 'header.php';

if (isset($_GET['address'])) {
    $userID = sanitizeText($_GET['address']);
    $user_details = $userCl->getUserDetailsByWalletAddress($userID);

    if ($user_details && strtolower($user_details->first_name) == strtolower($_GET['first_name'])) {
        $fullName = $user_details->first_name . ' ' . $user_details->last_name;
    } else {
        echo '
            <script>
                window.location.href = "./";
            </script>
            ';
    }
}

$sql = "SELECT * FROM `all_bids` WHERE `recipient` = :rp AND `status` = :st ORDER BY `id` DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':rp', $user_details->id);
$stmt->bindParam(':st', $stat);
$stmt->execute();
$bidRecords = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
<div class="card py-4 px-2" style="  min-height: 100%;">
    <div class="content-body px-md-5 mb-5 mt-4">
        <div class="container">
            <div class="page-title d-inline">
                <div class="d-flex align-items-center gap-3">
                    <span>
                        <img src="<?= $user_details->image ?>" alt="user-photo" class="img-thumbnail" width="60">
                    </span>

                    <div class="page-title-content">
                        <h4>
                            <?= $fullName ?>
                        </h4>
                        <p><?= $user_details->username ?> <span><i class="mdi mdi-check-circle"></i> </span></p>
                    </div>
                </div>
                <hr class="my-4">

                <div class="user_info d-flex my-4" style="gap: 50px;">
                    <span>
                        <h3><?= $user_details->total_volume == 0 ? $userCl->getTotalNftPriceByUser($user_details->id) : $user_details->total_volume ?> ETH</h3>
                        <p>Total Volume</p>
                    </span>
                    <span>
                        <h3><?= $userCl->getLowestNftPriceByUser($user_details->id) ?> ETH</h3>
                        <p>Floor Price</p>
                    </span>

                    <span class="text-center">
                        <h3><?= count($userCl->getAllNftsOwnedByUser($user_details->id)) ?></h3>
                        <p>Total Items</p>
                    </span>
                </div>
                <p class="mt-4 p-3" style="border: 1px dashed #56328b;">
                    <?= $user_details->bio ?>
                </p>

                <div class="profile-setting-panel-wrap pt-2 mt-5">
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
                                $statement->bindParam(':idd', $user_details->id);
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
                                                        <img src="../<?= $user_details->image ?>" />
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
                                                <form class="d-flex justify-content-center mt-3" method="POST" action="sign-in">
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
                                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent">
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
                                $sql = "SELECT * FROM `activities_db` WHERE `created_by` = :cb AND (`type` = 'sales' OR `type` = 'purchase') ORDER BY `id` DESC";

                                $statement = $pdo->prepare($sql);
                                $statement->bindParam(':cb', $user_details->code);
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
                                        <li class="list-group-item d-flex align-items-center gap-3 py-4 bg-transparent">
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
</div>

<?php

require_once 'footer.php';

?>