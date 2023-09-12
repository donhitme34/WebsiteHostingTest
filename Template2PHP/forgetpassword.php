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
    <div class="container-bg" style="background: #ECECEC;">
        <main class="container">
            <br>
            <h1>
                Forget your password?
            </h1>
            <p style="color:black">
                Please enter your username here. If your username exist in our database, we will send you a link to reset your password!
            </p>
            <br>
            <form action="processForgetPassword.php" method="post">
                <div class="form-group">  
                    <label for="username">
                        Username:
                    </label>
                    <input type="text" class="form-control" id="text" name="username" placeholder="Enter Username" required>
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
        var onloadCallback = function() {
            alert("grecaptcha is ready!");
        };
    </script>
</body>
</html>