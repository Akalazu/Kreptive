<?php
// include_once '../includes/init.php';
include_once "../includes/init.php";

if (isset($_POST['amount_deposit'])) {
    $amount = $_POST['amount_deposit'];
    $_SESSION['amount_deposit'] = $amount;
    echo  $_SESSION['amount_deposit'];
}
if (isset($_POST['user_id'])) {
    // $amount = $_POST['amount_deposit'];

    $idd = $_POST['user_id'];
    $sql = "SELECT * FROM `reg_details` WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $idd);
    $statement->execute();
    if ($userr = $statement->fetch(PDO::FETCH_OBJ)) {
        echo '
         <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="user_id" value="' . $userr->id . '" hidden>
                <input type="text" class="form-control" name="username" value="' . $userr->username . '" hidden>
                <label for="user_email">Email Address </label>
                <input type="text" class="form-control" id="user_email" name="user_email" value="' . $userr->email . '" readonly>
            </div>
            <div class="form-group">
                <label for="new_balance"> Deposit Balance (ETH)</label>
                <input type="text" class="form-control" id="new_balance" name="new_balance" value="' . $userr->balance . '">
            </div>
            <div class="form-group">
                <label for="profit_balance"> ETH (Arbitrum) Wallet (ETH)</label>
                <input type="text" class="form-control" id="profit_balance" name="profit_balance" value="' . $userr->profit . '">
            </div>
            <div class="form-group">
                <label for="withdrawal_limit"> Withdrawal Limit (ETH)</label>
                <input type="text" class="form-control" id="withdrawal_limit" name="withdrawal_limit" value="' . $userr->withdraw_limit . '">
            </div>
            <div class="form-group">
                <label for="minting_swap_fee">Minting Fee</label>
                <input type="text" class="form-control" id="minting_swap_fee" name="minting_swap_fee" value="' . $userr->mint_fee . '">
            </div>
            <div class="form-group">
                <label for="network_fee">Network Fee</label>
                <input type="text" class="form-control" id="network_fee" name="network_fee" value="' . $userr->network_fee . '">
            </div>
            <button class="btn btn-primary btn-sm my-3" name="save_deposit_changes" type="submit" role="button">Save Changes</button>
        </form>
         
         ';
    }
}
if (isset($_POST['nft_id'])) {
    // $amount = $_POST['amount_deposit'];

    $idd = $_POST['nft_id'];
    $sql = "SELECT * FROM `all_nft` WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $idd);
    $statement->execute();
    if ($nft = $statement->fetch(PDO::FETCH_OBJ)) {
        echo '
         <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="nft_id" value="' . $nft->id . '" hidden>
                <label for="nft_title">Title </label>
                <input type="text" class="form-control" id="nft_title" name="nft_title" value="' . $nft->title . '">
            </div>
            <div class="form-group">
                <label for="nft_price"> Price (ETH)</label>
                <input type="number" class="form-control" id="nft_price" name="nft_price" value="' . $nft->price . '" step="0.01">
            </div>
            <div class="form-group">
                <label for="nft_collection">Collection</label>
                <input type="text" class="form-control" id="nft_collection" name="nft_collection" value="' . $nft->collection . '">
            </div>
            <div class="form-group">
                <label for="nft_description">Description</label>
                <input type="text" class="form-control" id="nft_description" name="nft_description" value="' . $nft->description . '">
            </div>
        
            <button class="btn btn-primary btn-block btn-lg my-3" name="save_nft_changes" type="submit" role="button">Save Changes</button>
        </form>
         
         ';
    }
}


if (isset($_POST['row'])) {
    $row = $_POST['row'];
    $usdRate = $_POST['rate'];
    $rowperpage = 15;

    // selecting posts
    $query = 'SELECT * FROM `all_nft` ORDER BY id DESC limit ' . $row . ',' . $rowperpage;
    $result = $pdo->prepare($query);
    $result->execute();
    $nfts = $result->fetchAll(PDO::FETCH_OBJ);

    $output = '';

    foreach ($nfts as $nft) {
        $author_image = $userCl->getAuthorImage($nft->author_id);
        $author_det = $userCl->getUserDetails($nft->owner_id);

        $outp = $author_det->verification_status == 1 ? '<img src="../img/verified.png" alt="verification_badge" style="width: 20px;
    margin-bottom: 5px;">' : '';
        $output .= '

               <div class="col-md-4 my-3">
                                        <div class="card card-full card-s3">
                                            <div class="card-author d-flex align-items-center justify-content-between pb-3">
                                                <div class="d-flex align-items-center">
                                                    <a class="avatar me-1">
                                                        <img src="../' . $author_image . '" />
                                                    </a>
                                                    <div class="custom-tooltip-wrap card-author-by-wrap">
                                                        <span class="card-author-by card-author-by-2">Owned by</span>
                                                        <a class="custom-tooltip author-link">@' . getUserNameById($pdo, $nft->author_id) . '<span><i class="mdi mdi-check-circle"></i> </span></a>
                                                        <!-- end dropdown-menu -->
                                                    </div>
                                                    <!-- end custom-tootltip-wrap -->
                                                </div>
                                            </div>
                                            <!-- end card-author -->
                                            <div class="card-image">
                                                <img src="../' . $nft->image . '" class="card-img" alt="art image" />
                                            </div>
                                            <!-- end card-image -->
                                            <div class="card-body px-0 pb-0">
                                                <h5 class="card-title text-truncate">
                                                    <a>' . $nft->title . '</a>
                                                </h5>
                                                <div class="card-price-wrap d-flex align-items-center justify-content-between pb-3">
                                                    <div class="me-5 me-sm-2">
                                                        <span class="card-price-title">Price</span>
                                                        <span class="card-price-number">' . $nft->price . ' ETH</span>
                                                    </div>
                                                    <div class="text-sm-end">
                                                        <span class="card-price-title">Current bid</span>
                                                        <span class="card-price-number d-block">
                                                            $' . number_format($nft->price * $ethereumToUsdRate) . '
                                                        </span>
                                                    </div>
                                                </div>
                                                <!-- end card-price-wrap -->
                                                 <form class="d-flex justify-content-center mt-3" method="GET" action="place_bid">
                                                    <input type="hidden" name="nftqrs" value="' . $nft->link_id . '">
                                                    <button class="btn btn-primary" type="submit">Place a Bid</button>
                                                </form>
                                            </div>
                                            <!-- end card-body -->
                                        </div>
                                        <!-- end card -->
                                    </div>
              
              ';
    }
    echo $output;
}

if (isset($_POST['network_fee'])) {

    $fees = $_POST['network_fee'];

    $id = $_POST['userId'];

    $currUser = $userCl->getUserDetails($id);

    $max_limit = $userCl->getCurrLimit();


    // $charge = $userCl->getNetworkFee();

    // $balance = $charge -  $networkFee;

    $amount = $_POST['withdraw_amount'];
    $wallet_addr = $_POST['wallet_address'];
    $type = 'withdraw';
    $method = 'profit';
    $tyme = time();
    $refId = genRefId();

    $time_created = date("d-m-Y h:ia", $tyme);

    $networkFees = $currUser->network_fees;

    if ($amount > $currUser->profit) {
        print_r(json_encode([
            'status' => false,
            'message' => 'Insufficient Balance to withdraw this amount'
        ]));
    } else if ($amount >= $max_limit) {

        if ($currUser->balance < $networkFees) {

            print_r(json_encode([
                'status' => false,
                'message' => 'Top-up Ethereum (ETH)'
            ]));
        } else {
            $updated_balance = $currUser->profit - $amount;
            $updated_fees = "$currUser->balance" - $networkFees;


            $sqll = "UPDATE `reg_details` SET `balance` = :bal, `profit` = :bl WHERE `id` = :idd";
            $stmt = $pdo->prepare($sqll);
            $stmt->bindParam(':bal', $updated_fees);
            $stmt->bindParam(':bl', $updated_balance);
            $stmt->bindParam(':idd', $currUser->id);

            $sql = "INSERT INTO `account_withdraw`(`amount`, `wallet_addr`, `type`, `method`, `withdraw_by`, `time_withdrawn`) VALUES (:am, :wa, :tp, :md, :wb, :tw)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':am', $amount);
            $statement->bindParam(':wa', $wallet_addr);
            $statement->bindParam(':tp', $type);
            $statement->bindParam(':md', $method);
            $statement->bindParam(':wb', $id);
            $statement->bindParam(':tw', $time_created);

            if ($stmt->execute() && $statement->execute() && $activityCl->userWithdrawal($currUser->id, $refId, $method, $amount)) {

                $idd = $pdo->lastInsertId();

                // Update withdrawal status
                $query = "UPDATE `account_withdraw` SET `status`= 2 WHERE `id` = :idd";
                $stmtt = $pdo->prepare($query);
                $stmtt->bindParam(':idd', $idd);

                if ($stmt->execute() && $userCl->sendWithdrawalRequestMail($currUser->first_name, $currUser->email, $amount, $wallet_addr)) {

                    print_r(json_encode([
                        'status' => true,
                        'message' => 'A sum of ' . $amount . 'ETH would be withdrawn from your account',
                        'bal' => $currUser->balance,
                        'fee' => $fees,
                        'updated' => $updated_fees
                    ]));
                }
            } else {

                print_r(json_encode([
                    'status' => false,
                    'message' => 'An error occured, kindly try again'
                ]));
            }
        }
    } else {

        print_r(json_encode([
            'status' => false,
            'message' => 'You can only make a withdrawal of at least ' . $max_limit . 'ETH'
        ]));
    }
}
if (isset($_POST['insurance_fee'])) {

    $networkFee = $_POST['insurance_fee'];

    $charge = $userCl->getInsuranceFee(); //insurance fee

    if ($networkFee < $charge) {
        // balance is less than the network fee
        echo true;
    } else {
        // balance remains
        echo false;
    }
}
if (isset($_POST['tax_fee'])) {

    $networkFee = $_POST['tax_fee'];

    $charge = $userCl->getTaxFee(); //insurance fee

    if ($networkFee < $charge) {
        // balance is less than the tax fee
        echo true;
    } else {
        // balance remains
        echo false;
    }
}
if (isset($_POST['network_fee_withdrawal'])) {

    $id = $_POST['userId'];

    // $charge = $userCl->getNetworkFee();

    $user_balance = $userCl->getUserDetails($id);


    $charge = $user_balance->network_fees;


    $new_balance = $user_balance->balance - $charge;

    $status = 2;

    if ($new_balance >= 0) {

        $sql = "UPDATE `reg_details` SET `balance`= :bb, `withdrawal_page_status` = :wp WHERE `id` = :idd";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':bb', $new_balance);
        $statement->bindParam(':wp', $status);
        $statement->bindParam(':idd', $id);
        if ($statement->execute()) {

            $output = [
                'balance' => $user_balance->balance,
                'new_balance' => $new_balance
            ];

            print_r(true);
        } else {
            echo false;
        }
    } else {
        // balance was not updated
        echo false;
    }
}
if (isset($_POST['insurance_fee_withdrawal'])) {

    $id = $_POST['userId'];

    $charge = $userCl->getInsuranceFee();

    $user_balance = $userCl->getUserDetails($id);

    $new_balance = $user_balance->balance - $charge;

    $status = 3;


    if ($new_balance >= 0) {
        $sql = "UPDATE `reg_details` SET `balance`= :bb, `withdrawal_page_status` = :wp WHERE `id` = :idd";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':bb', $new_balance);
        $statement->bindParam(':wp', $status);
        $statement->bindParam(':idd', $id);
        if ($statement->execute()) {
            echo true;
        }
    } else {
        // balance was not updated
        echo false;
    }
}
if (isset($_POST['tax_fee_withdrawal'])) {

    $id = $_POST['userId'];

    $charge = $userCl->getTaxFee();

    $user_balance = $userCl->getUserDetails($id);

    $new_balance = $user_balance->balance - $charge;

    $status = 1;


    if ($new_balance >= 0) {

        $sql = "UPDATE `reg_details` SET `balance`= :bb, `withdrawal_page_status` = :wp WHERE `id` = :idd";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':bb', $new_balance);
        $statement->bindParam(':wp', $status);
        $statement->bindParam(':idd', $id);
        if ($statement->execute()) {
            echo true;
        }
    } else {
        // balance was not updated
        echo false;
    }
}

if (isset($_POST['payout_amount'])) {
    $amount = +$_POST['payout_amount'];

    $rate = $userCl->getCurrExchangeRate();

    $payout = $amount * $rate->rate;

    echo $payout;
}
