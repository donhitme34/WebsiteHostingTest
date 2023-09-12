<?php

    include_once "./dbconnect.php";
    
    $coupon_id=$_POST['record'];
    $query="DELETE FROM coupon where coupon_id='$coupon_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"User Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
?>