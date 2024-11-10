<?php


$pageName = 'view NFT';

include_once "portal_settings.php";
$user = $userCl->getUserDetails($idd);

$currUserr = $user->first_name . ' ' . $user->last_name;

if (isset($_POST['nftqrs'])) {
    $_SESSION['nftqrs'] = $_POST['nftqrs'];
}else{
    header('location: ./');
}

$nft_id = $_SESSION['nftqrs'];
$nft_details = $userCl->getNFTDetailsById($nft_id);

$owner = $userCl->getUserDetails($nft_details->owner_id);
$author = $userCl->getUserDetails($nft_details->author_id);

$recipient_full = $owner->first_name . ' ' . $owner->last_name;

if (isset($_POST['save_nft_changes'])) {
    $id = $_POST['nft_id'];
    $title = sanitizeText($_POST['nft_title']);
    $price = sanitizeText($_POST['nft_price']);
    $collection = sanitizeText($_POST['nft_collection']);
    $description = sanitizeText($_POST['nft_description']);

    $sql = "UPDATE `all_nft` SET `title`= :tt,`price`= :pr, `collection` = :cc, `description` =:dd WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    $statement->bindParam(':tt', $title);
    $statement->bindParam(':pr', $price);
    $statement->bindParam(':cc', $collection);
    $statement->bindParam(':dd', $description);

    if ($statement->execute()) {
        echo '
      <script>
      swal({
            title: "Successful",
            text: "NFT details have been updated",
            icon: "success",
            button: "Loading...",
          });
      </script>';

        header("Refresh: 2; url=my_collections");
    } else {
        $error =  '
      <script>
      swal({
            title: "Error!",
            text: "An error occured, please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
        echo $error;
    }
}


$url = $_SERVER['REQUEST_URI'];
if (!str_contains($url, 'view_nft.php') && isset($_SESSION['product_id'])) {
    $productId = $_SESSION['product_id'];

    $sql = "SELECT * FROM `all_nft` WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $productId);
    if ($statement->execute()) {
        $nft_details = $statement->fetch(PDO::FETCH_OBJ);
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
                                        <div class="col-md-6">
                                            <img src="../<?= $nft_details->image ?>" class="img-fluid rounded" alt="..." />
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
                                                        <li class="d-flex justify-content-between align-items-center">

                                                            <div class="d-flex align-items-center">
                                                                <img src="../<?= $author->image ?>" alt="" width="50" />

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

                                                                <img src="../<?= $owner->image ?>" alt="" width="50" />
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
                                            <form method="post">
                                                <label for="bidding_value">
                                                    <h5 class="mb-0">NFT Link</h5>
                                                </label>
                                                <input type="number" name="nft_id" id="nft_id" class="form-control my-2" value="<?= $nft_details->id ?>" hidden>
                                                <div style="position:relative">
                                                    <input type="text" name="nft_link" id="nft_link" class="form-control my-2" value="<?= $nft_details->link ?>" style="font-size: 11px;" readonly>
                                                    <i class="fa fa-copy" style="bottom: 17px;position: absolute;right: 14px;" onclick="copyText()"></i>
                                                </div>

                                                <div class=" row">
                                                    <div class="col-md-6">

                                                        <button class="btn btn-primary btn-block btn-lg mb-2 edit__btn" type="button" name="place_bid" data-bs-toggle="modal" data-bs-target="#edit-nft" id="<?= $nft_details->id ?>">Edit</button>
                                                    </div>
                                                    <div class="col-md-6">

                                                        <button class="btn btn-dark btn-block btn-lg" type="button" onclick="goBack()">Go Back</button>
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


<div class="modal fade" id="edit-nft" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Edit NFT Details</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body" id="modal-bodyy">


            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>


</div>
<script>
    $('.edit__btn').click(function() {
        $('#modal-bodyy').html('');
        var nft_id = $(this).attr("id");
        // console.log(nft_id);
        $.ajax({
            url: "getdata.php",
            method: "POST",
            data: {
                nft_id: nft_id
            },
            success: function(data) {
                $('#modal-bodyy').html(data);
                // $('.popup').removeAttr("mfp-hide");
            }
        });
    });
</script>

<?php
include_once 'portal_footer.php';
?>