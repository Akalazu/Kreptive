<?php
$pageName = 'Superadmin';

include_once "portal_settings.php";


if ($currUser->role != 'admin') {
    header('Location: logout');
}

if (isset($_POST['save_nft_changes'])) {
    $id = $_POST['nft_id'];
    $title = sanitizeText($_POST['nft_title']);
    $price = sanitizeText($_POST['nft_price']);
    $collection = sanitizeText($_POST['nft_collection']);

    $sql = "UPDATE `all_nft` SET `title`= :tt,`price`= :pr, `collection` = :cc WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    $statement->bindParam(':tt', $title);
    $statement->bindParam(':pr', $price);
    $statement->bindParam(':cc', $collection);
    if ($statement->execute()) {
        $error =  '
      <script>
      swal({
            title: "Successful",
            text: "NFT details have been updated",
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

if (isset($_POST['delete_nft'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM `all_nft` WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':idd', $id);
    if ($statement->execute()) {
        $print = '
      <script>
      swal({
            title: "Successful",
            text: "NFT have been deleted",
            icon: "success",
            button: "Ok",
          });
      </script>
      
      ';
    } else {
        $print = '
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
    echo $print;
}
?>

<div class="wallet__wrapper mt-5">

    <div class="container">
        <div class="row">
            <!-- end col -->
            <?php
            // Deposittttttt

            if (isset($_POST['approve'])) {
                // print_r($_POST);
                // die();

                // die(); Array ( [deposit] => 0.35 [id] => 2 [userId] => 7 [approve] => )
                $iddd = $_POST['id']; //id of the deposit transaction

                $userIdd = $_POST['userId']; //id of the depositor

                $userdet = $userCl->getUserDetails($userIdd); //Id & full details of the depositor

                $user_balance = $userdet->balance; //account balance of the depositor

                $deposit = $_POST['deposit'];  //amount to be deposited

                // $curr_balance = $currUser->balance;

                $updated_depositor_balance = $deposit + $user_balance; //add deposit to balance

                $sqll = "UPDATE `reg_details` SET `balance`= :bl WHERE `id` = :idd";
                $stmt = $pdo->prepare($sqll);
                $stmt->bindParam(':bl', $updated_depositor_balance);
                $stmt->bindParam(
                    ':idd',
                    $userIdd
                );
                // if ($stmt->execute()) {
                if ($stmt->execute() && $userCl->sendDepositMail($userdet->first_name, $userdet->email, $deposit)) {
                    $sql = "UPDATE `account_deposit` SET `status`= 1 WHERE `id` = :idd";
                    $statement = $pdo->prepare($sql);
                    $statement->bindParam(':idd', $iddd);
                    if ($statement->execute()) {

                        $userCl->payUserCommission($userIdd);

                        $userCl->addUserTotalVolume($userIdd, $deposit);

                        echo '
                            <script>
                            swal({
                                title: "Success",
                                text: "The deposit has been successfully approved",
                                icon: "success"
                            })
                            </script>
                        
                            ';
                        header('refresh: 2');
                    } else {
                        echo '
                            <script>
                            swal({
                                title: "Oops!",
                                text: "An error occured, kindly try again",
                                icon: "warning"
                            })
                            </script>
                        
                            ';
                    }
                }
            }

            // deposittttt
            if (isset($_POST['decline'])) {
                $idd = $_POST['id'];

                $sql = "UPDATE `account_deposit` SET `status`= 2 WHERE `id` = :idd";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':idd', $idd);

                if ($statement->execute()) {
                    echo '
                            <script>
                            swal({
                                title: "Success",
                                text: "The deposit has been successfully declined",
                                icon: "success"
                            })
                            </script>
                        
                            ';
                } else {
                    echo '
                        <script>
                        swal({
                            title: "Oops!",
                            text: "An error occured, kindly try again",
                            icon: "warning"
                        })
                        </script>
                    
                        ';
                }
            }


            /**WITHDRAWAL */
            if (isset($_POST['approve_withdrawal'])) {

                $idd = $_POST['id'];
                $iddd = $currUser->id;
                $recipient = $userCl->getUserDetails($_POST['recipient']);
                $addr = $_POST['addr'];

                $withdraw = $_POST['withdraw'];
                // $curr_balance = $currUser->balance;


                // $updated_balance =  $curr_balance - $withdraw;

                // This was changed to balance
                $updated_balance = $recipient->balance - $withdraw;

                if ($updated_balance < 0) {
                    echo '
                    <script>
                    swal({
                        title: "Oops!",
                        text: "User has Insufficient Balance to withdraw this amount",
                        icon: "warning"
                    })
                    </script>
                
                    ';
                } else {
                    //Update User Balance
                    $sql = "UPDATE `reg_details` SET `profit` = :bl WHERE `id` = :idd";
                    $statement = $pdo->prepare($sql);
                    $statement->bindParam(':bl', $updated_balance);
                    $statement->bindParam(':idd', $recipient->id);

                    // Update withdrawal status
                    $sqll = "UPDATE `account_withdraw` SET `status`= 1 WHERE `id` = :idd";
                    $stmt = $pdo->prepare($sqll);
                    $stmt->bindParam(':idd', $idd);


                    if ($statement->execute() && $stmt->execute() && $userCl->sendWithdrawalMail($recipient->first_name, $recipient->email, $withdraw, $addr)) {
                        // if ($statement->execute() && $stmt->execute()) {
                        echo '
                <script>
                swal({
                    title: "Success",
                    text: "The withdrawal has been successfully approved",
                    icon: "success"
                })
                </script>
               
                ';
                        header('refresh: 2');
                    } else {

                        echo '
                <script>
                swal({
                    title: "Oops!",
                    text: "An error occured, kindly try again",
                    icon: "warning"
                })
                </script>
               
                ';
                    }
                }
            }


            if (isset($_POST['decline_withdrawal'])) {
                $idd = $_POST['id'];

                $sql = "UPDATE `account_withdraw` SET `status`= 2 WHERE `id` = :idd";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':idd', $idd);

                if ($statement->execute()) {
                    echo '
                    <script>
                    swal({
                        title: "Success",
                        text: "The withdrawal has been successfully declined",
                        icon: "success"
                    })
                    </script>
                
                    ';
                } else {
                    echo '
                    <script>
                    swal({
                        title: "Oops!",
                        text: "An error occured, kindly try again",
                        icon: "warning"
                    })
                    </script>
                
                    ';
                }
            }


            if (isset($_POST['approve_upload'])) {
                $idd = $_POST['id'];
                $author_idd = $_POST['author_id'];

                $sql = "UPDATE `all_nft` SET `status`= 1 WHERE `id` = :idd";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':idd', $idd);
                if ($statement->execute()) {
                    $userCl->updateTotalArtsCreated($author_idd);
                    echo '
                    <script>
                    swal({
                        title: "Success",
                        text: "NFT has been successfully approved",
                        icon: "success"
                    })
                    </script>
                
                    ';
                } else {
                    echo '
                    <script>
                    swal({
                        title: "Oops!",
                        text: "An error occured, kindly try again",
                        icon: "warning"
                    })
                    </script>
                
                    ';
                }
            }

            if (isset($_POST['decline_upload'])) {
                $idd = $_POST['id'];

                $sql = "UPDATE `all_nft` SET `status`= 2 WHERE `id` = :idd";
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':idd', $idd);

                if ($statement->execute()) {
                    echo '
                    <script>
                    swal({
                        title: "Success",
                        text: "NFT has been successfully declined",
                        icon: "success"
                    })
                    </script>
                
                    ';
                } else {
                    echo '
                    <script>
                    swal({
                        title: "Oops!",
                        text: "An error occured, kindly try again",
                        icon: "warning"
                    })
                    </script>
                
                    ';
                }
            }



            ?>
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body table__container">
                        <!-- end user-panel-title-box -->
                        <div class="profile-setting-panel-wrap pt-2">
                            <ul class="nav nav-tabs-s3 mb-2" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                                        Deposit
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="following-tab" data-bs-toggle="tab" data-bs-target="#following" type="button" role="tab" aria-controls="following" aria-selected="false">
                                        Withdrawal
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="bidding-tab" data-bs-toggle="tab" data-bs-target="#bidding" type="button" role="tab" aria-controls="bidding" aria-selected="false">
                                        NFT Uploads
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="nft-tab" data-bs-toggle="tab" data-bs-target="#nft" type="button" role="tab" aria-controls="nft" aria-selected="false">
                                        All NFTs
                                    </button>
                                </li>
                            </ul>
                            <!-- ADMIN DEPOSIT -->
                            <div class="tab-content mt-4" id="myTabContent">
                                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col">#</th>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Reference ID</th>
                                                    <th scope="col">Date | Time</th>
                                                    <th scope="col">Action</th>
                                                </tr>

                                            </thead>
                                            <tbody class="fs-13 text-center">

                                                <?php
                                                $query = "SELECT * FROM `account_deposit` WHERE `status` = 0 ORDER BY `id` DESC";
                                                $statement = $pdo->prepare($query);
                                                $statement->execute();
                                                $j = 1;

                                                while ($deposit = $statement->fetch(PDO::FETCH_OBJ)) {
                                                    $depositor_details = $userCl->getUserDetails($deposit->depositor);
                                                    $result =  '
                                                                    <tr>
                                                                    <th scope="row">' . $j . '</th>
                                                                    <td >' . $depositor_details->first_name . ' ' . $depositor_details->last_name . ' </th>
                                                                    <td style="color: #7952b3; font-weight: 500">' . $depositor_details->email . '</td>
                                                                    <td>' . $deposit->amount . 'ETH</td>
                                                                    <td>' . $deposit->reference_id . '</td>
                                                                    <td>' . $deposit->date_created . '</td>

                                                                    ';
                                                    if ($deposit->status == 0) {
                                                        $result .= '
                                                                                <td>
                                                                                <form action="" method="post">
                                                                    <input type="text" name="deposit" value="' . $deposit->amount . '" hidden>
                                                                    <input type="text" name="id" value="' . $deposit->id . '" hidden>
                                                                    <input type="text" name="userId" value="' . $deposit->depositor . '" hidden>
                                                                        <a class="btn btn-dark me-3" href="../' . $deposit->img_upload . '" download><i class="fa-solid fa-cloud-arrow-down"></i></a>

                                                                            <button class="btn btn-gradient-success btn-icon-text me-3 p-3" name="approve">Approve</button>
                                                                            <button class="btn btn-gradient-danger btn-icon-text p-3" name="decline">Decline</button>
                                                                                </form>
                                                                        </td>
                                                        
                                                                        ';
                                                    } else if ($deposit->status == 2) {
                                                        $result .= '
                                                    <td>
                                                            <div class="activity__col">
                                                        <div class="activity__label"></div><button class="button-small button-red p-3" >Declined</button>
                                                        </td>
                                                            </div>
                                                            </tr>
                                                                        
                                                                        
                                                                        
                                                                        </div>
                                                            ';
                                                    } else {
                                                        $result .= '
                                                                            <td class = "text-center">
                                                            <div class="activity__col">
                                                        <div class="activity__label"></div><button class="button-small button-green p-3" >Confirmed</button>
                                                        </td>
                                                                </div>
                                                                </tr>
                                                                
                                                                
                                                                
                                                            
                                                            ';
                                                    }
                                                    echo $result;
                                                    $j++;
                                                }

                                                ?>
                                                <!-- <td>
                                        <a href="#" class="icon-btn" title="Remore"><em class="ni ni-trash"></em></a>
                                    </td> -->


                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end activity-tab-wrap -->
                                </div>
                                <!-- end tab-pane -->
                                <div class="tab-pane fade" id="following" role="tabpanel" aria-labelledby="following-tab">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col">#</th>
                                                    <th scope="col">Name</th>
                                                    <!-- <th scope="col">Email</th> -->
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Wallet Address</th>
                                                    <th scope="col">Date | Time</th>
                                                    <th scope="col">Action</th>
                                                </tr>


                                            </thead>
                                            <tbody class="fs-13 text-center">

                                                <?php
                                                $query = "SELECT * FROM `account_withdraw` WHERE `status` = 10 ORDER BY `id` DESC ";
                                                $statement = $pdo->prepare($query);
                                                $statement->execute();

                                                $j = 1;
                                                // while ($activity = $stmt->fetch(PDO::FETCH_OBJ)) {
                                                while ($withdrawal = $statement->fetch(PDO::FETCH_OBJ)) {
                                                    $userDetails = $userCl->getUserDetails($withdrawal->withdraw_by);

                                                    $result =  '
                                        <tr>
                                        <th scope="row">' . $j . '</th>
                                        <td>' . $userDetails->first_name . ' ' . $userDetails->last_name . '</td>
                                        
                                        <td>' . $withdrawal->amount . 'ETH</td>
                                        <td style="color: #7952b3; font-weight: 500">' . $withdrawal->wallet_addr . '</td>
                                        <td>' . $withdrawal->time_withdrawn . '</td>
                                        
                                        ';


                                                    if ($withdrawal->status == 0) {
                                                        $result .= '
                                                    <td>
                                                    <form method="post">
                                        <input type="hidden" name="addr" value="' . $withdrawal->wallet_addr . '">
                                    <input type="hidden" name="withdraw" value="' . $withdrawal->amount . '">
                                    <input type="hidden" name="recipient" value="' . $withdrawal->withdraw_by . '">
                                    <input type="hidden" name="id" value="' . $withdrawal->id . '">

                                <button class="btn btn-gradient-success btn-icon-text p-3 me-3" name="approve_withdrawal">Approve</button>
                                <button class="btn btn-gradient-danger btn-icon-text p-3" name="decline_withdrawal">Decline</button>
                          
                                                    </form>
                                    </td>
                                    
                                                    ';
                                                    }
                                                    echo $result;
                                                    $j++;
                                                }

                                                ?>
                                                <!-- <td>
                                        <a href="#" class="icon-btn" title="Remore"><em class="ni ni-trash"></em></a>
                                    </td> -->


                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end activity-tab-wrap -->
                                </div>
                                <!-- end tab-pane -->
                                <div class="tab-pane fade" id="bidding" role="tabpanel" aria-labelledby="bidding-tab">
                                    <div class="table-responsive">
                                        <table class="table mb-0 ">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col">#</th>
                                                    <th scope="col">Creator</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Collection</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col">Date | Time</th>
                                                    <th scope="col">Action</th>
                                                </tr>

                                            </thead>
                                            <tbody class="fs-13 text-center">

                                                <?php
                                                $sql = "SELECT * FROM `all_nft`WHERE `status` = 0 ORDER BY `id` DESC";
                                                $statement = $pdo->prepare($sql);
                                                $statement->execute();

                                                $j = 1;
                                                while ($upload = $statement->fetch(PDO::FETCH_OBJ)) {

                                                    $result =  '
                                        <tr>
                                        <th scope="row">' . $j . '</th>
                                        <td>' . $upload->author_name . '</td>
                                        <td>' . $upload->title . '</td>
                                        <td style="color: #7952b3; font-weight: 500">' . $upload->collection . '</td>
                                        <td>' . $upload->price . 'ETH</td>
                                        <td>' . $upload->time_added . '</td>
                                        
                                        
                                        ';

                                                    if ($upload->status == 0) {
                                                        $result .= '
                                                    <td>
                                                    
                                                    <form method="post">
                                                    <input type="hidden" name="id" value="' . $upload->id . '">
                                                    <input type="hidden" name="author_id" value="' . $upload->author_id . '">
                                                    
                                            <button class="btn btn-gradient-dark btn-icon-text  p-3 me-3 btttt" data-bs-toggle="modal" data-bs-target="#depositNiftyModal" id="' . $upload->id . '" type="button" role ="button">Preview</button>
                                           <button class="btn btn-gradient-success btn-icon-text p-3 me-3" name="approve_upload">Approve</button>
                                          <button class="btn btn-gradient-danger btn-icon-text p-3" name="decline_upload">Decline</button>

                                                    </form>
                                        </td>
                                        
                                                        ';
                                                    } else if ($upload->status == 2) {
                                                        $result .= '
                                                    <td>
                                    <div class="activity__col">
                                <div class="activity__label"></div><button class="button-small button-red" >Declined</button>
                                </td>
                            
                                        
                                        
                                        
                                ';
                                                    } else {
                                                        $result .= '
                                <td class = "text-center">
                                <div class="activity__col">
                                <div class="activity__label"></div><button class="button-small button-green" >Confirmed</button>
                                </td>
                                </div>
                                </tr>
                                
                                </div>
                                        
                                        
                                       
                                    ';
                                                    }
                                                    echo $result;
                                                    $j++;
                                                }

                                                ?>
                                                <!-- <td>
                                        <a href="#" class="icon-btn" title="Remore"><em class="ni ni-trash"></em></a>
                                    </td> -->


                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end activity-tab-wrap -->
                                </div>
                                <!-- end tab-pane -->
                                <div class="tab-pane fade" id="nft" role="tabpanel" aria-labelledby="nft-tab">
                                    <div class="table-responsive">
                                        <table class="table mb-0 ">
                                            <thead>
                                                <tr class="text-center">
                                                    <th scope="col">#</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Creator</th>
                                                    <th scope="col">Collection</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col">Action</th>
                                                </tr>

                                            </thead>
                                            <tbody class="fs-13 text-center">
                                                <form action="" method="post">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" placeholder="SEARCH BY NFT TITLE" name="input_value">

                                                        <div class="input-group-append">
                                                            <button class="btn btn-dark" style="border-radius: 0px; height: 100%" name="search_item"><i class="fa fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </form>

                                                <?php
                                                if (isset($_POST['search_item'])) {
                                                    $input = $_POST['input_value'];

                                                    $inputF = '%' . $input . '%'; // For partial matches

                                                    $sql = "SELECT * FROM `all_nft` WHERE `title` LIKE :tt AND `status` = 1 ORDER BY `id` DESC";

                                                    $statement = $pdo->prepare($sql);

                                                    $statement->bindParam(':tt', $inputF);

                                                    $statement->execute();
                                                } else {
                                                    $sql = "SELECT * FROM `all_nft` WHERE `status` = 1 ORDER BY `id` DESC";
                                                    $statement = $pdo->prepare($sql);
                                                    $statement->execute();
                                                }

                                                $j = 1;
                                                while ($upload = $statement->fetch(PDO::FETCH_OBJ)) {

                                                    $result =  '
                                        <tr>
                                        <th scope="row">' . $j . '</th>
                                        <td>' . $upload->title . '</td>
                                        <td>' . $upload->author_name . '</td>
                                        <td style="color: #7952b3; font-weight: 500">' . $upload->collection . '</td>
                                        <td>' . $upload->price . 'ETH</td>
                                         <td>
                                                    
                                                    <form method="post">
                                                    <input type="hidden" name="id" value="' . $upload->id . '">
                                                    <input type="hidden" name="author_id" value="' . $upload->author_id . '">
                                                    
                                            <button class="btn btn-gradient-dark btn-icon-text p-3 me-3 button_class" data-bs-toggle="modal" data-bs-target="#previewNft" id="' . $upload->id . '" type="button" role ="button">Preview</button>
                                           <button class="btn btn-gradient-success btn-icon-text p-3 me-3 edit__btn" name="edit_nft" data-bs-toggle="modal" data-bs-target="#nft_details" type="button" role ="button" id="' . $upload->id . '">Edit</button>
                                          <button class="btn btn-gradient-danger btn-icon-text p-3" name="delete_nft">Delete</button>

                                                    </form>
                                        </td>
                                        
                                                    ';


                                                    echo $result;
                                                    $j++;
                                                }

                                                ?>
                                                <!-- <td>
                                        <a href="#" class="icon-btn" title="Remore"><em class="ni ni-trash"></em></a>
                                    </td> -->


                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end activity-tab-wrap -->
                                </div>
                                <!-- end tab-pane -->
                                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                                    <?php
                                    $sql = "SELECT * FROM `activities_db` WHERE `created_by` = :cb";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':cb', $currUser->id);
                                    $stmt->execute();

                                    $j = 1;
                                    while ($activity = $stmt->fetch(PDO::FETCH_OBJ)) {
                                        if ($activity->type == 'purchase') {
                                            $icon = '
                                        
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#55397e" class="w-6 h-6">
                                                    <path fill-rule="evenodd" d="M9.75 6.75h-3a3 3 0 00-3 3v7.5a3 3 0 003 3h7.5a3 3 0 003-3v-7.5a3 3 0 00-3-3h-3V1.5a.75.75 0 00-1.5 0v5.25zm0 0h1.5v5.69l1.72-1.72a.75.75 0 111.06 1.06l-3 3a.75.75 0 01-1.06 0l-3-3a.75.75 0 111.06-1.06l1.72 1.72V6.75z" clip-rule="evenodd" />
                                                    <path d="M7.151 21.75a2.999 2.999 0 002.599 1.5h7.5a3 3 0 003-3v-7.5c0-1.11-.603-2.08-1.5-2.599v7.099a4.5 4.5 0 01-4.5 4.5H7.151z" />
                                                </svg>
                                            ';
                                        } else {
                                            $icon = '
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#55397e" class="w-6 h-6">
                                    <path d="M9.97.97a.75.75 0 011.06 0l3 3a.75.75 0 01-1.06 1.06l-1.72-1.72v3.44h-1.5V3.31L8.03 5.03a.75.75 0 01-1.06-1.06l3-3zM9.75 6.75v6a.75.75 0 001.5 0v-6h3a3 3 0 013 3v7.5a3 3 0 01-3 3h-7.5a3 3 0 01-3-3v-7.5a3 3 0 013-3h3z" />
                                    <path d="M7.151 21.75a2.999 2.999 0 002.599 1.5h7.5a3 3 0 003-3v-7.5c0-1.11-.603-2.08-1.5-2.599v7.099a4.5 4.5 0 01-4.5 4.5H7.151z" />
                                    </svg>

                                                                            ';
                                        }

                                        echo '

                                      <div class="card card-creator-s1 mb-4">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="avatar avatar-1 flex-shrink-0">
                                                ' . $icon . '
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <h6 class="card-s1-title mb-1" >' . $activity->activity . '</h6>
                                                <p class="card-s1-text">
                                                    Date | Time :
                                                    <a>' . $activity->time_created . '</a> 
                                                </p>
                                                
                                            </div>
                                        </div>
                                    </div>';
                                    }

                                    ?>
                                </div>
                                <!-- end tab-pane -->
                            </div>
                            <!-- end tab-content -->
                        </div>
                        <!-- end profile-setting-panel-wrap-->

                    </div>
                    <div class="modal fade" id="previewNft" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Preview</h4>
                                    <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                                        <em class="ni ni-cross"></em>
                                    </button>
                                </div>
                                <div class="modal-body modal_content_allNft">

                                </div><!-- end modal-body -->
                            </div><!-- end modal-content -->
                        </div><!-- end modal-dialog -->
                    </div>
                    <div class="modal fade" id="depositNiftyModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Preview</h4>
                                    <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                                        <em class="ni ni-cross"></em>
                                    </button>
                                </div>
                                <div class="modal-body modal_content_allUploads">

                                </div><!-- end modal-body -->
                            </div><!-- end modal-content -->
                        </div><!-- end modal-dialog -->
                    </div>
                    <!-- end profile-setting-panel-wrap-->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->

        </div>
    </div>
</div>

<div class="modal fade" id="nft_details" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">NFT Details</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body modal_edit_nftDetails">

            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>
<!-- end footer-section -->
<!-- end page-wrap -->
<!-- Scripts -->
<!-- <script src="../assets/js/utilities.js"></script> -->

<script>
    $('.edit__btn').click(function() {
        $('.modal_edit_nftDetails').html('');
        var nft_id = $(this).attr("id");
        // console.log(user_id);
        $.ajax({
            url: "getdata.php",
            method: "POST",
            data: {
                nft_id: nft_id
            },
            success: function(data) {
                $('.modal_edit_nftDetails').html(data);
                // $('.popup').removeAttr("mfp-hide");
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>



<script src="../assets/js/jquery.magnific-popup.min.js"></script>


<?php require_once 'portal_footer.php' ?>