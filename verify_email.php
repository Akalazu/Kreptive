<?php
include_once 'header.php';



if (!isset($_SESSION['regMail'])) {
    header('Location: sign-in');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $num3 = $_POST['num3'];
    $num4 = $_POST['num4'];
    $regmail = $_POST['regmail'];

    $numb = $num1 . '' . $num2 . '' . $num3 . '' . $num4;

    $sql = "SELECT * FROM `reg_details` WHERE `email` = :em";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':em', $regmail);
    $stmt->execute();
    $reg_details = $stmt->fetch(PDO::FETCH_OBJ);

    if ($numb == $reg_details->verify_code) {
        $sql = "UPDATE `reg_details` SET `verified` = 1 WHERE `email` = :em";
        $stmtt = $pdo->prepare($sql);
        $stmtt->bindParam(':em', $reg_details->email);
        $stmtt->execute();

        if ($stmt->execute()) {
            echo '
          <script>
        swal({
               title: "Verification Successful",
                  text: "You can now log in using your email and password" ,
                  icon: "success",
              button: "Ok",
            }).then(function() {
             window.location.href = "sign-in";
        });
        </script>
          ';
        } else {
            echo '
          <script>
        swal({
               title: "Error!",
                  text: "Kindly provide the correct digits sent to your email" ,
                  icon: "warning",
              button: "Ok",
            })
        });
        </script>
          ';
        }
    }
}
?>

<style>
    .card h6 {
        font-size: 20px
    }

    .inputs input {
        width: 60px;
        height: 60px
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0
    }


    .card-2 .content {
        margin-top: 50px
    }

    .card-2 .content a {
        color: red
    }

    .form-control:focus {
        box-shadow: none;
        border: 2px solid #1c2b46;
    }
</style>

<section class="login-section section-space-b pt-5 pt-md-5 mt-md-3" style="height: 100vh; display:flex; align-items: center">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 mb-5 mb-lg-0 d-none d-lg-block">
                <img src="images/thumb/remote.png" alt="" class="img-fluid" />
            </div>
            <!-- end col-lg-6 -->
            <div class="col-lg-6 col-md-9">
                <div class="section-head-sm">
                    <h1>Enter your security code</h1>
                    <div class="registration__info">We sent your code to <?= $_SESSION['regMail'] ?></div>
                </div>

                <form action="" method="post">
                    <div class="container p-2">

                        <div id="otp" class="inputs d-flex flex-row justify-conent-center">
                            <input type="email" name="regmail" id="regmail" value="<?= $_SESSION['regMail'] ?>" hidden>
                            <input class="m-2 text-center form-control rounded" type="text" id="first" name="num1" maxlength="1" />
                            <input class="m-2 text-center form-control rounded" type="text" id="second" name="num2" maxlength="1" />
                            <input class="m-2 text-center form-control rounded" type="text" id="third" name="num3" maxlength="1" />
                            <input class="m-2 text-center form-control rounded" type="text" id="fourth" name="num4" maxlength="1" />
                        </div>
                        <button class="btn btn-dark btn-lg btn-block validate mt-5" name="submit_btn">Validate</button>

                    </div>
                </form>
            </div>
            <!-- end col-lg-6 -->
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</section>
<!-- end login-section -->
<script>
    document.addEventListener("DOMContentLoaded", function(event) {

        function OTPInput() {
            const inputs = document.querySelectorAll('#otp > *[id]');
            for (let i = 0; i < inputs.length; i++) {
                inputs[i].addEventListener('keydown', function(event) {
                    if (event.key === "Backspace") {
                        inputs[i].value = '';
                        if (i !== 0) inputs[i - 1].focus();
                    } else {
                        if (i === inputs.length - 1 && inputs[i].value !== '') {
                            return true;
                        } else if (event.keyCode > 47 && event.keyCode < 58) {
                            inputs[i].value = event.key;
                            if (i !== inputs.length - 1) inputs[i + 1].focus();
                            event.preventDefault();
                        } else if (event.keyCode > 64 && event.keyCode < 91) {
                            inputs[i].value = String.fromCharCode(event.keyCode);
                            if (i !== inputs.length - 1) inputs[i + 1].focus();
                            event.preventDefault();
                        }
                    }
                });
            }
        }
        OTPInput();


    });
</script>
<?php

include_once 'footer.php';

?>
<!-- end footer-section -->


</html>