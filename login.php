<?php
session_start();

if (isset($_SESSION['user']) || $_SESSION["user"] == true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php 
    include 'header.php';
?>
<body class="animsition">
    <!-- Header -->
    <?php 
        include 'nav.php';
    ?>
    <div class="container-bg" style="background: #F9F9F9;">
        <main class="container">
            <br>
            <h1>
                User Login
            </h1>
            <p style='color:black'>
                For new users, please go to the <a href="register.php" style="color:#004aad;text-decoration: underline;">Sign up page</a>.
            </p>
            <br>
            <form action="loginProcessor.php" method="post">
                <div class="form-group">  
                    <label for="username">
                        Username:
                    </label>
                    <input type="text" class="form-control" id="text" name="username" placeholder="Enter Username" required>
                </div>
                <div class="form-group">
                    <label for="pwd">Password:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter Password" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" aria-label="button_password" id="togglePassword">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="g-recaptcha" data-sitekey="6LfJh_AkAAAAAF9RnG6EolcoX-hgak-gA9fm9NdS"></div>
                <br/>
                <div class="form-group">
                    <button class="btn btn-custom" type="submit" style="background-color: #0056b3 !important;color: #ffffff !important;">Submit</button>
                </div>
            </form>
            <br>
            <p style='color:black'>
                Forget your password? <a href="forgetpassword.php" style="color:#004aad;text-decoration: underline;">Click here!</a>
            </p>
            <br>
        </main>
    </div>
    <?php 
        include 'footer.php';
    ?>
    <script>
        var togglePassword = document.getElementById("togglePassword");
        var password = document.getElementById("pwd");

        togglePassword.addEventListener("click", function() {
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        });
    </script>
</body>
</html>
