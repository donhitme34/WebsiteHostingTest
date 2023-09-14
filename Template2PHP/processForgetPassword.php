<!DOCTYPE html>
<html lang="en">
<?php 
    include 'header.php';
?>
<body>
    <style>
        .container-bg {
            background: #ECECEC;
        }
    </style>
    <!-- Header -->
    <?php 
        include 'nav.php';
    ?>
    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'vendor/autoload.php';
    function generateRandomString($length) {
        $alphanumeric = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $alphanumeric[rand(0, strlen($alphanumeric) - 1)];
        }
        return $randomString;
    }
    
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
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
    $success = true;
    $response = json_decode($result, true);
    if ($response['success']) {
        if (empty($_POST["username"])) {
            $errorMsg .= "The username field cannot be empty.<br>";
            $success = false;
        }
        else{
            $username = sanitize_input($_POST["username"]);
        }
        if($success){
            global $email, $userI, $counter;
            $counter=0;
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);
            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT * FROM Ecomm.User WHERE
            Username=?");
                // Bind & execute the query statement:
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $email = $row["emailaddress"];
                    $userID = $row["idUser"];
                }
                else{
                    echo "<div class='jumbotron text-center' role='main'>";
                    echo "<h1>Check your email </h1>";
                    echo "<p style='color:black'>If you have registered with us, you will receive an email on resetting your password! Please check your email</p>";
                    echo "<br>";
                    echo "<a href='login.php'><btn class='btn btn-danger' type='button'>Return to login</button></a>";
                    echo "</div>";
                    ?>
                    <?php 
                        include 'footer.php';
                    ?>
                    </body>
                    </html>
                    <?php
                }
                $stmt->close();
            }
            $conn->close();
            $randomString = generateRandomString(200);
            
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);
            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT * FROM Ecomm.forgetPassword WHERE
            userID=?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $userID);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $counter = $counter + 1;
                }
                $stmt->close();
            }
            $conn->close();
            if($counter ==0){
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname']);
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    // Prepare the statement:
                    $stmt = $conn->prepare("INSERT INTO Ecomm.forgetPassword (stringResetPassword, userID) VALUES (?, ?)");
                    // Bind & execute the query statement:
                    $stmt->bind_param("si", $randomString, $userID);
                    if (!$stmt->execute()) {
                        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $success = false;
                    }
                    $stmt->close();
                }
                $conn->close();
            }
            else{
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname']);
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    // Prepare the statement:
                    $stmt = $conn->prepare("UPDATE Ecomm.forgetPassword SET stringResetPassword = ? WHERE userID = ?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("si", $randomString, $userID);
                    if (!$stmt->execute()) {
                        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $success = false;
                    }
                    $stmt->close();
                }
                $conn->close();
            }
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'sitictemail@gmail.com';
            $mail->Password = 'bqxsptogstkgmlef';
            $mail->SMTPSecure = 'tls';
            $mail->Port = '587';
            $mail->setFrom('sitictemail@gmail.com', 'Mailer'); // This is the email your form sends From
            $mail->addAddress($email, 'Joe User'); // Add a recipient address
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Reset your password';
            $mail->Body = 'Please reset your password here:  http://35.213.170.131/project/resetpassword.php?key=' . $randomString;
            if ($mail->send()) {
                //Do nth
            } else {
                echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
            }
            echo "<div class='jumbotron text-center'>";
            echo "<h4>Check your email </h4>";
            echo "<h6>If you have registered with us, you will receive an email on resetting your password! Please check your email</h6>";
            echo "<br>";
            echo "<a href='login.php'><btn class='btn btn-danger' type='button'>Return to login</button></a>";
            echo "</div>";
        }
        else{
            echo "<div class='jumbotron text-center'>";
            echo "<h4>Oops! </h4>";
            echo "<h6>The following input errors were detected:</h6>";
            echo "<p>" . $errorMsg . "</p>";
            echo "<br>";
            echo "<a href='login.php'><btn class='btn btn-danger' type='button'>Return to login</button></a>";
            echo "</div>";
        }
    }
    else {
        echo "<main class='container'>";
        echo "<h1>Oops!</h1>";
        echo "<h6>You did not pass the google captcha! Please login again</h6>";
        echo "<a href='login.php'><btn class='btn btn-warning' type='button'>Return to Login</button></a>";
        echo "</main>";
        echo "<br>";
    }
    
        
    ?>
    <?php 
        include 'footer.php';
    ?>
        
</body>
</html>