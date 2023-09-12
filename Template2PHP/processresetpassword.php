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
    $email = $errorMsg = "";
    $success = true;
    if (empty($_POST["pwd"])) {
        $errorMsg .= "The password field cannot be empty.<br>";
        $success = false;
    }

    if (empty($_POST["pwd_confirm"])) {
        $errorMsg .= "Please type your password again.<br>";
        $success = false;
    }

    if ($_POST["pwd"] != $_POST["pwd_confirm"]) {
        $errorMsg .= "Your Password do not match.<br>";
        $success = false;
    }
    $password = $_POST['pwd'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    if ($success) {
        updatePasswordtoDB();
        if ($success) {
            echo "<div class='jumbotron text-center'>";
            echo "<h4>Password reset successful!</h4>";
            echo "<br>";
            echo "<a href='login.php'><btn class='btn btn-success' type='button'>Log in</button></a>";
            echo "</div>";
        }
    } else {
        echo "<div class='jumbotron text-center'>";
        echo "<h4>Oops! </h4>";
        echo "<h6>The following input errors were detected:</h6>";
        echo "<p>" . $errorMsg . "</p>";
        echo "<br>";
        echo "<a href='register.php'><btn class='btn btn-danger' type='button'>Return to sign up</button></a>";
        echo "</div>";
    }
    
    
   

    function updatePasswordtoDB() {
        session_start();
        $userid = $_SESSION["userID"];
        session_write_close();
        $success=true;
        global $password_hash,$email, $password;
        $config = parse_ini_file('../../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);
        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            // Prepare the statement:
            $stmt = $conn->prepare("UPDATE Ecomm.User SET password = ? WHERE idUser = ?");
            // Bind & execute the query statement:
            $stmt->bind_param("si", $password_hash, $userid);
            if (!$stmt->execute()) {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $success = false;
            }
            $stmt->close();
        }
        $conn->close();

        $config = parse_ini_file('../../private/db-config.ini');
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
            idUser=?");
        // Bind & execute the query statement:
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
            // Note that email field is unique, so should only have
            // one row in the result set.
                $row = $result->fetch_assoc();
                $email = $row["emailaddress"];
            } 
            else {
                $errorMsg = "Email not found or password doesn't match...";
                $success = false;
            }
            $stmt->close();
        }
        $conn->close();
        if($success){
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
            $mail->Subject = 'Password update successfully!';
            $mail->Body = 'We email you to inform you that your update of password is successful! Please login with your new password.';    
            if ($mail->send()) {
                //Do nth
            } else {
                echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
            }
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);
            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                // Prepare the statement:
                $stmt = $conn->prepare("DELETE FROM Ecomm.forgetPassword WHERE userID = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $userid);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                }
                $stmt->close();
            }
            $conn->close();
        }
    }
    
    
    
    ?>
    <?php 
        include 'footer.php';
    ?>
        
</body>
</html>