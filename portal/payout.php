<?php
$pageName = 'deposit';

include_once "portal_settings.php";

$max_limit = $currUser->withdraw_limit ?? $userCl->getCurrLimit();

$withdraw_by = $currUser->id;
if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_POST['send_proof'])) {
    // $wallet_type = $_POST['wallet'];
    // $wallet_addrr = str_split($wallet_type, 12);
    // $type = $wallet_addrr[0];
    // print_r($_POST);
    // die();

    $type = 'withdraw';
    $amount = $_POST['price'];
    $wallet_addr = $_POST['wallet_addr'];

    $method = $_POST['method'];
    $tyme = time();
    $time_created = date("d-m-Y h:ia", $tyme);
    $user_idd = $_SESSION['currid'];
    $refId = genRefId();

    if ($method == 'minting') {
        if ($currUser->lazy_mint) {
            if ($amount > $currUser->mint_balance) {
                echo '
               <script>
             swal({
                   title: "Oops!",
                    text: "Insufficient Balance in Minting Profit Wallet",
                    icon: "warning"
                 });
             </script>
         ';
            } else {


                $mint_profit = $currUser->mint_balance; //user current minting balance

                $charge = $userCl->getUserMintFee($currUser->id); //get current charge for this transcaction

                $newMint_profit = $mint_profit - ($amount + $charge); //suppposed balance after transaction

                if ($newMint_profit >= 0) {
                    $wallet_addr = '-'; //wallet address wont be needed in swapping from mint balance to profit

                    $currency = "Ethereum";

                    $status = 1; //every confirmed swapping should bnot be pending.

                    $newProfitBal = $currUser->profit + $amount; //add amount to profit  balance

                    $sql = "UPDATE `reg_details` SET `profit`= :pp,`mint_balance`= :mb WHERE `id` = :idd";
                    $statement = $pdo->prepare($sql);
                    $statement->bindParam(':pp', $newProfitBal);
                    $statement->bindParam(':mb', $newMint_profit);
                    $statement->bindParam(':idd', $currUser->id);

                    $query = "INSERT INTO `account_withdraw`(`amount`, `wallet_addr`, `type`, `method`, `withdraw_by`, `time_withdrawn`, `status`) VALUES (:am, :wa, :tp, :md, :wb, :tw, :st)";
                    $stmtt = $pdo->prepare($query);
                    $stmtt->bindParam(':am', $amount);
                    $stmtt->bindParam(':wa', $wallet_addr);
                    $stmtt->bindParam(':tp', $type);
                    $stmtt->bindParam(':md', $method);
                    $stmtt->bindParam(':wb', $withdraw_by);
                    $stmtt->bindParam(':tw', $time_created);
                    $stmtt->bindParam(':st', $status);

                    if ($statement->execute() && $stmtt->execute() && $activityCl->withdrawMintingToProfit($currUser->code, $refId, $amount)) {
                        echo '
                   <script>
                 swal({
                       title: "Success",
                       text: "' . $amount . 'ETH has been added to ETH (Arbitrum) Wallet",
                       icon: "success",
                       button: "Loading...",
                     })
                 </script>
             ';
                        header('refresh: 2');
                    }
                } else {
                    echo '
               <script>
             swal({
                   title: "Oops!",
                    text: "Insufficient Balance to make this transaction",
                    icon: "warning"
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
                text: "Lazy Minting is currently disabled",
                icon: "warning"
             });
         </script>
     ';
        }
    } else {

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

            $_SESSION['withdraw_amount'] = $amount;
            $_SESSION['wallet_address'] = $wallet_addr;

            $status = 0; //every confirmed swapping should bnot be pending.

            $isValid = preg_match('/^0x[a-fA-F0-9]{40}$/', $wallet_addr);

            if (!$isValid) {
                echo '
                        <script>
                        swal({
                            title: "Error!",
                                text: "Please enter a valid Ethereum wallet address.",
                                icon: "warning"
                            });
                        </script>
                    ';
            } else {
                header('location: payout_review');
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
}
?>
<style>
    .status_btn {
        padding: 10px 15px;
        font-size: 14px;
        font-weight: 700;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: none !important;
    }
</style>


<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="page-header pt-5 px-md-5 px-3">
                <h3 class="page-title"> Withdrawal </h3>
                <nav aria-label="breadcrumb">
                    <button type="button" class="btn btn-success btn-rounded btn-fw p-3" class="btn-link" data-bs-toggle="modal" data-bs-target="#depositNiftyModal"><b>Withdraw Funds</b></button>

                </nav>
            </div>
            <div class="card-body table__container">
                <h6 class="text-center"><b>Withdrawal Logs</b></h6>
                </p>
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Account</th>
                            <th>Wallet Address</th>
                            <th>Date | Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `account_withdraw` WHERE `withdraw_by` = :dp ORDER BY `id` ASC ";
                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(':dp', $idd);
                        $statement->execute();
                        $j = 1;
                        while ($withdrawal = $statement->fetch(PDO::FETCH_OBJ)) {
                            if ($withdrawal->status == 1) {
                                $color = 'btn-gradient-success';
                                $status = "Approved";
                            } else if ($withdrawal->status == 2) {
                                $color = 'btn-gradient-danger';
                                $status = "Not Approved";
                            } else {
                                $color = 'btn-gradient-dark';
                                $status = "Pending";
                            }
                            echo '
                        <tr>
                            <td>' . $j . '</td>
                            <td>Ethereum</td>
                            <td class="text-success">' . $withdrawal->amount . 'ETH</td>
                            <td> ' . ucfirst($withdrawal->method) . ' Balance</td>
                            <td>' . $withdrawal->wallet_addr . '</td>
                            <td>' . $withdrawal->time_withdrawn . '</td>
                            <td> <button type="button" class="btn ' . $color . ' btn-rounded status_btn">' . $status . '</button>
</td>
                        </tr>
                        ';
                            $j++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- Modal -->
<div class="modal fade" id="depositNiftyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Withdraw Funds</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">

                <?php
                if ($currUser->profit > 0) {
                    $output = 'Minimum ' . $max_limit . ' ETH';
                } else {
                    $output = '';
                }

                ?>

                <form method="post" enctype="multipart/form-data">
                    <!-- <div class="input-group my-4"> -->
                    <div class="form-group">
                        <label for="amount"> Amount</label>
                        <input type="number" name="price" id="amount" class="form-control form-control-s1 " min="0" step=".01" placeholder="<?= $output ?>" required>
                        <b>
                            <p class="ex_rate mb-3">= $0</p>
                        </b>
                    </div>

                    <div class="form-group">
                        <label for="account">Withdraw From</label>
                        <select class="form-control" id="account" name="method" onchange="toggleInput()" required>
                            <option selected value="" disabled>Select Option</option>
                            <option value="profit">ETH (Arbitrum) Wallet - <?= $currUser->profit ?>ETH</option>
                        </select>
                    </div>
                    <div class="form-group" id="wallet_address" style="display: none;">
                        <label for="addr">Wallet Address</label>
                        <input type="text" name="wallet_addr" id="addr" class="form-control form-control-s1" placeholder="Enter Address" requiredare you seriousGo>
                    </div>
                    <label for="method">Network </label>
                    <input type="text" id="method" class="form-control form-control-s1 mb-3" value="ERC20" name="method" disabled style="background: none!important;">


                    <!-- </div> -->

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-gradient-dark btn-rounded btn-fw p-3" name="send_proof">Next</button>
                    </div>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal-->

<!-- end footer-section -->
<!-- end page-wrap -->

<script>
    document.body.addEventListener('click', (e) => {
        if (e.target.getAttribute('id') == 'amount') {
            // console.log(e.target);
            e.target.addEventListener('keyup', () => {
                if (e.target.value == '') {
                    document.querySelector('.ex_rate').innerHTML = '= $0';

                } else {
                    $.ajax({
                        url: 'getdata.php',
                        method: 'POST',
                        data: {
                            payout_amount: e.target.value
                        },
                        success: (data) => {
                            console.log(data);
                            document.querySelector('.ex_rate').innerHTML = '= $' + data;
                        }
                    })
                }

            })
            // console.log(e.target.value);
        }
    })

    function toggleInput() {
        var select = document.getElementById('account');
        var inputF = document.getElementById('wallet_address');
        var blurEl = document.getElementById('addr');


        if (select.value === 'profit') {
            console.log('profit');
            inputF.style.display = 'block';
            blurEl.setAttribute('required', 'required')
        } else {
            console.log('mint');
            inputF.style.display = 'none';
            blurEl.removeAttribute('required')
        }
    }
</script>
<?php require_once 'portal_footer.php' ?>