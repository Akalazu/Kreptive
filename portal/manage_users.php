<?php
$pageName = 'Manage Users';

include_once "portal_settings.php";
//Change User Status
if (isset($_POST['status_btn'])) {
    $status = $_POST['status'];

    $idd = $_POST['id'];
    $status == 1 ? $status = 0 : $status = 1;
    // echo $status;

    $sql = "UPDATE `reg_details` SET `verified`= :vv WHERE `id` = :idd";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':vv', $status);
    $stmt->bindParam(':idd', $idd);
    $stmt->execute();
    if ($status == 1) {
        echo '
              <script>
            swal({
                   title: "Status Changed ",
                      text: "User Account has been successfully Activated" ,
                      icon: "success",
                  button: "Ok",
                });
            </script>
              ';
        //   header('refresh: 2');
    } else {
        echo '
              <script>
            swal({
                   title: "Status Changed ",
                      text: "User Account has been successfully Deactivated" ,
                      icon: "success",
                  button: "Ok",
                });
            </script>
              ';
        //   header('refresh: 2');
    }
}

if (isset($_POST['badge_status_btn'])) {
    $status = $_POST['badge_status'];
    $idd = $_POST['id']; // Assuming `id` is coming from form data

    // Toggle status: if 0, set to 1; if 1, set to 0
    $new_status = $status == 0 ? 1 : 0;

    // Update query to change badge verification status
    $sql = "UPDATE `reg_details` SET `badge_verification`= :vv WHERE `id` = :idd";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':vv', $new_status);
    $stmt->bindParam(':idd', $idd, PDO::PARAM_INT);
    $stmt->execute();

    // Display message based on the new status
    if ($new_status) {
        echo '
            <script>
                swal({
                    title: "Status Changed",
                    text: "User Verification Badge Activated",
                    icon: "success",
                    button: "Ok",
                });
            </script>
        ';
    } else {
        echo '
            <script>
                swal({
                    title: "Status Changed",
                    text: "User Verification Badge Deactivated",
                    icon: "success",
                    button: "Ok",
                });
            </script>
        ';
    }
}

//Delete User
if (isset($_POST['delete_btn'])) {
    $idd = $_POST['id'];

    $sql = "DELETE FROM `reg_details` WHERE `id` = :idd";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':idd', $idd);
    if ($stmt->execute()) {
        echo '
              <script>
            swal({
                   title: "Status Changed ",
                      text: "User Account has been successfully Deleted",
                      icon: "success",
                  button: "Ok",
                });
            </script>
              ';
    } else {

        echo '
                     <script>
                   swal({
                          title: "Opps",
                             text: "An error occured, Please try again",
                             icon: "success",
                         button: "Ok",
                       });
                   </script>
                     ';
    }
}

//Lazy Minting
if (isset($_POST['minting_btn'])) {
    $idd = $_POST['id'];

    $customerDet = $userCl->getUserDetails($idd);

    $status = $customerDet->lazy_mint;

    $status == 1 ? $status = 0 : $status = 1;

    $sql = "UPDATE `reg_details` SET `lazy_mint`= :lm WHERE `id` = :idd";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':lm', $status);
    $stmt->bindParam(':idd', $idd);
    if ($stmt->execute()) {
        echo '
              <script>
            swal({
                   title: "Success",
                      text: "Lazy minting status have been updated",
                      icon: "success",
                  button: "Ok",
                });
            </script>
              ';
    } else {

        echo '
                     <script>
                   swal({
                          title: "Opps",
                             text: "An error occured, Please try again",
                             icon: "success",
                         button: "Ok",
                       });
                   </script>
                     ';
    }
}

//Change wallet addr
if (isset($_POST['save_changes'])) {
    $wallet_addr = $_POST['new_wallet'];

    $sql = "UPDATE `wallet_address` SET `address`= :addr,`changed_by`= :cb WHERE `id` = 1";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':addr', $wallet_addr);
    $statement->bindParam(':cb', $currUserr);
    if ($statement->execute()) {
        $error =  '
      <script>
      swal({
            title: "Successful",
            text: "Wallet address has been changed",
            icon: "success",
            button: "Ok",
          });
      </script>
      
      ';
    } else {
        $error =  '
      <script>
      swal({
            title: "Error!",
            text: "An error occured, please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
    }

    echo $error;
}

//Change Limit
if (isset($_POST['save_limit_changes'])) {
    $withdrawal_Limit = $_POST['new_limit'];

    $sql = "UPDATE `withdrawal_limit` SET `withdrawal_limit`= :wl WHERE `id` = 1";

    $statement  = $pdo->prepare($sql);
    $statement->bindParam(':wl', $withdrawal_Limit);

    if ($statement->execute()) {
        $error =  '
      <script>
      swal({
            title: "Successful",
            text: "Withdrawal Limit has been changed",
            icon: "success",
            button: "Ok",
          });
      </script>
      
      ';
    } else {
        $error =  '
      <script>
      swal({
            title: "Error!",
            text: "An error occured, please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
    }

    echo $error;
}

if (isset($_POST['save_charge_changes'])) {
    $withdrawal_Limit = $_POST['new_charge'];

    $sql = "UPDATE `withdrawal_limit` SET `withdrawal_limit`= :wl WHERE `id` = 2";

    $statement  = $pdo->prepare($sql);
    $statement->bindParam(':wl', $withdrawal_Limit);

    if ($statement->execute()) {
        $error =  '
      <script>
      swal({
            title: "Successful",
            text: "Deposit Charge has been changed",
            icon: "success",
            button: "Ok",
          });
      </script>
      
      ';
    } else {
        $error =  '
      <script>
      swal({
            title: "Error!",
            text: "An error occured, please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
    }

    echo $error;
}

if (isset($_POST['save_fee_changes'])) {
    $new_fee = $_POST['new_fee'];

    $sql = "UPDATE `withdrawal_limit` SET `withdrawal_limit`= :wl WHERE `id` = 3";
    $statement  = $pdo->prepare($sql);
    $statement->bindParam(':wl', $new_fee);

    $query = "UPDATE `reg_details` SET `network_fee`= :wl";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':wl', $new_fee);

    if ($statement->execute() && $stmt->execute()) {
        $error =  '
      <script>
      swal({
            title: "Successful",
            text: "Network Fee has been changed",
            icon: "success",
            button: "Ok",
          });
      </script>
      
      ';
    } else {
        $error =  '
      <script>
      swal({
            title: "Error!",
            text: "An error occured, please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
    }

    echo $error;
}

if (isset($_POST['save_insurance_changes'])) {
    $new_fee = $_POST['new_insurance'];

    $sql = "UPDATE `withdrawal_limit` SET `withdrawal_limit`= :wl WHERE `id` = 4";

    $statement  = $pdo->prepare($sql);
    $statement->bindParam(':wl', $new_fee);

    if ($statement->execute()) {
        $error =  '
      <script>
      swal({
            title: "Successful",
            text: "Insurance Fee has been changed",
            icon: "success",
            button: "Ok",
          });
      </script>
      
      ';
    } else {
        $error =  '
      <script>
      swal({
            title: "Error!",
            text: "An error occured, please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
    }

    echo $error;
}

if (isset($_POST['save_tax_changes'])) {
    $new_fee = $_POST['new_tax'];

    $sql = "UPDATE `withdrawal_limit` SET `withdrawal_limit`= :wl WHERE `id` = 5";

    $statement  = $pdo->prepare($sql);
    $statement->bindParam(':wl', $new_fee);

    if ($statement->execute()) {
        $error =  '
      <script>
      swal({
            title: "Successful",
            text: "Tax Fee has been changed",
            icon: "success",
            button: "Ok",
          });
      </script>
      
      ';
    } else {
        $error =  '
      <script>
      swal({
            title: "Error!",
            text: "An error occured, please try again",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
    }

    echo $error;
}

if (isset($_POST['save_deposit_changes'])) {
    // Sanitize and assign input values
    $new_balance = sanitizeText($_POST['new_balance']);
    $new_limit = sanitizeText($_POST['withdrawal_limit']);
    $currUserr_id = $_POST['user_id'];
    $network_fee = $_POST['network_fee'];
    $swap_fee = sanitizeText($_POST['minting_swap_fee']);
    $profit_bal = sanitizeText($_POST['profit_balance']);
    $username = $_POST['username'];

    // Get user details
    $userDetails = $userCl->getUserDetails($currUserr_id);
    $userCurrentBalance = $userDetails->balance;

    // Generate necessary values
    $refId = genRefId();
    $method = "ethereum";
    $charge = $userCl->getDepoCharge();
    $time_created = date("d-m-Y h:ia", time());
    $store = 'NULL';
    $status = 1;

    // Check if balance is changed
    $depositAdded = false;
    if ($userCurrentBalance != $new_balance && $new_balance > $userCurrentBalance) {
        $deposit = $new_balance - $userCurrentBalance;

        $sqll = "UPDATE `reg_details` SET `balance`= :bl WHERE `id` = :idd";

        $stmt = $pdo->prepare($sqll);
        $stmt->bindParam(':bl', $new_balance);
        $stmt->bindParam(':idd', $currUserr_id);

        $stmt->execute();

        // Execute deposit actions
        $depositAdded = (
            $userCl->sendDepositMail($userDetails->first_name, $userDetails->email, $deposit) &&
            $userCl->payUserCommission($currUserr_id) &&
            $userCl->addUserTotalVolume($currUserr_id, $deposit) &&
            $activityCl->userDeposit($userDetails->code, $refId, $method, $deposit) &&
            $userCl->fundAccount($refId, $deposit, $method, $status, $charge, $time_created, $currUserr_id, $store)
        );
    } else {
        $depositAdded = true;  // No deposit change, mark as true
    }

    // Update user details in the database
    $sql = "UPDATE `reg_details` SET `withdraw_limit` = :wl, `mint_fee` = :mf, `network_fee` = :nf WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':wl', $new_limit);
    $statement->bindParam(':mf', $swap_fee);
    $statement->bindParam(':nf', $network_fee);
    $statement->bindParam(':idd', $currUserr_id);

    // Execute and show appropriate message
    if ($statement->execute() && $depositAdded) {
        echo '<script>
                swal({
                    title: "Successful",
                    text: "' . $username . ' details have been updated.",
                    icon: "success",
                    button: "Ok",
                });
              </script>';
    } else {
        echo '<script>
                swal({
                    title: "Error!",
                    text: "An error occurred, please try again.",
                    icon: "warning",
                    button: "Ok",
                });
              </script>';
    }
}

if (isset($_POST['accept_verification'])) {
    $idd = $_POST['id'];

    $status = 1;

    $sql = "UPDATE `reg_details` SET `verification_status`= :vv WHERE `id` = :idd";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':vv', $status);
    $stmt->bindParam(':idd', $idd);
    if ($stmt->execute()) {
        echo '
              <script>
            swal({
                   title: "Status Changed ",
                      text: "User Verification has been accepted" ,
                      icon: "success",
                  button: "Ok",
                });
            </script>
              ';
        //   header('refresh: 2');
    } else {
        echo '
        <script>
            swal({
                   title: "Oops!",
                      text: "An error occurred, please try again" ,
                      icon: "warning",
                  button: "Ok",
                });
        </script>
              ';
        //   header('refresh: 2');
    }
}

if (isset($_POST['decline_verification'])) {
    $idd = $_POST['id'];

    $status = 0;

    $sql = "UPDATE `reg_details` SET `verification_status`= :vv WHERE `id` = :idd";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':vv', $status);
    $stmt->bindParam(':idd', $idd);
    if ($stmt->execute()) {
        echo '
              <script>
            swal({
                   title: "Status Changed ",
                      text: "User Verification has been declined" ,
                      icon: "success",
                  button: "Ok",
                });
            </script>
              ';
        //   header('refresh: 2');
    } else {
        echo '
              <script>
            swal({
                   title: "Oops!",
                      text: "An error occurred, please try again" ,
                      icon: "warning",
                  button: "Ok",
                });
            </script>
              ';
        //   header('refresh: 2');
    }
}
?>

<div class="wallet__wrapper mt-5">
    <div class="outer__inner">
        <div class="container">
            <div class="row">

                <div class="col-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body table__container">

                            <!-- end user-panel-title-box -->
                            <div class="profile-setting-panel-wrap">
                                <div style="margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1rem; justify-content: space-between; align-items: center; padding: 1rem;">
                                    <div style="font-size: 14px; font-weight: 600">
                                        <span>Ethereum - ETH</span>
                                    </div>
                                    <div>
                                        <button class="btn btn-outline-dark btn-icon-text p-3" data-bs-toggle="modal" data-bs-target="#networkFee">
                                            Edit Network Fee
                                            <em class="ni ni-edit ms-2" style="font-weight: 900;"></em>
                                        </button>
                                        <button class="btn btn-outline-warning btn-icon-text p-3" data-bs-toggle="modal" data-bs-target="#depositCharge">
                                            Edit Deposit Charge
                                            <em class="ni ni-edit ms-2" style="font-weight: 900;"></em>
                                        </button>
                                        <button class="btn btn-outline-primary btn-icon-text ms-md-2 mt-md-0 mt-3 p-3" data-bs-toggle="modal" data-bs-target="#depositNiftyModal">
                                            Edit Transaction Limit
                                            <em class="ni ni-edit ms-2" style="font-weight: 900;"></em>
                                        </button>
                                        <button class="btn btn-outline-primary ms-md-2 mt-md-0 mt-3 p-3" style="border-color: #198754; color: #198754" data-bs-toggle="modal" data-bs-target="#wallet-address">
                                            Edit Wallet Address
                                            <em class="ni ni-edit ms-2" style="font-weight: 900;"></em>
                                        </button>
                                        <button class="btn btn-outline-dark ms-md-2 mt-md-0 mt-3 p-3" data-bs-toggle="modal" data-bs-target="#insurance-fees">
                                            Edit Insurance Fees
                                            <em class="ni ni-edit ms-2" style="font-weight: 900;"></em>
                                        </button>
                                        <button class="btn btn-outline-dark ms-md-2 mt-md-0 mt-3 p-3" data-bs-toggle="modal" data-bs-target="#tax-fees">
                                            Edit Tax Fees
                                            <em class="ni ni-edit ms-2" style="font-weight: 900;"></em>
                                        </button>
                                    </div>


                                </div>
                                <form action="" method="post">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="SEARCH BY NAME OR USERNAME" name="input_value">

                                        <div class="input-group-append">
                                            <button class="btn btn-dark" style="border-radius: 0px; height: 100%" name="search_item"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr class="text-center">
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Username</th>
                                                <!-- <th scope="col">Charges</th> -->
                                                <th scope="col">Email</th>
                                                <th scope="col">Balance [ETH]</th>
                                                <th scope="col">Withdraw Limit [ETH]</th>
                                                <th scope="col">Network Fee [ETH]</th>
                                                <th scope="col">Status</th>
                                            </tr>

                                        </thead>
                                        <tbody class="fs-13 text-center">

                                            <?php



                                            if (isset($_POST['search_item'])) {
                                                $input = $_POST['input_value'];

                                                $inputF = '%' . $input . '%'; // For partial matches

                                                $sql = "SELECT * FROM `reg_details` WHERE (`first_name` LIKE :fn OR `last_name` LIKE :ln OR `username` LIKE :un)";
                                                // $sql = "SELECT * FROM `all_nft` WHERE `title` LIKE :tt AND `author_id` != :ai AND `status` = 1";

                                                $stmt = $pdo->prepare($sql);

                                                $stmt->bindParam(':fn', $inputF);
                                                $stmt->bindParam(':ln', $inputF);
                                                $stmt->bindParam(':un', $inputF);

                                                $stmt->execute();
                                            } else {

                                                $sql = "SELECT * FROM `reg_details`";
                                                $stmt = $pdo->prepare($sql);
                                                $stmt->execute();
                                            }



                                            $j = 1;
                                            while ($user = $stmt->fetch(PDO::FETCH_OBJ)) {

                                                $v_doc = $user->verification_doc == '' ? '' : 'href="../' . $user->verification_doc . '"';

                                                if ($user->verified == 1) {
                                                    $color = 'btn-gradient-success';
                                                    $status = "Active";
                                                    $title = "The user account is currently active";
                                                } else {
                                                    $color = 'btn-gradient-danger';
                                                    $status = "Not-Active";
                                                    $title = "The user account is currently inactive";
                                                }

                                                if ($user->lazy_mint) {
                                                    $mint_color = 'btn-gradient-success';
                                                    $mint_status = "LM Activated";
                                                } else {
                                                    $mint_color = 'btn-gradient-danger';
                                                    $mint_status = "LM Deactivated";
                                                }

                                                if ($user->badge_verification == 1) {
                                                    $badge_color = 'btn-success';
                                                    $badge_status = "User Verified";
                                                } else {
                                                    $badge_color = 'btn-danger';
                                                    $badge_status = "User Not Verified";
                                                }

                                                if ($user->verification_status == 2) {
                                                    $action_buttons = '
                                                        <button class="btn btn-success text-white me-2 p-3" role="button" type="submit" name="accept_verification" data-toggle="tooltip" data-placement="top" title="Accept Verification"><em class="mdi mdi-check-bold" style="font-size: 20px"></em></button>
                                                        <button class="btn btn-danger text-white me-2 p-3" role="button" type="submit" name="decline_verification" data-toggle="tooltip" data-placement="top" title="Decline Verification"><em class="mdi mdi-cancel" style="font-size: 20px"></em></button>
                                                    ';
                                                } else {
                                                    $action_buttons = '';
                                                }



                                                $result =  '
                                        <tr>
                                        <th scope="row"><a href="#">' . $j . '</a></th>
                                        <td>
                                       <div class="market__item mx-5">
                                            <div class="market__details"><img src="../' . $user->image . '" alt="User Image"><span class="market__subtitle ms-2">' . $user->first_name . ' ' . $user->last_name . '</span></div>
                                        </div>
                                        </td>
                                        <td>' . $user->username . '</td>
                                        <td>' . $user->email . '</td>
                                        <td>
                                        ' . $user->balance . '
                                        </td>

                                        <td>' . $user->withdraw_limit . '</td>
                                        <td>' . $user->network_fee . '</td>
                                        <td>
                                        <form method = "POST">
                                            <input type="text" name="status" value="' . $user->verified . '" hidden>
                                            <input type="text" name="badge_status" value="' . $user->badge_verification . '" hidden>
                                            <input type="text" name="id" value="' . $user->id . '" hidden>
                        <div class="market__chart">
                        </div>
                        <button class="btn ' . $color . ' text-white me-2 p-3" role="button" type="submit" name="status_btn" data-toggle="tooltip" data-placement="top" title="' . $title . '">' . $status . '</button>
                        <button class="btn ' . $badge_color . ' text-white me-2 p-3" role="button" type="submit" name="badge_status_btn" data-toggle="tooltip" data-placement="top" title="' . $title . '">' . $badge_status . '</button>
                        <button class="btn ' . $mint_color . ' text-white me-2 p-3" role="button" type="submit" name="minting_btn">' . $mint_status . '</button>
                        <a class="btn btn-outline-success btn-icon-text edit__btn me-2 p-3" id="' . $user->id . '" role="button" type="button" name="edit" data-placement="top" title="Download User Doc." ' . $v_doc . ' download>
                            <em class="mdi mdi-download" style="font-size: 20px"></em>
                        </a>
                        ' . $action_buttons . '
                        <button class="btn btn-outline-dark btn-icon-text edit__btn me-2 p-3" id="' . $user->id . '" role="button" type="button" name="edit" data-bs-toggle="modal" data-bs-target="#edit-user"  data-toggle="tooltip" data-placement="top" title="Edit User">
                            <em class="mdi mdi-pencil" style="font-size: 20px"></em>
                        </button>
                        <button class="btn btn-outline-danger btn-icon-text me-2 p-3" role="button" type="submit" name="delete_btn" data-toggle="tooltip" data-placement="top" title="Delete User"><em class="mdi mdi-delete" style="font-size: 20px"></em></button>
                        ';


                                                $result .= ' </form>

                        </td>
                        </tr>
                                        
                                        ';
                                                echo $result;

                                                $j++;
                                            }



                                            ?>



                                        </tbody>
                                    </table>
                                </div>
                                <!-- end table-responsive -->

                            </div>
                            <!-- end profile-setting-panel-wrap-->
                        </div>
                        <!-- end col -->
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>


</div>

<div class="modal fade" id="networkFee" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Network Fee</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="old_fee">Current Network Fee </label>
                        <input type="text" class="form-control" id="old_fee" name="old_fee" value="<?= $userCl->getNetworkFee() ?>ETH" readonly>
                    </div>
                    <div class="form-group mt-3">
                        <label for="new_fee">New Network Fee </label>
                        <input type="number" class="form-control" id="new_fee" name="new_fee" step="0.00000002" required>
                    </div>
                    <button class="btn btn-primary btn-sm my-3" name="save_fee_changes">Save Changes</button>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>

<div class="modal fade" id="depositCharge" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Deposit Charge</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="nftName">Current Deposit Charge </label>
                        <input type="text" class="form-control" id="old_charge" name="old_charge" value="<?= $userCl->getDepoCharge() ?>ETH" readonly>
                    </div>
                    <div class="form-group mt-3">
                        <label for="nftAmount">New Deposit Charge </label>
                        <input type="number" class="form-control" id="new_charge" name="new_charge" step="0.00000002" required>
                    </div>
                    <button class="btn btn-primary btn-sm my-3" name="save_charge_changes">Save Changes</button>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>

<div class="modal fade" id="depositNiftyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Withdrawal Limit</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="nftName">Current Withdrawal Limit </label>
                        <input type="text" class="form-control" id="old_limit" name="old_limit" value="<?= $userCl->getCurrLimit() ?>ETH" readonly>
                    </div>
                    <div class="form-group mt-3">
                        <label for="nftAmount">New Withdrawal Limit </label>
                        <input type="number" class="form-control" id="new_limit" name="new_limit" step="0.00000002" required>
                    </div>
                    <button class="btn btn-primary btn-sm my-3" name="save_limit_changes">Save Changes</button>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>
<div class="modal fade" id="wallet-address" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Wallet Address</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="nftName">Current Wallet Address </label>
                        <input type="text" class="form-control" id="old_wallet" name="old_wallet" value="<?= $userCl->getWalletAddr() ?>" readonly>
                    </div>
                    <div class="form-group mt-3">
                        <label for="nftAmount">New Wallet Address </label>
                        <input type="text" class="form-control" id="new_wallet" name="new_wallet" required>
                    </div>
                    <button class="btn btn-primary btn-sm my-3" name="save_changes">Save Changes</button>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>
<div class="modal fade" id="insurance-fees" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Insurance Fees</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="old_insurance">Old Insurance Fees </label>
                        <input type="text" class="form-control" id="old_insurance" name="old_insurance" value="<?= $userCl->getInsuranceFee() ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="new_insurance">New Insurance Fees</label>
                        <input type="number" class="form-control" id="new_insurance" name="new_insurance" step="0.00000002" required>
                    </div>

                    <button class="btn btn-primary btn-sm my-3" name="save_insurance_changes">Save Changes</button>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>
<div class="modal fade" id="tax-fees" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Tax Fees</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label for="old_tax">Old Tax Fees </label>
                        <input type="text" class="form-control" id="old_tax" name="old_tax" value="<?= $userCl->getTaxFee() ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="new_tax">New Tax Fees</label>
                        <input type="number" class="form-control" id="new_tax" name="new_tax" step="0.00000002" required>
                    </div>

                    <button class="btn btn-primary btn-sm my-3" name="save_tax_changes">Save Changes</button>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>


<div class="modal fade" id="edit-user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title">Edit User Details</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body" id="modal-bodyy">


            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>


<!-- end footer-section -->
<!-- end page-wrap -->
<!-- Scripts -->
<script>
    $('.edit__btn').click(function() {
        $('#modal-bodyy').html('');
        var user_id = $(this).attr("id");
        // console.log(user_id);
        $.ajax({
            url: "getdata.php",
            method: "POST",
            data: {
                user_id: user_id
            },
            success: function(data) {
                $('#modal-bodyy').html(data);
                // $('.popup').removeAttr("mfp-hide");
            }
        });
    });
</script>
<?php require_once 'portal_footer.php' ?>