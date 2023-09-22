<?php

    include_once "./dbconnect.php";
    
    $p_id=$_POST['record'];
    $query="DELETE FROM User where idUser='$p_id'";

    $data=mysqli_query($conn,$query);

    if($data){
        echo"User Deleted";
    }
    else{
        echo"Not able to delete";
    }
    
