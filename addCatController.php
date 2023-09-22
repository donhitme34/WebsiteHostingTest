<?php
    include_once "./dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       $success = true;
        $catname = $errorMsg = "";
        //check if empty
        if(empty($_POST["c_name"])){
            $errorMsg .= "Category name is required.<br>";
            $success = false;
        }
        else{
            $catname = sanitize_input($_POST["c_name"]);
        }
        
        if($success){
            $stmt = $conn->prepare("INSERT INTO category
         (Name) 
         VALUES (?)");
         $stmt->bind_param("s",$catname);
         $insert = $stmt->execute();
         if(!$insert)
         {
             echo mysqli_error($conn);
             header("Location: ./viewCategories.php?category=error");
         }
         else
         {
             echo "Records added successfully.";
             header("Location: ./viewCategories.php?category=success");
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