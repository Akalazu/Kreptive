<?php


class User
{
  // BindValue vs BindParameter In PHP
  protected $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }
  public function getUserDetails($id)
  {
    $sql = "SELECT * FROM `reg_details` WHERE `id` = :cd";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":cd", $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    return $user;
  }

  public function getAllAdminMails()
  {
    $sql = "SELECT `email` FROM `reg_details` WHERE `role` = 'admin'";
    $stmt = $this->pdo->query($sql);
    $admin_mails = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $admin_mails;
  }

  public function getUserDetailsByName($name)
  {
    $name_arr = explode(' ', $name);
    $fName = $name_arr[0];

    $sql = "SELECT * FROM `reg_details` WHERE `first_name` = :cd";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":cd", $fName);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    return $user;
  }

  public function getTotalArtsCreated($id)
  {
    $sql = "SELECT `total_arts` FROM `reg_details` WHERE `id` = :idd";
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    $statement->execute();
    $artsCreated = $statement->fetch(PDO::FETCH_OBJ);
    return $artsCreated->total_arts;
  }

  public function getTotalCollections($id)
  {
    $numberOfArts = $this->getTotalArtsCreated($id);
    $numberOfCollection = intval($numberOfArts / 5);
    if ($numberOfCollection > 1) {
      return $numberOfCollection . ' ' . 'collections';
    } else {
      return $numberOfCollection . ' ' . 'collection';
    }
  }

  public function updateTotalArtsCreated($id)
  {
    $totalArts = $this->getTotalArtsCreated($id);
    $totalArts = $totalArts + 1;
    $sql = "UPDATE `reg_details` SET `total_arts` = :ta WHERE `id` = :idd";
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':ta', $totalArts);
    $statement->bindParam(':idd', $id);
    if ($statement->execute()) {
      return true;
    }
  }

  public function noOfTimes($id)
  {
    $sql = "SELECT COUNT(*) AS count FROM `all_nft` WHERE `author_id` = :idd";
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_OBJ);
    $count = $result->count;
    return $count;
  }

  public function loopAllArtsCreated()
  {
    $sql = "SELECT `id` FROM `reg_details`";
    $statement = $this->pdo->prepare($sql);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_OBJ)) {
      $eachRowId = $row->id;
      $noOfTimes = $this->noOfTimes($eachRowId);

      $sqll = "UPDATE `reg_details` SET `total_arts` = :ta WHERE `id` = :rowId";
      $stmt = $this->pdo->prepare($sqll);
      $stmt->bindParam(':ta', $noOfTimes);
      $stmt->bindParam(':rowId', $eachRowId);
      if ($stmt->execute()) {
        echo "Done!";
      }
    }
  }

  public function getUserDetailsByEmail($email)
  {
    $sql = "SELECT * FROM `reg_details` WHERE `email` = :mail";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':mail', $email);
    $stmt->execute();

    $detOnEmail = $stmt->fetch(PDO::FETCH_OBJ);
    return $detOnEmail;
  }

  public function getAuthorId($currUserr)
  {
    $query = "SELECT * FROM `all_nft` WHERE `author_name` = :an";
    $stmtt = $this->pdo->prepare($query);
    $stmtt->bindParam(':an', $currUserr);
    $stmtt->execute();
    $auth = $stmtt->fetch(PDO::FETCH_OBJ);
    return $auth->author_id;
  }
  public function getNFTDetailsById($productId)
  {
    $sql = "SELECT * FROM `all_nft` WHERE `id` = :idd";
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':idd', $productId);
    if ($statement->execute()) {
      $product = $statement->fetch(PDO::FETCH_OBJ);
      return $product;
    }
  }

  public function getAllBidsForArt($artId)
  {
    $query = "SELECT * FROM `all_bids` WHERE `art_id` = :ai AND `status` = 0";
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':ai', $artId);
    $statement->execute();
    if ($bids = $statement->fetchAll(PDO::FETCH_OBJ)) {
      return $bids;
    }
  }

  public function checkIfUserPlacedBidForArt($userId, $artId)
  {
    // Get all bids for the specified art ID
    $bids = $this->getAllBidsForArt($artId);

    if ($bids) {
      // Check if the user has placed a bid
      foreach ($bids as $bid) {
        if ($bid->bidder_id == $userId) {
          return true;
        }
      }
    }
    return false;
  }

  public function getAuthorImage($author_id)
  {
    $query = "SELECT * FROM `reg_details` WHERE `id` = :ai";
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($author = $statement->fetch(PDO::FETCH_OBJ)) {

      $author_img = $author->image;
      return $author_img;
    }
  }

  public function getTopCollectors()
  {
    $query = "SELECT * FROM `reg_details` ORDER BY `balance` DESC LIMIT 5";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($topCollectors = $statement->fetchAll(PDO::FETCH_OBJ)) {
      return $topCollectors;
    }
  }

  public function getTopArtists()
  {
    $query = "
        SELECT owner_id, COUNT(owner_id) AS activity_count
        FROM all_nft
        GROUP BY owner_id
        ORDER BY activity_count DESC
        LIMIT 5
    ";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($topArtists = $statement->fetchAll(PDO::FETCH_OBJ)) {
      return $topArtists;
    }
  }
  public function updateRoyaltyRecord($id, $royalty)
  {
    $query = "UPDATE `all_bids` SET `royalties_status` = 1 AND `royalties` = :ry WHERE `id` = :idd";
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':ry', $royalty);
    $statement->bindParam(':idd', $id);
    if ($statement->execute()) {
      return true;
    } else {
      return false;
    }
  }
  public function updateOwnerRoyalties($bid_id, $id, $royalties)
  {

    $owner = $this->getUserDetails($id);
    $updatedProfit = $owner->profit + $royalties;

    // $query = "UPDATE `reg_details` SET `mint_balance` = :ry WHERE `id` = :idd";
    $query = "UPDATE `reg_details` SET `profit` = :ry WHERE `id` = :idd";

    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':ry', $updatedProfit);
    $statement->bindParam(':idd', $id);
    if ($statement->execute() && $this->updateRoyaltyRecord($bid_id, $royalties)) {
      return true;
    } else {
      return false;
    }
  }

  public function getWalletAddr()
  {
    $query = "SELECT * FROM `wallet_address` WHERE `id` = 1";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($wallet_addr = $statement->fetch(PDO::FETCH_OBJ)) {
      return $wallet_addr->address;
    }
  }
  public function getUserMintFee($user)
  {
    $query = "SELECT * FROM `reg_details` WHERE `id` = :idd";
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':idd', $user);
    $statement->execute();
    if ($mintingFee = $statement->fetch(PDO::FETCH_OBJ)) {
      return $mintingFee->mint_fee;
    }
  }
  public function getMintFee()
  {
    $query = "SELECT * FROM `withdrawal_limit` WHERE `id` = 2";
    $statement = $this->pdo->prepare($query);
    $statement->execute();
    if ($mintingFee = $statement->fetch(PDO::FETCH_OBJ)) {
      return $mintingFee->withdrawal_limit;
    }
  }

  public function getNFTDetailsByToken($token)
  {
    $query = "SELECT * FROM `all_nft` WHERE `link_id` = :token";
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':token', $token);
    $statement->execute();
    if ($nft = $statement->fetch(PDO::FETCH_OBJ)) {
      return $nft;
    } else {
      return false;
    }
  }
  public function getCurrExchangeRate()
  {
    $sql = "SELECT * FROM `exchange_rate` WHERE `id` = 1";

    $statement = $this->pdo->query($sql);

    $rate = $statement->fetch(PDO::FETCH_OBJ);

    return $rate;
  }

  // Once a bid is confirmed, others are cancelled
  public function abortOtherBids($bid_id, $art_id)
  {

    // echo $bid_id . ', ' . $art_id . ', ' . $recipient;
    // die();
    $status = 2;
    $query = "UPDATE `all_bids` SET `status` = :st WHERE  `id` != :idd AND `art_id` = :aid";

    // UPDATE `all_bids` SET `status` = 2 WHERE `id` != 4 AND `art_id` = 566;

    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':idd', $bid_id);
    $statement->bindParam(':st', $status);
    $statement->bindParam(':aid', $art_id);
    if ($statement->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function updateUserBalance($userid, $balance)
  {
    try {
      $queryy = "UPDATE `reg_details` SET `profit` = :pf WHERE `id` = :idd";

      $sttmt = $this->pdo->prepare($queryy);

      $sttmt->bindParam(':idd', $userid);
      $sttmt->bindParam(':pf', $balance);

      return $sttmt->execute() ? 'true' : 'false';
    } catch (\Throwable $th) {
      //throw $th;
      return $th;
    }
  }

  public function updateRoyalties()
  {
    $queryyy = "SELECT * FROM `all_nft`";
    $statement = $this->pdo->query($queryyy);
    $all_nft = $statement->fetchAll(PDO::FETCH_OBJ);

    foreach ($all_nft as $nft) {
      $royalty = $nft->royalties;
      if (!$royalty) {
        //update value to zero
        $queryy = "UPDATE `all_nft` SET `royalties` = 0 WHERE `id` = :idd";
        $stmt = $this->pdo->prepare($queryy);
        $stmt->bindParam(':idd', $nft->id);
        $stmt->execute();
      }
    }
  }

  public function checkBidStatus($bid_id)
  {
    $query = "SELECT `status` FROM `all_bids` WHERE `id` = :idd";
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':idd', $bid_id);
    $statement->execute();
    if ($status = $statement->fetch(PDO::FETCH_OBJ)) {
      return $status->status;
    } else {
      return false;
    }
  }

  public function updateRecipientBalance($bid_id, $new_profit)
  {
    $query = "UPDATE `all_bids` SET `recipient_new` = :rnew WHERE `id` = :idd";
    //  echo $status . ', '. $id. ', '. $art_id;
    // die();
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':rnew', $new_profit);
    $statement->bindParam(':idd', $bid_id);
    if ($statement->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // Confirm or cancel a bid
  public function updateBidStatus($status, $id, $art_id, $new_profit)
  {
    $query = "UPDATE `all_bids` SET `status` = :st WHERE `id` = :idd";
    //  echo $status . ', ' . $id . ', ' . $art_id;
    // die();
    $statement = $this->pdo->prepare($query);
    $statement->bindParam(':st', $status);
    $statement->bindParam(':idd', $id);
    if ($status == 1) {
      if ($statement->execute() && $this->updateRecipientBalance($id, $new_profit) && $this->abortOtherBids($id, $art_id)) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
  public function getCurrLimit()
  {
    $query = "SELECT * FROM `withdrawal_limit` WHERE `id` = 1";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($withdrawal_limit = $statement->fetch(PDO::FETCH_OBJ)) {
      return $withdrawal_limit->withdrawal_limit;
    }
  }
  public function getDepoCharge()
  {
    $query = "SELECT * FROM `withdrawal_limit` WHERE `id` = 2";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($withdrawal_limit = $statement->fetch(PDO::FETCH_OBJ)) {
      return $withdrawal_limit->withdrawal_limit;
    }
  }

  public function getNetworkFee()
  {
    $query = "SELECT * FROM `withdrawal_limit` WHERE `id` = 3";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($network_fee = $statement->fetch(PDO::FETCH_OBJ)) {
      return $network_fee->withdrawal_limit;
    }
  }

  public function getInsuranceFee()
  {
    $query = "SELECT * FROM `withdrawal_limit` WHERE `id` = 4";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($network_fee = $statement->fetch(PDO::FETCH_OBJ)) {
      return $network_fee->withdrawal_limit;
    }
  }
  public function getTaxFee()
  {
    $query = "SELECT * FROM `withdrawal_limit` WHERE `id` = 5";
    $statement = $this->pdo->prepare($query);
    //  $statement->bindParam(':ai', $author_id);
    $statement->execute();
    if ($network_fee = $statement->fetch(PDO::FETCH_OBJ)) {
      return $network_fee->withdrawal_limit;
    }
  }

  public function updatedTotalWithdrawal($amount, $user_id)
  {
    // Get total withdrawal the user has made so far
    $sql = "SELECT `total_withdrawal` FROM `reg_details` WHERE `id` = :idd";
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':idd', $user_id);
    $statement->execute();
    $user_details = $statement->fetch(PDO::FETCH_OBJ);
    $total_withdrawal = $user_details->total_withdrawal;

    // Get the maximum withdrawal
    $max_limit = $this->getCurrLimit();

    if ($total_withdrawal < $max_limit) {

      $total_withdrawal = $total_withdrawal + $amount;

      $sql = "UPDATE `reg_details` SET `total_withdrawal`= :tww WHERE `id` = :id";

      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':tww', $total_withdrawal);
      $stmt->bindParam(':id', $user_id);

      if ($stmt->execute()) {
        return true;
      }
    } else {
      echo '
            <script>
            swal({
                title: "Oops!",
                text: "User has reached maximum withdrawal for today",
                icon: "warning"
            })
            </script>
           
            ';
      return false;
    }
  }

  public function getAllCollections($id)
  {
    $sql = "SELECT * FROM `author_collections` WHERE `author_id` = :idd";
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    if ($statement->execute()) {
      $collections = $statement->fetchAll(PDO::FETCH_OBJ);
      return $collections;
    }
  }

  public function updateRecoverRequest($id)
  {
    $sql = "UPDATE `reg_details` SET `recover_request`= 0 WHERE `id` = :idd";
    $statement = $this->pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    if ($statement->execute()) {
      return true;
    }
  }

  public function verifyEmail($email)
  {
    $sql = "UPDATE `reg_details` SET `verified` = 1 WHERE `email` = :em";
    $stmtt = $this->pdo->prepare($sql);
    $stmtt->bindParam(':em', $email);
    $stmtt->execute();
    if ($stmtt->execute()) {
      return true;
    }
  }

  public function payUserCommission($userId)
  {

    try {
      // Step 1: Fetch the last recorded commission for the user from the commission table
      $stmt = $this->pdo->prepare("SELECT * FROM commission WHERE user_id = :userId ORDER BY time_created DESC LIMIT 1");
      $stmt->execute(['userId' => $userId]);
      $commission = $stmt->fetch(PDO::FETCH_OBJ);

      // Check if a commission record exists for the user
      if ($commission) {
        // Step 2: Get the commission amount from the latest record
        $commissionAmount = $commission->amount;

        // Step 3: Retrieve user details using getUserDetails function
        $userDetails = $this->getUserDetails($userId);

        // Check if user details were found
        if (!$userDetails) {
          $output = [
            'error' => true,
            'message' => "User details not found."
          ];
          return false;
        }

        // Step 4: Check if the user's balance is enough to pay the commission
        $userBalance = $userDetails->balance;
        if ($userBalance > $commissionAmount) {

          // Step 5: Deduct the commission amount from the user's balance
          $newBalance = $userBalance - $commissionAmount;

          // Begin transaction for atomicity
          $this->pdo->beginTransaction();

          // Step 6: Update the user's balance in the database
          $updateBalanceStmt = $this->pdo->prepare("UPDATE reg_details SET balance = :newBalance WHERE id = :userId");
          $updateBalanceStmt->execute([
            'newBalance' => $newBalance,
            'userId' => $userId
          ]);

          // Step 7: Update the commission status to 1 (indicating it's paid)
          $updatecommissiontmt = $this->pdo->prepare("UPDATE commission SET status = 1 WHERE id = :commissionId");
          $updatecommissiontmt->execute([
            'commissionId' => $commission->id
          ]);

          // Commit transaction
          $this->pdo->commit();

          $output = [
            'error' => false,
            'message' => "Commission paid successfully."
          ];
          return true;
        }
      }
    } catch (Exception $e) {
      // Rollback the transaction only if it was started
      if ($this->pdo->inTransaction()) {
        $this->pdo->rollBack();
      }
      return "Error: " . $e->getMessage();
    }
  }

  public function initiateUserCommission($id, $amount)
  {
    $commisssion = 0.10 * $amount;
    $date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO `commission` (`user_id`, `amount`, `time_created`) VALUES (:ui, :am, :tc)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':ui', $id);
    $stmt->bindParam(':am', $commisssion);
    $stmt->bindParam(':tc', $date);
    if ($stmt->execute()) {
      $this->payUserCommission($id);
      return true;
    } else {
      return false;
    }
  }

  // Get transactions for each user
  public function getRecentTransactions($userId)
  {
    $transactions = [];

    // SQL query to get the 2 most recent transactions for each table
    $queries = [
      'account_deposit' => "SELECT 'deposit' AS type, amount, id, status, date_created FROM account_deposit WHERE depositor = :userId ORDER BY id DESC LIMIT 2",
      'account_withdrawal' => "SELECT 'withdraw' AS type, amount, id , status, time_withdrawn FROM account_withdraw WHERE withdraw_by = :userId ORDER BY id DESC LIMIT 2",
      'commission' => "SELECT * FROM commission WHERE user_id = :userId ORDER BY id DESC LIMIT 2"
    ];

    // Loop through each query and execute it
    foreach ($queries as $table => $query) {
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
      $stmt->execute();

      // Fetch results and add to transactions array
      $transactions = array_merge($transactions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }


    // Return only the 2 most recent transactions
    return array_slice($transactions, 0, 6);
  }

  // check if user has bought or minted a transaction
  public function hasMintedOrBoughtTenNFTs($user_id)
  {
    // Query to check minting count (author_id)
    $mintQuery = "SELECT COUNT(*) AS mint_count FROM all_nft WHERE author_id = :user_id";
    $stmtMint = $this->pdo->prepare($mintQuery);
    $stmtMint->execute(['user_id' => $user_id]);
    $mintCount = $stmtMint->fetch(PDO::FETCH_OBJ)->mint_count;

    // Query to check buying count (owner_id)
    $buyQuery = "SELECT COUNT(*) AS buy_count FROM all_nft WHERE owner_id = :user_id";
    $stmtBuy = $this->pdo->prepare($buyQuery);
    $stmtBuy->execute(['user_id' => $user_id]);
    $buyCount = $stmtBuy->fetch(PDO::FETCH_OBJ)->buy_count;

    // Check if either count is at least 10
    return $mintCount >= 10 || $buyCount >= 10;
  }

  // fullVerification
  public function fullVerification($userId)
  {

    $sql = "UPDATE `reg_details` SET `badge_verification` = 1";
    $stmt = $this->pdo->query($sql);
    $stmt->execute() ?? false;
  }

  // Function to get the sum total of NFT prices for a specific user
  public function getTotalNftPriceByUser($userId)
  {
    try {
      $query = "SELECT SUM(price) AS total_price FROM all_nft WHERE owner_id = :userId";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(':userId', $userId);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_OBJ);
      return $result->total_price ?? 0;
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return 0;
    }
  }

  // Function to get the lowest NFT price for a specific user
  public function getLowestNftPriceByUser($userId)
  {
    try {
      $query = "SELECT MIN(price) AS lowest_price FROM all_nft WHERE owner_id = :userId";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetch(PDO::FETCH_OBJ);
      return $result->lowest_price ?? 0; // Return null if no NFTs found

    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return null;
    }
  }

  public function getAllNftsOwnedByUser($userId)
  {
    try {
      $query = "SELECT * FROM all_nft WHERE owner_id = :userId";
      $stmt = $this->pdo->prepare($query);
      $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
      $stmt->execute();

      // Fetch all NFTs owned by the user as an associative array
      $nfts = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $nfts; // Return an array of NFTs
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
      return [];
    }
  }

  public function getNFTId()
  {

    do {
      $nftId = generateFakeAddress();

      $stmt = $this->pdo->prepare("SELECT * FROM all_nft WHERE link_id = :id");
      $stmt->bindParam(':id', $nftId, PDO::PARAM_STR);
      $stmt->execute();

      $convoId = $stmt->fetch(PDO::FETCH_OBJ);
    } while ($convoId);


    return $nftId; // Return unique chat ID

  }
  public function checkUserVerificationStatus($userId)
  {
    $query = "SELECT badge_verification FROM reg_details WHERE id = :userId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['badge_verification'] == 1 ? true : false; // Return null if user verification status is not found
  }

  public function sendMailToUser($name, $email, $code)
  {
    $message = '

      <html>

          <head>
            <meta charset="UTF-8" />
            <meta content="width=device-width, initial-scale=1" name="viewport" />
            <meta name="x-apple-disable-message-reformatting" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta content="telephone=no" name="format-detection" />
            <title>New message 2</title>

            <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
            <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />

            <style>

              a.es-button {
                mso-style-priority: 100 !important;
                text-decoration: none !important;
              }

              a.es-button {
                border-width: 0 !important;
                padding: 10px 30px 10px 30px !important;
                mso-style-priority: 100 !important;
                text-decoration: none;
                -webkit-text-size-adjust: none;
                -ms-text-size-adjust: none;
                mso-line-height-rule: exactly;
                color: #ffffff;
                padding: 20px;
                font-size: 20px;
                border-width: 10px 30px 10px 30px;
                display: inline-block;
                background: #7952B3;
                border-radius: 6px;
                font-family: arial, "helvetica neue",
                  helvetica, sans-serif;
                width: auto;
                text-align: center;
                border-left-width: 30px;
                border-right-width: 30px;
              }

              @media only screen and (max-width: 600px) {

                p,
                ul li,
                ol li,
                a {
                  line-height: 150% !important;
                }

                h1,
                h2,
                h3,
                h1 a,
                h2 a,
                h3 a {
                  line-height: 120% !important;
                }

                h1 {
                  font-size: 36px !important;
                  text-align: left;
                }

                h2 {
                  font-size: 26px !important;
                  text-align: left;
                }

                h3 {
                  font-size: 20px !important;
                  text-align: left;
                }

                .es-header-body h1 a,
                .es-content-body h1 a,
                .es-footer-body h1 a {
                  font-size: 36px !important;
                  text-align: left;
                }

                .es-header-body h2 a,
                .es-content-body h2 a,
                .es-footer-body h2 a {
                  font-size: 26px !important;
                  text-align: left;
                }

                .es-header-body h3 a,
                .es-content-body h3 a,
                .es-footer-body h3 a {
                  font-size: 20px !important;
                  text-align: left;
                }

                .es-menu td a {
                  font-size: 12px !important;
                }

                .es-header-body p,
                .es-header-body ul li,
                .es-header-body ol li,
                .es-header-body a {
                  font-size: 14px !important;
                }

                .es-content-body p,
                .es-content-body ul li,
                .es-content-body ol li,
                .es-content-body a {
                  font-size: 14px !important;
                }

                .es-footer-body p,
                .es-footer-body ul li,
                .es-footer-body ol li,
                .es-footer-body a {
                  font-size: 14px !important;
                }

                .es-m-txt-c,
                .es-m-txt-c h1,
                .es-m-txt-c h2,
                .es-m-txt-c h3 {
                  text-align: center !important;
                }

                .es-button-border {
                  display: inline-block !important;
                }

                a.es-button,
                button.es-button {
                  font-size: 20px !important;
                  display: inline-block !important;
                }

                .es-adaptive table,
                .es-left,
                .es-right {
                  width: 100% !important;
                }

                .es-content table,
                .es-header table,
                .es-footer table,
                .es-content,
                .es-footer,
                .es-header {
                  width: 100% !important;
                  max-width: 600px !important;
                }

                .adapt-img {
                  width: 100% !important;
                  height: auto !important;
                }

                .es-m-p0r {
                  padding-right: 0 !important;
                }

                .es-mobile-hidden,
                .es-hidden {
                  display: none !important;
                }

                .es-menu td {
                  width: 1% !important;
                }

              }
            </style>
          </head>

          <body style="
                width: 100%;
                font-family: arial, "helvetica neue", helvetica, sans-serif;
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
                padding: 0;
                margin: 0;
              ">
            <div class="es-wrapper-color" style="background-color: #fafafa">

              <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    border-collapse: collapse;
                    border-spacing: 0px;
                    padding: 0;
                    margin: 0;
                    width: 100%;
                    height: 100%;
                    background-repeat: repeat;
                    background-position: center top;
                    background-color: #fafafa;
                  ">
                <tr>
                  <td valign="top" style="padding: 0; margin: 0">
                    <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                          mso-table-lspace: 0pt;
                          mso-table-rspace: 0pt;
                          border-collapse: collapse;
                          border-spacing: 0px;
                          table-layout: fixed !important;
                          width: 100%;
                        ">
                      <tr>
                        <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                          <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                background-color: transparent;
                                width: 600px;
                              " bgcolor="#FFFFFF">
                            <tr>
                              <td align="left" style="padding: 20px; margin: 0">
                                <table cellpadding="0" cellspacing="0" width="100%" style="
                                      mso-table-lspace: 0pt;
                                      mso-table-rspace: 0pt;
                                      border-collapse: collapse;
                                      border-spacing: 0px;
                                    ">
                                  <tr>
                                    <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                          ">
                                        <tr>
                                          <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="
                          mso-table-lspace: 0pt;
                          mso-table-rspace: 0pt;
                          border-collapse: collapse;
                          border-spacing: 0px;
                          table-layout: fixed !important;
                          width: 100%;
                          background-color: transparent;
                          background-repeat: repeat;
                          background-position: center top;
                        ">
                      <tr>
                        <td align="center" style="padding: 0; margin: 0">
                          <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                background-color: transparent;
                                width: 600px;
                              ">
                            <tr>
                              <td align="left" style="padding: 0; margin: 0">
                                <table cellpadding="0" cellspacing="0" width="100%" style="
                                      mso-table-lspace: 0pt;
                                      mso-table-rspace: 0pt;
                                      border-collapse: collapse;
                                      border-spacing: 0px;
                                    ">
                                  <tr>
                                    <td class="es-m-p0r" valign="top" align="center" style="padding: 0; margin: 0; width: 600px">
                                      <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                          ">
                                        <tr>
                                          <td align="center" style="
                                                padding: 0;
                                                margin: 0;
                                                padding-top: 5px;
                                                padding-bottom: 10px;
                                                font-size: 0px;
                                              ">
                                            <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                                  display: block;
                                                  border: 0;
                                                  outline: none;
                                                  text-decoration: none;
                                                  -ms-interpolation-mode: bicubic;
                                                  font-size: 12px;
                                                " width="100%" title="Logo" class="adapt-img" height="100%" />
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                          mso-table-lspace: 0pt;
                          mso-table-rspace: 0pt;
                          border-collapse: collapse;
                          border-spacing: 0px;
                          table-layout: fixed !important;
                          width: 100%;
                        ">
                      <tr>
                        <td align="center" style="padding: 0; margin: 0">
                          <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                background-color: #ffffff;
                                width: 600px;
                              ">
                            <tr>
                              <td align="left" style="
                                    padding: 0;
                                    margin: 0;
                                    padding-top: 15pxjo;
                                    padding-left: 20px;
                                    padding-right: 20px;
                                  ">
                                <table cellpadding="0" cellspacinlg="0" width="100%" style="
                                      mso-table-lspace: 0pt;
                                      mso-table-rspace: 0pt;
                                      border-collapse: collapse;
                                      border-spacing: 0px;
                                    ">
                                  <tr>
                                    <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                      <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                          ">
                                        
                                        <tr>
                                          <td align="center" class="es-m-txt-c" style="
                                                padding: 0;
                                                margin: 0;
                                                padding-top: 15px;
                                                padding-bottom: 15px;
                                              ">
                                            <h1 style="
                                                  margin: 0;
                                                  line-height: 55px;
                                                  mso-line-height-rule: exactly;
                                                  font-family: roboto, sans-serif;
                                                  font-size: 40px;
                                                  font-style: normal;
                                                  font-weight: bold;
                                                  color: #333333;
                                                ">
                                              Email Verification
                                            </h1>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="left" style="
                                                padding: 0;
                                                margin: 0;
                                                padding-top: 10px;
                                                padding-bottom: 10px;
                                              ">
                                            <p style="
                                                  margin: 0;
                                                  -webkit-text-size-adjust: none;
                                                  -ms-text-size-adjust: none;
                                                  mso-line-height-rule: exactly;
                                                  font-family: arial, "helvetica neue",
                                                    helvetica, sans-serif;
                                                  line-height: 24px;
                                                  color: #333333;
                                                  font-size: 16px;
                                                ">
                                              <span style="
                                                    font-family: "source sans pro",
                                                      "helvetica neue", helvetica, arial,
                                                      sans-serif;
                                                  ">Hello, ' . $name . '.<br /><br /> Thank you for joining Kreptive! We require you to validate your email address before you can access your portal on Kreptive. <br />To achieve this, kindly enter the code below. </p>
                                            </p>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td class="esdev-adapt-off" align="left" style="padding: 20px; margin: 0">
                                <table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="
                                      mso-table-lspace: 0pt;
                                      mso-table-rspace: 0pt;
                                      border-collapse: collapse;
                                      border-spacing: 0px;
                                      width: 560px;
                                    ">
                                  <tr>
                                    <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                      <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            float: left;
                                          ">
                                        <tr class="es-mobile-hidden">
                                          <td align="left" style="padding: 0; margin: 0; width: 146px">
                                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                                  mso-table-lspace: 0pt;
                                                  mso-table-rspace: 0pt;
                                                  border-collapse: collapse;
                                                  border-spacing: 0px;
                                                ">
                                              <tr>
                                                <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                              </tr>
                                            </table>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                      <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            float: left;
                                          ">
                                        <tr>
                                          <td align="left" style="padding: 0; margin: 0; width: 121px">
                                            <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#7952B3" style="
                                                  mso-table-lspace: 0pt;
                                                  mso-table-rspace: 0pt;
                                                  border-collapse: separate;
                                                  border-spacing: 0px;
                                                  border-radius: 10px 0 0 10px;
                                                " role="presentation">
                                              <tr>
                                                <td align="right" style="
                                                      padding: 15px 0;
                                                      margin: 0;
                                                      
                                                    ">
                                                  <p style="
                                                        margin: 0;
                                                        -webkit-text-size-adjust: none;
                                                        -ms-text-size-adjust: none;
                                                        mso-line-height-rule: exactly;
                                                        font-family: roboto, sans-serif;
                                                  background-color: #7952B3;
                                                        line-height: 21px;
                                                        color: #fff;
                                                        font-size: 24px;
                                                        font-weight:700;

                                                      ">
                                                    Code:
                                                  </p>
                                                </td>
                                              </tr>
                                            
                                            </table>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                      <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            float: left;
                                          ">
                                        <tr>
                                          <td align="left" style="padding: 0; margin: 0; width: 155px">
                                            <table cellpadding="0" cellspacing="0" width="100%" bgcolor="#7952B3" style="
                                                  mso-table-lspace: 0pt;
                                                  mso-table-rspace: 0pt;
                                                  border-collapse: separate;
                                                  border-spacing: 0px;
                                                  background-color: #7952B3;
                                                  border-radius: 0 10px 10px 0;
                                                " role="presentation">
                                              <tr>
                                                <td align="left" style="
                                                      padding: 15px 0;
                                                      margin: 0;
                                                  
                                                    ">
                                                  <p style="
                                                        margin: 0;
                                                        -webkit-text-size-adjust: none;
                                                        -ms-text-size-adjust: none;
                                                        mso-line-height-rule: exactly;
                                                        font-family: roboto, sans-serif;
                                                        line-height: 21px;
                                                        color: #fff;
                                                        font-size: 24px;
                                                        font-weight: 700;
                                                      ">
                                                    <strong>' . $code . '</strong>
                                                  </p>
                                                </td>
                                              </tr>
                                            </table>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                    <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                      <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                            float: right;
                                          ">
                                        <tr class="es-mobile-hidden">
                                          <td align="left" style="padding: 0; margin: 0; width: 138px">
                                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                                  mso-table-lspace: 0pt;
                                                  mso-table-rspace: 0pt;
                                                  border-collapse: collapse;
                                                  border-spacing: 0px;
                                                ">
                                              <tr>
                                                <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                              </tr>
                                            </table>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td align="left" style="
                                    padding: 0;
                                    margin: 0;
                                    padding-bottom: 10px;
                                    padding-left: 20px;
                                    padding-right: 20px;
                                  ">
                                <table cellpadding="0" cellspacing="0" width="100%" style="
                                      mso-table-lspace: 0pt;
                                      mso-table-rspace: 0pt;
                                      border-collapse: collapse;
                                      border-spacing: 0px;
                                    ">
                                  <tr>
                                    <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: separate;
                                            border-spacing: 0px;
                                            border-radius: 5px;
                                          " role="presentation">
                                        <tr>
                                          <td align="center" style="
                                                padding: 0;
                                                margin: 0;
                                                padding-top: 10px;
                                                padding-bottom: 10px;
                                              ">
                                            
                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="left" style="
                                                padding: 0;
                                                margin: 0;
                                                padding-bottom: 10px;
                                                padding-top: 20px;
                                              ">
                                            <p style="
                                                  margin: 0;
                                                  -webkit-text-size-adjust: none;
                                                  -ms-text-size-adjust: none;
                                                  mso-line-height-rule: exactly;
                                                  
                                                    font-family: "source sans pro",
                                                      "helvetica neue", helvetica, arial,
                                                      sans-serif;
                                                  
                                                  color: #333333;
                                                  font-size: 14px;
                                                ">
                                            
                                            </p>
                                            <p style="
                                                  margin: 0;
                                                  -webkit-text-size-adjust: none;
                                                  -ms-text-size-adjust: none;
                                                  mso-line-height-rule: exactly;
                                                  
                                                    font-family: "source sans pro",
                                                      "helvetica neue", helvetica, arial,
                                                      sans-serif;
                                                  
                                                  color: #333333;
                                                  font-size: 14px;
                                                ">
                                              <br />Thanks,
                                            </p>
                                            <p style="
                                                  margin: 0;
                                                  -webkit-text-size-adjust: none;
                                                  -ms-text-size-adjust: none;
                                                  mso-line-height-rule: exactly;
                                                  
                                                    font-family: "source sans pro",
                                                      "helvetica neue", helvetica, arial,
                                                      sans-serif;
                                                  
                                                  color: #333333;
                                                  font-size: 14px;
                                                ">
                                            Kreptive Team
                                            </p>
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="
                          mso-table-lspace: 0pt;
                          mso-table-rspace: 0pt;
                          border-collapse: collapse;
                          border-spacing: 0px;
                          table-layout: fixed !important;
                          width: 100%;
                          background-color: transparent;
                          background-repeat: repeat;
                          background-position: center top;
                        ">
                      <tr>
                        <td align="center" style="padding: 0; margin: 0">
                          <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                background-color: transparent;
                                width: 640px;
                              ">
                            <tr>
                              <td align="left" style="
                                    margin: 0;
                                    padding-top: 20px;
                                    padding-bottom: 20px;
                                    padding-left: 20px;
                                    padding-right: 20px;
                                  ">
                                <table cellpadding="0" cellspacing="0" width="100%" style="
                                      mso-table-lspace: 0pt;
                                      mso-table-rspace: 0pt;
                                      border-collapse: collapse;
                                      border-spacing: 0px;
                                    ">
                                  <tr>
                                    <td align="left" style="padding: 0; margin: 0; width: 600px">
                                      <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                          ">
                                        
                                        <tr>
                                          <td align="center" style="
                                                padding: 0;
                                                margin: 0;
                                                padding-bottom: 25px;
                                                padding-top: 30px;
                                              ">
                                            <p style="
                                                  margin: 0;
                                                  -webkit-text-size-adjust: none;
                                                  -ms-text-size-adjust: none;
                                                  mso-line-height-rule: exactly;
                                                  font-family: arial, "helvetica neue",
                                                    helvetica, sans-serif;
                                                  line-height: 18px;
                                                  color: #333333;
                                                  font-size: 12px;
                                                ">
                                              Kreptive © ' . date('Y') . '. All
                                              Rights Reserved.
                                            </p>
                                            
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                          mso-table-lspace: 0pt;
                          mso-table-rspace: 0pt;
                          border-collapse: collapse;
                          border-spacing: 0px;
                          table-layout: fixed !important;
                          width: 100%;
                        ">
                      <tr>
                        <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                          <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                mso-table-lspace: 0pt;
                                mso-table-rspace: 0pt;
                                border-collapse: collapse;
                                border-spacing: 0px;
                                background-color: transparent;
                                width: 600px;
                              " bgcolor="#FFFFFF">
                            <tr>
                              <td align="left" style="padding: 20px; margin: 0">
                                <table cellpadding="0" cellspacing="0" width="100%" style="
                                      mso-table-lspace: 0pt;
                                      mso-table-rspace: 0pt;
                                      border-collapse: collapse;
                                      border-spacing: 0px;
                                    ">
                                  <tr>
                                    <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                      <table cellpadding="0" cellspacing="0" width="100%" style="
                                            mso-table-lspace: 0pt;
                                            mso-table-rspace: 0pt;
                                            border-collapse: collapse;
                                            border-spacing: 0px;
                                          ">
                                        <tr>
                                          <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </div>
          </body>

      </html>
      
    ';


    try {
      $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
      $bname = "Kreptive";


      $mail->isSMTP();
      $mail->Host = "smtp.hostinger.com";
      $mail->SMTPAuth = true;
      $mail->Username = "support@kreptive.com";
      $mail->Password = 'lwsK7|Or';
      $mail->SMTPSecure = "ssl";
      $mail->Port = 465;

      $mail->setFrom("support@kreptive.com", $bname);
      $mail->addAddress($email);
      $mail->isHTML(true);

      $mail->Subject = "Verify your Email";
      $mail->Body = $message;


      if ($mail->send()) {
        return true;
      } else {
        return false;
      }
    } catch (Exception $e) {
      return false;
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }

  public function sendAdminKycEmail($name, $email, $username, $document_type, $time)
  {

    $message = "
          <!DOCTYPE html>
          <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>KYC Document Upload Notification</title>
                  <style>
                      body { font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; }
                      .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
                      .header { background-color: #007bff; color: #ffffff; padding: 15px; text-align: center; border-top-left-radius: 10px; border-top-right-radius: 10px; }
                      .content { font-size: 16px; color: #555; }
                      .footer { margin-top: 20px; font-size: 14px; color: #777; }
                      .highlight { color: #28a745; }
                  </style>
              </head>
              <body>
                  <div class='container'>
                      <div class='header'>
                          <h1>KYC Document Upload Notification</h1>
                      </div>
                      <div class='content'>
                          <p>Dear Admin,</p>
                          <p>A new KYC document has been uploaded by <strong class='highlight'>$name</strong> for verification.</p>
                          <p><strong>Document Details:</strong></p>
                          <ul>
                              <li><strong>Username:</strong> $username</li>
                              <li><strong>Document Type:</strong> $document_type</li>
                              <li><strong>Submission Date | Time:</strong> $time</li>
                          </ul>
                          <p>Please review the document and take the necessary action for verification.</p>
                      </div>
                      <div class='footer'>
                          <p>This is an automated message, please do not reply.</p>
                      </div>
                  </div>
              </body>
          </html>
      ";

    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "QuantumSyncLedger";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = "lwsK7|Or";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("support@kreptive.com", $bname);
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "Withdrawal Alert";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }


  public function sendDepositMail($name, $email, $amount)
  {

    $message = '

<html>

<head>
  <meta charset="UTF-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="x-apple-disable-message-reformatting" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta content="telephone=no" name="format-detection" />
  <title>New message 2</title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />

  <style>
    *{
      font-family: roboto, "helvetica neue" !important;
    }
    a.es-button {
      mso-style-priority: 100 !important;
      text-decoration: none !important;
    }

    a.es-button {
      border-width: 0 !important;
      padding: 10px 30px 10px 30px !important;
      mso-style-priority: 100 !important;
      text-decoration: none;
      -webkit-text-size-adjust: none;
      -ms-text-size-adjust: none;
      mso-line-height-rule: exactly;
      color: #ffffff;
      padding: 20px;
      font-size: 20px;
      border-width: 10px 30px 10px 30px;
      display: inline-block;
      background: #7952B3;
      border-radius: 6px;
      font-family: arial, "helvetica neue",
        helvetica, sans-serif;
      width: auto;
      text-align: center;
      border-left-width: 30px;
      border-right-width: 30px;
    }

    @media only screen and (max-width: 600px) {

      p,
      ul li,
      ol li,
      a {
        line-height: 150% !important;
      }

      h1,
      h2,
      h3,
      h1 a,
      h2 a,
      h3 a {
        line-height: 120% !important;
      }

      h1 {
        font-size: 36px !important;
        text-align: left;
      }

      h2 {
        font-size: 26px !important;
        text-align: left;
      }

      h3 {
        font-size: 20px !important;
        text-align: left;
      }

      .es-header-body h1 a,
      .es-content-body h1 a,
      .es-footer-body h1 a {
        font-size: 36px !important;
        text-align: left;
      }

      .es-header-body h2 a,
      .es-content-body h2 a,
      .es-footer-body h2 a {
        font-size: 26px !important;
        text-align: left;
      }

      .es-header-body h3 a,
      .es-content-body h3 a,
      .es-footer-body h3 a {
        font-size: 20px !important;
        text-align: left;
      }

      .es-menu td a {
        font-size: 12px !important;
      }

      .es-header-body p,
      .es-header-body ul li,
      .es-header-body ol li,
      .es-header-body a {
        font-size: 14px !important;
      }

      .es-content-body p,
      .es-content-body ul li,
      .es-content-body ol li,
      .es-content-body a {
        font-size: 14px !important;
      }

      .es-footer-body p,
      .es-footer-body ul li,
      .es-footer-body ol li,
      .es-footer-body a {
        font-size: 14px !important;
      }

      .es-m-txt-c,
      .es-m-txt-c h1,
      .es-m-txt-c h2,
      .es-m-txt-c h3 {
        text-align: center !important;
      }

      .es-button-border {
        display: inline-block !important;
      }

      a.es-button,
      button.es-button {
        font-size: 20px !important;
        display: inline-block !important;
      }

      .es-adaptive table,
      .es-left,
      .es-right {
        width: 100% !important;
      }

      .es-content table,
      .es-header table,
      .es-footer table,
      .es-content,
      .es-footer,
      .es-header {
        width: 100% !important;
        max-width: 600px !important;
      }

      .adapt-img {
        width: 100% !important;
        height: auto !important;
      }

      .es-m-p0r {
        padding-right: 0 !important;
      }

      .es-mobile-hidden,
      .es-hidden {
        display: none !important;
      }

      .es-menu td {
        width: 1% !important;
      }

    }
  </style>
</head>

<body style="
      width: 100%;
      font-family: arial, "helvetica neue", helvetica, sans-serif;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    ">
  <div class="es-wrapper-color" style="background-color: #fafafa">

    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #fafafa;
        ">
      <tr>
        <td valign="top" style="padding: 0; margin: 0">
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="padding: 0; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td class="es-m-p0r" valign="top" align="center" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 10px;
                                      font-size: 0px;
                                    ">
                                  <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                        font-size: 12px;
                                      " width="100%" title="Logo" class="adapt-img" height="100%" />
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-top: 15px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacinlg="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              
                              <tr>
                                <td align="center" class="es-m-txt-c" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 15px;
                                      padding-bottom: 15px;
                                    ">
                                  <h1 style="
                                        margin: 0;
                                        line-height: 55px;
                                        mso-line-height-rule: exactly;
                                        font-family: roboto, "helvetica neue",
                                          helvetica, arial, sans-serif;
                                        font-size: 46px;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #333333;
                                      ">
                                   Deposit Confirmation
                                  </h1>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 24px;
                                        color: #333333;
                                        font-size: 16px;
                                      ">
                                    <span style="
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        ">Hello, ' . $name . '.<br /><br /> 
                                         Your deposit of ' . $amount . 'ETH has been confirmed and your available balance has been updated <br /><br />Kindly login to your portal to preview. <br /><br />
                                         Thanks for choosing Kreptive!
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td class="esdev-adapt-off" align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            width: 560px;
                          ">
                        <tr>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 146px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                             
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: right;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 138px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-bottom: 10px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                  border-radius: 5px;
                                " role="presentation">
                              <tr>
                                 <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                 
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 10px;
                                      padding-top: 20px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    Got a question? Respond to this mail and
                                    will be at service in to time.
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    <br />Thanks,
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                   Kreptive Team
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 640px;
                    ">
                  <tr>
                    <td align="left" style="
                          margin: 0;
                          padding-top: 20px;
                          padding-bottom: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="left" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr class="links">
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                          ">
                                        <a target="_blank" href="https://www.kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Visit Us
                                        </a>
                                        
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="mailto:support@kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Contact Us</a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Terms of Use</a>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 25px;
                                      padding-top: 30px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 18px;
                                        color: #333333;
                                        font-size: 12px;
                                      ">
                                    Kreptive © ' . date('Y') . ' Inc. All
                                    Rights Reserved.
                                  </p>
                                  
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>';
    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "Kreptive";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = 'lwsK7|Or';
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("support@kreptive.com", $bname);
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "Deposit Confirmation";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }
  public function sendWithdrawalRequestMail($name, $email, $amount, $addr)
  {

    $message = '

      <html>

      <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="x-apple-disable-message-reformatting" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="telephone=no" name="format-detection" />
        <title>New message 2</title>

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />

        <style>
          *{
            font-family: roboto, "helvetica neue" !important;
          }
          a.es-button {
            mso-style-priority: 100 !important;
            text-decoration: none !important;
          }

          a.es-button {
            border-width: 0 !important;
            padding: 10px 30px 10px 30px !important;
            mso-style-priority: 100 !important;
            text-decoration: none;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            mso-line-height-rule: exactly;
            color: #ffffff;
            padding: 20px;
            font-size: 20px;
            border-width: 10px 30px 10px 30px;
            display: inline-block;
            background: #7952B3;
            border-radius: 6px;
            font-family: arial, "helvetica neue",
              helvetica, sans-serif;
            width: auto;
            text-align: center;
            border-left-width: 30px;
            border-right-width: 30px;
          }

          @media only screen and (max-width: 600px) {

            p,
            ul li,
            ol li,
            a {
              line-height: 150% !important;
            }

            h1,
            h2,
            h3,
            h1 a,
            h2 a,
            h3 a {
              line-height: 120% !important;
            }

            h1 {
              font-size: 36px !important;
              text-align: left;
            }

            h2 {
              font-size: 26px !important;
              text-align: left;
            }

            h3 {
              font-size: 20px !important;
              text-align: left;
            }

            .es-header-body h1 a,
            .es-content-body h1 a,
            .es-footer-body h1 a {
              font-size: 36px !important;
              text-align: left;
            }

            .es-header-body h2 a,
            .es-content-body h2 a,
            .es-footer-body h2 a {
              font-size: 26px !important;
              text-align: left;
            }

            .es-header-body h3 a,
            .es-content-body h3 a,
            .es-footer-body h3 a {
              font-size: 20px !important;
              text-align: left;
            }

            .es-menu td a {
              font-size: 12px !important;
            }

            .es-header-body p,
            .es-header-body ul li,
            .es-header-body ol li,
            .es-header-body a {
              font-size: 14px !important;
            }

            .es-content-body p,
            .es-content-body ul li,
            .es-content-body ol li,
            .es-content-body a {
              font-size: 14px !important;
            }

            .es-footer-body p,
            .es-footer-body ul li,
            .es-footer-body ol li,
            .es-footer-body a {
              font-size: 14px !important;
            }

            .es-m-txt-c,
            .es-m-txt-c h1,
            .es-m-txt-c h2,
            .es-m-txt-c h3 {
              text-align: center !important;
            }

            .es-button-border {
              display: inline-block !important;
            }

            a.es-button,
            button.es-button {
              font-size: 20px !important;
              display: inline-block !important;
            }

            .es-adaptive table,
            .es-left,
            .es-right {
              width: 100% !important;
            }

            .es-content table,
            .es-header table,
            .es-footer table,
            .es-content,
            .es-footer,
            .es-header {
              width: 100% !important;
              max-width: 600px !important;
            }

            .adapt-img {
              width: 100% !important;
              height: auto !important;
            }

            .es-m-p0r {
              padding-right: 0 !important;
            }

            .es-mobile-hidden,
            .es-hidden {
              display: none !important;
            }

            .es-menu td {
              width: 1% !important;
            }

          }
        </style>
      </head>

      <body style="
            width: 100%;
            font-family: arial, "helvetica neue", helvetica, sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            padding: 0;
            margin: 0;
          ">
        <div class="es-wrapper-color" style="background-color: #fafafa">

          <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                padding: 0;
                margin: 0;
                width: 100%;
                height: 100%;
                background-repeat: repeat;
                background-position: center top;
                background-color: #fafafa;
              ">
            <tr>
              <td valign="top" style="padding: 0; margin: 0">
                <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                    ">
                  <tr>
                    <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                      <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 600px;
                          " bgcolor="#FFFFFF">
                        <tr>
                          <td align="left" style="padding: 20px; margin: 0">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                      background-color: transparent;
                      background-repeat: repeat;
                      background-position: center top;
                    ">
                  <tr>
                    <td align="center" style="padding: 0; margin: 0">
                      <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 600px;
                          ">
                        <tr>
                          <td align="left" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td class="es-m-p0r" valign="top" align="center" style="padding: 0; margin: 0; width: 600px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 5px;
                                            padding-bottom: 10px;
                                            font-size: 0px;
                                          ">
                                        <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                              display: block;
                                              border: 0;
                                              outline: none;
                                              text-decoration: none;
                                              -ms-interpolation-mode: bicubic;
                                              font-size: 12px;
                                            " width="100%" title="Logo" class="adapt-img" height="100%" />
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                    ">
                  <tr>
                    <td align="center" style="padding: 0; margin: 0">
                      <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: #ffffff;
                            width: 600px;
                          ">
                        <tr>
                          <td align="left" style="
                                padding: 0;
                                margin: 0;
                                padding-top: 15px;
                                padding-left: 20px;
                                padding-right: 20px;
                              ">
                            <table cellpadding="0" cellspacinlg="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    
                                    <tr>
                                      <td align="center" class="es-m-txt-c" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 15px;
                                            padding-bottom: 15px;
                                          ">
                                        <h1 style="
                                              margin: 0;
                                              line-height: 55px;
                                              mso-line-height-rule: exactly;
                                              font-family: roboto, "helvetica neue",
                                                helvetica, arial, sans-serif;
                                              font-size: 46px;
                                              font-style: normal;
                                              font-weight: bold;
                                              color: #333333;
                                            ">
                                        Withdrawal Confirmation
                                        </h1>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                          ">
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              font-family: arial, "helvetica neue",
                                                helvetica, sans-serif;
                                              line-height: 24px;
                                              color: #333333;
                                              font-size: 16px;
                                            ">
                                          <span>Hello, ' . $name . '.<br /><br /> 
                                              Your withdrawal request of ' . $amount . 'ETH has been confirmed and is currently processing. <br /><br />
                                              
                                              It would be sent to the wallet address below once confirmed <br /><br /><b>' . $addr . '</b> <br /><br />
                                              Thanks for choosing Kreptive!
                                        </p>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td class="esdev-adapt-off" align="left" style="padding: 20px; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  width: 560px;
                                ">
                              <tr>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: left;
                                      ">
                                    <tr class="es-mobile-hidden">
                                      <td align="left" style="padding: 0; margin: 0; width: 146px">
                                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr>
                                            <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: left;
                                      ">
                                  
                                  </table>
                                </td>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: left;
                                      ">
                                    
                                  </table>
                                </td>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: right;
                                      ">
                                    <tr class="es-mobile-hidden">
                                      <td align="left" style="padding: 0; margin: 0; width: 138px">
                                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr>
                                            <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="left" style="
                                padding: 0;
                                margin: 0;
                                padding-bottom: 10px;
                                padding-left: 20px;
                                padding-right: 20px;
                              ">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: separate;
                                        border-spacing: 0px;
                                        border-radius: 5px;
                                      " role="presentation">
                                    <tr>
                                      <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                          ">
                                        
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-bottom: 10px;
                                            padding-top: 20px;
                                          ">
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              
                                                font-family: "source sans pro",
                                                  "helvetica neue", helvetica, arial,
                                                  sans-serif;
                                              
                                              color: #333333;
                                              font-size: 14px;
                                            ">
                                          Got a question? Respond to this mail and
                                          will be at service in to time.
                                        </p>
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              
                                                font-family: "source sans pro",
                                                  "helvetica neue", helvetica, arial,
                                                  sans-serif;
                                              
                                              color: #333333;
                                              font-size: 14px;
                                            ">
                                          <br />Thanks,
                                        </p>
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              
                                                font-family: "source sans pro",
                                                  "helvetica neue", helvetica, arial,
                                                  sans-serif;
                                              
                                              color: #333333;
                                              font-size: 14px;
                                            ">
                                        Kreptive Team
                                        </p>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                      background-color: transparent;
                      background-repeat: repeat;
                      background-position: center top;
                    ">
                  <tr>
                    <td align="center" style="padding: 0; margin: 0">
                      <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 640px;
                          ">
                        <tr>
                          <td align="left" style="
                                margin: 0;
                                padding-top: 20px;
                                padding-bottom: 20px;
                                padding-left: 20px;
                                padding-right: 20px;
                              ">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="left" style="padding: 0; margin: 0; width: 600px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td style="padding: 0; margin: 0">
                                        <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr class="links">
                                            <td align="center" valign="top" width="33.33%" style="
                                                  margin: 0;
                                                  padding-left: 5px;
                                                  padding-right: 5px;
                                                  padding-top: 7px;
                                                  padding-bottom: 7px;
                                                  border: 0;
                                                ">
                                              <a target="_blank" href="https://www.kreptive.com" style="
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    text-decoration: none;
                                                    display: block;
                                                    font-family: arial,
                                                      "helvetica neue", helvetica,
                                                      sans-serif;
                                                    color: #999999;
                                                    font-size: 12px;
                                                  ">Visit Us
                                              </a>
                                            </td>
                                            <td align="center" valign="top" width="33.33%" style="
                                                  margin: 0;
                                                  padding-left: 5px;
                                                  padding-right: 5px;
                                                  padding-top: 7px;
                                                  padding-bottom: 7px;
                                                  border: 0;
                                                  border-left: 1px solid #cccccc;
                                                ">
                                              <a target="_blank" href="mailto:support@kreptive.com" style="
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    text-decoration: none;
                                                    display: block;
                                                    font-family: arial,
                                                      "helvetica neue", helvetica,
                                                      sans-serif;
                                                    color: #999999;
                                                    font-size: 12px;
                                                  ">Contact Us</a>
                                            </td>
                                            <td align="center" valign="top" width="33.33%" style="
                                                  margin: 0;
                                                  padding-left: 5px;
                                                  padding-right: 5px;
                                                  padding-top: 7px;
                                                  padding-bottom: 7px;
                                                  border: 0;
                                                  border-left: 1px solid #cccccc;
                                                ">
                                              <a target="_blank" href="" style="
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    text-decoration: none;
                                                    display: block;
                                                    font-family: arial,
                                                      "helvetica neue", helvetica,
                                                      sans-serif;
                                                    color: #999999;
                                                    font-size: 12px;
                                                  ">Terms of Use</a>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-bottom: 25px;
                                            padding-top: 30px;
                                          ">
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              font-family: arial, "helvetica neue",
                                                helvetica, sans-serif;
                                              line-height: 18px;
                                              color: #333333;
                                              font-size: 12px;
                                            ">
                                          Kreptive © ' . date('Y') . ' Inc. All
                                          Rights Reserved.
                                        </p>
                                        
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                    ">
                  <tr>
                    <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                      <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 600px;
                          " bgcolor="#FFFFFF">
                        <tr>
                          <td align="left" style="padding: 20px; margin: 0">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </body>

      </html>
    ';
    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "Kreptive";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = 'lwsK7|Or';
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("support@kreptive.com", $bname);
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "Withdrawal Confirmation";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }
  public function sendWithdrawalMail($name, $email, $amount, $addr)
  {

    $message = '

      <html>

      <head>
        <meta charset="UTF-8" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="x-apple-disable-message-reformatting" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta content="telephone=no" name="format-detection" />
        <title>New message 2</title>

        <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />

        <style>
          *{
            font-family: roboto, "helvetica neue" !important;
          }
          a.es-button {
            mso-style-priority: 100 !important;
            text-decoration: none !important;
          }

          a.es-button {
            border-width: 0 !important;
            padding: 10px 30px 10px 30px !important;
            mso-style-priority: 100 !important;
            text-decoration: none;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            mso-line-height-rule: exactly;
            color: #ffffff;
            padding: 20px;
            font-size: 20px;
            border-width: 10px 30px 10px 30px;
            display: inline-block;
            background: #7952B3;
            border-radius: 6px;
            font-family: arial, "helvetica neue",
              helvetica, sans-serif;
            width: auto;
            text-align: center;
            border-left-width: 30px;
            border-right-width: 30px;
          }

          @media only screen and (max-width: 600px) {

            p,
            ul li,
            ol li,
            a {
              line-height: 150% !important;
            }

            h1,
            h2,
            h3,
            h1 a,
            h2 a,
            h3 a {
              line-height: 120% !important;
            }

            h1 {
              font-size: 36px !important;
              text-align: left;
            }

            h2 {
              font-size: 26px !important;
              text-align: left;
            }

            h3 {
              font-size: 20px !important;
              text-align: left;
            }

            .es-header-body h1 a,
            .es-content-body h1 a,
            .es-footer-body h1 a {
              font-size: 36px !important;
              text-align: left;
            }

            .es-header-body h2 a,
            .es-content-body h2 a,
            .es-footer-body h2 a {
              font-size: 26px !important;
              text-align: left;
            }

            .es-header-body h3 a,
            .es-content-body h3 a,
            .es-footer-body h3 a {
              font-size: 20px !important;
              text-align: left;
            }

            .es-menu td a {
              font-size: 12px !important;
            }

            .es-header-body p,
            .es-header-body ul li,
            .es-header-body ol li,
            .es-header-body a {
              font-size: 14px !important;
            }

            .es-content-body p,
            .es-content-body ul li,
            .es-content-body ol li,
            .es-content-body a {
              font-size: 14px !important;
            }

            .es-footer-body p,
            .es-footer-body ul li,
            .es-footer-body ol li,
            .es-footer-body a {
              font-size: 14px !important;
            }

            .es-m-txt-c,
            .es-m-txt-c h1,
            .es-m-txt-c h2,
            .es-m-txt-c h3 {
              text-align: center !important;
            }

            .es-button-border {
              display: inline-block !important;
            }

            a.es-button,
            button.es-button {
              font-size: 20px !important;
              display: inline-block !important;
            }

            .es-adaptive table,
            .es-left,
            .es-right {
              width: 100% !important;
            }

            .es-content table,
            .es-header table,
            .es-footer table,
            .es-content,
            .es-footer,
            .es-header {
              width: 100% !important;
              max-width: 600px !important;
            }

            .adapt-img {
              width: 100% !important;
              height: auto !important;
            }

            .es-m-p0r {
              padding-right: 0 !important;
            }

            .es-mobile-hidden,
            .es-hidden {
              display: none !important;
            }

            .es-menu td {
              width: 1% !important;
            }

          }
        </style>
      </head>

      <body style="
            width: 100%;
            font-family: arial, "helvetica neue", helvetica, sans-serif;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            padding: 0;
            margin: 0;
          ">
        <div class="es-wrapper-color" style="background-color: #fafafa">

          <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                padding: 0;
                margin: 0;
                width: 100%;
                height: 100%;
                background-repeat: repeat;
                background-position: center top;
                background-color: #fafafa;
              ">
            <tr>
              <td valign="top" style="padding: 0; margin: 0">
                <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                    ">
                  <tr>
                    <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                      <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 600px;
                          " bgcolor="#FFFFFF">
                        <tr>
                          <td align="left" style="padding: 20px; margin: 0">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                      background-color: transparent;
                      background-repeat: repeat;
                      background-position: center top;
                    ">
                  <tr>
                    <td align="center" style="padding: 0; margin: 0">
                      <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 600px;
                          ">
                        <tr>
                          <td align="left" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td class="es-m-p0r" valign="top" align="center" style="padding: 0; margin: 0; width: 600px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 5px;
                                            padding-bottom: 10px;
                                            font-size: 0px;
                                          ">
                                        <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                              display: block;
                                              border: 0;
                                              outline: none;
                                              text-decoration: none;
                                              -ms-interpolation-mode: bicubic;
                                              font-size: 12px;
                                            " width="100%" title="Logo" class="adapt-img" height="100%" />
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                    ">
                  <tr>
                    <td align="center" style="padding: 0; margin: 0">
                      <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: #ffffff;
                            width: 600px;
                          ">
                        <tr>
                          <td align="left" style="
                                padding: 0;
                                margin: 0;
                                padding-top: 15px;
                                padding-left: 20px;
                                padding-right: 20px;
                              ">
                            <table cellpadding="0" cellspacinlg="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    
                                    <tr>
                                      <td align="center" class="es-m-txt-c" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 15px;
                                            padding-bottom: 15px;
                                          ">
                                        <h1 style="
                                              margin: 0;
                                              line-height: 55px;
                                              mso-line-height-rule: exactly;
                                              font-family: roboto, "helvetica neue",
                                                helvetica, arial, sans-serif;
                                              font-size: 46px;
                                              font-style: normal;
                                              font-weight: bold;
                                              color: #333333;
                                            ">
                                        Withdrawal Confirmation
                                        </h1>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                          ">
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              font-family: arial, "helvetica neue",
                                                helvetica, sans-serif;
                                              line-height: 24px;
                                              color: #333333;
                                              font-size: 16px;
                                            ">
                                          <span>Hello, ' . $name . '.<br /><br /> 
                                              Your withdrawal of ' . $amount . 'ETH has been confirmed and sent to the wallet address below <br /><br /><b>' . $addr . '</b> <br /><br />
                                              Thanks for choosing Kreptive!
                                        </p>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td class="esdev-adapt-off" align="left" style="padding: 20px; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  width: 560px;
                                ">
                              <tr>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: left;
                                      ">
                                    <tr class="es-mobile-hidden">
                                      <td align="left" style="padding: 0; margin: 0; width: 146px">
                                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr>
                                            <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: left;
                                      ">
                                  
                                  </table>
                                </td>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: left;
                                      ">
                                    
                                  </table>
                                </td>
                                <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                        float: right;
                                      ">
                                    <tr class="es-mobile-hidden">
                                      <td align="left" style="padding: 0; margin: 0; width: 138px">
                                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr>
                                            <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr>
                          <td align="left" style="
                                padding: 0;
                                margin: 0;
                                padding-bottom: 10px;
                                padding-left: 20px;
                                padding-right: 20px;
                              ">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: separate;
                                        border-spacing: 0px;
                                        border-radius: 5px;
                                      " role="presentation">
                                    <tr>
                                      <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 10px;
                                            padding-bottom: 10px;
                                          ">
                                        
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="left" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-bottom: 10px;
                                            padding-top: 20px;
                                          ">
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              
                                                font-family: "source sans pro",
                                                  "helvetica neue", helvetica, arial,
                                                  sans-serif;
                                              
                                              color: #333333;
                                              font-size: 14px;
                                            ">
                                          Got a question? Respond to this mail and
                                          will be at service in to time.
                                        </p>
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              
                                                font-family: "source sans pro",
                                                  "helvetica neue", helvetica, arial,
                                                  sans-serif;
                                              
                                              color: #333333;
                                              font-size: 14px;
                                            ">
                                          <br />Thanks,
                                        </p>
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              
                                                font-family: "source sans pro",
                                                  "helvetica neue", helvetica, arial,
                                                  sans-serif;
                                              
                                              color: #333333;
                                              font-size: 14px;
                                            ">
                                        Kreptive Team
                                        </p>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                      background-color: transparent;
                      background-repeat: repeat;
                      background-position: center top;
                    ">
                  <tr>
                    <td align="center" style="padding: 0; margin: 0">
                      <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 640px;
                          ">
                        <tr>
                          <td align="left" style="
                                margin: 0;
                                padding-top: 20px;
                                padding-bottom: 20px;
                                padding-left: 20px;
                                padding-right: 20px;
                              ">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="left" style="padding: 0; margin: 0; width: 600px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td style="padding: 0; margin: 0">
                                        <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr class="links">
                                            <td align="center" valign="top" width="33.33%" style="
                                                  margin: 0;
                                                  padding-left: 5px;
                                                  padding-right: 5px;
                                                  padding-top: 7px;
                                                  padding-bottom: 7px;
                                                  border: 0;
                                                ">
                                              <a target="_blank" href="https://www.kreptive.com" style="
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    text-decoration: none;
                                                    display: block;
                                                    font-family: arial,
                                                      "helvetica neue", helvetica,
                                                      sans-serif;
                                                    color: #999999;
                                                    font-size: 12px;
                                                  ">Visit Us
                                              </a>
                                            </td>
                                            <td align="center" valign="top" width="33.33%" style="
                                                  margin: 0;
                                                  padding-left: 5px;
                                                  padding-right: 5px;
                                                  padding-top: 7px;
                                                  padding-bottom: 7px;
                                                  border: 0;
                                                  border-left: 1px solid #cccccc;
                                                ">
                                              <a target="_blank" href="mailto:support@kreptive.com" style="
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    text-decoration: none;
                                                    display: block;
                                                    font-family: arial,
                                                      "helvetica neue", helvetica,
                                                      sans-serif;
                                                    color: #999999;
                                                    font-size: 12px;
                                                  ">Contact Us</a>
                                            </td>
                                            <td align="center" valign="top" width="33.33%" style="
                                                  margin: 0;
                                                  padding-left: 5px;
                                                  padding-right: 5px;
                                                  padding-top: 7px;
                                                  padding-bottom: 7px;
                                                  border: 0;
                                                  border-left: 1px solid #cccccc;
                                                ">
                                              <a target="_blank" href="" style="
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    text-decoration: none;
                                                    display: block;
                                                    font-family: arial,
                                                      "helvetica neue", helvetica,
                                                      sans-serif;
                                                    color: #999999;
                                                    font-size: 12px;
                                                  ">Terms of Use</a>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td align="center" style="
                                            padding: 0;
                                            margin: 0;
                                            padding-bottom: 25px;
                                            padding-top: 30px;
                                          ">
                                        <p style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              font-family: arial, "helvetica neue",
                                                helvetica, sans-serif;
                                              line-height: 18px;
                                              color: #333333;
                                              font-size: 12px;
                                            ">
                                          Kreptive © ' . date('Y') . ' Inc. All
                                          Rights Reserved.
                                        </p>
                                        
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
                <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      table-layout: fixed !important;
                      width: 100%;
                    ">
                  <tr>
                    <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                      <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            background-color: transparent;
                            width: 600px;
                          " bgcolor="#FFFFFF">
                        <tr>
                          <td align="left" style="padding: 20px; margin: 0">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </body>

      </html>
    ';
    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "Kreptive";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = 'lwsK7|Or';
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("support@kreptive.com", $bname);
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "Withdrawal Confirmation";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }

  public function sendNftPurchaseMail($name, $title, $email, $amount)
  {

    $message = '

<html>

<head>
  <meta charset="UTF-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="x-apple-disable-message-reformatting" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta content="telephone=no" name="format-detection" />
  <title>New message 2</title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />

  <style>
    *{
      font-family: roboto, "helvetica neue" !important;
    }
    a.es-button {
      mso-style-priority: 100 !important;
      text-decoration: none !important;
    }

    a.es-button {
      border-width: 0 !important;
      padding: 10px 30px 10px 30px !important;
      mso-style-priority: 100 !important;
      text-decoration: none;
      -webkit-text-size-adjust: none;
      -ms-text-size-adjust: none;
      mso-line-height-rule: exactly;
      color: #ffffff;
      padding: 20px;
      font-size: 20px;
      border-width: 10px 30px 10px 30px;
      display: inline-block;
      background: #7952B3;
      border-radius: 6px;
      font-family: arial, "helvetica neue",
        helvetica, sans-serif;
      width: auto;
      text-align: center;
      border-left-width: 30px;
      border-right-width: 30px;
    }

    @media only screen and (max-width: 600px) {

      p,
      ul li,
      ol li,
      a {
        line-height: 150% !important;
      }

      h1,
      h2,
      h3,
      h1 a,
      h2 a,
      h3 a {
        line-height: 120% !important;
      }

      h1 {
        font-size: 36px !important;
        text-align: left;
      }

      h2 {
        font-size: 26px !important;
        text-align: left;
      }

      h3 {
        font-size: 20px !important;
        text-align: left;
      }

      .es-header-body h1 a,
      .es-content-body h1 a,
      .es-footer-body h1 a {
        font-size: 36px !important;
        text-align: left;
      }

      .es-header-body h2 a,
      .es-content-body h2 a,
      .es-footer-body h2 a {
        font-size: 26px !important;
        text-align: left;
      }

      .es-header-body h3 a,
      .es-content-body h3 a,
      .es-footer-body h3 a {
        font-size: 20px !important;
        text-align: left;
      }

      .es-menu td a {
        font-size: 12px !important;
      }

      .es-header-body p,
      .es-header-body ul li,
      .es-header-body ol li,
      .es-header-body a {
        font-size: 14px !important;
      }

      .es-content-body p,
      .es-content-body ul li,
      .es-content-body ol li,
      .es-content-body a {
        font-size: 14px !important;
      }

      .es-footer-body p,
      .es-footer-body ul li,
      .es-footer-body ol li,
      .es-footer-body a {
        font-size: 14px !important;
      }

      .es-m-txt-c,
      .es-m-txt-c h1,
      .es-m-txt-c h2,
      .es-m-txt-c h3 {
        text-align: center !important;
      }

      .es-button-border {
        display: inline-block !important;
      }

      a.es-button,
      button.es-button {
        font-size: 20px !important;
        display: inline-block !important;
      }

      .es-adaptive table,
      .es-left,
      .es-right {
        width: 100% !important;
      }

      .es-content table,
      .es-header table,
      .es-footer table,
      .es-content,
      .es-footer,
      .es-header {
        width: 100% !important;
        max-width: 600px !important;
      }

      .adapt-img {
        width: 100% !important;
        height: auto !important;
      }

      .es-m-p0r {
        padding-right: 0 !important;
      }

      .es-mobile-hidden,
      .es-hidden {
        display: none !important;
      }

      .es-menu td {
        width: 1% !important;
      }

    }
  </style>
</head>

<body style="
      width: 100%;
      font-family: arial, "helvetica neue", helvetica, sans-serif;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    ">
  <div class="es-wrapper-color" style="background-color: #fafafa">

    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #fafafa;
        ">
      <tr>
        <td valign="top" style="padding: 0; margin: 0">
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="padding: 0; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td class="es-m-p0r" valign="top" align="center" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 10px;
                                      font-size: 0px;
                                    ">
                                  <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                        font-size: 12px;
                                      " width="100%" title="Logo" class="adapt-img" height="100%" />
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-top: 15px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacinlg="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              
                              <tr>
                                <td align="center" class="es-m-txt-c" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 15px;
                                      padding-bottom: 15px;
                                    ">
                                  <h1 style="
                                        margin: 0;
                                        line-height: 55px;
                                        mso-line-height-rule: exactly;
                                        font-family: roboto, "helvetica neue",
                                          helvetica, arial, sans-serif;
                                        font-size: 46px;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #333333;
                                      ">
                                   Successful NFT Purchase
                                  </h1>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 24px;
                                        color: #333333;
                                        font-size: 16px;
                                      ">
                                    <span>Hello, ' . $name . '.<br /><br /> 
                                        You have just successfully purchased ' . $title . ' NFT at ' . $amount . 'ETH which has been added to your collection of NFTs
                                         <br /><br />
                                         Kudos!, and keep doing more with Kreptive!
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td class="esdev-adapt-off" align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            width: 560px;
                          ">
                        <tr>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 146px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                             
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: right;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 138px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-bottom: 10px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                  border-radius: 5px;
                                " role="presentation">
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                  
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 10px;
                                      padding-top: 20px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    Got a question? Respond to this mail and
                                    will be at service in to time.
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    <br />Thanks,
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                   Kreptive Team
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 640px;
                    ">
                  <tr>
                    <td align="left" style="
                          margin: 0;
                          padding-top: 20px;
                          padding-bottom: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="left" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr class="links">
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                          ">
                                        <a target="_blank" href="https://www.kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Visit Us
                                        </a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="mailto:support@kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Contact Us</a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Terms of Use</a>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 25px;
                                      padding-top: 30px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 18px;
                                        color: #333333;
                                        font-size: 12px;
                                      ">
                                    Kreptive © ' . date('Y') . ' Inc. All
                                    Rights Reserved.
                                  </p>
                                  
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>';
    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "Kreptive";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = 'lwsK7|Or';
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("support@kreptive.com", $bname);
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "Successful NFT Purchase";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
      return false;
    }
  }

  public function sendLoggedInMail($name, $time, $email)
  {

    $message = '

<html>

<head>
  <meta charset="UTF-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="x-apple-disable-message-reformatting" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta content="telephone=no" name="format-detection" />
  <title>New message 2</title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />

  <style>
    *{
      font-family: roboto, "helvetica neue" !important;
    }
    a.es-button {
      mso-style-priority: 100 !important;
      text-decoration: none !important;
    }

    a.es-button {
      border-width: 0 !important;
      padding: 10px 30px 10px 30px !important;
      mso-style-priority: 100 !important;
      text-decoration: none;
      -webkit-text-size-adjust: none;
      -ms-text-size-adjust: none;
      mso-line-height-rule: exactly;
      color: #ffffff;
      padding: 20px;
      font-size: 20px;
      border-width: 10px 30px 10px 30px;
      display: inline-block;
      background: #7952B3;
      border-radius: 6px;
      font-family: arial, "helvetica neue",
        helvetica, sans-serif;
      width: auto;
      text-align: center;
      border-left-width: 30px;
      border-right-width: 30px;
    }

    @media only screen and (max-width: 600px) {

      p,
      ul li,
      ol li,
      a {
        line-height: 150% !important;
      }

      h1,
      h2,
      h3,
      h1 a,
      h2 a,
      h3 a {
        line-height: 120% !important;
      }

      h1 {
        font-size: 36px !important;
        text-align: left;
      }

      h2 {
        font-size: 26px !important;
        text-align: left;
      }

      h3 {
        font-size: 20px !important;
        text-align: left;
      }

      .es-header-body h1 a,
      .es-content-body h1 a,
      .es-footer-body h1 a {
        font-size: 36px !important;
        text-align: left;
      }

      .es-header-body h2 a,
      .es-content-body h2 a,
      .es-footer-body h2 a {
        font-size: 26px !important;
        text-align: left;
      }

      .es-header-body h3 a,
      .es-content-body h3 a,
      .es-footer-body h3 a {
        font-size: 20px !important;
        text-align: left;
      }

      .es-menu td a {
        font-size: 12px !important;
      }

      .es-header-body p,
      .es-header-body ul li,
      .es-header-body ol li,
      .es-header-body a {
        font-size: 14px !important;
      }

      .es-content-body p,
      .es-content-body ul li,
      .es-content-body ol li,
      .es-content-body a {
        font-size: 14px !important;
      }

      .es-footer-body p,
      .es-footer-body ul li,
      .es-footer-body ol li,
      .es-footer-body a {
        font-size: 14px !important;
      }

      .es-m-txt-c,
      .es-m-txt-c h1,
      .es-m-txt-c h2,
      .es-m-txt-c h3 {
        text-align: center !important;
      }

      .es-button-border {
        display: inline-block !important;
      }

      a.es-button,
      button.es-button {
        font-size: 20px !important;
        display: inline-block !important;
      }

      .es-adaptive table,
      .es-left,
      .es-right {
        width: 100% !important;
      }

      .es-content table,
      .es-header table,
      .es-footer table,
      .es-content,
      .es-footer,
      .es-header {
        width: 100% !important;
        max-width: 600px !important;
      }

      .adapt-img {
        width: 100% !important;
        height: auto !important;
      }

      .es-m-p0r {
        padding-right: 0 !important;
      }

      .es-mobile-hidden,
      .es-hidden {
        display: none !important;
      }

      .es-menu td {
        width: 1% !important;
      }

    }
  </style>
</head>

<body style="
      width: 100%;
      font-family: arial, "helvetica neue", helvetica, sans-serif;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    ">
  <div class="es-wrapper-color" style="background-color: #fafafa">

    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #fafafa;
        ">
      <tr>
        <td valign="top" style="padding: 0; margin: 0">
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="padding: 0; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td class="es-m-p0r" valign="top" align="center" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 10px;
                                      font-size: 0px;
                                    ">
                                  <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                        font-size: 12px;
                                      " width="100%" title="Logo" class="adapt-img" height="100%" />
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-top: 15px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacinlg="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              
                              <tr>
                                <td align="center" class="es-m-txt-c" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 15px;
                                      padding-bottom: 15px;
                                    ">
                                  <h4 style="
                                        margin: 0;
                                        line-height: 55px;
                                        mso-line-height-rule: exactly;
                                        font-size: 26px;
                                        font-family: roboto, sans-serif;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #333333;
                                      ">
                                   Login Confirmation
                                  </h4>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 24px;
                                        color: #333333;
                                        font-size: 16px;
                                      ">
                                    <span>Hello, ' . $name . '.<br /><br /> 

                                    Kindly note that someone accessed your Kreptive portal on ' . $time . '. <br> <br>

Please email support@kreptive.com or get in touch with our 24-hour interactive contact center right away if you were unable to access your Kreptive portal during the above-mentioned time frame<br /><br />
Thank you for choosing Kreptive.
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td class="esdev-adapt-off" align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            width: 560px;
                          ">
                        <tr>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 146px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                             
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: right;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 138px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-bottom: 10px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                  border-radius: 5px;
                                " role="presentation">
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                  
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 10px;
                                      padding-top: 20px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    Got a question? Respond to this mail and
                                    will be at service in to time.
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    <br />Thanks,
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                   Kreptive Team
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 640px;
                    ">
                  <tr>
                    <td align="left" style="
                          margin: 0;
                          padding-top: 20px;
                          padding-bottom: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="left" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr class="links">
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                          ">
                                        <a target="_blank" href="https://www.kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Visit Us
                                        </a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="mailto:support@kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Contact Us</a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Terms of Use</a>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 25px;
                                      padding-top: 30px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 18px;
                                        color: #333333;
                                        font-size: 12px;
                                      ">
                                    Kreptive © ' . date('Y') . ' Inc. All
                                    Rights Reserved.
                                  </p>
                                  
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>';

    try {
      $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
      $bname = "Kreptive";

      $mail->isSMTP();
      $mail->Host = "smtp.hostinger.com";
      $mail->SMTPAuth = true;
      $mail->Username = "support@kreptive.com";
      $mail->Password = 'lwsK7|Or';
      $mail->SMTPSecure = "ssl";
      $mail->Port = 465;

      $mail->setFrom("support@kreptive.com", $bname);
      $mail->addAddress($email);
      $mail->isHTML(true);

      $mail->Subject = "Kreptive Portal Login";
      $mail->Body = $message;

      if ($mail->send()) {
        return true;
      }
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
  public function sendForgotPwrdMail($name, $email, $idd)
  {
    $tyme = time();
    $_SESSION['timeRecovered'] = $tyme;

    $message = '

<html>

<head>
  <meta charset="UTF-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="x-apple-disable-message-reformatting" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta content="telephone=no" name="format-detection" />
  <title>New message 2</title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />

  <style>
    *{
      font-family: roboto, "helvetica neue" !important;
    }
    a.es-button {
      mso-style-priority: 100 !important;
      text-decoration: none !important;
    }

    a.es-button {
      border-width: 0 !important;
      padding: 10px 30px 10px 30px !important;
      mso-style-priority: 100 !important;
      text-decoration: none;
      -webkit-text-size-adjust: none;
      -ms-text-size-adjust: none;
      mso-line-height-rule: exactly;
      color: #ffffff;
      padding: 20px;
      font-size: 20px;
      border-width: 10px 30px 10px 30px;
      display: inline-block;
      background: #7952B3;
      border-radius: 6px;
      font-family: arial, "helvetica neue",
        helvetica, sans-serif;
      width: auto;
      text-align: center;
      border-left-width: 30px;
      border-right-width: 30px;
    }

    @media only screen and (max-width: 600px) {

      p,
      ul li,
      ol li,
      a {
        line-height: 150% !important;
      }

      h1,
      h2,
      h3,
      h1 a,
      h2 a,
      h3 a {
        line-height: 120% !important;
      }

      h1 {
        font-size: 36px !important;
        text-align: left;
      }

      h2 {
        font-size: 26px !important;
        text-align: left;
      }

      h3 {
        font-size: 20px !important;
        text-align: left;
      }

      .es-header-body h1 a,
      .es-content-body h1 a,
      .es-footer-body h1 a {
        font-size: 36px !important;
        text-align: left;
      }

      .es-header-body h2 a,
      .es-content-body h2 a,
      .es-footer-body h2 a {
        font-size: 26px !important;
        text-align: left;
      }

      .es-header-body h3 a,
      .es-content-body h3 a,
      .es-footer-body h3 a {
        font-size: 20px !important;
        text-align: left;
      }

      .es-menu td a {
        font-size: 12px !important;
      }

      .es-header-body p,
      .es-header-body ul li,
      .es-header-body ol li,
      .es-header-body a {
        font-size: 14px !important;
      }

      .es-content-body p,
      .es-content-body ul li,
      .es-content-body ol li,
      .es-content-body a {
        font-size: 14px !important;
      }

      .es-footer-body p,
      .es-footer-body ul li,
      .es-footer-body ol li,
      .es-footer-body a {
        font-size: 14px !important;
      }

      .es-m-txt-c,
      .es-m-txt-c h1,
      .es-m-txt-c h2,
      .es-m-txt-c h3 {
        text-align: center !important;
      }

      .es-button-border {
        display: inline-block !important;
      }

      a.es-button,
      button.es-button {
        font-size: 20px !important;
        display: inline-block !important;
      }

      .es-adaptive table,
      .es-left,
      .es-right {
        width: 100% !important;
      }

      .es-content table,
      .es-header table,
      .es-footer table,
      .es-content,
      .es-footer,
      .es-header {
        width: 100% !important;
        max-width: 600px !important;
      }

      .adapt-img {
        width: 100% !important;
        height: auto !important;
      }

      .es-m-p0r {
        padding-right: 0 !important;
      }

      .es-mobile-hidden,
      .es-hidden {
        display: none !important;
      }

      .es-menu td {
        width: 1% !important;
      }

    }
  </style>
</head>

<body style="
      width: 100%;
      font-family: arial, "helvetica neue", helvetica, sans-serif;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    ">
  <div class="es-wrapper-color" style="background-color: #fafafa">

    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #fafafa;
        ">
      <tr>
        <td valign="top" style="padding: 0; margin: 0">
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-header" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-header-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="padding: 0; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td class="es-m-p0r" valign="top" align="center" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 10px;
                                      font-size: 0px;
                                    ">
                                  <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                        font-size: 12px;
                                      " width="100%" title="Logo" class="adapt-img" height="100%" />
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                    ">
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-top: 15px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacinlg="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              
                              <tr>
                                <td align="center" class="es-m-txt-c" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 15px;
                                      padding-bottom: 15px;
                                    ">
                                  <h4 style="
                                        margin: 0;
                                        line-height: 55px;
                                        mso-line-height-rule: exactly;
                                        font-size: 26px;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #333333;
                                      ">
                                   Account Password Reset
                                  </h4>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 24px;
                                        color: #333333;
                                        font-size: 16px;
                                      ">
                                    <span>Hi, ' . $name . '.<br /><br /> 

                                   It seems you forgot your password <br> <br>

Kindly click the button below to reset your password


                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td class="esdev-adapt-off" align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" class="esdev-mso-table" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            width: 560px;
                          ">
                        <tr>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 146px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                             
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-left" align="left" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: left;
                                ">
                              
                            </table>
                          </td>
                          <td class="esdev-mso-td" valign="top" style="padding: 0; margin: 0">
                            <table cellpadding="0" cellspacing="0" class="es-right" align="right" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  float: right;
                                ">
                              <tr class="es-mobile-hidden">
                                <td align="left" style="padding: 0; margin: 0; width: 138px">
                                  <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" height="40" style="padding: 0; margin: 0"></td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-bottom: 10px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                  border-radius: 5px;
                                " role="presentation">
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                   <span class="msohide es-button-border" style="
                                        border-style: solid;
                                        border-color: #7952B3;
                                        background: #7952B3;
                                        border-width: 0px;
                                        display: inline-block;
                                        border-radius: 6px;
                                        font-weight: 600;
                                        width: auto;
                                        
                                      "><a href="https://kreptive.com/reset_password?query_id=' . $idd . '" class="es-button" target="_blank" >Reset Password </a></span>
                                </td>
                              </tr>
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 10px;
                                      padding-top: 20px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    Got a question? Respond to this mail and
                                    will be at service in to time.
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                    <br />Thanks,
                                  </p>
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        
                                          font-family: "source sans pro",
                                            "helvetica neue", helvetica, arial,
                                            sans-serif;
                                        
                                        color: #333333;
                                        font-size: 14px;
                                      ">
                                   Kreptive Team
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-footer" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 640px;
                    ">
                  <tr>
                    <td align="left" style="
                          margin: 0;
                          padding-top: 20px;
                          padding-bottom: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="left" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr class="links">
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                          ">
                                        <a target="_blank" href="https://www.kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Visit Us
                                        </a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="mailto:support@kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Contact Us</a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              font-family: arial,
                                                "helvetica neue", helvetica,
                                                sans-serif;
                                              color: #999999;
                                              font-size: 12px;
                                            ">Terms of Use</a>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 25px;
                                      padding-top: 30px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, "helvetica neue",
                                          helvetica, sans-serif;
                                        line-height: 18px;
                                        color: #333333;
                                        font-size: 12px;
                                      ">
                                    Kreptive © ' . date('Y') . ' Inc. All
                                    Rights Reserved.
                                  </p>
                                  
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>';
    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "Kreptive";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = 'lwsK7|Or';
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom("support@kreptive.com", $bname);
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "Account Password Reset";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }


  public function sendBidderMail(
    $name,
    $email,
    $title,
    $price
  ) {

    $message = '
      <html>

<head>
  <meta charset="UTF-8" />
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta name="x-apple-disable-message-reformatting" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta content="telephone=no" name="format-detection" />
  <title>New message 2</title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />
  <link rel="stylesheet" href="../css/style.css" />


  <style>
    a.es-button {
      mso-style-priority: 100 !important;
      text-decoration: none !important;
    }

    a.es-button {
      border-width: 0 !important;
      padding: 10px 30px 10px 30px !important;
      mso-style-priority: 100 !important;
      text-decoration: none;
      -webkit-text-size-adjust: none;
      -ms-text-size-adjust: none;
      mso-line-height-rule: exactly;
      color: #ffffff;
      padding: 20px;
      font-size: 20px;
      border-width: 10px 30px 10px 30px;
      display: inline-block;
      background: #3c8dbc;
      border-radius: 6px;
      font-family: arial, "helvetica neue",
        helvetica, sans-serif;
      width: auto;
      text-align: center;
      border-left-width: 30px;
      border-right-width: 30px;
    }

    @media only screen and (max-width: 600px) {

      p,
      ul li,
      ol li,
      a {
        line-height: 150% !important;
      }

      h1,
      h2,
      h3,
      h1 a,
      h2 a,
      h3 a {
        line-height: 120% !important;
      }

      h1 {
        font-size: 36px !important;
        text-align: left;
      }

      h2 {
        font-size: 26px !important;
        text-align: left;
      }

      h3 {
        font-size: 20px !important;
        text-align: left;
      }

      .es-header-body h1 a,
      .es-content-body h1 a,
      .es-footer-body h1 a {
        font-size: 36px !important;
        text-align: left;
      }

      .es-header-body h2 a,
      .es-content-body h2 a,
      .es-footer-body h2 a {
        font-size: 26px !important;
        text-align: left;
      }

      .es-header-body h3 a,
      .es-content-body h3 a,
      .es-footer-body h3 a {
        font-size: 20px !important;
        text-align: left;
      }

      .es-menu td a {
        font-size: 12px !important;
      }

      .es-header-body p,
      .es-header-body ul li,
      .es-header-body ol li,
      .es-header-body a {
        font-size: 14px !important;
      }

      .es-content-body p,
      .es-content-body ul li,
      .es-content-body ol li,
      .es-content-body a {
        font-size: 14px !important;
      }

      .es-footer-body p,
      .es-footer-body ul li,
      .es-footer-body ol li,
      .es-footer-body a {
        font-size: 14px !important;
      }

      .es-m-txt-c,
      .es-m-txt-c h1,
      .es-m-txt-c h2,
      .es-m-txt-c h3 {
        text-align: center !important;
      }

      .es-button-border {
        display: inline-block !important;
      }

      a.es-button,
      button.es-button {
        font-size: 20px !important;
        display: inline-block !important;
      }

      .es-adaptive table,
      .es-left,
      .es-right {
        width: 100% !important;
      }

      .es-content table,
      .es-header table,
      .es-footer table,
      .es-content,
      .es-footer,
      .es-header {
        width: 100% !important;
        max-width: 600px !important;
      }

      .adapt-img {
        width: 100% !important;
        height: auto !important;
      }

      .es-m-p0r {
        padding-right: 0 !important;
      }

      .es-mobile-hidden,
      .es-hidden {
        display: none !important;
      }

      .es-menu td {
        width: 1% !important;
      }

    }
  </style>
</head>

<body style="
      width: 100%;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    ">
  <div class="es-wrapper-color" style="background-color: #fafafa">

    <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #fafafa;
        ">
      <tr>
        <td valign="top" style="padding: 0; margin: 0">
          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                  <tr>
                    <td align="left" style="padding: 20px; margin: 0">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td align="center" style="padding: 0; margin: 0; display: none"></td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                    ">
                  <tr>

                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-top: 15pxjo;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacinlg="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">

                              <tr>
                                <td align="center" class="es-m-txt-c" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 15px;
                                      padding-bottom: 15px;
                                    ">
                                  <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                        font-size: 12px;
                                      " width="80%" title="Logo" class="adapt-img" />
                                  <h1 style="
                                        margin: 0;
                                        line-height: 55px;
                                        mso-line-height-rule: exactly;
                                        font-family: roboto, " helvetica neue", helvetica, arial, sans-serif; font-size: 46px; font-style: normal; font-weight: bold; color: #333333; ">
                                    Bid Confirmation
                                  </h1>
                                </td>
                              </tr>
                              <tr>
                                <td align=" left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 10px;
                                    ">
                                    <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                       ">
                                      <span>Hello, ' . $name . '.<br /><br />
                                        We are thrilled to confirm the placement of your bid for the NFT artwork titled ' . $title . ' at ' . $price . 'ETH <br /><br />
                                        Note that this email is a confirmation that you are participating in the auction, and the bidder whose bid is accepted by the owner will receive an appropriate response.
                                    </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>

                  <tr>
                    <td align="left" style="
                          padding: 0;
                          margin: 0;
                          padding-bottom: 10px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                            <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                  border-radius: 5px;
                                " role="presentation">
                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 10px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                         ">
                                    Got a question? Respond to this mail and
                                    will be at service in to time.
                                  </p>
                                  <p style=" margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly;">
                                    <br />Thanks,
                                  </p>
                                  <p style=" margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly ">
                                    Kreptive Team
                                  </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding=" 0" cellspacing="0" class="es-footer" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              ">
            <tr>
              <td align="center" style="padding: 0; margin: 0">
                <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 640px;
                    ">
                  <tr>
                    <td align="left" style="
                          margin: 0;
                          padding-top: 20px;
                          padding-bottom: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                        ">
                      <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                        <tr>
                          <td align="left" style="padding: 0; margin: 0; width: 600px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                              <tr>
                                <td style="padding: 0; margin: 0">
                                  <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr class="links">
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                          ">
                                        <a target="_blank" href="https://www.kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              color: #999999;
                                            ">Visit Us
                                        </a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="mailto:support@kreptive.com" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              color: #999999;                                            ">Contact Us</a>
                                      </td>
                                      <td align="center" valign="top" width="33.33%" style="
                                            margin: 0;
                                            padding-left: 5px;
                                            padding-right: 5px;
                                            padding-top: 7px;
                                            padding-bottom: 7px;
                                            border: 0;
                                            border-left: 1px solid #cccccc;
                                          ">
                                        <a target="_blank" href="" style="
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              text-decoration: none;
                                              display: block;
                                              color: #999999;

                                            ">Terms of Use</a>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                              <tr>
                                <td align="center" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 25px;
                                      padding-top: 30px;
                                    ">
                                  <p style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        font-family: arial, " helvetica neue", helvetica, sans-serif; line-height: 18px; color: #333333; font-size: 12px; ">
                                    Kreptive © ' . date('Y') . ' Inc. All
                                    Rights Reserved.
                                  </p>
                                  
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          <table cellpadding=" 0" cellspacing="0" class="es-content" align="center" style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              ">
                              <tr>
                                <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                                  <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: transparent;
                      width: 600px;
                    " bgcolor="#FFFFFF">
                                    <tr>
                                      <td align="left" style="padding: 20px; margin: 0">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          ">
                                          <tr>
                                            <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                              <table cellpadding="0" cellspacing="0" width="100%" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                ">
                                                <tr>
                                                  <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
  </div>
</body>

</html>

<!-- Another -->
';
    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "Kreptive";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = "lwsK7|Or";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom(
      "support@kreptive.com",
      $bname
    );
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "NFT Bid";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }

  public function sendRecipientMail($name, $email, $title, $price)
  {

    $message = '

      <html>

            <head>
              <meta charset="UTF-8" />
              <meta content="width=device-width, initial-scale=1" name="viewport" />
              <meta name="x-apple-disable-message-reformatting" />
              <meta http-equiv="X-UA-Compatible" content="IE=edge" />
              <meta content="telephone=no" name="format-detection" />
              <title>New message 2</title>

              <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i" rel="stylesheet" />
              <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i" rel="stylesheet" />
              <link rel="stylesheet" href="../css/style.css" />


              <style>
                a.es-button {
                  mso-style-priority: 100 !important;
                  text-decoration: none !important;
                }

                a.es-button {
                  border-width: 0 !important;
                  padding: 10px 30px 10px 30px !important;
                  mso-style-priority: 100 !important;
                  text-decoration: none;
                  -webkit-text-size-adjust: none;
                  -ms-text-size-adjust: none;
                  mso-line-height-rule: exactly;
                  color: #ffffff;
                  padding: 20px;
                  font-size: 20px;
                  border-width: 10px 30px 10px 30px;
                  display: inline-block;
                  background: #3c8dbc;
                  border-radius: 6px;
                  font-family: arial, "helvetica neue",
                    helvetica, sans-serif;
                  width: auto;
                  text-align: center;
                  border-left-width: 30px;
                  border-right-width: 30px;
                }

                @media only screen and (max-width: 600px) {

                  p,
                  ul li,
                  ol li,
                  a {
                    line-height: 150% !important;
                  }

                  h1,
                  h2,
                  h3,
                  h1 a,
                  h2 a,
                  h3 a {
                    line-height: 120% !important;
                  }

                  h1 {
                    font-size: 36px !important;
                    text-align: left;
                  }

                  h2 {
                    font-size: 26px !important;
                    text-align: left;
                  }

                  h3 {
                    font-size: 20px !important;
                    text-align: left;
                  }

                  .es-header-body h1 a,
                  .es-content-body h1 a,
                  .es-footer-body h1 a {
                    font-size: 36px !important;
                    text-align: left;
                  }

                  .es-header-body h2 a,
                  .es-content-body h2 a,
                  .es-footer-body h2 a {
                    font-size: 26px !important;
                    text-align: left;
                  }

                  .es-header-body h3 a,
                  .es-content-body h3 a,
                  .es-footer-body h3 a {
                    font-size: 20px !important;
                    text-align: left;
                  }

                  .es-menu td a {
                    font-size: 12px !important;
                  }

                  .es-header-body p,
                  .es-header-body ul li,
                  .es-header-body ol li,
                  .es-header-body a {
                    font-size: 14px !important;
                  }

                  .es-content-body p,
                  .es-content-body ul li,
                  .es-content-body ol li,
                  .es-content-body a {
                    font-size: 14px !important;
                  }

                  .es-footer-body p,
                  .es-footer-body ul li,
                  .es-footer-body ol li,
                  .es-footer-body a {
                    font-size: 14px !important;
                  }

                  .es-m-txt-c,
                  .es-m-txt-c h1,
                  .es-m-txt-c h2,
                  .es-m-txt-c h3 {
                    text-align: center !important;
                  }

                  .es-button-border {
                    display: inline-block !important;
                  }

                  a.es-button,
                  button.es-button {
                    font-size: 20px !important;
                    display: inline-block !important;
                  }

                  .es-adaptive table,
                  .es-left,
                  .es-right {
                    width: 100% !important;
                  }

                  .es-content table,
                  .es-header table,
                  .es-footer table,
                  .es-content,
                  .es-footer,
                  .es-header {
                    width: 100% !important;
                    max-width: 600px !important;
                  }

                  .adapt-img {
                    width: 100% !important;
                    height: auto !important;
                  }

                  .es-m-p0r {
                    padding-right: 0 !important;
                  }

                  .es-mobile-hidden,
                  .es-hidden {
                    display: none !important;
                  }

                  .es-menu td {
                    width: 1% !important;
                  }

                }
              </style>
            </head>

            <body style="
                  width: 100%;
                  -webkit-text-size-adjust: 100%;
                  -ms-text-size-adjust: 100%;
                  padding: 0;
                  margin: 0;
                ">
              <div class="es-wrapper-color" style="background-color: #fafafa">

                <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      padding: 0;
                      margin: 0;
                      width: 100%;
                      height: 100%;
                      background-repeat: repeat;
                      background-position: center top;
                      background-color: #fafafa;
                    ">
                  <tr>
                    <td valign="top" style="padding: 0; margin: 0">
                      <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            table-layout: fixed !important;
                            width: 100%;
                          ">
                        <tr>
                          <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                            <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  background-color: transparent;
                                  width: 600px;
                                " bgcolor="#FFFFFF">
                              <tr>
                                <td align="left" style="padding: 20px; margin: 0">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr>
                                            <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>

                      <table cellpadding="0" cellspacing="0" class="es-content" align="center" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            table-layout: fixed !important;
                            width: 100%;
                          ">
                        <tr>
                          <td align="center" style="padding: 0; margin: 0">
                            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  background-color: #ffffff;
                                  width: 600px;
                                ">
                              <tr>

                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 15pxjo;
                                      padding-left: 20px;
                                      padding-right: 20px;
                                    ">
                                  <table cellpadding="0" cellspacinlg="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">

                                          <tr>
                                            <td align="center" class="es-m-txt-c" style="
                                                  padding: 0;
                                                  margin: 0;
                                                  padding-top: 15px;
                                                  padding-bottom: 15px;
                                                ">
                                              <img src="https://kreptive.com/images/logo__dark.png" alt="Logo" style="
                                                    display: block;
                                                    border: 0;
                                                    outline: none;
                                                    text-decoration: none;
                                                    -ms-interpolation-mode: bicubic;
                                                    font-size: 12px;
                                                  " width="80%" title="Logo" class="adapt-img" />
                                              <h1 style="
                                                    margin: 0;
                                                    line-height: 55px;
                                                    mso-line-height-rule: exactly;
                                                    font-family: roboto, " helvetica neue", helvetica, arial, sans-serif; font-size: 46px; font-style: normal; font-weight: bold; color: #333333; ">
                                                Bid Confirmation
                                              </h1>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align=" left" style="
                                                  padding: 0;
                                                  margin: 0;
                                                  padding-top: 10px;
                                                  padding-bottom: 10px;
                                                ">
                                                <p style="
                                                    margin: 0;
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                  ">
                                                  <span>Hello, ' . $name . '.<br /><br />
                                                    Please be duly informed that a bid of ' . $price . 'ETH has been placed on your ' . $title . ' NFT</span><br /><br />
                                                  Kindly logon to your portal using this link https://kreptive.com/sign-in to perform an action on this bid
                                                </p>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>

                              <tr>
                                <td align="left" style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 10px;
                                      padding-left: 20px;
                                      padding-right: 20px;
                                    ">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                        <table cellpadding="0" cellspacing="0" width="100%" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: separate;
                                              border-spacing: 0px;
                                              border-radius: 5px;
                                            " role="presentation">
                                          <tr>
                                            <td align="left" style="
                                                  padding: 0;
                                                  margin: 0;
                                                  padding-bottom: 10px;
                                                ">
                                              <p style="
                                                    margin: 0;
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    ">
                                                Got a question? Respond to this mail and
                                                will be at service in to time.
                                              </p>
                                              <p style=" margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly;">
                                                <br />Thanks,
                                              </p>
                                              <p style=" margin: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; mso-line-height-rule: exactly ">
                                                Kreptive Team
                                              </p>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                      <table cellpadding=" 0" cellspacing="0" class="es-footer" align="center" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            table-layout: fixed !important;
                            width: 100%;
                            background-color: transparent;
                            background-repeat: repeat;
                            background-position: center top;
                          ">
                        <tr>
                          <td align="center" style="padding: 0; margin: 0">
                            <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  background-color: transparent;
                                  width: 640px;
                                ">
                              <tr>
                                <td align="left" style="
                                      margin: 0;
                                      padding-top: 20px;
                                      padding-bottom: 20px;
                                      padding-left: 20px;
                                      padding-right: 20px;
                                    ">
                                  <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                    <tr>
                                      <td align="left" style="padding: 0; margin: 0; width: 600px">
                                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                          <tr>
                                            <td style="padding: 0; margin: 0">
                                              <table cellpadding="0" cellspacing="0" width="100%" class="es-menu" role="presentation" style="
                                                    mso-table-lspace: 0pt;
                                                    mso-table-rspace: 0pt;
                                                    border-collapse: collapse;
                                                    border-spacing: 0px;
                                                  ">
                                                <tr class="links">
                                                  <td align="center" valign="top" width="33.33%" style="
                                                        margin: 0;
                                                        padding-left: 5px;
                                                        padding-right: 5px;
                                                        padding-top: 7px;
                                                        padding-bottom: 7px;
                                                        border: 0;
                                                      ">
                                                    <a target="_blank" href="https://www.kreptive.com" style="
                                                          -webkit-text-size-adjust: none;
                                                          -ms-text-size-adjust: none;
                                                          mso-line-height-rule: exactly;
                                                          text-decoration: none;
                                                          display: block;
                                                          color: #999999;
                                                        ">Visit Us
                                                    </a>
                                                  </td>
                                                  <td align="center" valign="top" width="33.33%" style="
                                                        margin: 0;
                                                        padding-left: 5px;
                                                        padding-right: 5px;
                                                        padding-top: 7px;
                                                        padding-bottom: 7px;
                                                        border: 0;
                                                        border-left: 1px solid #cccccc;
                                                      ">
                                                    <a target="_blank" href="mailto:support@kreptive.com" style="
                                                          -webkit-text-size-adjust: none;
                                                          -ms-text-size-adjust: none;
                                                          mso-line-height-rule: exactly;
                                                          text-decoration: none;
                                                          display: block;
                                                          color: #999999;                                            ">Contact Us</a>
                                                  </td>
                                                  <td align="center" valign="top" width="33.33%" style="
                                                        margin: 0;
                                                        padding-left: 5px;
                                                        padding-right: 5px;
                                                        padding-top: 7px;
                                                        padding-bottom: 7px;
                                                        border: 0;
                                                        border-left: 1px solid #cccccc;
                                                      ">
                                                    <a target="_blank" href="" style="
                                                          -webkit-text-size-adjust: none;
                                                          -ms-text-size-adjust: none;
                                                          mso-line-height-rule: exactly;
                                                          text-decoration: none;
                                                          display: block;
                                                          color: #999999;

                                                        ">Terms of Use</a>
                                                  </td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td align="center" style="
                                                  padding: 0;
                                                  margin: 0;
                                                  padding-bottom: 25px;
                                                  padding-top: 30px;
                                                ">
                                              <p style="
                                                    margin: 0;
                                                    -webkit-text-size-adjust: none;
                                                    -ms-text-size-adjust: none;
                                                    mso-line-height-rule: exactly;
                                                    font-family: arial, " helvetica neue", helvetica, sans-serif; line-height: 18px; color: #333333; font-size: 12px; ">
                                                Kreptive © ' . date('Y') . ' Inc. All
                                                Rights Reserved.
                                              </p>
                                              
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                      <table cellpadding=" 0" cellspacing="0" class="es-content" align="center" style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                            table-layout: fixed !important;
                            width: 100%;
                          ">
                                          <tr>
                                            <td class="es-info-area" align="center" style="padding: 0; margin: 0">
                                              <table class="es-content-body" align="center" cellpadding="0" cellspacing="0" style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                  background-color: transparent;
                                  width: 600px;
                                " bgcolor="#FFFFFF">
                                                <tr>
                                                  <td align="left" style="padding: 20px; margin: 0">
                                                    <table cellpadding="0" cellspacing="0" width="100%" style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      ">
                                                      <tr>
                                                        <td align="center" valign="top" style="padding: 0; margin: 0; width: 560px">
                                                          <table cellpadding="0" cellspacing="0" width="100%" style="
                                              mso-table-lspace: 0pt;
                                              mso-table-rspace: 0pt;
                                              border-collapse: collapse;
                                              border-spacing: 0px;
                                            ">
                                                            <tr>
                                                              <td align="center" style="padding: 0; margin: 0; display: none"></td>
                                                            </tr>
                                                          </table>
                                                        </td>
                                                      </tr>
                                                    </table>
                                                  </td>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
              </div>
            </body>

      </html>

    ';
    $mail  = new PHPMailer\PHPMailer\PHPMailer(true);
    $bname = "Kreptive";

    $mail->isSMTP();
    $mail->Host = "smtp.hostinger.com";
    $mail->SMTPAuth = true;
    $mail->Username = "support@kreptive.com";
    $mail->Password = "lwsK7|Or";
    $mail->SMTPSecure = "ssl";
    $mail->Port = 465;

    $mail->setFrom(
      "support@kreptive.com",
      $bname
    );
    $mail->addAddress($email);
    $mail->isHTML(true);

    $mail->Subject = "NFT Bid";
    $mail->Body = $message;

    if ($mail->send()) {
      return true;
    } else {
      echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }
}
