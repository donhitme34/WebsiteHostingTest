<?php

session_start();
if (!isset($_SESSION['user']) || $_SESSION["user"] != true) {
    header("Location: login.php");
    exit;
}

$success = true;
$errorMsg = '';

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
            $errorMsg = "Sorry, file already exists.";
            $success = false;
        }
    } else {
        $errorMsg = "File is not an image.";
        $success = false;
    }
}

if (isset($_POST['upload'])) {
    $idproduct = $_POST['idproduct'];
    $itemname = $_POST['itemname'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $final_image = $_POST['existingImage'];
    if (isset($_FILES['newImage'])) {

        $location = "./uploads/";
        $img = $_FILES['newImage']['name'];
        $tmp = $_FILES['newImage']['tmp_name'];
        $dir = '../uploads/';
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp');
        $image = rand(1000, 1000000) . "." . $ext;
        $final_image = $location . $image;
        if (in_array($ext, $valid_extensions)) {
            $path = UPLOAD_PATH . $image;
            move_uploaded_file($tmp, $dir . $image);
        }
    } else {
        $final_image = $_POST['existingImage'];
    }


    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);
    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        // Prepare the statement:
        $stmt = $conn->prepare("UPDATE Ecomm.product SET 
        image=?,
        itemname=?, 
        description=?, 
        product_category_id=?,
        price=?
        WHERE idproduct=?");
        // Bind & execute the query statement:
        $stmt->bind_param("sssidi", $final_image, $itemname, $description, $category, $price, $idproduct);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();
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

