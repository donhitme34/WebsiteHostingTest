<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get user input
    $name = $_POST["name"];
    $phone_number = $_POST["phone-number"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    
    $name = sanitize_input($name);
    $phone_number = sanitize_input($phone_number);
    $email = sanitize_input($email);
    $message = sanitize_input($message);
    // Prepare the email
    $to = "sitictemail@gmail.com";
    $subject = "User Feedback";
    $body = "Name: " . $name . "<br>";
    $body .= "Phone Number: " . $phone_number . "<br>";
    $body .= "Email: " . $email . "<br><br>";
    $body .= "Message: <br>" . nl2br($message);

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sitictemail@gmail.com';
        $mail->Password = 'bqxsptogstkgmlef';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('sitictemail@gmail.com', 'Mailer');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        header("Location: index.php?feedback=1");
    } catch (Exception $e) {
        header("Location: index.php?feedback=0");
    }
}
?>
