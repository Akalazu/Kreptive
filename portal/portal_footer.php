<!-- content-wrapper ends -->
<!-- partial:partials/_footer.html -->
<!-- <footer class="footer">
    <div class="container-fluid d-flex justify-content-between">
        <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © bootstrapdash.com 2021</span>
        <span class="float-none float-sm-end mt-1 mt-sm-0 text-end"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
    </div>
</footer> -->
<!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>

<!-- <script src="../assets/js/bundle.js"></script> -->
<script src="../assets/js/scripts.js"></script>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="../assets/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="../assets/assets/vendors/chart.js/Chart.min.js"></script>
<script src="../assets/assets/js/jquery.cookie.js" type="text/javascript"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="../assets/assets/js/off-canvas.js"></script>
<script src="../assets/assets/js/hoverable-collapse.js"></script>
<script src="../assets/assets/js/misc.js"></script>
<!-- endinject -->
<!-- Custom js for this page -->
<script src="../assets/assets/js/dashboard.js"></script>
<script src="../assets/assets/js/todolist.js"></script>
<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
                pageLanguage: "en",
            },
            "google_translate_element"
        );
    }

    function goBack() {
        window.history.back();
    }

    function copyText() {
        var text = document.getElementById("nft_link");
        text.select();
        document.execCommand("copy");
        alert("Text has been copied!");
    }

    function copyProfileLink() {
        var text = document.getElementById("profileLink");
        text.select();
        document.execCommand("copy");
        alert("Link has been copied!");
    }

    function previewNft(event) {
        if (event.target.classList.contains('btttt')) {
            var modal_body = document.querySelector('.modal_content_allUploads');
        } else {
            var modal_body = document.querySelector('.modal_content_allNft');
        }

        modal_body.innerHTML = '';
        console.log(modal_body);
        var nftt_id = event.target.id;
        $.ajax({
            url: "get_product_data.php",
            method: "POST",
            data: {
                art_Id: nftt_id
            },
            success: function(data) {
                // $('.modal_class').html(data);
                modal_body.innerHTML = data;
                // $('.popup').removeAttr("mfp-hide");
            }
        });
    }


    var prevButtons = document.querySelectorAll('.button_class'); //All NFTs
    var previewButtons = document.querySelectorAll('.btttt'); //NFT Uploads
    // console.log(prevButton);
    // console.log(previewButtons);

    previewButtons.forEach((previewButton) => {
        previewButton.addEventListener('click', previewNft);
    })

    prevButtons.forEach((prevButton) => {
        prevButton.addEventListener('click', previewNft);
    })
    // prevButton.addEventListener('click', previewItem(event, modal_body))

    function themeSwitcher(selector) {
        const themeTogglers = document.querySelectorAll(selector);

        // Retrieve and apply theme from local storage
        const applyStoredTheme = () => {
            const storedTheme = localStorage.getItem("website_theme");
            document.body.classList.toggle("dark-mode", storedTheme === "dark-mode");
        };

        // Add click event listeners to all toggler elements
        themeTogglers.forEach((toggler) =>
            toggler.addEventListener("click", (e) => {
                e.preventDefault();
                const isDarkMode = document.body.classList.toggle("dark-mode");
                localStorage.setItem(
                    "website_theme",
                    isDarkMode ? "dark-mode" : "default"
                );
            })
        );

        // Apply theme on page load
        applyStoredTheme();

        // Update theme if changed in another tab
        window.addEventListener("storage", applyStoredTheme);
    }

    // Initialize theme switcher
    themeSwitcher(".theme-toggler");
</script>


<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"> </script>


<script>
    $(".curr_amount").each(function() {
        var num = $(this).attr("data-val");
        console.log(typeof num);
        var decimal = 0;
        if (num.indexOf(".") > 0) { // if number is Decimal
            decimal = num.toString().split(".")[1].length;
        }
        $(this)
            .prop("Counter", 0.0)
            .animate({
                Counter: $(this).attr("data-val")
            }, {
                duration: 2000,
                easing: "swing",
                step: function(now) {
                    $(this).text('$' + parseFloat(now).toLocaleString(undefined, {
                        maximumFractionDigits: decimal
                    }));
                }
            });
    })

    $('.load_more').click(function() {
        var row = Number($('#row').val()); //0
        var allcount = Number($('#all').val()); //all nft
        var rate = Number($('#usdrate').val()); //all nft
        var rowperpage = 16;
        row = row + rowperpage;

        if (row <= allcount) {
            $("#row").val(row);

            $.ajax({
                url: 'getdata.php',
                type: 'post',
                data: {
                    row: row,
                    rate: rate
                },
                beforeSend: function() {
                    $(".text").html('Loading <i class=" ms-2 fa-solid fa-spinner fa-spin"></i>');
                },
                success: function(response) {

                    // Setting little delay while displaying new content
                    setTimeout(function() {
                        // appending posts after last post with class="post"
                        $(".overview__item:last").after(response).show().fadeIn("slow");

                        var rowno = row + rowperpage;
                        // 
                        // checking row value is greater than allcount or not
                        $(".text").text("Load more");
                    }, 2000);

                }
            });
        } else {
            swal({
                title: "Oops!",
                text: "There seems to be no more to load",
                icon: "warning",
            });
        }

    });

    var eyeIconButton = document.querySelector('.field__view');
    var eyeIcon = document.querySelector('#eye__icon');
    var passwordInput = document.querySelector('#password__field');

    if (eyeIconButton) {
        eyeIconButton.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIconButton.innerHTML = '';
                eyeIconButton.innerHTML = '<i class="fa-regular fa-eye-slash" id="eye__icon"></i>';
            } else if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                eyeIconButton.innerHTML = '';
                eyeIconButton.innerHTML = '<i class="fa-regular fa-eye" id="eye__icon"></i>';
            }
        })
    }
</script>
<!-- End custom js for this page -->
</body>

</html>