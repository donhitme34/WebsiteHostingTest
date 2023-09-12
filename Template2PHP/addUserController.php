<?php
    include_once "./dbconnect.php";
    
    //Helper function that checks input for malicious or unwanted content.
        function sanitize_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
    if(isset($_POST['upload']))
    {
        $success = true;
        $Username = $errorMsg = "";
        $firstname =$errorMsg = "";
        $lastname = $errorMsg = ""; 
        $emailaddress = $errorMsg = ""; 
        $password = $errorMsg = ""; 
        $homeAddress = $errorMsg = ""; 
        
        if(empty($_POST["Username"])){
            $errorMsg .= "Username is required.<br>";
            $success = false;
        }
        else{
            $Username = sanitize_input($_POST["Username"]);
        }
        //check for email field
        if (empty($_POST["emailaddress"]))
        {
            $errorMsg .= "Email is required.<br>";
            $success = false;
        }
        else
        {
            $emailaddress = sanitize_input($_POST["emailaddress"]);
            // Additional check to make sure e-mail address is well-formed.
            if (!filter_var($emailaddress, FILTER_VALIDATE_EMAIL))
                {
                $errorMsg .= "Invalid email format.";
                $success = false;
                }
        }

        //check for first name field
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
        //check for password field
        if (empty($_POST["password"]))
        {
            $errorMsg .= "Password is required.<br>";
            $success = false;
        }
        else{
            $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        }
        if(empty($_POST["homeAddress"])){
            $errorMsg .= "Address is required.<br>";
            $success = false;
        }
        else{
            $homeAddress = sanitize_input($_POST["homeAddress"]);
        }
        if($success){
            $stmt = $conn->prepare("INSERT INTO User
            (Username,firstname,lastname,password,emailaddress,homeAddress) 
             VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("ssssss",$Username,$firstname, $lastname,$password,$emailaddress,$homeAddress);
            $result = $stmt->execute();
             if(!$result)
             {
                 echo mysqli_error($conn);
                 header("Location: ./showUser.php?user=error");
             }
             else
             {
                 echo "Records added successfully.";
                 header("Location: ./showUser.php?user=success");
             }
     
        }
        else{
            echo $errorMsg;
        }
    }
        
?>