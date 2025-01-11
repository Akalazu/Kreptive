<footer class="footer-section on-dark" style="background: #1c2b46">
    <div class="container">
        <div class="section-space-sm">
            <div class="row">
                <div class="col-lg-3 col-md-9 me-auto">
                    <div class="footer-item mb-5 mb-lg-0">
                        <a href="./" class="footer-logo-link logo-link">

                            <img class="logo-light logo-img" src="images/logo.png" style="  height: 70px" />
                        </a>
                        <p class="my-4 footer-para">
                            Niftlify - The Home of World class Artworks
                        </p>
                        <div class="header__link" id="google_translate_element"></div>
                        <!-- <ul class="styled-icon">
                            <li>
                                <a href="#"><em class="icon ni ni-twitter"></em></a>
                            </li>
                            <li>
                                <a href="#"><em class="icon ni ni-facebook-f"></em></a>
                            </li>
                            <li>
                                <a href="#"><em class="icon ni ni-instagram"></em></a>
                            </li>
                            <li>
                                <a href="#"><em class="icon ni ni-pinterest"></em></a>
                            </li>
                        </ul> -->
                    </div>
                    <!-- end footer-item -->
                </div>
                <!-- end col-lg-3 -->
                <div class="col-lg-8">
                    <div class="row g-gs">

                        <!-- end col -->
                        <div class="col-md-3 ">
                            <div class="footer-item">
                                <h5 class="mb-4">Quick Links</h5>
                                <ul class="list-item list-item-s1">
                                    <li><a href="explore">Explore</a></li>
                                    <li><a href="#faq">FAQ</a></li>
                                </ul>
                            </div>
                            <!-- end footer-item -->
                        </div>
                        <!-- end col-lg-3 -->
                        <div class="col-md-4 ">
                            <div class="footer-item">
                                <h5 class="mb-4">More Info</h5>
                                <ul class="list-item list-item-s1">

                                    <li><a href="about-us">About Us</a></li>
                                    <li><a href="privacy_policy">Privacy Policy</a></li>
                                    <li><a href="term_of_service">Terms of Service</a></li>
                                    <li><a href="sign-in">Join our Community</a></li>

                                </ul>
                            </div>
                            <!-- end footer-item -->
                        </div>
                        <div class="col-md-5">
                            <div class="footer-item">
                                <h5 class="mb-4">Our News Letter</h5>
                                <ul class="list-item list-item-s1">
                                    <div class="footer__text" style="color: rgba(255, 255, 255, 0.8);">
                                        Subscribe our newsletter to get more updates on our services and other important information
                                    </div>
                                    <form method="POST" class="subscription mt-3">
                                        <input class="subscription__input" type="email" name="email" placeholder="Enter your email" required= />
                                        <button class="subscription__btn" name="newsletter_sub">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" " class=" w-6 h-6">
                                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" clip-rule="evenodd" />
                                            </svg>

                                        </button>
                                    </form>

                                </ul>
                            </div>
                            <!-- end footer-item -->
                        </div>
                        <!-- end col-lg-3 -->
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end section-space-sm -->
        <hr class="bg-white-slim my-0" />
        <div class="copyright-wrap d-flex flex-wrap py-3 align-items-center justify-content-between">
            <p class="footer-copy-text py-2">
                Copyright &copy; 2020 - <?= date('Y') ?> Niftlify.
            </p>

        </div>
        <!-- end d-flex -->
    </div>
    <!-- .container -->
</footer>
<!-- end footer-section -->
</div>
<!-- Scripts -->
<script src="https://unpkg.com/typewriter-effect@latest/dist/core.js"></script>
<script>
    AOS.init();
</script>
<script>
    function copyText() {
        var text = document.getElementById("nft_link");
        text.select();
        document.execCommand("copy");
        alert("Text has been copied!");
    }

    var app = document.getElementById('app');

    var typewriter = new Typewriter(app, {
        loop: true
    });

    typewriter.typeString('Your story, your art, your NFT.')
        .pauseFor(2500)
        .deleteAll()
        .typeString('NFTs made simple, secure, and accessible.')
        .pauseFor(2500)
        .deleteAll()
        .typeString('<strong>Discover. Trade. Collect.</strong>')
        .pauseFor(2500)
        .start();

    // function themeSwitcher(selector) {
    //     let themeToggler = document.querySelectorAll(selector);
    //     if (themeToggler.length > 0) {
    //         themeToggler.forEach((item) => {
    //             item.addEventListener("click", function(e) {
    //                 e.preventDefault();
    //                 document.body.classList.toggle("dark-mode");
    //                 if (document.body.classList.contains("dark-mode")) {
    //                     localStorage.setItem("website_theme", "dark-mode");
    //                 } else {
    //                     localStorage.setItem("website_theme", "default");
    //                 }
    //             });
    //         });
    //     }

    //     function retrieveTheme() {
    //         var theme = localStorage.getItem("website_theme");
    //         if (theme != null) {
    //             document.body.classList.remove("default", "dark-mode");
    //             document.body.classList.add(theme);
    //         }
    //     }

    //     retrieveTheme();

    //     if (window) {
    //         window.addEventListener(
    //             "storage",
    //             function() {
    //                 retrieveTheme();
    //             },
    //             false
    //         );
    //     }
    // }

    // themeSwitcher(".theme-toggler");


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

<script src="assets/js/utilities.js"></script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"> </script>
<script src="assets/js/bundle.js"></script>
<script src="assets/js/scripts.js"></script>
<script src="assets/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="assets/assets/js/dashboard.js"></script>



</body>

</html>