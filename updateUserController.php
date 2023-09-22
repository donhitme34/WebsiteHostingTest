<?php
    include_once "./dbconnect.php";
    $success=true;
    $idUser=$_POST['idUser'];
    $Username= $_POST['Username'];
    $firstname= $errorMsg = "";
    $lastname= $errorMsg = "";
    $email= $errorMsg = "";
    $homeAddress = $errorMsg = "";
    //Helper function that checks input for malicious or unwanted content.
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
     
        //check for email field
        if (empty($_POST["emailaddress"]))
        {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        }
        else
        {
            $email = sanitize_input($_POST["emailaddress"]);
            // Additional check to make sure e-mail address is well-formed.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                $errorMsg .= "Invalid email format.";
                $success = false;
                }
        }
        
        //check for Last name field
        if (empty($_POST["firstname"]))
        {
        }
        else
        {
            $firstname = sanitize_input($_POST["firstname"]);
        }
        //check for Last name field
        if (empty($_POST["lastname"]))
        {
            $errorMsg .= "Last name is required.<br>";
            $success = false;
        }
        else
        {
            $lastname = sanitize_input($_POST["lastname"]);

        }
        if(empty($_POST["homeAddress"])){
            $errorMsg .= "Address is required.<br>";
            $success = false;
        }
        else{
            $homeAddress = sanitize_input($_POST["homeAddress"]);
        }
        
        if($success){
            $stmt = $conn->prepare("UPDATE User SET 
            Username=?,
            firstname=?, 
            lastname=?, 
            emailaddress=?,
            homeAddress=? 
            WHERE idUser=?");

            $stmt->bind_param("sssssi",$Username,$firstname,$lastname,$email,$homeAddress,$idUser);
            $updateUser= $stmt->execute();
            if($updateUser)
            {
                echo "true";
            }
             else
             {
                 echo mysqli_error($conn);
             }
        }
       
    
?>