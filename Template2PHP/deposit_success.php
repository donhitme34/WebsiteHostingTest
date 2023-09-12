<!DOCTYPE html>
<html lang="en">
<?php


session_start();
//if not logged in, will redirect to login.php
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: ewallet_page.php');
    exit();
}
//if stripe session is not set, go back the home page
else if(!isset($_SESSION['stripe_session'])|| !$_SESSION['stripe_session']){
    header('Location: index.php');
    exit();
}

include "nav.php";

$username = $_SESSION['username'];

try {
    require_once '../project/vendor/stripe/stripe-php/init.php'; 
    $config = parse_ini_file('../../private/stripe-config.ini'); 
    
    \Stripe\Stripe::setApiKey($config['secretkey']);
    $session = \Stripe\Checkout\Session::retrieve($_SESSION['stripe_session']);
    
    if($session == null){
        
        echo 'You should not be here...';
        ?> <a href="index.php" class="btn btn-danger">Return to Home</a> <?php
        
    }
    else{
    $payment_status = $session->payment_status;

    }
} 
catch(\Stripe\Exception\UnexpectedValueException $e){
    include "nav.php";
    echo 'You should not be here';
    ?> <br> <a href="index.php" class="btn btn-danger">Return to Home</a> <?php
}

?>

    
    <?php
        include "header.php";
    ?>
      <body class="animsition">
      
    <main class="container">
         <?php
          if ($payment_status == 'paid' ){
          
       wallet_Deposit();
       

       unset($_SESSION['stripe_session']);
      
    }
    else 
    {   
      
        ?> <a href="index.php" class="btn btn-danger">Return to Home</a> <?php
    }
         
        
        ?>
     
    </main>
          
          <?php
        include "footer.php";
        ?>
            
    </body>
   
       
</html>
<?php

function wallet_Deposit() {
   global $username;
    $deposit_amt = $_SESSION["deposit_amt"];
    $ewalletCredit = 0.0;

    // Create database connection.
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname'], 3306);
    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Error! Please Try Again!";
        $success = false;
    } else {
        $stmt = $conn->prepare("UPDATE User SET walletCredit = walletCredit + ? WHERE Username = ?");
        // Bind & execute the query statement:
        $stmt->bind_param("ds", $deposit_amt, $username);
        $stmt->execute();
        $stmt->close();
        
        $userID = 0;
        $stmt2 = $conn->prepare("SELECT idUser FROM User WHERE Username=?");
        $stmt2->bind_param("s", $username);
        $stmt2->execute();
        $stmt2->bind_result($userID);
        // fetch result
        $stmt2->fetch();
        $stmt2->close();
        
        $stmt3 = $conn->prepare("SELECT walletCredit FROM User WHERE Username=?");
        $stmt3->bind_param("s", $username);
        $stmt3->execute();
        $stmt3->bind_result($ewalletCredit);
        // fetch result
        $stmt3->fetch();
        $stmt3->close();
        
        $transType = "Deposit";
        // Prepare the statement:
        $stmt4 = $conn->prepare("INSERT INTO Ecomm.ewallet_transactions (ewallet_amount, trans_date, trans_type, userID, amt_withdrew_deposited) VALUES (?,NOW(),?,?,?)");
        // Bind & execute the query statement:
        $stmt4->bind_param("dsid", $ewalletCredit, $transType, $userID, $deposit_amt);
        $stmt4->execute();
        $stmt4->close();
        
        echo '<br><h1>Your Deposit is Successful!</h1><br>';
        echo 'Updated E-Wallet Amount: $'. number_format($ewalletCredit, 2).'<br>';
        
    }
    ?>
<br>
     <a href="ewallet_page.php" class="btn btn-danger">Return to E-Wallet</a>
<?php
    $conn->close();
}

