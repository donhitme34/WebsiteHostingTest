<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION["user"] != true) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['idproduct'])) {
    $itemCode = $_GET['idproduct'];

    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("DELETE FROM Ecomm.product WHERE idproduct = ? AND sellerID = ?");
    $stmt->bind_param("ii", $itemCode, $_SESSION['UID']);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php?delete_success=1");
        exit;
    } else {
        $stmt->close();
        $conn->close();
        header("Location: index.php?delete_error=1");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
