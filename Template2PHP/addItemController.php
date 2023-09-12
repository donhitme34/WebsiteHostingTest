<?php
    include_once "./dbconnect.php";
    
    if(isset($_POST['upload']))
    {
        $success = true;
        $itemname = $errorMsg = "";
        $description= $errorMsg = "";
        $price = $errorMsg = "";
        $sellerID = $errorMsg = "";
        $category = $_POST['category'];
       
            
        $name = $_FILES['file']['name'];
        $temp = $_FILES['file']['tmp_name'];
    
        $location="./images/";
        $image=$location.$name;

        $target_dir="./images/";
        $finalImage=$target_dir.$name;

        move_uploaded_file($temp,$finalImage);

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
        //check for seller field
        if (empty($_POST["sellerID"]))
        {
            $errorMsg .= "Seller ID is required.<br>";
            $success = false;
        }
        else
        {
            $sellerID = sanitize_input($_POST["sellerID"]);
        }
        
        if($success){
            $stmt = $conn->prepare("INSERT INTO product
         (itemname,image,price,description,product_category_id,sellerID) 
         VALUES (?,?,?,?,?,?)");
         $stmt->bind_param("ssisii",$itemname,$image,$price,$description,$category,$sellerID);
         $insert = $stmt->execute();
         if(!$insert)
         {
             echo mysqli_error($conn);
         }
         else
         {
             echo "Records added successfully.";
         }
        }
         
     
    }
    
    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
        
?>