<?php
    include_once "./dbconnect.php";
    $success = true;
    $coupon_id=$_POST['coupon_id'];
    $coupon_code= $errorMsg = "";
    $discount= $errorMsg = "";
    $status= $_POST['status'];
    
     //check inputs
        if(empty($_POST["coupon_code"])){
            $errorMsg .= "Coupon Code is required.<br>";
            $success = false;
        }
        else{
            $coupon_code = sanitize_input($_POST["coupon_code"]);
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
        $stmt = $conn->prepare("UPDATE coupon SET 
        coupon_code=?,
        discount=?, 
        status=?
        WHERE coupon_id=?");

    $stmt->bind_param("sisi",$coupon_code, $discount, $status, $coupon_id);
    $updateCoupon=$stmt->execute();
    if($updateCoupon)
    {
        echo "true";
    }
     else
     {
         echo mysqli_error($conn);
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