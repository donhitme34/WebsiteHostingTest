<!DOCTYPE html>
<html lang="en">
<?php


session_start();
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: logini.php');
    exit();
}
else if(!isset($_SESSION['stripe_session'])|| !$_SESSION['stripe_session']){
    header('Location: cart.php');
    exit();
}

require_once '../project/vendor/stripe/stripe-php/init.php'; 
$config = parse_ini_file('../../private/stripe-config.ini');

try {
    \Stripe\Stripe::setApiKey($config['secretkey']);
    $session = \Stripe\Checkout\Session::retrieve($_SESSION['stripe_session']);
    
    if($session == null){
        
        echo 'You should not be here...';
        ?> <a href="index.php" type="button" class="btn btn-danger">Return to Home</a> <?php
        
    }
    else{
    $payment_status = $session->payment_status;

    }
} 
catch(\Stripe\Exception\UnexpectedValueException $e){
    echo 'You should not be here';
    ?> <br> <a href="index.php" type="button" class="btn btn-danger">Return to Home</a> <?php
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
          if ($payment_status == 'paid' ){
          
              payment_Checkout();
            
       unset($_SESSION['stripe_session']);
       unset($_SESSION["cart_item"]);
    }
    else 
    {   
      
        ?> <a href="cart.php" type="button" class="btn btn-danger">Return to Cart</a> <?php
    }
        
        ?>
        <br><h1>Your Payment is Successful! Enjoy Your Product!!</h1>
        <br>
     <a href="index.php" type="button" class="btn btn-danger">Return Home</a>
    </main>
          <?php
        include "footer.php";
        ?>
    </body>
       
</html>
<?php

    function payment_Checkout() {

                 $paymentType = "stripe";
                 $paymentStatus = "paid";
                 $itemReceived = 0;
                 $releasedPayment = 0;
                 $total_price = $_SESSION['totalPrice'];
                 $final_price = filter_var($total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                 $username = $_SESSION['username'];
                 $userID = 0;

                // Create database connection.
                $config = parse_ini_file('../../private/db-config.ini');
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
            $quantity = $_SESSION['quantity'];
            $idProduct = $_SESSION['idProduct'];
             
            
            $stmt3 = $conn->prepare("INSERT INTO Ecomm.transaction (dateCreated, userID, total_amt, paymentType, paymentStatus, itemReceived, releasedPayment, itemID, quantity ) VALUES (NOW(),?,?,?,?,?,?,?,?)");
            // Bind & execute the query statement:
            $stmt3->bind_param("idssiiii", $userID, $final_price, $paymentType, $paymentStatus, $itemReceived, $releasedPayment, $idProduct, $quantity);
            $stmt3->execute();
            $stmt3->close();
            

    }
    $conn->close();
 }

    ?>
<br>
    
<?php
    
?>
