<?php
$pageName = 'deposit';

include_once "portal_settings.php";

if (isset($_POST['send_proof'])) {
    if ($_FILES['img_upload']['name'] != '') {

        $idd = $_SESSION['currid'];
        $refId =  sanitizeText($_POST['reference_id']);
        $amount = $_POST['amount'];
        $method = sanitizeText($_POST['method']);
        $charge = 0.23;
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

        if ($statement->execute() && $activityCl->userDeposit($currUser->code, $refId, $method, $amount)) {
            echo '
           <script>
         swal({
               title: "Successful",
               text: "A sum of ' . $amount . ' ETH would be added to your account once payment is verified",
               icon: "success",
               button: "Redirecting...",
             });
         </script>
     ';
            header('refresh: 3; deposit_logs');
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

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="page-header pt-5 px-md-5 px-3">
                <h3 class="page-title"> Fund Account </h3>
                <nav aria-label="breadcrumb">
                    <!--<button type="button" class="btn btn-inverse-dark btn-fw" class="btn-link" data-bs-toggle="modal" data-bs-target="#depositNiftyModal">Fund Wallet</button>-->
                    <a type="button" class="btn btn-success btn-rounded btn-fw p-3" href="deposit"><b>Fund Wallet</b></a>
            
            
                </nav>
            </div>
            <div class="card-body table__container">
                <h6 class="text-center"><b>Deposit Logs</b></h6>
                </p>
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Reference ID</th>
                            <th>Date | Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `account_deposit` WHERE `depositor` = :dp ORDER BY `id` ASC ";
                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(':dp', $idd);
                        $statement->execute();
                        $j = 1;
                        while ($depos_history = $statement->fetch(PDO::FETCH_OBJ)) {
                            if ($depos_history->status == 1) {
                                $color = 'btn-gradient-success';
                                $status = "Approved";
                            } else if ($depos_history->status == 2) {
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
                            <td class="text-success">' . $depos_history->amount . 'ETH</td>
                            <td>' . $depos_history->reference_id . '</td>
                            <td>' . $depos_history->date_created . '</td>
                            <td> <button type="button" class="btn ' . $color . ' btn-rounded py-2 px-4">' . $status . '</button>
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
                <h4 class="modal-title">Fund Wallet</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <div class="qrcode-wrap d-flex align-items-center justify-content-center mb-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $userCl->getWalletAddr() ?>&size=240x240" class="me-3 flex-shirnk-0">
                </div>
                <p class="fs-14">Scan QR code or Click on the address below to copy address</p>
                <div class="d-flex align-items-center">
                    <input type="text" class="copy-input" value="<?= $userCl->getWalletAddr() ?>" id="copy-input" readonly>
                    <div class="tooltip-s1">
                        <button data-clipboard-target="#copy-input" class="copy-text ms-1" type="button">
                            <span class="tooltip-s1-text tooltip-text">Copy</span>
                            <em class="mdi mdi-content-copy"></em>
                        </button>
                    </div>
                </div>

                <form method="post" enctype="multipart/form-data">
                    <div class="input-group my-4">
                        <input type="number" name="amount" class="form-control form-control-s1" min="0" step=".01" placeholder="Enter Amount in ETH" required>
                        <input type="text" hidden value="ethereum" name="method">
                        <input type="text" hidden value="<?= genRefId() ?>" name="reference_id">
                    </div>
                    <p>Upload proof of payment</p>
                    <div class="input-group mb-4">
                        <input type="file" class="form-control" placeholder="Upload a proof of payment" name="img_upload" required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-gradient-dark btn-rounded btn-fw" name="send_proof">Save and Submit</button>
                    </div>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal-->
<!-- end footer-section -->
<!-- end page-wrap -->



<?php require_once 'portal_footer.php' ?>