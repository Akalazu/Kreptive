<?php

include_once "portal_settings.php";
// include_once "../includes/init.php";
$user = $userCl->getUserDetails($idd);

$currUserr = $user->first_name . ' ' . $user->last_name;
// Simulate fetching component data from the server
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    // Here, you would typically perform your actual data retrieval logic
    $sql = "SELECT * FROM `all_nft` WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $productId);
    if ($statement->execute()) {
        $product = $statement->fetch(PDO::FETCH_OBJ);

        // For this example, we'll simulate success and return component name
        $productPrice = $product->price; // Simulated component price
        $productName = $product->title; // Simulated component name

        echo json_encode(['status' => 'success', 'productPrice' => $productPrice, 'productName' => $productName]);
    }
}

// Simulating purchase NFT transaction
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['productPrice'])) {

    /** The needed details*/
    $productId = $_POST['productId'];
    $productPrice = $_POST['productPrice'];
    $productAuthorId = $_POST['productAuthorId'];
    $productTitle = $_POST['productTitle'];
    /** The needed details*/

    $remaining_balance = $user->balance - $productPrice;

    $author_details = $userCl->getUserDetails($productAuthorId);

    // print_r($author_details);

    // Get NFT details
    $nft_details =  $userCl->getNFTDetailsById($productId);



    $email = $user->email;


    if ($remaining_balance < 0) {
        echo json_encode(['status' => 'errors']);
    } else {
        // $author_updated_balance = 100;
        if ($nft_details->type != 'lazy') {
            // This was changed to balance
            $author_updated_balance = $author_details->balance + $productPrice;
            $queryy = "UPDATE `reg_details` SET `profit` = :pf WHERE `id` = :idd";
        } else {
            // This was changed to balance
            $author_updated_balance = $author_details->balance + $productPrice;
            $queryy = "UPDATE `reg_details` SET `profit` = :pf WHERE `id` = :idd";
        }
        //add profit to his account

        $sttmt = $pdo->prepare($queryy);

        $sttmt->bindParam(':idd', $productAuthorId);
        $sttmt->bindParam(':pf', $author_updated_balance);
        $ref_id = $activityCl->genRefId();


        if ($sttmt->execute()) {

            $sql = "UPDATE `all_nft` SET `author_name`= :an, `author_id` =:ai WHERE `id` = :idd";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':idd', $productId);
            $stmt->bindParam(':an', $currUserr);
            $stmt->bindParam(':ai', $user->id);

            if ($stmt->execute() && $activityCl->purchaseArt($user->id, $ref_id, $productTitle, $productPrice) && $activityCl->salesArt($author_details->id, $ref_id, $productTitle, $productPrice, $user->username)) {

                $sqll = "UPDATE `reg_details` SET `balance`= :bl WHERE `id` = :idd";
                $stmtt = $pdo->prepare($sqll);
                $stmtt->bindParam(':idd', $user->id);
                $stmtt->bindParam(':bl', $remaining_balance);

                // if ($stmtt->execute() && $userCl->sendNftPurchaseMail($currUserr, $productTitle, $email, $productPrice)) {
                if ($stmtt->execute()) {
                    echo json_encode(['status' => 'success']);
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request 2']);
        }
    };
}

// Simulating view NFT

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_Id'])) {
    $product_Id = $_POST['product_Id'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nft_id'])) {
    if (isset($_POST['nft_id'])) {
        // $amount = $_POST['amount_deposit'];

        $idd = $_POST['nft_id'];
        $sql = "SELECT * FROM `all_nft` WHERE `id` = :idd";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':idd', $idd);
        $statement->execute();
        if ($nftt = $statement->fetch(PDO::FETCH_OBJ)) {
            $myCollections = $userCl->getAllCollections($nftt->author_id);
            $output = '';
            foreach ($myCollections as $myCollection) {
                $output .= '<option value="' . $myCollection->title . '">' . $myCollection->title . '</option>';
            }
            $result =  '
            <form method="post">
                <input type="text" class="form-control" id="nft_id" name="nft_id" value="' . $nftt->id . '" hidden>
                            <div class="form-group mb-3">
                            <label for="nftName">Title</label>
                            <input type="text" class="form-control" id="nft_name" name="nft_name" value="' . $nftt->title . '" required>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="nftName">Title</label>
                                <input type="number" class="form-control" id="nft_price" name="nft_price" value="' . $nftt->price . '" required>
                            </div>
                            <select class="form-choice form-control form-control-s1" name="collections" required>
                                <option value = "' . $nftt->collection . '" selected >' . $nftt->collection . '</option>
                                ' . $output . '
                            </select>
                            <div class=" form-group">
                                <label for="nft_desc">Description</label>
                                <textarea name="nft_desc" class="form-control" placeholder="Describe the NFT you are about to mint.">' . $nftt->description . '</textarea>
                            </div>

                            <button class=" btn-primary btn-sm my-3" name="save_author_collections" type="submit">Save Changes</button>
             </form>
         
         ';

            echo $result;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['art_Id'])) {
    $art_id = $_POST['art_Id'];

    $sql = "SELECT * FROM `all_nft` WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $art_id);
    if ($statement->execute()) {
        $art = $statement->fetch(PDO::FETCH_OBJ);
        $artTitle = $art->title;
        $artImage = $art->image;

        echo '
            <div class="my-3">
                <img src="../' . $artImage . '" style="height: 400px; width: 100%" class="img-fluid">
            </div>
            <div class="my-3 d-flex justify-content-between" style="margin: 0 30px;">

                <div>
                    <span class="my-2">Title</span>
                    <p class="art__title small text-dark">' . $artTitle . '</p>
                </div>
                <div>
                    <span class="my-2">Price</span>
                    <p class="art__title small text-dark">' . $art->price . 'ETH</p>
                </div>
                
                </div>
                <div style="margin: 0 30px;">
                    <span class="my-2">Description</span>
                    <p class="art__title small text-dark">' . $art->description . '</p>
                </div>

        ';
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['productIdd'])) {
    $productIdd = $_GET['productIdd'];


    $product = $userCl->getNFTDetailsById($productIdd);

    // For this example, we'll simulate success and return component name
    $productName = $product->title; // Simulated component name

    echo json_encode(['status' => 'success', 'productName' => $productName]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletedProductId'])) {
    $deletedProductId = $_POST['deletedProductId'];

    $deletedProductDetails = $userCl->getNFTDetailsById($deletedProductId);

    $sql = "DELETE FROM `all_nft` WHERE `id` = :idd";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idd', $deletedProductId);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'deletedProductName' => $deletedProductDetails->title]);
    } else {

        echo json_encode(['status' => 'error']);
    }
}
