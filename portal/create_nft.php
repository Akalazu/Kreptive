<?php
$pageName = 'create NFT';

include_once "portal_settings.php";
$user = $userCl->getUserDetails($idd);

$currUserr = $user->first_name . ' ' . $user->last_name;



if (isset($_POST['save_author_collections'])) {
  $collection_name = sanitizeText($_POST['collection_name']);
  $collection_desc = $_POST['collection_desc'];
  if (containsNumbers($collection_name)) {
    echo '
    <script>
      swal({
            title: "Opps!",
            text: "Collection Name cannot contain Numbers",
            icon: "warning",
            button: "Ok",
          });
      </script>
    ';
  } else {

    $tyme = time();
    $time_created = date("d-m-Y h:ia", $tyme);

    $collection_desc  = empty($collection_desc) ? '' : $collection_desc;

    // $sql = "UPDATE `author_collections` SET `title`= :ttl,`description`= :dc, `author_id` = :aid WHERE `id` = 1";
    $sql = "INSERT INTO `author_collections`(`title`, `description`, `author_id`, `time_created`) VALUES (:tt,:ds,:ad,:tc)";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':tt', $collection_name);
    $statement->bindParam(':ds', $collection_desc);
    $statement->bindParam(':ad', $idd);
    $statement->bindParam(':tc', $time_created);
    if ($statement->execute()) {
      $error =  '
      <script>
      swal({
            title: "Successful",
            text: "A new Collection was created",
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
}


if (isset($_POST['create_item'])) {


  // print_r($_POST);
  // die();

  $remaining_balance = $currUser->balance - 0.2;

  $title = sanitizeName($_POST['name']);
  $description = sanitizeText($_POST['message']);
  $description = empty($description) ? 'Nil' : $description;
  $collection = $_POST['collections'];
  $amount = $_POST['price'];

  $tyme = time();
  $time_created = date("d-m-Y h:ia", $tyme);

  if ($remaining_balance < 0) {
    echo '
        
         <script>
      swal({
            title: "Oops!",
            text: "You do not have sufficient balance to mint this NFT",
            icon: "warning",
            button: "Ok",
          });
      </script>
        ';
  } else {

    if ($_FILES['img_upload']['name'] != '') {


      $fileName = $_FILES['img_upload']['name'];
      $tmp = $_FILES['img_upload']['tmp_name'];
      $size =  $_FILES['img_upload']['size'];

      $extension = explode(
        '.',
        $fileName
      );
      $extension = strtolower(end($extension));
      $location = "uploads/" . $fileName;
      $store = "../" . $location;

      if (
        $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png'
      ) {
        if ($size >= 5000000) {
          $error =  '
          <script>
          swal({
                title: "Error!",
                text: "Passport is larger than 5mb!, please compress it.",
                icon: "warning",
                button: "Ok",
              });
          </script>
          
          ';
          echo $error;
        } else {
          if (move_uploaded_file($tmp, "$store")) {

            $sqll = "UPDATE `reg_details` SET `balance`= :bl WHERE id = :idd";
            $stmt = $pdo->prepare($sqll);
            $stmt->bindParam(':bl', $remaining_balance);
            $stmt->bindParam(':idd', $idd);



            $sql = "INSERT INTO `all_nft`(`author_name`, `author_id`, `creator_id`,`collection`, `price`, `description`, `title`, `image`, `time_added`) VALUES (:au, :ai, :ci, :cl, :pc, :ds, :tt, :img, :ta) ";

            $statement = $pdo->prepare($sql);
            $statement->bindParam(':au', $currUserr);
            $statement->bindParam(':ai', $idd);
            $statement->bindParam(':ci', $idd);
            $statement->bindParam(':cl', $collection);
            $statement->bindParam(':pc', $amount);
            $statement->bindParam(':ds', $description);
            $statement->bindParam(':tt', $title);
            $statement->bindParam(':img', $location);
            $statement->bindParam(':ta', $time_created);

            if ($statement->execute() && $stmt->execute()) {
              echo '
                        <script>
            swal({
                   title: "Success",
                      text: "NFT has been created Successfully" ,
                      icon: "success",
                  button: "Ok",
                });
            </script>
              ';
              header('refresh:2; nft_logs');
            } else {
              echo '<script>
      swal({
            title: "Error!",
            text: "NFT was not created, insufficient balance",
            icon: "warning",
            button: "Ok",
          });
      </script>
      ';
            }
          } else {
            echo '
              <script>
            swal({
                   title: "Creation Failed",
                      text: "NFT should be in JPG or JPEG or PNG format" ,
                      icon: "error",
                  button: "Ok",
                });
            </script>
              ';
          }
        }
      } else {
        echo '
             <script>
      swal({
            title: "Error!",
            text: "NFT  must be in JPG or PNG format",
            icon: "warning",
            button: "Ok",
          });
      </script>
            ';
      }
    }
  }
}
?>



<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="page-header pt-5 px-md-5 px-3">
        <h3 class="page-title"> Create NFT </h3>
        <nav aria-label="breadcrumb">
          <button type="button" class="btn btn-dark btn-rounded btn-fw"><a href=" mint_nft" style="text-decoration: none; color: white">Mint an NFT</a></button>

        </nav>
      </div>
      <div class="card-body table__container">
        <h6 class="text-center">My NFTs</h6>
        </p>
        <table class="table table-hover table-responsive">
          <thead>
            <tr>
              <th class="font-weight-bold"><b>#</b></th>
              <th><b>Title</b></th>
              <th><b>Amount</b></th>
              <th><b>Collection</b></th>
              <th><b>Created</b></th>
              <th><b>Status</b></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * FROM `all_nft` WHERE `author_name` = :an ";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':an', $currUserr);
            $stmt->execute();

            $j = 1;
            while ($myNft = $stmt->fetch(PDO::FETCH_OBJ)) {
              if ($myNft->status == 1) {
                $color = 'btn-gradient-success';
                $status = "Minted";
              } else if ($myNft->status == 2) {
                $color = 'btn-gradient-danger';
                $status = "Not Minted";
              } else {
                $color = 'btn-gradient-dark';
                $status = "Minting";
              }
              echo '
              <tr>
                          <td>' . $j . '</td>
                          <td>' . $myNft->title . '</td>
                          <td class="text-green"> ' . $myNft->price . 'ETH</td>
                          <td> ' . $myNft->collection . '</td>
                          <td> ' . $myNft->time_added  . '</td>
                          <td><button type="button" class="btn ' . $color . ' btn-icon-text p-3 px-4">' . $status . '</button></td>
                </tr>
                        
                </div>
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