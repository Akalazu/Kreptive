<?php
$pageName = 'deposit';

include_once "portal_settings.php";

if (isset($_POST['send_proof'])) {

    $idd = $_SESSION['currid'];
    $refId =  sanitizeText($_POST['reference_id']);
    $amount = $_POST['amount_deposit'];
    $method = sanitizeText($_POST['method']);
    $charge = $_POST['charges'];
    $tyme = time();

    $store = 'NULL';

    $time_created = date("d-m-Y h:ia", $tyme);

    $sql = "INSERT INTO `account_deposit`(`reference_id`, `amount`, `method`, `charge`, `date_created`, `depositor`, `img_upload`) VALUES (:ri, :am, :mt, :ch, :dc, :dp, :iu)";

    $statement = $pdo->prepare($sql);
    $statement->bindParam(':ri', $refId);
    $statement->bindParam(':am', $amount);
    $statement->bindParam(':mt', $method);
    // $statement->bindParam(':st', $refId);
    $statement->bindParam(':ch', $charge);
    $statement->bindParam(':dc', $time_created);
    // $statement->bindParam(':dv', $refId);
    $statement->bindParam(':dp', $currUser->id);
    $statement->bindParam(':iu', $store);

    if ($statement->execute() &&  $activityCl->userDeposit($currUser->code, $refId, $method, $amount)) {
        // unset($_SESSION['amount_deposit']);
        echo '
                        <script>
                        swal({
                            title: "Successful",
                            text: "A sum of ' . $amount . 'ETH would be added to your account once payment is verified",
                            icon: "success",
                            button: "Redirecting...",
                            });
                        </script>
                        ';
        header('refresh: 2; deposit_logs');
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
}
?>

<div class="wallet__wrapper mt-5">
    <div class="outer__inner">
        <div class="container">
            <div class="row">

                <!-- end col -->
                <div class="col-lg-12">

                    <div class="wallet__wrapper">
                        <div class="outer__inner">
                            <div class="bidding js-bidding">

                                <div class="bidding__body">
                                    <div class="bidding__center">

                                        <div class="bidding__wrapper">

                                            <div class="bidding__item js-bidding-item">
                                                <div class="notes">


                                                    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between;">
                                                        <!-- <button style="padding-inline: 1.5rem; background: rgb(234,205,163); background: linear-gradient(90deg, rgba(234,205,163,1) 0%, rgba(244,176,92,1) 35%, rgba(245,131,109,1) 100%);" class="button button-small wallet__button button js-popup-open payment__button" href="#popup-successfully" data-effect="mfp-zoom-in">Fund Account</button> -->
                                                        <div style="display: flex; gap: .25rem; align-items: center; font-weight: 600">
                                                            <div class="market__icon"><img src="../assets/images/ethereum.svg" alt="Ethereum"></div>
                                                            <span style="font-size: 1rem;">Deposit Ethereum - ETH</span>
                                                        </div>

                                                        <!-- <button style="padding-inline: 1.5rem; background-color: #627EEA;" class="button button-small wallet__button button js-popup-open payment__button" href="#popup-successfully" data-effect="mfp-zoom-in">Fund Account</button> -->
                                                    </div>

                                                    <!-- <div class="notes__title">Payment details  </div> -->
                                                    <p style="font-size: 12px; text-align: center; font-weight: 600; margin-bottom: 2.5rem;">Payment Details</p>

                                                    <!-- <div class="notes__info">Wallet Recepient Address</div> -->

                                                    <div style="text-align: center;">
                                                        <div class="notes__text"><b>Send only ETH to this deposit address, and ensure the network is Ethereum (ERC20)</b></div>
                                                        <div class="notes__code" style="max-width: max-content;">

                                                            <input type="text" class="form-control" value="<?= $userCl->getWalletAddr() ?>" id="textInput" hidden="" readonly="">

                                                            <span style="word-break: break-word; font-size:14px"><?= $userCl->getWalletAddr() ?></span>
                                                            <button class="notes__copy" onclick="copyAddress()">
                                                                <i class="mdi mdi-content-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <br>
                                                    <!-- <div class="notes__text" style="text-align: center;">OR Scan the QR code to send</div> -->
                                                    <br>
                                                    <div style="text-align: center;">

                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $userCl->getWalletAddr() ?>&size=240x240">
                                                    </div>


                                                    <br>
                                                    <br>

                                                    <div class="container">
                                                        <div class="row">
                                                            <form method="post" enctype="multipart/form-data">

                                                                <input type="text" hidden="" value="ethereum" name="method">
                                                                <input type="text" hidden value="<?= genRefId() ?>" name="reference_id">
                                                                <input type="text" hidden value="<?= $userCl->getDepoCharge() ?>" name="charges">

                                                                <div class="form-group">
                                                                    <label for="messsage">Amount</label>
                                                                    <input type="number" class="form-control" name="amount_deposit" step="0.000001" required="">
                                                                </div>

                                                                <div class="currency__btns">
                                                                    <button class="button" name="send_proof" type="submit" role="button">Send Request</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end profile-setting-panel-wrap-->
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </div>


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
<!-- Scripts -->

<script>
    (function() {
        var currency = $(".currency"),
            input = currency.find(".currency__input"),
            value = currency.find(".currency__value"),
            price = currency.find(".currency__result"),
            button = currency.find(".currency__variants .currency__button");
        input.on("keyup", function() {
            var inputValue = input.val();
            button.removeClass("active");
            value.text(inputValue);
            price.text(inputValue);
        });
        button.on("click", function() {
            var _this = $(this),
                _thisText = _this.text(),
                inputValue = input.val(); // console.log(_thisText);

            button.removeClass("active");

            _this.addClass("active");

            value.text(_thisText.slice(0, -1));
            input.val(_thisText);
            price.text(_thisText.slice(0, -4));
        });
    })();
    (function() {
        var bidding = $(".js-bidding"),
            step = bidding.find(".js-bidding-step"),
            item = bidding.find(".js-bidding-item"),
            button = bidding.find(".js-bidding-button"),
            back = bidding.find(".js-bidding-back");
        var counter = 0;
        button.on("click", function() {
            var currentNext = ++counter;
            step.eq(currentNext).addClass("next");
            step.eq(currentNext - 1).addClass("active");
            item.hide();
            item.eq(currentNext).show();
        });
        back.on("click", function() {
            var currentPrev = --counter;
            step.eq(currentPrev + 1).removeClass("next");
            step.eq(currentPrev).removeClass("active");
            item.hide();
            item.eq(currentPrev).show();
        });
    })(); // slider
    $(document).ready(function() {
        $('.button__preview').click(function() {
            var amount_deposit = Number($('.currency__input').val());


            $.ajax({
                url: 'getdata.php',
                type: 'post',
                data: {
                    amount_deposit: amount_deposit
                },
                success: function(data) {
                    $('.amt_deposit').text(data);
                    $('.amount').val(data);
                }
            })
        })
    })

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

    var button__preview = document.querySelector('.button__preview');
    button__preview.addEventListener('click', function(e) {
        e.preventDefault();
    })

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
</script>

<?php require_once 'portal_footer.php' ?>