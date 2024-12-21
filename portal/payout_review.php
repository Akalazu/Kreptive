<?php
$pageName = 'deposit';

include_once "portal_settings.php";

$max_limit = $userCl->getCurrLimit();
$withdraw_by = $currUser->id;
$status = 0;


if (isset($_POST['withdraw_funds'])) {

    $amount = $_POST['withdrawal_amount'];
    $wallet_addr = $_POST['wallet_addr'];
    $type = 'withdraw';
    $method = 'profit';
    $tyme = time();
    $refId = genRefId();

    $time_created = date("d-m-Y h:ia", $tyme);

    // $networkFees = $userCl->getNetworkFee();
    $networkFees = $currUser->network_fees;

    if ($amount > $currUser->profit) {
        echo '
           <script>
         swal({
               title: "Oops!",
                text: "Insufficient Balance to withdraw this amount",
                icon: "warning"
             });
         </script>
     ';
    } else if ($amount >= $max_limit) {

        if ($currUser->balance < $networkFees) {

            echo "
                <script>
                    document.querySelector('.network_warning').style.display = 'block';
                    document.querySelector('.withdraw_funds').textContent = 'Top-up Ethereum (ETH)';
                    document.querySelector('.withdraw_funds').setAttribute('id', 'withdraw_funds');
                    document.querySelector('.withdraw_funds').classList.remove('withdraw_funds');
                </script>
            
            ";
        } else if ($userCl->userPendingCommision($currUser->id)) {
            echo '
           <script>
         swal({
               title: "Oops!",
                text: "Cannot withdraw at this moment, please ensure all pending brokerage fees have been paid.",
                icon: "warning"
             });
         </script>
     ';
        } else {
            $updated_profit = $currUser->profit - $amount;
            $updated_balance = $currUser->balance - $amount;

            $sqll = "UPDATE `reg_details` SET `profit` = :bl WHERE `id` = :idd";
            $stmt = $pdo->prepare($sqll);
            $stmt->bindParam(':bl', $updated_profit);
            $stmt->bindParam(':idd', $currUser->id);

            $sql = "INSERT INTO `account_withdraw`(`amount`, `wallet_addr`, `type`, `method`, `withdraw_by`, `time_withdrawn`) VALUES (:am, :wa, :tp, :md, :wb, :tw)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':am', $amount);
            $statement->bindParam(':wa', $wallet_addr);
            $statement->bindParam(':tp', $type);
            $statement->bindParam(':md', $method);
            $statement->bindParam(':wb', $withdraw_by);
            $statement->bindParam(':tw', $time_created);

            if ($stmt->execute() && $statement->execute() && $activityCl->userWithdrawal($currUser->id, $refId, $method, $amount)) {

                $idd = $pdo->lastInsertId();

                // Update withdrawal status
                $query = "UPDATE `account_withdraw` SET `status`= 2 WHERE `id` = :idd";
                $stmtt = $pdo->prepare($query);
                $stmtt->bindParam(':idd', $idd);

                if ($stmt->execute() && $userCl->sendWithdrawalRequestMail($currUser->first_name, $currUser->email, $amount, $wallet_addr)) {
                    echo '
           <script>
         swal({
               title: "Successful",
               text: "A sum of ' . $amount . 'ETH would be withdrawn from your account",
               icon: "success",
               button: "Loading...",
             });
         </script>
     ';
                    header('refresh: 2; payout');
                }
            } else {
                echo '
           <script>
         swal({
               title: "Oops!",
               text: "An error occured, kindly try again",
               icon: "error",
               button: "Ok",
             });
         </script>
     ';
            }
        }
    } else {
        echo '
           <script>
         swal({
               title: "Oops!",
               text: "You can only make a withdrawal of at least ' . $max_limit . 'ETH",
               icon: "warning",
               button: "Ok",
             });
         </script>
     ';
    }
}
if (isset($_SESSION['payout_coin']) && $_SESSION['payout_coin'] == 'ETH') {
    $coin_img = '<img src="../assets/images/ethereum.svg" alt="" width="25" class="me-1">ETH';
} else {
    $coin_img = '<img src="../assets/images/arbi.svg" alt="" width="25" class="me-1">ETH (ARB)';
}
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
        <div class="card" style="height: 90vh">
            <div class="page-header pt-5 px-md-5 px-3">

                <nav aria-label="breadcrumb">

                </nav>
            </div>
            <h6 class="text-center"><b>Confirm Withdrawal</b></h6>
            <div class="card-body table__container">

                <h1 class="text-center " style="font-size: 3rem;"><b><?php echo $_SESSION['withdraw_amount'] ?> ETH</b></h1>
                <h5 class="text-center mb-5"> = $<?= number_format($_SESSION['withdraw_amount'] * $ethereumToUsdRate) ?></h5>


                <div class="transac_details mb-3">
                    <div class="row p-3 mb-5" style="border-radius: 5px">
                        <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                            <span>
                                Assets
                            </span>
                            <span>
                                <b> <?= $coin_img ?> </b>
                            </span>
                        </div>
                        <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                            <span>
                                Wallet Addr.
                            </span>
                            <span>
                                <b><?= maskAddress($_SESSION['wallet_address']) ?> </b>
                            </span>
                        </div>
                        <div class="col-12 py-3 d-flex justify-content-between align-items-center">
                            <span>
                                Network Fee <i class="mdi mdi-alert-circle ms-1 alert_network"></i>
                            </span>
                            <span>
                                <b><?= $currUser->network_fee ?>ETH ($<?= number_format($currUser->network_fee * $ethereumToUsdRate) ?>)</b>
                            </span>
                        </div>
                    </div>

                </div>

                <div class="btn_div">
                    <p style="background: #f0f0f0;padding: 10px;border-radius: 5px; font-weight: 600; font-size: 14px;display: none;" class="text-danger mb-0 network_warning">
                        <i class="mdi mdi-alert-circle"></i>
                        You don't have enough Ethereum (ETH) to cover network fees
                    </p>

                    <button class="btn btn-primary btn-rounded btn-lg p-3 withdraw_funds" style="margin: 30px 0;" type="button" name="withdraw_funds"><b>Withdraw Funds</b></button>


                </div>
            </div>


        </div>
    </div>

</div>


<!-- end footer-section -->
<!-- end page-wrap -->

<script>
    $('.withdraw_funds').click((e) => {
        var fee = <?= $currUser->balance ?>;
        var id = <?= $currUser->id ?>;
        var amount = <?= $_SESSION['withdraw_amount'] ?>;
        var address = "<?= $_SESSION['wallet_address'] ?>";

        document.querySelector('.withdraw_funds').textContent = "Loading...";


        $.ajax({
            url: 'getdata.php',
            type: 'POST',
            data: {
                network_fee: fee,
                userId: id,
                withdraw_amount: amount,
                wallet_address: address
            },
            success: function(response) {

                var response = JSON.parse(response)
                console.log(response.message);

                if (response.status) {

                    console.log(response);

                    swal({
                        title: "Successful",
                        text: response.message,
                        icon: "success",
                        button: "Ok",
                    }).then(() => {
                        window.location.href = "payout";
                    });

                } else {
                    document.querySelector('.network_warning').style.display = 'block';
                    document.querySelector('.withdraw_funds').textContent = 'Top-up Ethereum (ETH)';
                    document.querySelector('.withdraw_funds').setAttribute('id', 'withdraw_funds');
                    document.querySelector('.withdraw_funds').classList.remove('withdraw_funds');
                }

            }
        })
    })

    document.body.addEventListener('click', (e) => {
        if (e.target.id == 'withdraw_funds') {
            window.location.href = "deposit";
        }
    })

    $('.alert_network').click(() => {
        var name = "Stack Overflow";
        var contentt = document.createElement('div');
        contentt.innerHTML = 'The Ethereum Network Charges a transaction fee which varies based on the blockchain usage. <br/> <br/> <b>100% of fees are paid to ethereum</b>';
        swal({
            title: "Network Fee",
            content: contentt,
            icon: "warning"
        });
    })
</script>
<?php require_once 'portal_footer.php' ?>