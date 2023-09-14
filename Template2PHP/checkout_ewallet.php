<!DOCTYPE html>
<?php

session_start();

if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['totalPrice']) || !$_SESSION['totalPrice']) {
    header('Location: cart.php');
    exit();
}

if ($_SESSION['totalPrice'] > $_SESSION['ewalletCreds']) {
    header('Location: cart.php');
    exit();
}



?>   
<html lang="en">
    
    <?php
        include "header.php";
    ?>
      <body class="animsition">
        <?php
        include "nav.php";
     ?>
        
        <main class="container">
           <?php
           ewalletPayment();
           
           function ewalletPayment(){
               global $config;
               $total_price = $_SESSION['totalPrice'];
               $quantity = $_SESSION['quantity'];
               $idProduct = $_SESSION['idProduct'];
               $final_price = filter_var($total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
               $paymentType = "ewallet";
               $paymentStatus = "paid";
               $itemReceived = 0;
               $releasedPayment = 0;
               $username = $_SESSION['username'];
               $userID = 0;
               
                // Create database connection.
                $config = parse_ini_file('../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname'], 3306);
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
        } else {
                $stmt = $conn->prepare("SELECT idUser FROM Ecomm.User WHERE Username = ?");
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $stmt->bind_result($userID);
                $stmt->fetch();
                $stmt->close();
                // Prepare the statement:
                $stmt2 = $conn->prepare("UPDATE User SET walletCredit = walletCredit - ? WHERE Username = ?");
                // Bind & execute the query statement:
                $stmt2->bind_param("ds", $final_price, $username);
                if (!$stmt2->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt2->errno . ") " . $stmt2->error;
                    $success = false;
                    }
                    $stmt2->close();
                
                $stmt3 = $conn->prepare("INSERT INTO Ecomm.transaction (dateCreated, userID, total_amt, paymentType, paymentStatus, itemReceived, releasedPayment, itemID, quantity ) VALUES (NOW(),?,?,?,?,?,?,?,?)");
                // Bind & execute the query statement:
                $stmt3->bind_param("idssiiii", $userID, $final_price, $paymentType, $paymentStatus, $itemReceived, $releasedPayment, $idProduct, $quantity);
                $stmt3->execute();
                $stmt3->close();
                
                $ewalletCredit = 0;
                $stmt4 = $conn->prepare("SELECT walletCredit FROM Ecomm.User WHERE Username = ?");
                $stmt4->bind_param("s", $username);
                $stmt4->execute();
                $stmt4->bind_result($ewalletCredit);
                $stmt4->fetch();
                $stmt4->close();
                
                $paymentType = "E-Wallet Payment";
                $stmt5 = $conn->prepare("INSERT INTO Ecomm.ewallet_transactions (ewallet_amount, trans_date, trans_type, userID, amt_withdrew_deposited) VALUES(?, NOW(),?,?,?)");
                $stmt5->bind_param("dsid", $ewalletCredit, $paymentType, $userID, $final_price);
                $stmt5->execute();
                $stmt5->close();
                
                echo '<br><h1>Your Payment is Successful! Enjoy Your Item!</h1><br>';
                echo 'Remaining Wallet Credit: $'.number_format($ewalletCredit,2).'<br>';
                
                unset($_SESSION['totalPrice']);
                unset($_SESSION["cart_item"]);
                
}

           }   
           ?>
            <br>
            <a href="index.php" class="btn btn-danger">Return Home</a><br>
            
        </main>
          
          <?php
        include "footer.php";
        ?>
         
    </body>
   
    </html>