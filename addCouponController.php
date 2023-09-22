<?php
    include_once "./dbconnect.php";
    
    if(isset($_POST['upload']))
    {
        $success = true;
        $code = $errorMsg = "";
        $discount = $errorMsg = "";
        $status = $_POST['status'];
        
        //check inputs
        if(empty($_POST["coupon_code"])){
            $errorMsg .= "Coupon Code is required.<br>";
            $success = false;
        }
        else{
            $code = sanitize_input($_POST["coupon_code"]);
        }
        
        if (empty($_POST["discount"]))
        {
            $errorMsg .= "Discount amount is required.<br>";
            $success = false;
        }
        else
        {
            $discount = sanitize_input($_POST["discount"]);
        }

      
      if($success){
            $stmt = $conn->prepare("INSERT INTO coupon
        (coupon_code, discount, status) 
         VALUES (?,?,?)");
        $stmt->bind_param("sis",$code, $discount, $status);
        $result = $stmt->execute();
         if(!$result)
         {
             echo mysqli_error($conn);
             header("Location: ./viewAllCoupons.php?coupon=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ./viewAllCoupons.php?coupon=success");
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

