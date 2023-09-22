<?php

    include_once "./dbconnect.php";
   
    $order_id=$_POST['record'];
    //echo $order_id;
    $sql = "SELECT releasedPayment from transaction where idtransaction='$order_id'"; 
    $result=$conn-> query($sql);
  //  echo $result;

    $row=$result-> fetch_assoc();
    
   // echo $row["pay_status"];
    
    if($row["releasedPayment"]==0){
         $update = mysqli_query($conn,"UPDATE transaction SET releasedPayment=1 where idtransaction='$order_id'");
    }
    else if($row["releasedPayment"]==1){
         $update = mysqli_query($conn,"UPDATE transaction SET releasedPayment=0 where idtransaction='$order_id'");
    }
    
        
 
    // if($update){
    //     echo"success";
    // }
    // else{
    //     echo"error";
    // }
    
?>