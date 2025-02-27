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

    $mint_fee = $_POST['mint_fee'];

    if ($currUser->balance <= 0 && $currUser->lazy_mint == 1) {
        $remaining_balance = $currUser->balance;
        $type = "lazy";
    } else {
        $remaining_balance = $currUser->balance - ($mint_fee ?? 0.1);
        $type = "not_lazy";
    }

    $title = sanitizeName($_POST['name']);
    $description = sanitizeText($_POST['message']);
    $description = empty($description) ? 'Nil' : $description;
    $collection = 'Niffiti';
    $royalties = $_POST['royalties'];
    $amount = $_POST['price'];

    $tyme = time();
    $time_created = date("d-m-Y h:ia", $tyme);

    if ($remaining_balance < 0 && $currUser->lazy_mint == 0) {

        echo '
        <script>
            // Open the deposit modal when balance is insufficient
            document.addEventListener("DOMContentLoaded", function() {
                var depositModal = new bootstrap.Modal(document.getElementById("deposit-modal"));
                depositModal.show();
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
            $finFileName = 'mint' . $currUser->code . '_' . implode('', uniqueNum(0, 9, 5)) . '.' . $extension;
            $location = "uploads/" . $finFileName;
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

                        $link_id = $userCl->getNFTId();
                        $link = "https://niffiti.com/$currUser->first_name-$currUser->last_name/$link_id";

                        $sqll = "UPDATE `reg_details` SET `balance`= :bl WHERE id = :idd";
                        $stmt = $pdo->prepare($sqll);
                        $stmt->bindParam(':bl', $remaining_balance);
                        $stmt->bindParam(':idd', $idd);


                        $sql = "INSERT INTO `all_nft`(`author_name`, `owner_username`, `author_id`, `owner_id`, `type`, `collection`, `price`, `description`, `title`, `link`, `link_id`, `royalties`,`image`,`time_added`) VALUES (:au, :ou, :ai, :oi, :ty, :cl, :pc, :ds, :tt, :li, :ld, :ry, :img, :ta) ";

                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(':au', $currUserr);
                        $statement->bindParam(':ou', $currUserr);
                        $statement->bindParam(':ai', $idd);
                        $statement->bindParam(':oi', $idd);
                        $statement->bindParam(':ty', $type);
                        $statement->bindParam(':cl', $collection);
                        $statement->bindParam(':pc', $amount);
                        $statement->bindParam(':ds', $description);
                        $statement->bindParam(':tt', $title);
                        $statement->bindParam(':li', $link);
                        $statement->bindParam(':ld', $link_id);
                        $statement->bindParam(':ry', $royalties);
                        $statement->bindParam(':img', $location);
                        $statement->bindParam(':ta', $time_created);

                        if ($statement->execute() && $stmt->execute()) {
                            echo '
                        <script>
                            swal({
                                title: "Success",
                                    text: "NFT has been created Successfully" ,
                                    icon: "success",
                                button: "Minting...",
                                });
                            </script>
                            ';
                            header('refresh:2; create_nft');
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
                      text: "An error occurred, please try again" ,
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

<style>
    .cont-card {
        border-radius: 10px;
        box-shadow: 0 5px 10px 0 rgba(0, 0, 0, 0.3);
        width: 600px;
        height: 260px;
        background-color: #ffffff;
        padding: 10px 30px 40px;
    }

    .cont-card h3 {
        font-size: 22px;
        font-weight: 600;

    }

    .drop_box {
        margin: 10px 0;
        padding: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        border: 1px dotted #a3a3a3;
        border-radius: 5px;
    }

    .drop_box h4 {
        font-size: 16px;
        font-weight: 400;
        color: #2e2e2e;
    }

    .drop_box p {
        margin-top: 10px;
        margin-bottom: 20px;
        font-size: 12px;
        color: #a3a3a3;
    }

    .btn {
        text-decoration: none;
        background-color: #005af0;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        outline: none;
        transition: 0.3s;
    }

    .btn:hover {
        text-decoration: none;
        background-color: #ffffff;
        color: #005af0;
        padding: 10px 20px;
        border: none;
        outline: 1px solid #010101;
    }

    .form input {
        margin: 10px 0;
        width: 100%;
        background-color: #e2e2e2;
        border: none;
        outline: none;
        padding: 12px 20px;
        border-radius: 4px;
    }

    .notes__code {
        padding: 12px 1rem;
        background: #e6e8ec;
        border-radius: 4px;
        text-align: center;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 16px;
        line-height: 1.5;
        font-weight: 500;
        margin: auto;
    }
</style>


<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="page-header pt-5 px-md-5 px-3">
                <h3 class="page-title"> <a href="create_nft" class="text-dark"><i class="mdi mdi-arrow-left "></i> Go Back</a></h3>
            </div>
            <div class="card-body">
                <h4 class="card-title mb-5">Create Collectibles</h4>
                <!-- <p class="card-description"> Basic form elements </p> -->
                <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class=" form-group">
                        <label for="exampleInputName1">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="e.g. Gloze Ape Art" required>
                    </div>
                    <div class="form-group">
                        <label for="collections">Collection</label>

                        <input type="text" class="form-control" name="collections" required>
                    </div>
                    <div class="form-group">
                        <label for="messsage">Description</label>
                        <input type="text" class="form-control" name="message" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" name="price" placeholder="Enter price in ETH(ARB)" min="0" step=".01" required>
                    </div>

                    <div class="form-group">
                        <label for="price">Royalties(%)</label>
                        <input type="text" class="form-control" min="0" step=".01" value="10" name="royalties" required readonly>
                        <!-- <input type="number" class="form-control" name="price" min="0" step=".01" required> -->
                    </div>
                    <div class="image__preview">

                    </div>

                    <div class="form-group">
                        <label for="">Upload Files</label>
                        <div class="drop_box">
                            <header>
                                <h4>Select File here</h4>
                            </header>
                            <p>Files Supported: PDF, TEXT, DOC , DOCX</p>
                            <input type="file" accept=".jpg,.png,.jpeg" id="fileID" name="img_upload" onchange="previewImage(event)" hidden>
                            <button class="btn" type="button">Choose File</button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price">Minting Fee</label>
                        <input type="text" class="form-control" min="0" step=".01" value="<?= $userCl->getDepoCharge() ?> ETH" name="mint_fee" required readonly>
                    </div>

                    <!-- checkbox input -->
                    <div class="form-group d-flex align-items-center gap-2">
                        <input type="checkbox" class="form-check-input" name="accept_terms" id="accept_terms" required>
                        <label for="accept_terms" class="mb-0">A 10% brokerage fee in ETH applies to successful sales.</label>
                    </div>
            </div>


            <button type="submit" class="btn btn-primary me-2 mt-4" name="create_item">Submit</button>
            </form>
        </div>
    </div>
</div>

</div>

<div class="modal fade" id="deposit-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-4">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Insufficient Balance</h4>
                <button type="button" class="btn-close icon-btn" data-bs-dismiss="modal" aria-label="Close">
                    <em class="ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <p>You need at least <b>0.1 ETH</b> in your ETH Wallet to create an NFT. Please kindly make a deposit</p>
                <form method="post">

                    <label for="old_insurance">Copy the address below to make a deposit or click on the deposit button</label>

                    <div class="form-group notes__code mt-4" style="max-width: max-content;">

                        <input type="text" class="form-control" id="textInput" value="<?= $userCl->getWalletAddr() ?>" hidden readonly>

                        <span style="word-break: break-word; font-size:13px"><?= $userCl->getWalletAddr() ?></span>
                        <button class="notes__copy border-0" onclick="copyAddress()" type="button">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                    </div>

                    <a class="btn btn-primary my-3" href="deposit">Deposit</a>
                </form>
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div>


<script>
    function copyAddress() {
        // Get the text input element
        var copyText = document.getElementById("textInput").value;

        // Use the modern Clipboard API to copy text
        navigator.clipboard.writeText(copyText).then(function() {
            // Optionally, notify the user that the text has been copied
            alert("Address copied!");
        }).catch(function(err) {
            console.error('Failed to copy text: ', err);
        });
    }

    var preview_page = document.querySelector(".image__preview");

    function previewImage(event) {
        var image_SRC = URL.createObjectURL(event.target.files[0]);
        var image_created = document.createElement("img");
        image_created.src = image_SRC;
        image_created.attr = "Uploaded Image";
        image_created.classList.add("img-fluid");
        image_created.classList.add("img-thumbnail");

        preview_page.innerHTML = "";
        preview_page.innerHTML = "<h5 class='my-3'>Upload Preview</h5>";
        preview_page.appendChild(image_created);
    }

    const dropArea = document.querySelector(".drop_box"),
        button = dropArea.querySelector("button"),
        dragText = dropArea.querySelector("header"),
        input = dropArea.querySelector("input");
    let file;
    var filename;

    button.onclick = () => {
        input.click();
    };



    // input.addEventListener("change", function(e) {
    //     var fileName = e.target.files[0].name;
    //     let filedata = `
    //     <h4>${fileName}</h4>`;
    //     dropArea.innerHTML = filedata;
    // });
</script>

<?php require_once 'portal_footer.php' ?>