<?php
session_start();

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if (isset($_POST['submit'])) {
    if (empty($_POST["lname"])) {
        $lname = $_SESSION['lname'];
    }
    else{
        $lname = sanitize_input($_POST["lname"]);
    }
    if (empty($_POST["fname"])) {
        $fname = $_SESSION['fname'];
    }
    else{
        $fname = sanitize_input($_POST["fname"]);
    }
    if (empty($_POST["address"])) {
        $address = $_SESSION['address'];
    }
    else{
        $address = sanitize_input($_POST['address']);
    }
    echo $_SESSION['UID'];
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        $stmt = $conn->prepare("UPDATE Ecomm.User SET firstname = ?, lastname = ?, homeAddress = ? WHERE idUser = ?");
        $stmt->bind_param("sssi", $fname, $lname, $address, $_SESSION['UID']);

        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();

    // Redirect back to the profile page
    header('Location: profile.php');
}
$_SESSION['fname'] = $fname;
$_SESSION['lname'] = $lname;
?>
