<!DOCTYPE html>
<html lang="en">
    <?php
    include 'header.php'
    ?>
    <body class="animsition">

        <!-- Header -->
        <?php
        include 'nav.php';
        ?>
        <main>
            <!-- Title Page -->
            <section class="bg-title-page p-t-40 p-b-50 flex-col-c-m" style="background-image: url(images/bg.jpg);">
                <h1 class="l-text2 t-center" style="color:black">
                    Contact
                </h1>
            </section>

            <!-- content page -->
            <section class="bgwhite p-t-66 p-b-60">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 p-b-30">
                            <div class="p-r-20 p-r-0-lg">
                                <div class="contact-map size21" id="google_map" data-map-x="1.377264" data-map-y="103.848701" data-pin="images/icons/icon-position-map.png" data-scrollwhell="0" data-draggable="1"></div>
                            </div>
                        </div>

                        <div class="col-md-6 p-b-30">
                            <form class="leave-comment" method="POST" action="send_email.php" name="feedback_form" id="feedback_form">
                                <h2 class="m-text26 p-b-36 p-t-15">
                                    Send us your message
                                </h2>

                                <div class="bo4 of-hidden size15 m-b-20">
                                    <input class="sizefull s-text7 p-l-22 p-r-22" type="text" name="name" placeholder="Full Name">
                                </div>

                                <div class="bo4 of-hidden size15 m-b-20">
                                    <input class="sizefull s-text7 p-l-22 p-r-22" aria-label="phoneNumber" type="text" name="phone-number" placeholder="Phone Number" pattern="^[89]\d{7}$" title="Enter an 8-digit number starting with 8 or 9">
                                </div>

                                <div class="bo4 of-hidden size15 m-b-20">
                                    <input class="sizefull s-text7 p-l-22 p-r-22" type="email" name="email" placeholder="Email Address">
                                </div>

                                <textarea class="dis-block s-text7 size20 bo4 p-l-22 p-r-22 p-t-13 m-b-20" name="message" placeholder="Message"></textarea>

                                <div class="w-size25">
                                    <!-- Button -->
                                    <button class="flex-c-m size2 bg1 bo-rad-23 hov1 m-text3 trans-0-4">
                                        Send
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <script>
            document.getElementById('feedback_form').addEventListener('submit', function (event) {
                const phoneNumberInput = document.querySelector('input[name="phone-number"]');
                const phoneNumber = phoneNumberInput.value;
                const phoneNumberPattern = /^[89]\d{7}$/;

                if (!phoneNumberPattern.test(phoneNumber)) {
                    alert('Please enter a valid phone number.');
                    event.preventDefault();
                }
            });
        </script>


        <!-- Footer -->
        <?php
        include 'footer.php';
        ?>



        <!-- Back to top -->
        <div class="btn-back-to-top bg0-hov" id="myBtn">
            <span class="symbol-btn-back-to-top">
                <i class="fa fa-angle-double-up" aria-hidden="true"></i>
            </span>
        </div>

        <!-- Container Selection -->
        <div id="dropDownSelect1"></div>
        <div id="dropDownSelect2"></div>

        <script>
            $(".selection-1").select2({
                minimumResultsForSearch: 20,
                dropdownParent: $('#dropDownSelect1')
            });

            $(".selection-2").select2({
                minimumResultsForSearch: 20,
                dropdownParent: $('#dropDownSelect2')
            });
        </script>
        <!--===============================================================================================-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKFWBqlKAGCeS1rMVoaNlwyayu0e0YRes"></script>
        <script src="js/map-custom.js"></script>
        <!--===============================================================================================-->
        <script src="js/main.js"></script>

    </body>
</html>
