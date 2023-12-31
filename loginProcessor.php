
    <?php
    /*
     * Helper function to authenticate the login.
     */
//    use PHPMailer\PHPMailer\PHPMailer;
//    use PHPMailer\PHPMailer\Exception;
//    require 'vendor/autoload.php';
    if (isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= 3 
        && (time() - $_SESSION['last_failed_attempt'] < 600)) {
        $errorMsg = "Too many failed attempts. Please wait for 10 minutes before trying again.";
        include 'header.php';
        ?>
        <body>
        <!-- Header -->
        <?php 
            include 'nav.php';
        echo "<main class='container'>";
        echo "<h1>Oops!</h1>";
        echo "<p style='color:black'>" . $errorMsg . "</p>";
        echo "<a href='login.php' class='btn btn-warning'>Return to Login</a>";
        echo "</main>";
        include 'footer.php';
        exit();  // Stop further processing
    }   
    if (!isset($_SESSION)) {
            session_start();
    }
    if (!isset($_SESSION['failed_attempts'])) {
        $_SESSION['failed_attempts'] = 0;
    }
    if (!isset($_SESSION['last_failed_attempt'])) {
        $_SESSION['last_failed_attempt'] = 0;
    }
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $secret = '6LfJh_AkAAAAAGNUcNNZHSE49xFENe7jJf-1GGIz';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, [
        'secret' => $secret,
        'response' => $response,
        'remoteip' => $remoteip
    ]);

    $result = curl_exec($curl);
    curl_close($curl);

    $response = json_decode($result, true);
    
    if ($response['success']) {
        if (isset($_SESSION['failed_attempts']) && $_SESSION['failed_attempts'] >= 3
            && (time() - $_SESSION['last_failed_attempt'] < 600)) {
            $errorMsg = "Too many failed attempts. Please wait for 10 minutes before trying again.";
            // Display your error message and exit, similar to your previous code
            echo "<main class='container'>";
            echo "<h1>Oops!</h1>";
            echo "<p style='color:black'>" . $errorMsg . "</p>";
            echo "<a href='login.php' class='btn btn-warning'>Return to Login</a>";
            echo "</main>";
            exit();  // Stop further processing
        }
        authenticateUser();
        if ($jumpflag==1){
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <?php 
                include 'header.php';
            ?>
            <body>
                <!-- Header -->
                <?php 
                    include 'nav.php';
                ?>
            <?php
            echo "<div class='jumbotron text-center' role='main'>";
            echo "<h1>Verify your email!</h1>";
            if (empty($_POST["fname"])) {
                echo "<p style='color:black'>Thank you for signing up, " . $lname . "</p>";
            } else {
                echo "<p style='color:black'>Thank you for signing up, " . $fname . " " . $lname . "</p>";
            }
            echo "<br>";
            echo "<p style='color:black'>We have sent an Email to verify at" . $email."Please check your email and enter your 6 pin password</p>";
            echo "<form action='verified.php' method='post'>
                <div class='form-group'>
                <label for='pinnumber'>6 Pin number:</label>
                <input class='form-control' type='text' id='pinnumber'
                       name='pinnumber' required placeholder='Enter the 6 pin to verify your account!'>
                </div>
                <div class='form-group'>
                    <button class='btn btn-custom' type='Submit' style='background-color: #0056b3 !important;color: #ffffff !important;'>Submit</button>
                </div>";
            echo "</div>";
        }
        else if ($success){
            session_start();
            echo $gcaptcha;
            $_SESSION["fname"] = $fname;
            $_SESSION["lname"] = $lname;
            $_SESSION["email"] = $email;
            $_SESSION["username"] = $username;
            $_SESSION["admin"] =$isAdmin;
            $_SESSION["user"] = true;
            $_SESSION['UID']=$userID;
            ?>
             
            <?php
                if($isAdmin){
                    header("Location: admin.php");
                }
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <?php 
                include 'header.php';
            ?>
            <body>
                <!-- Header -->
                <?php 
                    include 'nav.php';
                   
                ?>
            <?php
            echo "<main id='main-content' class='container'>";
            echo "<h1>Login Successful!</h1>";
            echo "<p style='color:black'>Welcome back, " . $fname . " " . $lname. "</p>";
            echo "<a href='index.php' class='btn btn-custom-success' style='background-color: #1e7e34;color: #ffffff;'>Return to Home</a>";
            echo "</main>";
            echo "<br>";
        }
        else{
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <?php
            include 'header.php';
            ?>
            <body>
                <!-- Header -->
            <?php
            include 'nav.php';
            ?>
            <?php
            $_SESSION['failed_attempts'] += 1;
            $_SESSION['last_failed_attempt'] = time();
            echo "<main class='container'>";
            echo "<h1>Oops!</h1>";
            echo "<h2>The following input errors were detected:</h2>";
            echo "<p style='color:black'>" . $errorMsg . "</p>";
            echo "<a href='login.php' class='btn btn-warning'>Return to Login</a>";
            echo "</main>";
            echo "<br>";
        }
    } else {
        $_SESSION['failed_attempts'] += 1;
        $_SESSION['last_failed_attempt'] = time();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <?php 
            include 'header.php';
        ?>
        <body>
            <!-- Header -->
            <?php 
                include 'nav.php';
            ?>
        <?php
        echo "<main class='container'>";
        echo "<h1>Oops!</h1>";
        echo "<h2>You did not pass the google captcha! Please login again</h2>";
        echo "<a href='login.php' class='btn btn-warning'>Return to Login</a>";
        echo "</main>";
        echo "<br>";
    }
    function authenticateUser() {
        global $fname, $lname, $email, $password_hash, $errorMsg,$success, $username, $isverified, $isAdmin;
        global $jumpflag, $userID;
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['pwd'], FILTER_SANITIZE_STRING);
        $success = true;
        // Create database connection.
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);
        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } 
        else {
        // Prepare the statement:
            $stmt = $conn->prepare("SELECT * FROM Ecomm.User WHERE
            Username=?");
        // Bind & execute the query statement:
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
            // Note that email field is unique, so should only have
            // one row in the result set.
                $row = $result->fetch_assoc();
                $fname = $row["firstname"];
                $lname = $row["lastname"];
                $password_hash = $row["password"];
                $isverified = $row["isVerified"];
                $email = $row["emailaddress"];
                $username = $row["Username"];
                $userID = $row["idUser"];
                $isAdmin = $row["isAdmin"];
                if($isAdmin == 1){
                    $isAdmin = true;
                }
                else{
                    $isAdmin = false;
                }
                // Check if the password matches:
                if (!password_verify($_POST["pwd"], $password_hash)) {
                // Don't be too specific with the error message - hackers don't
                // need to know which one they got right or wrong. :)
//                    $_SESSION['failed_attempts'] += 1;
//                    $_SESSION['last_failed_attempt'] = time();
                    $errorMsg = "Username not found or password doesn't match...";
                    $success = false;
                }
                else {
                    // Reset failed attempts on successful login
                    $_SESSION['failed_attempts'] = 0;
                    $_SESSION['last_failed_attempt'] = 0;
                }
            } 
            else {
                $errorMsg = "Username not found or password doesn't match...";
                $success = false;
            }
            
            $stmt->close();
        }
        $conn->close();
    }
    ?>
    <?php 
        include 'footer.php';
    ?>
        
</body>
</html>