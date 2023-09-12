<!DOCTYPE html>
<html lang="en">
<?php

session_start();
//if not logged in, will redirect to login.php
    if (!isset($_SESSION['username']) || !$_SESSION['username']) {
        header('Location: login.php');
        exit();
        }
                 global $withdraw_amt, $ewalletCredit;
                 $username = $_SESSION['username'];
                 $ewalletCredit = $_SESSION['ewalletCredit'];
                 
                 $withdraw_amt = filter_var($_POST["withdraw_amt"], FILTER_SANITIZE_NUMBER_FLOAT);
                 
                 if($withdraw_amt > $ewalletCredit){
                     ?>
                         <script>alert("Cannot Withdraw! Check Your Balance!") </script>
                         <?php
                     header('Location: withdraw_Wallet.php');
                    
                     exit();
                     
                 }
                 else {
     
                // Create database connection.
                $config = parse_ini_file('../../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname'], 3306);
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    
                    if($ewalletCredit >= $withdraw_amt){
                    // Prepare the statement:
                    // Update Wallet Credits
                    $stmt2 = $conn->prepare("UPDATE User SET walletCredit = walletCredit - ? WHERE Username = ?");
                    // Bind & execute the query statement:
                    $stmt2->bind_param("ds", $withdraw_amt, $username);
                    if (!$stmt2->execute()) {
                        $errorMsg = "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error;
                        $success = false;
                    }
                    $stmt2->close();
                    
                    }
                    else {
                        ?>
                <br><h2><strong>You cannot withdraw! You do not have enough in your E-Wallet</strong><h2><br>
                        
                        <?php
                        
                    }
                    
                    $userID = 0;
                    $stmt3 = $conn->prepare("SELECT idUser FROM User WHERE Username=?");
                    $stmt3->bind_param("s", $username);
                    $stmt3->execute();
                    $stmt3->bind_result($userID);
                    // fetch result
                    $stmt3->fetch();
                    $stmt3->close();
                    
                    $transType = "Withdrawal";
                    
                    // Prepare the statement:
                    $stmt4 = $conn->prepare("INSERT INTO Ecomm.ewallet_transactions (ewallet_amount, trans_date, trans_type, userID, amt_withdrew_deposited) VALUES (?,NOW(),?,?,?)");
                    // Bind & execute the query statement:
                    $stmt4->bind_param("dsid", $ewalletCredit, $transType, $userID, $withdraw_amt);
                    $stmt4->execute();
                    
                    
                    $stmt4->close();
                    
                    }
                $conn->close();
                 }
                
?>
     <?php
        include "header.php";
    ?>
      <body class="animsition">
        <?php
        include "nav.php";
     ?>
 
        <main class="container">
             <?php
             echo "<br><h2>Your Withdrawal is successful!</h2><br>";
             echo'Your Remaining E-Wallet Amount: $'. number_format($ewalletCredit - $withdraw_amt, 2).'<br>';
          
                ?>
            <br>
                <a href="ewallet_page.php" class="btn btn-danger">Return to E-Wallet</a>
            
       </main>
         <?php
        include "footer.php";
        ?>
       
    </body>
   
    </html>
