<?php
header("HTTP/1.0 404 Not Found");
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
    <div class="container" role="main">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="mt-5">404 - Page Not Found</h1>
                <p class="lead" Style="color:black">Sorry, the page you are looking for does not exist. Please check the URL or return to the homepage.</p>
                <a href="/" class="btn btn-custom" style="background-color: #0056b3 !important;color: #ffffff !important;">Go to Homepage</a>
            </div>
        </div>
    </div>
    <br>
    <?php   
        include 'footer.php';
    ?>
</body>
</html>
