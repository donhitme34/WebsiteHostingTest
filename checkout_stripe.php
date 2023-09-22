<!DOCTYPE html>
<?php

session_start();
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: login.php');
    exit();
}

if(!isset($_SESSION['totalPrice']) || ! $_SESSION['totalPrice']){
    header('Location: cart.php');
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
               $total_price = $_SESSION['totalPrice'];
               $final_price = filter_var($total_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            $stripe = new \Stripe\StripeClient($config['secretkey']); //Stripe Secret Key
            $lineItems = [
                [
                    'price_data' => [
                        'currency' => 'sgd',
                        'product_data' => [
                            'name' => 'Payment for Cart',
                        ],
                        'unit_amount' => $final_price * 100, //convert to cents
                    ],
                    'quantity' => 1,
                ]
            ];

            // Create Stripe checkout session
            $checkoutSession = $stripe->checkout->sessions->create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => 'http://35.213.170.131/project/checkout_stripe_success.php',
                'cancel_url' => 'http://35.213.170.131/project/cart.php?'
            ]);
            
            $sessionid = $checkoutSession->id;
            $_SESSION['stripe_session'] = $sessionid;

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