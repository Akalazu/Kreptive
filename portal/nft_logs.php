<?php
$pageName = 'create NFT';

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

<div class="wallet__wrapper mt-5">
    <div class="outer__inner">
        <div class="container">
            <div class="row">
                <?php include_once 'portal_sidebar.php' ?>

                <!-- end col -->

                <div class="col-lg-9">
                    <div class="user-panel-title-box">
                        <h3>Transactions</h3>
                    </div>
                    <!-- end user-panel-title-box -->
                    <div class="profile-setting-panel-wrap">
                        <div style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; justify-content: space-between; padding: 1rem;">
                            <div style="display: flex; gap: .25rem; align-items: center; font-weight: 600">
                                <div class="market__icon"><img src="../assets/images/ethereum.svg" alt="Ethereum"></div>
                                <span>Ethereum - ETH</span>
                            </div>
                            <a class="button-small" href="create_nft">
                                Create NFT
                                <em class="ni ni-plus ms-2" style="font-weight: 900;"></em>
                            </a>

                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0 table-s2">
                                <thead>
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Amount</th>
                                        <!-- <th scope="col">Charges</th> -->
                                        <th scope="col">Collection</th>
                                        <th scope="col">Created</th>
                                        <th scope="col">Status</th>
                                    </tr>

                                </thead>
                                <tbody class="fs-13 text-center">

                                    <?php
                                    $sql = "SELECT * FROM `all_nft` WHERE `author_name` = :an ";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':an', $currUserr);
                                    $stmt->execute();

                                    $j = 1;

                                    while ($myNft = $stmt->fetch(PDO::FETCH_OBJ)) {
                                        if ($myNft->status == 1) {
                                            $color = 'success';
                                            $status = "Approved";
                                        } else if ($myNft->status == 2) {
                                            $color = 'danger';
                                            $status = "Not Approved";
                                        } else {
                                            $color = 'warning';
                                            $status = "Pending";
                                        }
                                        echo '
                                        <tr>
                                        <th scope="row"><a href="#">' . $j . '</a></th>
                                        <td>' . $myNft->title . '
                                        </td>
                                        <td>' . $myNft->price  . '</td>
                                        <td>' . $myNft->collection . '</td>
                                        <td>
                                        ' . $myNft->time_added . '
                                        </td>
                                        <td><span class="button-small btn-outline-success badge bg-' . $color . '">' . $status . '</span></td>
                                        </tr>
                                        
                                        
                                        
                                        </div>
                                        
                                        
                                        ';
                                        $j++;
                                    }

                                    ?>
                                    <!-- <td>
                                        <a href="#" class="icon-btn" title="Remore"><em class="ni ni-trash"></em></a>
                                    </td> -->


                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                        <!-- <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center mt-5 pagination-s1">
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true" class="ni ni-chevron-left"></span>
                                    </a>
                                </li>
                                <li class="page-item active">
                                    <a class="page-link" href="#">1</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true" class="ni ni-chevron-right"></span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                        <p class="fs-13 mt-2 text-center">
                            Showing 1 to 6 of 30 entries
                        </p> -->
                    </div>
                    <!-- end profile-setting-panel-wrap-->

                </div>
                <!-- end profile-setting-panel-wrap-->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>


</div>

<div style="display: none">
    <svg width="0" height="0">
        <symbol xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" id="icon-upload-file">
            <path d="M10.229.667c.707 0 1.386.281 1.886.781l1.105 1.105c.5.5.781 1.178.781 1.886v8.229c0 1.473-1.194 2.667-2.667 2.667H4.667C3.194 15.334 2 14.14 2 12.667V3.334C2 1.861 3.194.667 4.667.667h5.562zM9.333 2H4.667c-.693 0-1.263.529-1.327 1.205l-.006.128v9.333c0 .693.529 1.263 1.205 1.327l.128.006h6.667c.693 0 1.263-.529 1.327-1.205l.006-.128V5.334h-1.333a2 2 0 0 1-1.995-1.851l-.005-.149V2zM7.745 6.051c.242-.1.53-.052.727.145h0l2 2c.26.26.26.682 0 .943s-.682.26-.943 0h0l-.862-.862v3.057c0 .368-.298.667-.667.667s-.667-.298-.667-.667h0V8.276l-.862.862c-.26.26-.682.26-.943 0s-.26-.682 0-.943h0l2-2c.064-.064.138-.112.216-.145zm2.922-3.977v1.259c0 .368.298.667.667.667h1.259c-.065-.188-.173-.361-.317-.505l-1.105-1.105c-.144-.144-.317-.251-.505-.317z"></path>
        </symbol>
    </svg>

</div>
<!-- end footer-section -->
<!-- end page-wrap -->
<?php require_once 'portal_footer.php' ?>