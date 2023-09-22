<!DOCTYPE html>
<html lang="en">
<?php 
    include 'header.php';
?>
<body>
    <!-- Header -->
    <?php 
        include 'nav.php';
    ?>
    <?php
    
    $email = $errorMsg = "";
    $success = true;
    if (empty($_POST["pinnumber"])) {
        $errorMsg .= "The 6 pin is required.<br>";
        $success = false;
    } 
    else {
        $pinnumber = sanitize_input($_POST["pinnumber"]);
    }
    CheckMember();
    if ($success){
        echo "<main class='container'>";
        echo "<h1>Verification Successful!</h1>";
        echo "<p style='color:black'>Thanks for signing up with us</p>";
        echo "<a href='login.php' class='btn btn-custom-success' style='background-color: #1e7e34;color: #ffffff;'>Return to login</a>";
        echo "</main>";
        echo "<br>";
    }
    else{
        echo "<main class='container'>";
        echo "<h1>Oops!</h1>";
        echo "<h2>I think you have some errors...</h2>";
        echo "<p style='color:black'>" . $errorMsg . "</p>";
        echo "<a href='register.php' class='btn btn-warning'>Return to Register</a>";
        echo "</main>";
        echo "<br>";
    }
  
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    function CheckMember() {
        global $fname, $lname, $email, $password_hash, $errorMsg,$success, $username;
        session_start();
        $username = $_SESSION["username"];
        session_write_close(); 
        $pinnumber = $_POST["pinnumber"];
        $pinmatch =0;
        $success = true;
        // Create database connection.
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);
        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;

            $success = false;
        } 
        else {
        // Prepare the statement:
            $stmt = $conn->prepare("SELECT * FROM Ecomm.User WHERE
            Username=?");
        // Bind & execute the query statement:
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
            // Note that email field is unique, so should only have
            // one row in the result set.
                $row = $result->fetch_assoc();
                $pinmatch = $row["promptpin"];
                // Check if the password matches:
                if($pinmatch == $pinnumber){
                    // Create database connection.
                    $config = parse_ini_file('../private/db-config.ini');
                    $conn = new mysqli($config['servername'], $config['username'],
                            $config['password'], $config['dbname']);
                    // Check connection
                    if ($conn->connect_error) {
                        $errorMsg = "Connection failed: " . $conn->connect_error;
                        $success = false;
                    } else {
                        // Prepare the statement:
                        $stmt = $conn->prepare("UPDATE Ecomm.User SET isVerified = 1 WHERE Username = ?");
                        // Bind & execute the query statement:
                        $stmt->bind_param("s", $username);
                        if (!$stmt->execute()) {
                            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                            $success = false;
                        }
                        $stmt->close();
                    }
                    $conn->close();
                }
                else{
                    $success = false;
                    $errorMsg = "6 pin does not match!";
                    // Create database connection.
                    $config = parse_ini_file('../private/db-config.ini');
                    $conn = new mysqli($config['servername'], $config['username'],
                            $config['password'], $config['dbname']);
                    // Check connection
                    if ($conn->connect_error) {
                        $errorMsg = "Connection failed: " . $conn->connect_error;
                        $success = false;
                    } else {
                        // Prepare the statement:
                        $stmt = $conn->prepare("DELETE FROM Ecomm.User WHERE Username = ?");
                        // Bind & execute the query statement:
                        $stmt->bind_param("s", $username);
                        if (!$stmt->execute()) {
                            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                            $success = false;
                        }
                        $stmt->close();
                    }
                    $conn->close();
                }
            } 
            else {
                $errorMsg = "Username not found or password doesn't match...";
                $success = false;
            }
            $stmt->close();
        }
        $conn->close();
    }
    ?>
    <?php 
        include 'footer.php';
    ?>
        
</body>
</html>