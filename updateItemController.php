 <?php
    include_once "./dbconnect.php";
    $success = true;
    $idproduct=$_POST['idproduct'];
    $itemname=  $errorMsg = "";
    $description= $errorMsg = "";
    $price= $errorMsg = "";
    $sellerID= $_POST['sellerID'];
    $category= $_POST['category'];

    if( isset($_FILES['newImage']) ){
        
        $location="./uploads/";
        $img = $_FILES['newImage']['name'];
        $tmp = $_FILES['newImage']['tmp_name'];
        $dir = './images/';
        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif','webp');
        $image =rand(1000,1000000).".".$ext;
        $final_image=$location. $image;
        if (in_array($ext, $valid_extensions)) {
            $path = UPLOAD_PATH . $image;
            move_uploaded_file($tmp, $dir.$image);
        }
    }else{
        $final_image=$_POST['existingImage'];
    }
        //check itemname
        if(empty($_POST["itemname"])){
            $errorMsg .= "Item name is required.<br>";
            $success = false;
        }
        else{
            $itemname = sanitize_input($_POST["itemname"]);
        }
        //check for description field
        if (empty($_POST["description"]))
        {
            $errorMsg .= "Description is required.<br>";
            $success = false;
        }
        else
        {
            $description = sanitize_input($_POST["description"]);
        }

        //check for price field
        if (empty($_POST["price"]))
        {
            $errorMsg .= "Price is required.<br>";
            $success = false;
        }
        else
        {
            $price = sanitize_input($_POST["price"]);
        }
        
        if($success){
                $stmt = $conn->prepare("UPDATE product SET 
                itemname=?, 
                description=?, 
                price=?,
                product_category_id=?,
                sellerID =?,
                image=? 
                WHERE idproduct=?");
            $stmt->bind_param("ssdiiis",$itemname,$description,$price,$category,$sellerID,$final_image,$idproduct);
            $updateItem = $stmt->execute();
            if($updateItem)
            {
                echo "true";
            }
            // else
            // {
            //     echo mysqli_error($conn);
            // }
        }
        
    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
?>