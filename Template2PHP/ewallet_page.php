<!DOCTYPE html>
<html lang="en">
<?php

session_start();

//if not logged in, will redirect to login.php
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: login.php');
    exit();
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
                 retrievewalletCreds();
          ?>
            <br>
            <br>
            <!--Deposit-->
           <a class="btn btn-dark"  href='deposit_Wallet.php' >Deposit </a> 
           <!--Withdraw-->
            <a class="btn btn-danger"  href='withdraw_Wallet.php' >Withdraw </a> 
            
             <div class="container">
            <div class="row">
                <div class="col m-auto">
                    <div class="card mt-5">
                        <table class="table table-bordered">
                            <tr>
                                <td> No. </td>
                                <td> Transaction Date</td>
                                <td> New E-Wallet Amount </td>
                                <td> Amount Deposited/Withdrew </td>
                                <td> Transaction Type </td>
                                
                            </tr>
                            <?php
                            retrieveEwalletTrans();
                            ?>
                        </table>
                    </div>
                </div>
            </div>
             </div>
            
        </main>
           
            <?php
        include "footer.php";
        ?>
    </body>
    
    
   
    <?php
          
                function retrievewalletCreds(){
                    $username = $_SESSION['username'];
                    $ewalletCredit = 0.0;
              
                // Create database connection.
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname'], 3306);
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    // Prepare the statement:
                    $stmt = $conn->prepare("SELECT walletCredit FROM User WHERE username=?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($ewalletCredit);
                    // fetch result
                    $stmt->fetch();
                    
                    echo '<h1>Total Amount: $'.number_format($ewalletCredit,2).'</h1>';
                    $_SESSION['ewalletCredit'] = $ewalletCredit;
                    ?>
                   
                    <?php
                    $stmt->close();
                   
                }
                $conn->close();
                }
            
            
                function retrieveEwalletTrans(){
                // Create database connection.
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname'], 3306);
                global $count;
                $userID = 0;
                $username = $_SESSION['username'];
                $stmt2 = $conn->prepare("SELECT idUser FROM User WHERE Username=?");
                $stmt2->bind_param("s", $username);
                $stmt2->execute();
                $stmt2->bind_result($userID);
                // fetch result
                $stmt2->fetch();
                $stmt2->close();
                
                // Get data from transaction table
            if ($stmt3 = $conn->prepare("SELECT * FROM Ecomm.ewallet_transactions WHERE userID = ? ORDER BY trans_date DESC")) {
                $stmt3->bind_param("i", $userID);
                
            if ($stmt3->execute()) {
                
                $count = 1;
                $result = $stmt3->get_result();
                
            while ($row = $result->fetch_assoc()) {
            // display results ?>
          <tr>
              
              <?php 
              //formatting datetime to SG Local Time
                     $datetime = $row["trans_date"];
                     $datetime_object = new DateTime($datetime, new DateTimeZone('UTC'));
                     $datetime_object->setTimezone(new DateTimeZone('Asia/Singapore'));
                     $formatted_datetime = $datetime_object->format('Y-m-d H:i:s');
                      ?>

            <td><?php echo $count; ?></td>
            <td><?php echo $formatted_datetime ?> </td>
            <td>$<?php echo number_format($row["ewallet_amount"], 2); ?></td>
            <td>$<?php echo number_format($row["amt_withdrew_deposited"], 2); ?> </td>
            <td><?php echo $row["trans_type"]; ?> </td>
 
                       <?php
                       $count=$count+1;
                   }
                   ?>
          </tr>
          
              
                <?php
             
             } else {
            echo "Error: " . $stmt3->error;
            }
            $stmt3->close();
            
            } else {
            echo "Error: " . $conn->error;
            }
            $conn->close();
        
       }

    ?>
</html>

