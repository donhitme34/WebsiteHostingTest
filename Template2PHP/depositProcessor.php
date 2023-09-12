<!DOCTYPE html>
<html lang="en">
<?php

session_start();

//if not logged in, will redirect to login.php
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: index.php');
    exit();
}
//if no deposit amount, will redirect back to ewallet_page.php
if(!isset($_POST['deposit_amt'])|| !$_POST['deposit_amt']){
    header('Location: ewallet_page.php');
    exit();
}

require_once '../project/vendor/stripe/stripe-php/init.php'; 
$config = parse_ini_file('../../private/stripe-config.ini');

?>   
<html lang="en">

      <body class="animsition">
        
        <main class="container">
           <?php
           stripePayment();
           
           function stripePayment(){
               global $config;
               //sanitise input
            $deposit_amt = filter_var($_POST['deposit_amt'], FILTER_SANITIZE_NUMBER_FLOAT);
            $_SESSION["deposit_amt"] = $deposit_amt;
            $stripe = new \Stripe\StripeClient($config['secretkey']); //Stripe Secret Key
            $lineItems = [
                [
                    'price_data' => [
                        'currency' => 'sgd',
                        'product_data' => [
                            'name' => 'Deposit to E-Wallet',
                        ],
                        'unit_amount' => $deposit_amt * 100, //convert to cents
                    ],
                    'quantity' => 1,
                ]
            ];

            // Create Stripe checkout session
            $checkoutSession = $stripe->checkout->sessions->create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => 'http://35.213.170.131/project/deposit_success.php',
                'cancel_url' => 'http://35.213.170.131/project/ewallet_page.php?'
            ]);
            
            $sessionid = $checkoutSession->id;
            $_SESSION['stripe_session'] = $sessionid;
            
            
             // Retrieve provider_session_id. Store in database.
            //$checkoutSession->id;
            // Send user to Stripe
            header('Content-Type: application/json');
            header("HTTP/1.1 303 See Other"); 
            header("Location: " . $checkoutSession->url);
            
            exit;
}
           
           ?>
            
        </main>
         
    </body>
    <br>
    <br>
    </html>