<?php
session_start();

if (isset($_POST['submit'])) {
    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        $stmt = $conn->prepare("DELETE FROM Ecomm.User WHERE idUser= ?");
        $stmt->bind_param("i", $_SESSION['UID']);

        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();

    header('Location: logout.php');
}
?>
