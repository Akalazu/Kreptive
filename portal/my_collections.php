<?php
$pageName = 'my collections';

include_once "portal_settings.php";
$user = $userCl->getUserDetails($idd);

$currUserr = $user->first_name . ' ' . $user->last_name;

if (isset($_POST['send_proof'])) {
    if ($_FILES['img_upload']['name'] != '') {

        $idd = $_SESSION['currid'];
        $refId =  sanitizeText($_POST['reference_id']);
        $amount = $_POST['amount'];
        $method = sanitizeText($_POST['method']);
        $charge = $_POST['charges'];
        $tyme = time();
        $time_created = date("d-m-Y h:ia", $tyme);

        $sql = "INSERT INTO `account_deposit`(`reference_id`, `amount`, `method`, `charge`, `date_created`, `depositor`) VALUES (:ri, :am, :mt, :ch, :dc, :dp) ";

        $statement = $pdo->prepare($sql);
        $statement->bindParam(':ri', $refId);
        $statement->bindParam(':am', $amount);
        $statement->bindParam(':mt', $method);
        // $statement->bindParam(':st', $refId);
        $statement->bindParam(':ch', $charge);
        $statement->bindParam(':dc', $time_created);
        // $statement->bindParam(':dv', $refId);
        $statement->bindParam(':dp', $idd);

        if ($statement->execute() &&  $activityCl->userDeposit($currUser->code, $refId, $method, $amount)) {
            unset($_SESSION['amount_deposit']);
            echo '
           <script>
         swal({
               title: "Successful",
               text: "A sum of ETH ' . $amount . ' would be added to your account once payment is verified",
               icon: "success",
               button: "Redirecting...",
             });
         </script>
     ';
            header('refresh: 2; fund_account');
        } else {
            echo '
             <script>
           swal({
                  title: "Error",
                     text: "Account was not funded" ,
                     icon: "error",
                 button: "Loading...",
               });
           </script>
             ';
        }
    } else {
        echo '
             <script>
           swal({
                  title: "Error",
                     text: "Kindly Upload an image as proof of payment" ,
                     icon: "error",
                 button: "Ok",
               });
           </script>
             ';
    }
}
?>


<div class="card" style="min-height: 100%; margin: 0 12px">
    <div class="card-body table__container">
        <h6 class="text-center"><b>Collectibles for Sale</b></h6>
        <div class="row">

            <?php
            $sql = "SELECT * FROM `all_nft` WHERE `owner_id` = :ai AND `status` = 1 ORDER BY `id` DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':ai', $currUser->id);
            $stmt->execute();

            $j = 1;
            while ($nft = $stmt->fetch(PDO::FETCH_OBJ)) {
            ?>

                <div class="col-md-4 my-3">
                    <div class="card card-full card-s3">
                        <div class="card-author d-flex align-items-center justify-content-between pb-3">
                            <div class="d-flex align-items-center">
                                <a class="avatar me-1">
                                    <img src="../<?= $userCl->getAuthorImage($nft->owner_id) ?>" />
                                </a>
                                <div class="custom-tooltip-wrap card-author-by-wrap">
                                    <span class="card-author-by card-author-by-2">Owned by</span>
                                    <a class="custom-tooltip author-link">@<?= getUserNameById($pdo, $nft->owner_id) ?><span><i class="mdi mdi-check-circle"></i> </span></a>
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
                            <form class="d-flex justify-content-center mt-3" method="POST" action="view_nft">
                                <input type="hidden" name="nftqrs" value="<?= $nft->id ?>">
                                <button class="btn btn-primary" type="submit" id="<?= $nft->id ?>">View NFT</button>
                            </form>
                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->
                </div>



            <?php
            }
            ?>
        </div>

    </div>
</div>



<!-- end footer-section -->
<!-- end page-wrap -->
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>


<script>
    $('.bttt').click(function() {
        $('.modal-body').html('');
        var nftt_id = $(this).attr("id");
        $.ajax({
            url: "get_product_data.php",
            method: "POST",
            data: {
                art_Id: nftt_id
            },
            success: function(data) {
                $('.modal-body').html(data);
                // $('.popup').removeAttr("mfp-hide");
            }
        });
    });
</script>
<?php require_once 'portal_footer.php' ?>