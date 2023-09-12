<!DOCTYPE html>
<html lang="en">
    <?php
    include 'header.php';
    ?>
    <body class="animsition">
        <script>
            function validateForm() {
                var password = document.getElementById("pwd").value;
                var confirmPassword = document.getElementById("pwd_confirm").value;

                if (password != confirmPassword) {
                    alert("Passwords do not match!");
                    return false;
                }
                return true;
            }

            var togglePassword = document.getElementById("togglePassword");
            var password = document.getElementById("pwd");

            togglePassword.addEventListener("click", function () {
                if (password.type === "password") {
                    password.type = "text";
                } else {
                    password.type = "password";
                }
            });
        </script>
        <!-- Header -->
        <?php
        include 'nav.php';
        ?>
        <div class="container-bg" style="background: #ECECEC;">
            <main class="container">
                <br>
                <h1>User Registration</h1>
                <p style="color:black;">
                    For existing users, please go to the
                    <a href="login.php" style="color:#004aad;text-decoration: underline;">Sign In page</a>.
                </p>
                <form action="registerProcessor.php" method="post" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input class="form-control" type="text" id="username"
                               required name="username" placeholder="Enter your preferred username">
                    </div>
                    <div class="form-group">
                        <label for="fname">First Name:</label>
                        <input class="form-control" type="text" id="fname"
                               name="fname" placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name:</label>
                        <input class="form-control" type="text" id="lname"
                               required maxlength="45" name="lname" placeholder="Enter last name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input class="form-control" type="email" id="email"
                               required name="email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input class="form-control" type="text" id="address" required name="address" placeholder="Enter housing address">
                    </div>
                    <div class="form-group">
                        <label for="pwd">Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*" id="pwd" name="pwd" placeholder="Enter Password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" aria-label="togglePassword" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text" style="color: black;">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</small>
                    </div>
                    <div class="form-group">
                        <label for="pwd_confirm">Confirm Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="pwd_confirm" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*" minlength="8" name="pwd_confirm" placeholder="Confirm Password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" aria-label="togglePasswordConfirm" id="togglePasswordConfirm">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text" style="color: black;">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</small>
                    </div>
                    <div class="form-check">
                        <label>
                            <input type="checkbox" name="agree" required style="appearance: checkbox; display: inline-block; width: auto; -webkit-appearance: checkbox; -moz-appearance: checkbox;">
                            Agree to <a href="tnc.php" style="color:#004aad;text-decoration: underline;">terms and conditions.</a>
                        </label>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6LfJh_AkAAAAAF9RnG6EolcoX-hgak-gA9fm9NdS"></div>
                    <br/>
                    <div class="form-group">
                        <button class="btn btn-custom" type="submit" style="background-color: #0056b3 !important;color: #ffffff !important;">Submit</button>
                    </div>
                </form>
                <br>
            </main>
        </div>
        <?php
        include 'footer.php';
        ?>
        <script>
            var togglePassword = document.getElementById("togglePassword");
            var password = document.getElementById("pwd");

            togglePassword.addEventListener("click", function () {
                if (password.type === "password") {
                    password.type = "text";
                } else {
                    password.type = "password";
                }
            });

            var togglePasswordConfirm = document.getElementById("togglePasswordConfirm");
            var passwordConfirm = document.getElementById("pwd_confirm");

            togglePasswordConfirm.addEventListener("click", function () {
                if (passwordConfirm.type === "password") {
                    passwordConfirm.type = "text";
                } else {
                    passwordConfirm.type = "password";
                }
            });
        </script>
    </body>
</html>
