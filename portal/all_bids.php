 <?php
    require_once 'portal_settings.php';

    // ini_set('display_errors', 1);

    // print_r($_SESSION);

    if (isset($_POST['accept_bid'])) {

        $bid_id = sanitizeText($_POST['bidid']);
        $bid = sanitizeText($_POST['bid']);
        $buyer = $userCl->getUserDetails(sanitizeText($_POST['buyerid']));
        $nft = $userCl->getNFTDetailsById(sanitizeText($_POST['artid']));

        $author = $userCl->getUserDetails(sanitizeText($nft->author_id));

        $price = $nft->price;

        $royalty = ($nft->royalties == 0 || !$nft->royalties) ? 0 : ($nft->royalties / 100) * $bid;

        $buyer_fname = $buyer->first_name . ' ' . $buyer->last_name;


        /**
         * 1. Change owner id on nft db
         * 2. Cancel every other bid once it is accepted
         * 3. change bid status to 1
         * 4. Add in activity log for both buyer and seller
         * 5. Add/Minus balance
         */

        //  5. Add/Minus balance
        $remaining_balance = $buyer->balance - $bid;

        if ($remaining_balance < 0) {
            echo '
        <script>
        swal({
            title: "Oops!",
            text: "NFT purchase failed, insufficient balance",
            icon: "warning"
        })
        </script>
        
        ';
        } else if ($userCl->checkBidStatus($bid_id) == 1) {
            echo '
        <script>
        swal({
            title: "Oops!",
            text: "This bid has already been accepted",
            icon: "warning"
        }).then(()=>{
            window.location.href = "all_bids";
        });
        </script>
        
        ';
        } else {
            $new_profit_balance = $currUser->profit + $bid;


            $ref_id = genRefId();

            if ($userCl->updateUserBalance($currUser->id, $new_profit_balance) == 'true') {

                if ($nft->author_id != $nft->owner_id) {
                    if (!$userCl->updateOwnerRoyalties($bid_id, $author->id, $royalty)) {
                        die("this was not successful");
                    }
                }

                $sql = "UPDATE `all_nft` SET `owner_id` =:oi, `owner_username` = :ou, `price` = :pp WHERE `id` = :idd";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':idd', $nft->id);
                $stmt->bindParam(':oi', $buyer->id);
                $stmt->bindParam(':ou', $buyer->username);
                $stmt->bindParam(':pp', $bid);

                if ($stmt->execute() && $userCl->initiateUserCommission($currUser->id, $bid) && $activityCl->userCommission($currUser->id, $ref_id, $bid)) {

                    // Update buyer balance
                    $sqll = "UPDATE `reg_details` SET `balance`= :bl WHERE `id` = :idd";

                    $stmtt = $pdo->prepare($sqll);
                    $stmtt->bindParam(':idd', $buyer->id);
                    $stmtt->bindParam(':bl', $remaining_balance);
                    $status = 1;

                    if ($stmtt->execute() && $userCl->sendNftPurchaseMail($buyer_fname, $nft->title, $buyer->email, $nft->price) && $userCl->updateBidStatus($status, $bid_id, $nft->id, $new_profit_balance) && $userCl->addUserTotalVolume($currUser->id, $bid)) {

                        $activityCl->salesArt($currUser->code, $ref_id, $nft->title, $bid, $buyer->username);
                        $activityCl->purchaseArt($buyer->code, $ref_id, $nft->title, $bid);


                        // if ($stmtt->execute() && $userCl->updateBidStatus($status, $bid_id, $nft->id)) {
                        echo '
                    <script>
                    swal({
                        title: "Success",
                        text: "NFT purchase successful",
                        icon: "success"
                    })
                    </script>
                ';
                    } else {
                        echo '
            <script>
            swal({
                title: "Error",
                text: "An error occured, Please try again",
                icon: "success"
            })
            </script>
            ';
                    }
                }
            } else {
                echo '
            <script>
            swal({
                title: "Error",
                text: "An error occured, Please try again",
                icon: "success"
            })
            </script>
            ';
            }
        }
    }

    if (isset($_POST['decline_bid'])) {
        $bid_id = sanitizeText($_POST['bidid']);

        $query = "DELETE FROM `all_bids` WHERE id = :id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':id', $bid_id);
        if ($statement->execute()) {
            echo '
            <script>
        swal({
            title: "Confirmed",
            text: "Bid has been successfully rejected",
            icon: "success"
        })
          </script>

        ';
        }
    }
    ?>

 <div class="content-body">
     <div class="row">
         <div class="col-12 grid-margin stretch-card">
             <div class="card">
                 <div class="card-body table__container">
                     <h6 class="text-center"><b>All Bids</b></h6>
                     </p>
                     <table class="table table-hover table-responsive">
                         <thead>
                             <tr>
                                 <th scope="col">#</th>
                                 <th scope="col">Title</th>
                                 <th scope="col">Price</th>
                                 <th scope="col">Bidder</th>
                                 <th scope="col">Current Bid</th>
                                 <th scope="col">Time</th>
                                 <th scope="col">Status</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php
                                $stat = 0;

                                $sql = "SELECT * FROM `all_bids` WHERE `recipient` = :rp AND `status` = :st ORDER BY `id` DESC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':rp', $currUser->id);
                                $stmt->bindParam(':st', $stat);
                                $stmt->execute();

                                $j = 1;

                                while ($all_bid = $stmt->fetch(PDO::FETCH_OBJ)) {
                                    $nft_details  = $userCl->getNFTDetailsById($all_bid->art_id);
                                    $bidder = $userCl->getUserDetails($all_bid->bidder_id);
                                    echo '

                             <tr>
                            <td data-label="#">' . $j . '</td>
                            <td data-label="Title">' . $nft_details->title . '</td>
                            <td data-label="Amount">' . $nft_details->price . ' ETH</td>
                            <td data-label="Collection">' . $bidder->first_name . ' ' . $bidder->last_name . '</td>
                            <td data-label="Bid">' . $all_bid->bid . ' ETH</td>
                            <td data-label="Date & Time">' . timeAgo($all_bid->time) . '</td>
                            <td data-label="Action">
                           
                            <form method="post" class="d-flex justify-content-center gap-3">
                            <input type="text" hidden value="' . $all_bid->bid . '" name="bid">
                            <input type="text" hidden value="' . $all_bid->bidder_id . '" name="buyerid">
                            <input type="text" hidden value="' . $all_bid->art_id . '" name="artid">
                            <input type="text" hidden value="' . $all_bid->id . '" name="bidid">
                             <button class="btn btn-success p-3 px-4" name="accept_bid">Accept</button>
                            <button class="btn btn-danger p-3 px-4" name="decline_bid">Decline</button></form>
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
     <?php require_once 'portal_footer.php' ?>