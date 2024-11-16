<?php
$pageName = 'Account Settings';

include_once "portal_settings.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_change'])) {

  // Array ( [first_name] => Akalazu [last_name] => David [username] => CodeBurster_ [emailAddress] => realearlsamm@gmail.com [bio] => I make art with the simple goal of giving you something pleasing to look at for a few seconds. [save_change] => )
  $fname = sanitizeText($_POST['first_name']);
  $lname = sanitizeText($_POST['last_name']);
  $uname = sanitizeText($_POST['username']);
  $email = sanitizeText($_POST['emailAddress']);
  $bio = sanitizeText($_POST['bio']);

  if (!(empty($fname) || empty($lname) || empty($uname) || empty($email))) {
    $sql = "UPDATE `reg_details` SET `first_name`= :fn,`last_name`= :ln,`username`= :un, `bio` = :bi, `email`= :em WHERE id = :idd";
    $stmtt = $pdo->prepare($sql);
    $stmtt->bindParam(':idd', $currUser->id);
    $stmtt->bindParam(':fn', $fname);
    $stmtt->bindParam(':ln', $lname);
    $stmtt->bindParam(':un', $uname);
    $stmtt->bindParam(':em', $email);
    $stmtt->bindParam(':bi', $bio);

    if ($stmtt->execute()) {
      echo '
              <script>
            swal({
                   title: "Update Successful",
                      text: "Your details has been updated" ,
                      icon: "success",
                  button: "Loading...",
                });
            </script>
              ';
      header('refresh: 2; ');
    } else {
      echo '
              <script>
            swal({
                   title: "Error",
                      text: "Details were not updated. Please try again" ,
                      icon: "error",
                  button: "Ok",
                });
            </script>
              ';
    }
  } else {
    echo '
              <script>
            swal({
                   title: "Error",
                      text: "Fields cannot be empty" ,
                      icon: "warning",
                  button: "Ok",
                });
            </script>
              ';
  }
  if (isset($_FILES['photoUpdate']['name'])) {
    if ($_FILES['photoUpdate']['name'] != '') {
      $fileName = $_FILES['photoUpdate']['name'];
      $tmp = $_FILES['photoUpdate']['tmp_name'];
      $size =  $_FILES['photoUpdate']['size'];

      $extension = explode(
        '.',
        $fileName
      );
      $extension = strtolower(end($extension));
      $newfileName =  $currUser->code . '.' . $extension;
      $store = "uploads/dp/" . $newfileName;
      $location = '../' . $store;


      if (
        $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'
      ) {
        if ($size >= 1000000) {
          $error =  '
      <script>
      swal({
            title: "Error!",
            text: "Passport is larger than 1mb!, please compress it.",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
          echo $error;
        } else {

          if (move_uploaded_file($tmp, $location)) {
            $sql = "UPDATE `reg_details` SET image = :passp WHERE id = :idd";
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':passp', $store);
            $statement->bindValue(':idd', $currUser->id);
            if ($statement->execute()) {
              echo '
          <script>
        swal({
               title: "Passport Update Successful for ' . $currUser->first_name . ' ' .  $currUser->last_name . '",
                  text: "Update Success" ,
                  icon: "success",
              button: "Ok",
            });
        </script>
          ';
              header('refresh:2');
            }
          }
        }
      } else {
        echo '
          <script>
        swal({
               title: "Update Failed",
                  text: "Passport should be in jpg or jpeg format" ,
                  icon: "error",
              button: "Ok",
            });
        </script>
          ';
      }
    }
  }
}

if (isset($_POST['save_pass'])) {
  $ref_id = genRefId();

  $newpass = sanitizeText($_POST['newpwrd']);
  $conpass = sanitizeText($_POST['conpwrd']);

  if (empty($newpass) or empty($conpass)) {
    $error = '
        <script>
      swal({
            title: "Error!",
            text: "Fields cannot be empty",
            icon: "warning",
            button: "Ok",
          });
      </script>
        
        ';
    echo $error;
  } elseif ($conpass !== $newpass) {
    $error = '
        <script>
      swal({
            title: "Password Not Updated",
            text: "Passwords do not match",
            icon: "warning",
            button: "Ok",
          });
      </script>
        
        ';
    echo $error;
  } elseif (strlen($newpass) < 6 or strlen($conpass) < 6) {
    $error = '
        <script>
      swal({
            title: "Password Not Updated",
            text: "Passwords must be at least 6 characters",
            icon: "warning",
            button: "Ok",
          });
      </script>
        
        ';
    echo $error;
  } else {
    $activityCl->updatedPass($currUser->code, $ref_id);
    $passwordHash = password_hash($conpass, PASSWORD_BCRYPT);

    $sql = "UPDATE `reg_details` SET `password` = :obb WHERE `id` = :idd";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':obb', $passwordHash);
    $statement->bindValue(':idd', $idd);
    if ($statement->execute()) {
      echo '
             <script>
      swal({
            title: "Update Successful",
            text: "Passwords has been changed",
            icon: "success",
            button: "Ok",
          });
      </script>
            ';
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_doc'])) {

  $identification_type = $_POST['identification'];

  // print_r($_FILES);
  if (isset($_FILES['id_card'])) {
    $id_fileName = $_FILES['id_card']['name'];
    $id_tmp = $_FILES['id_card']['tmp_name'];
    $id_size =  $_FILES['id_card']['size'];

    $id_extension = explode(
      '.',
      $id_fileName
    );


    $id_extension = strtolower(end($id_extension));

    $id_newfileName =  $currUser->code . '_ID' . '.' . $id_extension;

    $id_store = "uploads/documents/" . $id_newfileName;

    if (
      $id_extension == 'jpg' || $id_extension == 'jpeg' || $id_extension == 'png'
      || $id_extension == 'pdf'
    ) {
      if ($id_size >= 3000000) {
        $error =  '

         <script>
      swal({
            title: "Error!",
            text: "Passport is larger than 3mb!, please compress it.",
            icon: "warning",
            button: "Ok",
          });
      </script>
      
      ';
        echo $error;
      } else {

        if (move_uploaded_file($id_tmp, '../' . $id_store)) {
          $status = 2;
          $time = date('h:ia d-m-Y', time());

          $sql = "UPDATE `reg_details` SET verification_type = :ty, verification_doc = :passp, verification_status = :sta WHERE id = :idd";
          $statement = $pdo->prepare($sql);
          $statement->bindValue(':ty', $identification_type);
          $statement->bindValue(':passp', $id_store);
          $statement->bindValue(':sta', $status);
          $statement->bindValue(':idd', $idd);
          if ($statement->execute() && $activityCl->kycVerification($currUser->id, $identification_type)) {
            foreach ($admin_mails as $adminMail) {
              $userCl->sendAdminKycEmail($currUser->first_name . ' ' . $currUser->last_name, $adminMail->email, $currUser->username, $identification_type, $time);
            }
            echo
            '
         <script>
        swal({
               title: "Document Upload Successful for ' . $fullname . '",
                  text: "Update Success" ,
                  icon: "success",
              button: "Loading",
            });
        </script>
          ';
            header('refresh:2');
          }
        }
      }
    } else {
      echo '
     <script>
        swal({
               title: "Update Failed",
                  text: "Document should be in PDF or JPEG or PNG format" ,
                  icon: "error",
              button: "Ok",
            });
        </script>
          ';
    }
  }
}

if (isset($_POST['full_verification'])) {
  $user_id = sanitizeText($_POST['userId']);
  if ($userCl->hasMintedOrBoughtTenNFTs($user_id)) {
    $userCl->fullVerification($user_id);
    echo
    '
     <script>
        swal({
               title: "Full Verification Successful",
                  text: "User has been fully verified" ,
                  icon: "success",
              button: "Ok",
            });
        </script>
          ';
    header('refresh:3');
  } else {
    echo
    '
     <script>
        swal({
               title: "Verification Failed",
                  text: "User has not minted or bought 10 NFTs yet" ,
                  icon: "error",
              button: "Ok",
            });
        </script>
          ';
    header('refresh:4');
  }
}
?>

<div class="container">
  <div class="row">
    <!-- end col -->
    <div class="card">
      <div class="card-body table__container">
        <!-- end user-panel-title-box -->
        <div class="profile-setting-panel-wrap">
          <ul class="nav nav-tabs nav-tabs-s1 nav-tabs-mobile-size" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="account-information-tab" data-bs-toggle="tab" data-bs-target="#account-information" type="button" role="tab" aria-controls="account-information" aria-selected="true">
                Edit Info
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab" aria-controls="change-password" aria-selected="false">
                Password
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="validate-profile-tab" data-bs-toggle="tab" data-bs-target="#validate-profile" type="button" role="tab" aria-controls="validate-profile" aria-selected="false">
                Verify Profile
              </button>
            </li>
          </ul>
          <div class="tab-content mt-4" id="myTabContent">
            <div class="tab-pane fade show active" id="account-information" role="tabpanel" aria-labelledby="account-information-tab">
              <form method="post" enctype="multipart/form-data" class="mb-5">

                <div class="profile-setting-panel">
                  <h5 class="mb-4">Edit Profile</h5>
                  <div class="d-flex align-items-center">
                    <div class="image-result-area avatar avatar-3">
                      <img id="image-result" src="../<?= $currUser->image ?>" alt="" />
                    </div>
                    <input class="upload-image" data-target="image-result" id="upload-image-file" name="photoUpdate" type="file" hidden />
                    <label for="upload-image-file" class="upload-image-label btn">Update Photo</label>
                  </div>
                  <!-- end d-flex -->
                  <div class="row mt-4">
                    <div class="col-lg-6 mb-3">
                      <label for="first_name" class="form-label">First Name</label>
                      <input type="text" name="first_name" class="form-control form-control-s1" value="<?= $currUser->first_name ?>" />
                    </div>
                    <div class="col-lg-6 mb-3">
                      <label for="last_name" class="form-label">Last Name</label>
                      <input type="text" name="last_name" id="last_name" class="form-control form-control-s1" value="<?= $currUser->last_name ?>" />
                    </div>
                    <!-- end col -->
                    <div class="col-lg-6 mb-3">
                      <label for="username" class="form-label">Username</label>
                      <input type="text" id="username" name="username" class="form-control form-control-s1" value="<?= $currUser->username ?>" />
                    </div>
                    <!-- end col -->
                    <div class="col-lg-6 mb-3">
                      <label for="emailAddress" class="form-label">Email</label>
                      <input type="email" id="emailAddress" name="emailAddress" class="form-control form-control-s1" value="<?= $currUser->email ?>" />
                    </div>
                    <div class="col-12 mb-3">
                      <label for="bio" class="form-label">Bio</label>

                      <textarea name="bio" id="bio" cols="30" rows="10" class="form-control form-control-s1"><?= $currUser->bio ?></textarea>
                    </div>
                  </div>
                  <!-- end row -->
                  <button class="btn btn-dark mt-3" name="save_change">
                    Update Profile
                  </button>
                </div>
              </form>



              <h4 class="my-3">Profile Link</h4>
              <div class="mb-3">
                <div class="position-relative">
                  <input type="text" class="form-control form-control-s1" id="profileLink" name="newpwrd" value="https://kreptive.com/username/<?= $currUser->address ?>" />
                  <span class="password-toggle" title="Toggle show/hide pasword">
                    <em class="password-shown mdi mdi-content-copy" onClick="copyProfileLink()"></em>
                  </span>
                </div>
              </div>
            </div>
            <!-- end tab-pane -->
            <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
              <form method="post">
                <div class="profile-setting-panel">
                  <!-- <h5 class="mb-4">Change Password</h5> -->

                  <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <div class="position-relative">
                      <input type="password" class="form-control form-control-s1" id="newPassword" name="newpwrd" placeholder="New password" />
                      <a href="newPassword" class="password-toggle" title="Toggle show/hide pasword">
                        <em class="password-shown ni ni-eye-off"></em>
                        <em class="password-hidden ni ni-eye"></em>
                      </a>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                    <div class="position-relative">
                      <input type="password" class="form-control form-control-s1" id="confirmNewPassword" name="conpwrd" placeholder="Confirm new password" />
                      <a href="confirmNewPassword" class="password-toggle" title="Toggle show/hide pasword">
                        <em class="password-shown ni ni-eye-off"></em>
                        <em class="password-hidden ni ni-eye"></em>
                      </a>
                    </div>
                  </div>
                  <button class="btn btn-dark mt-3" name="save_pass">
                    Update Password
                  </button>
                </div>
              </form>
              <!-- end profile-setting-panel -->
            </div>
            <!-- end tab-pane -->
            <div class="tab-pane fade" id="validate-profile" role="tabpanel" aria-labelledby="validate-profile-tab">
              <div class="profile-setting-panel">
                <p class="mb-3 fs-14">
                  Verify your identity to unlock exclusive access to our community.
                </p>
                <p>
                  Start by submitting a valid document as the first step of verification. Once approved, you can apply for full verification to access all community privileges.
                </p>

                <?php
                if ($currUser->verification_status == 1) {
                  echo "'
                            <p><b>To apply, ensure you've minted or own at least 10 NFTs. Join now and connect with other verified members!</b></p>";
                }
                ?>

                <hr class="my-4" />

                <?php
                if ($currUser->verification_status == 2) {
                  echo '
                            <div class="alert alert-warning" role="alert">
                                Your KYC verification is currently pending. 
                            </div>
                            ';
                } else if ($currUser->verification_status == 1) {
                  echo '
                            <div role="alert">
                                <p>
                                  Your KYC verification is completed successfully. 
                                </p>
                                <p>
                                  You can now apply for full verification to access all community privileges.
                                </p>

                                

                                <form action="" method="post">
                              
                                <input type="hidden" id="userId" name="userId" class="form-control form-control-s1" value="' . $currUser->id . '" />

                                <button name="full_verification" class="btn btn-success mt-3" type="submit">
                                  Apply for Full Verification
                                </button></form>
                            </div>
                            ';
                } else {
                  echo "
                      <h6 class='mb-4 fw-semibold'>
                        Upload Verification Document
                      </h6>
                      <form method='post' class='mt-3' enctype='multipart/form-data'>
                        <div class='row'>
                          <div class='col-lg-12 my-4'>
                            <div class='mb-3'>
                              <label for='document' class='form-label'>Document Type.</label>
                              <select class='form-select' id='identification' name='identification' required>

                              <option>Select Identification</option>
                              <option value='National ID'>National ID</option>
                              <option value='Driver License'>Driver's License</option>
                              <option value='Voter Card'>Voter's Card</option>
                              <option value='International Passport'>International Passport</option>
                                </select>
                            </div>
                          </div>
                          <div class='col-lg-12'>
                            <div class='mb-3'>
                              <label for='document' class='form-label'>Select File</label>
                              <input type='file' id='document' name='id_card' class='form-control form-control-s1' required>
                            </div>
                          </div>
                          <!-- end col -->
                          <div class='col-12'>
                            <button class='btn btn-primary' name='upload_doc' type='submit'>
                              Submit
                            </button>
                          </div>
                        </div> 
                      </form>
                            ";
                }

                ?>

              </div>
              <!-- end profile-setting-panel -->
            </div>
            <!-- end tab-pane -->
          </div>
          <!-- end tab-content -->
        </div>
      </div>
    </div>

    <!-- end col -->
  </div>
  <!-- end row -->
</div>
<!-- end container -->
<!-- </section> -->
<!-- end profile-section -->

<!-- end row -->
<?php require_once 'portal_footer.php' ?>