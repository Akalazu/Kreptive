<?php
$title = "withdraw";
include_once 'portal_settings.php';
// $max_limit = $userCl->getCurrLimit();
// $withdraw_by = $currUser->first_name . ' ' . $currUser->last_name;
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $amount = $_POST['amount'];
//     $wallet_addr = $_POST['wallet_addr'];
//     $type = $_POST['type'];
//     $method = $_POST['method'];
//     $tyme = time();
//     $time_created = date("d-m-Y h:ia", $tyme);
//     $user_idd = $_SESSION['currid'];


//     if ($amount >= $max_limit) {
//         $sql = "INSERT INTO `account_withdraw`(`amount`, `wallet_addr`, `type`, `method`, `withdraw_by`, `time_withdrawn`) VALUES (:am, :wa, :tp, :md, :wb, :tw)";
//         $statement = $pdo->prepare($sql);
//         $statement->bindParam(':am', $amount);
//         $statement->bindParam(':wa', $wallet_addr);
//         $statement->bindParam(':tp', $type);
//         $statement->bindParam(':md', $method);
//         $statement->bindParam(':wb', $withdraw_by);
//         $statement->bindParam(':tw', $time_created);
//         if ($statement->execute()) {
//             echo '
//            <script>
//          swal({
//                title: "Successful",
//                text: "A sum of ' . $amount . 'ETH would be withdrawn from your account",
//                icon: "success",
//                button: "Redirecting...",
//              });
//          </script>
//      ';
//             header('refresh: 2; payouts');
//         } else {
//             echo '
//            <script>
//          swal({
//                title: "Oops!",
//                text: "An error occured, kindly try again",
//                icon: "error",
//                button: "Ok",
//              });
//          </script>
//      ';
//         }
//     } else {
//         echo '
//            <script>
//          swal({
//                title: "Oops!",
//                text: "You can only make a withdrawal of at least ' . $max_limit . 'ETH",
//                icon: "warning",
//                button: "Ok",
//              });
//          </script>
//      ';
//     }
// }
?>

<div class="wallet__wrapper">
    <div>
        <div class="wallet__head"></div>
        <div class="wallet__body" style="padding: 2rem;">

            <div style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; justify-content: space-between;">
                <div style="display: flex; gap: .25rem; align-items: center; font-weight: 600">
                    <div class="market__icon"><img src="../img/content/currency/ethereum.svg" alt="Ethereum"></div>
                    <span>Ethereum - ETH</span>
                </div>



            </div>
            <p style="font-size: 12px; text-align: center; font-weight: 600; margin-bottom: 2.5rem;">Withdraw Funds (Request)</p>
            <div class="withdraw__form">
                <form method="POST">
                    <div class="upload__list">
                        <div class="upload__item">
                            <!-- <div class="upload__category">Withdrawal Details</div> -->
                            <div class="upload__fieldset">
                                <div class="field">
                                    <div class="field__label">Amount <span style="font-size: 10px">(ETH)</span></div>

                                    <div class="field__wrap">
                                        <input class="field__input" type="number" name="amount" id="amount" placeholder="Min <?= $max_limit ?>ETH" required="" step=".001">
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="field__label">Wallet address <span style="font-size: 10px">(ETH)</span></div>

                                    <div class="field__wrap">
                                        <input class="field__input" type="text" name="wallet_addr" id="email" placeholder="" required="">
                                    </div>
                                </div>
                                <div class="upload__row field">

                                    <div class="upload__col" style="flex: 1">

                                        <div class="field">
                                            <div class="field__label">Type</div>
                                            <div class="field__wrap">
                                                <!--<select class="nice-select select" style="display: none; width: 100%;" name="type">-->
                                                <!--    <option  name="type" value="Trading Profit">Trading Profit</option>-->
                                                <!--</select>-->
                                                <select class="nice-select select" style="width: 100%;" tabindex="0" name="type">
                                                    <option class="option selected focus" name="type" value="Trading Profit" selected>Trading Profit</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="upload__col" style="flex: 1">

                                        <div class="field">
                                            <div class="field__label">Method</div>
                                            <div class="field__wrap">

                                                <!--<div class="nice-select select" style="width: 100%;" tabindex="0"><span class="current">Ethereum</span>-->
                                                <select class="nice-select select" style="width: 100%;" name="method" tabindex="0">
                                                    <option name="method" value="Ethereum" selected>Ethereum</option>
                                                </select>
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="upload__foot">
                        <button class="button upload__button " name="preview_btn" style="background: rgb(234,205,163); background: linear-gradient(90deg, rgba(234,205,163,1) 0%, rgba(244,176,92,1) 35%, rgba(245,131,109,1) 100%);" type="button" role="button" onclick="SquadPay()">Save & Send Request</button>
                    </div>
                </form>


            </div>

        </div>
    </div>

</div>

<?php include_once 'portal_footer.php'; ?>