<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION["user"] != true) {
    header("Location: login.php");
    exit;
}

$success = true;
$errorMsg = '';
$image = '';
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (empty($_POST["itemname"])) {
    $errorMsg .= "Please enter your item name.<br>";
    $success = false;
    header("Location: itemRegister.php?error_message=" . urlencode($errorMsg));
}
else{
    $lname = sanitize_input($_POST["itemname"]);
}
if (empty($_POST["description"])) {
    $errorMsg .= "Please enter your description.<br>";
    $success = false;
    header("Location: itemRegister.php?error_message=" . urlencode($errorMsg));
}
else{
    $lname = sanitize_input($_POST["description"]);
}
if (empty($_POST["price"])) {
    $errorMsg .= "Please enter your item price.<br>";
    $success = false;
    header("Location: itemRegister.php?error_message=" . urlencode($errorMsg));
}
else{
    $lname = sanitize_input($_POST["price"]);
}


if (isset($_FILES['itemImage']) && $_FILES['itemImage']['error'] == 0) {
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["itemImage"]["tmp_name"]);
    if ($check !== false) {
        // Check if file already exists
        if (!file_exists($target_file)) {
            // Allow certain file formats
            if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
                    $image = $target_file;
                } else {
                    $errorMsg = "Sorry, there was an error uploading your file.";
                    $success = false;
                }
            } else {
                $errorMsg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $success = false;
            }
        } else {
            $image = $target_file;
        }
    } else {
        $errorMsg = "File is not an image.";
        $success = false;
    }
}
if (isset($_POST['upload'])) {
    if (empty($image)) {
        $errorMsg = "Please upload an image.";
        $success = false;
    }
    else{
        $itemname = sanitize_input($_POST['itemname']);
        $description = sanitize_input($_POST['description']);
        $product_category_id = 1;
        $price = sanitize_input($_POST['price']);
        $target = "images/".basename($image);
        $sellerID = $_SESSION['UID'];


        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);
        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } else {
            // Prepare the statement:
            $stmt = $conn->prepare("INSERT INTO Ecomm.product (image,itemname,description,product_category_id,price,sellerID) VALUES (?, ?, ?, ?, ?, ?)");
            // Bind & execute the query statement:
            $stmt->bind_param("sssidi", $image, $itemname, $description, $product_category_id, $price, $sellerID);
            if (!$stmt->execute()) {
                $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $success = false;
            }
            $stmt->close();
        }
        $conn->close();
    }
    if ($success) {
        header("Location: index.php?success=1");
        exit;
    } else {
        header("Location: itemRegister.php?error_message=" . urlencode($errorMsg));
        exit;
    }
} else {
    header("Location: itemRegister.php");
    exit;
}
?>

