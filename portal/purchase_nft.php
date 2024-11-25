<?php
$pageName = 'purchase NFT';

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


<div class="container">

    <div class="col-lg-12">

        <?php

        if (isset($_POST['search_item'])) {
            $input = $_POST['input_value'];

            $inputF = '%' . $input . '%'; // For partial matches

            $sql = "SELECT * FROM `all_nft` WHERE (`title` LIKE :tt OR `owner_username` LIKE :ou) AND `owner_id` != :ai AND `status` = 1";
            // $sql = "SELECT * FROM `all_nft` WHERE `title` LIKE :tt AND `author_id` != :ai AND `status` = 1";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':tt', $inputF);
            $stmt->bindParam(':ou', $inputF);
            $stmt->bindParam(':ai', $currUser->id);

            $stmt->execute();

            $j = 1;
        } else {

            $sql = "SELECT * FROM `all_nft` WHERE `owner_id` != '$currUser->id' && `status` = 1 ORDER BY `id` DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $j = 1;
        }

        // $j = 2;

        ?>
        <div class="row">

            <div class="card" style="  min-height: 100%;">
                <div class="card-body table__container">
                    <h6 class="text-center"><b>Collectibles for Sale</b></h6>
                    <div class="row">

                        <form action="" method="post">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="SEARCH BY TITLE OR USERNAME" name="input_value">

                                <div class="input-group-append">
                                    <button class="btn btn-dark" style="border-radius: 0px; height: 100%" name="search_item"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </form>

                        <?php

                        $j = 1;
                        $allNfts = $stmt->fetchAll(PDO::FETCH_OBJ);

                        if (count($allNfts) < 1) {
                            echo '
                        <script>
                        swal({
                            title: "Oops!",
                            text: "No data was found, please try again",
                            icon: "warning",
                            button: "Ok",
                            }).then(()=>{
                            window.location.href = "purchase_nft"
                            });
                        </script>
                        ';
                        } else {

                            foreach ($allNfts as $nft) {
                        ?>

                                <div class="col-md-4 my-3">
                                    <div class="card card-full card-s3">
                                        <div class="card-author d-flex align-items-center justify-content-between pb-3">
                                            <div class="d-flex align-items-center">
                                                <a class="avatar me-1">
                                                    <img src="../<?= $userCl->getAuthorImage($nft->author_id) ?>" />
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
                                            <form class="d-flex justify-content-center mt-3" method="GET" action="place_bid">
                                                <input type="hidden" name="nftqrs" value="<?= $nft->id ?>">
                                                <button class="btn btn-primary" type="submit" id="<?= $nft->id ?>">Place a Bid</button>
                                            </form>
                                        </div>
                                        <!-- end card-body -->
                                    </div>
                                    <!-- end card -->
                                </div>



                        <?php
                            }
                        }
                        ?>
                    </div>

                </div>
            </div>
        </div>


        <!-- THE END -->

        <!-- end table-responsive -->

    </div>
    <!-- end profile-setting-panel-wrap-->


    <!-- end profile-setting-panel-wrap-->
    <!-- end row -->
</div>

<div class="modal fade" id="depositNiftyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">NFT Details</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">

            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="../assets/js/bundle.js"></script>
<script src="../assets/js/scripts.js"></script>


<script>
    $(document).ready(function() {
        const purchaseButtons = document.querySelectorAll('.purchaseButton');
        // console.log(purchaseButtons);

        purchaseButtons.forEach(function(purchaseButton) {
            purchaseButton.addEventListener('click', function() {
                // const productId = $(this).data('product-id');
                const productId = purchaseButton.getAttribute('data-product-id');
                const productPrice = purchaseButton.getAttribute('data-product-price');
                const productAuthorId = purchaseButton.getAttribute('data-product-author');
                const productTitle = purchaseButton.getAttribute('data-product-title');
                // const productPrice = $(this).data('product-price');
                // const productAuthorId = $(this).data('product-author');
                // const productTitle = $(this).data('product-title');
                // console.log("herrr_________");
                $.ajax({
                    url: 'get_product_data.php',
                    type: 'GET',
                    data: {
                        productId: productId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            const productPrice = response.productPrice;
                            const productName = response.productName;
                            var span = document.createElement("span");
                            span.innerHTML = `You are about to purchase the <b>${productName}</b> NFT for <b>${productPrice} ETH</b>`;
                            swal({

                                title: 'Purchasing NFT',
                                content: span,
                                icon: 'warning',
                                cancel: true,
                                dangerMode: true,
                                buttons: true,
                            }).then((willPurchase) => {
                                if (willPurchase) {
                                    $.ajax({
                                        url: 'get_product_data.php',
                                        type: 'POST',
                                        data: {
                                            productId: productId,
                                            productPrice: productPrice,
                                            productAuthorId: productAuthorId,
                                            productTitle: productTitle
                                        },
                                        dataType: 'json',
                                        success: function(response) {
                                            if (response.status === 'success') {
                                                swal({
                                                    title: "Success",
                                                    text: "NFT purchase successful",
                                                    icon: "success"
                                                }).then(() => {
                                                    // Refresh the page after the "OK" button is clicked
                                                    location.reload();
                                                });
                                            } else if (response.status === 'errors') {
                                                swal({
                                                    title: "Oops!",
                                                    text: "NFT purchase failed, insufficient balance",
                                                    icon: "warning"
                                                });
                                            } else {
                                                swal({
                                                    title: "Oops!",
                                                    text: "NFT purchase failed, Please try again",
                                                    icon: "warning"
                                                });

                                            }
                                        }
                                    })

                                } else {
                                    swal(
                                        'Cancelled',
                                        'NFT purchase aborted',
                                        'success'
                                    );
                                }
                            })
                        }
                    }
                })
            })
        })

    })

    $('.btttt').click(function() {
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