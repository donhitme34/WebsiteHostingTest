<!DOCTYPE html>
<html lang="en">
<?php 
    include 'header.php';
?>
<body class="animsition">
    <!-- Header -->
    <?php 
        include 'nav.php';
    ?>
    <?php 
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        global $userid;
        $success =true;
        if (strpos($url, "?") !== false) {
            $query_string = parse_url($url, PHP_URL_QUERY);
            parse_str($query_string, $params);
            $key = $params['key'];
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);
            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT * FROM Ecomm.forgetPassword WHERE
                stringResetPassword=?");
                // Bind & execute the query statement:
                $stmt->bind_param("s", $key);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $userid = $row["userID"];
                }
                else{
                    $success= false;
                }
                $stmt->close();
            }
            $conn->close();
            if($success){
                session_start();
                $_SESSION["userID"] =$userid;
                session_write_close();
                echo "<div class='jumbotron text-center' role='main'>";
                echo "<h1>Enter your new password!</h1>";
                echo "<br>";
                echo "<p style='color:black'>Please enter your new password. Do remember that it bas to be at least 8 characters long!</p>";
                echo "<form action='processresetpassword.php' method='post'>
                    <div class='form-group'>
                    <label for='pwd'>Password:</label>
                    <input class='form-control' type='password' id='pwd'
                           minlength='8' maxlength='24' required name='pwd' placeholder='Enter password'>
                    </div>
                    <div class='form-group'>
                        <label for='pwd_confirm'>Confirm Password:</label>
                        <input class='form-control' type='password' id='pwd_confirm'
                               minlength='8' maxlength='24' required name='pwd_confirm' placeholder='Confirm password'>
                    </div>
                    <div class='form-group'>
                        <button class='btn btn-custom' type='Submit' style='background-color: #0056b3 !important;color: #ffffff !important;'>Submit</button>
                    </div>";
                    echo "</form>";
                echo "</div>";
            }
            else{
                echo "<main class='container' style='background: #ECECEC;'>";
                echo "<br>";
                echo "<h1>Wrong Request!</h1>";
                echo "<h2>You are currently not requesting to reset your password.</h2>";
                echo "<a href='index.php' btn class='btn btn-warning'>Return to Home</a>";
                echo "<br>";
                echo "<br>";
                echo "</main>";
                echo "<br>";
            }
        }
        else{
            echo "<main class='container' style='background: #ECECEC;'>";
            echo "<br>";
            echo "<h1>Wrong Request!</h1>";
            echo "<h2>You are currently not requesting to reset your password.</h2>";
            echo "<a href='index.php' class='btn btn-warning'>Return to Home</a>";
            echo "<br>";
            echo "<br>";
            echo "</main>";
            echo "<br>";
        }
    ?>
    <?php 
        include 'footer.php';
    ?>
    <script>
        var onloadCallback = function() {
            alert("grecaptcha is ready!");
        };
    </script>
</body>
</html>