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
//    use PHPMailer\PHPMailer\PHPMailer;
//    use PHPMailer\PHPMailer\Exception;
//    require 'vendor/autoload.php';
    $email = $errorMsg = "";
    $success = true;
    if (empty($_POST["email"])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } 
    else {
        $email = sanitize_input($_POST["email"]);
    // Additional check to make sure e-mail address is well-formed.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }
    if (empty($_POST["lname"])) {
        $errorMsg .= "Please enter your last name.<br>";
        $success = false;
    }
    else{
        $lname = sanitize_input($_POST["lname"]);
    }
    
    if (empty($_POST["fname"])){
        
    }
    else{
        $fname = sanitize_input($_POST["fname"]);
    }

    if (empty($_POST["pwd"])) {
        $errorMsg .= "The password field cannot be empty.<br>";
        $success = false;
    }
    if (empty($_POST["address"])) {
        $errorMsg .= "The address field cannot be empty.<br>";
        $success = false;
    }
    else{
        $address = sanitize_input($_POST["address"]);
    }
    if (empty($_POST["username"])) {
        $errorMsg .= "The username field cannot be empty.<br>";
        $success = false;
    }
    else{
        $username = sanitize_input($_POST["username"]);
    }

    if (empty($_POST["pwd_confirm"])) {
        $errorMsg .= "Please type your password again.<br>";
        $success = false;
    }

    if ($_POST["pwd"] != $_POST["pwd_confirm"]) {
        $errorMsg .= "Your Password do not match.<br>";
        $success = false;
    }
    $password = $_POST["pwd"];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
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
        if ($success) {
            saveMemberToDB();
            if ($success) {
//                $mail = new PHPMailer(true);                              
//                $mail->isSMTP();
//                $mail->Host = 'smtp.gmail.com';
//                $mail->SMTPAuth = true;
//                $mail->Username = 'sitictemail@gmail.com';
//                $mail->Password = 'bqxsptogstkgmlef';
//                $mail->SMTPSecure = 'tls';
//                $mail->Port = '587';
//                $mail->setFrom('sitictemail@gmail.com', 'Mailer'); // This is the email your form sends From
//                $mail->addAddress($email, 'Joe User'); // Add a recipient address
//                $mail->isHTML(true);                                  // Set email format to HTML
//                $mail->Subject = 'Verify your account here';
//                $mail->Body = 'Your 6 pin password:' . $promptpin;
                session_start();
                $_SESSION["username"] =$username;
                session_write_close();     
//                if ($mail->send()) {
//                    //Do nth
//                } else {
//                    echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
//                }
                echo "<div class='jumbotron text-center' role='main'>";
                echo "<h1>Verify your email!</h1>";
                if (empty($_POST["fname"])) {
                    echo "<p style='color:black'>Thank you for signing up, " . $lname . "</p>";
                } else {
                    echo "<p style='color:black'>Thank you for signing up, " . $fname . " " . $lname . "</p>";
                }
                echo "<br>";
                echo "<p style='color:black'>We have sent an Email to verify at " . $email." .Please check your email and enter your 6 pin password</p>";
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
            } else {
                if (strpos($errorMsg, "User.emailaddress_UNIQUE")) {
                    echo "<div class='jumbotron text-center' role='main'>";
                    echo "<h1>Oops! </h1>";
                    echo "<h2>This email have been registered before. Please try again!</h2>";
                    echo "<br>";
                    echo "<a href='register.php' class='btn btn-danger'>Return to sign up</a>";
                    echo "</div>";
                } else if (strpos($errorMsg, "User.Username_UNIQUE")) {
                    echo "<div class='jumbotron text-center' role='main'>";
                    echo "<h1>Oops! </h1>";
                    echo "<h2>This Username have been registered before. Please try again!</h2>";
                    echo "<br>";
                    echo "<a href='register.php' class='btn btn-danger'>Return to sign up</a>";
                    echo "</div>";
                }
            }
        } else {
            echo "<div class='jumbotron text-center' role='main'>";
            echo "<h1>Oops! </h1>";
            echo "<h2>The following input errors were detected:</h2>";
            echo "<p>" . $errorMsg . "</p>";
            echo "<br>";
            echo "<a href='register.php' class='btn btn-danger'>Return to sign up</a>";
            echo "</div>";
        }
    } else{
        echo "<main class='container' style='background: #ECECEC;'>";
        echo "<h1>Oops!</h1>";
        echo "<h2>You did not pass the google captcha! Please register again</h2>";
        echo "<a href='register.php' class='btn btn-warning'>Return to Register</a>";
        echo "</main>";
        echo "<br>";
    }
    
    
    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    /*
     * Helper function to write the member data to the DB
     */

    function saveMemberToDB() {
        global $username, $address,$fname, $lname, $email, $password_hash, $errorMsg, $success;
        global $walletvalue, $authentication2fa,$promptpin, $isAdmin;
        $walletvalue=0.00;
        $promptpin = rand(100000, 999999);
        $isAdmin=0;
        $authentication2fa = 0;
        $isVerified =0;
        // Create database connection.
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);
        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            // Prepare the statement:
            $stmt = $conn->prepare("INSERT INTO Ecomm.User (Username,firstname,lastname,password,walletCredit,2faenabled,promptpin,emailaddress, homeAddress,isAdmin,isVerified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            // Bind & execute the query statement:
            $stmt->bind_param("ssssdisssii",$username, $fname, $lname, $password_hash,$walletvalue,$authentication2fa,$promptpin,$email,$address,$isAdmin, $isVerified);
            if (!$stmt->execute()) {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
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