<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["confirmNewPassword"])) {
    $currentPassword = htmlspecialchars($_POST['currentPassword'], ENT_QUOTES, 'UTF-8');
    $newPassword = htmlspecialchars($_POST['newPassword'], ENT_QUOTES, 'UTF-8');
    $confirmNewPassword = htmlspecialchars($_POST['confirmNewPassword'], ENT_QUOTES, 'UTF-8');

    
    // Check if new password and confirmation match
    if ($newPassword !== $confirmNewPassword) {
        $errorMsg = "New passwords do not match.";
        $success = false;
    } else {
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        
        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            // Check if the user entered the correct current password
            $stmt = $conn->prepare("SELECT * FROM Ecomm.User WHERE idUser=?");
            $stmt->bind_param("i", $_SESSION['UID']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $password_hash = $row["password"];
                
                if (!password_verify($currentPassword, $password_hash)) {
                    $errorMsg = "Incorrect current password.";
                    $success = false;
                } else {
                    // Update the user's password
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE Ecomm.User SET password = ? WHERE idUser = ?");
                    $stmt->bind_param("si", $newPasswordHash, $_SESSION['UID']);
                    
                    if (!$stmt->execute()) {
                        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $success = false;
                    } else {
                        $success = true;
                    }
                }
            } else {
                $errorMsg = "User not found.";
                $success = false;
            }
            
            $stmt->close();
        }
        
        $conn->close();
    }
    
    if ($success) {
        header("Location: profile.php?password_updated=1");
    } else {
        header("Location: profile.php?error_message=" . urlencode($errorMsg));
    }
} else {
    header("Location: profile.php");
}
?>
