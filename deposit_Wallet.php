<!DOCTYPE html>
<?php

session_start();

//if not logged in, will redirect to login.php
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: login.php');
    exit();
}
?>
<html lang="en">
    
    <?php
        include "header.php";
        $username = $_SESSION['username'];
    ?>
      <body class="animsition">
        <?php
        include "nav.php";
     ?>
          
        <main class="container">
            <h1>Top-Up E-Wallet</h1> <br>
            <form action="depositProcessor.php" method="post">
                <div class="form-group">
                    <div class="col-xs-3">
                    <label for="deposit_amt">Enter Amount to Deposit (SGD):</label>
                    <input class="form-control" type="number" id="deposit_amt"
                           name="deposit_amt" step="0.01" min="0.10" max="1000.00" required placeholder="Enter Amount to Deposit (SGD):">
                </div>
                </div>
                    <br>
                <div class="form-group">
                    <button class="btn btn-dark" type="submit">Submit</button>
                    <a href="ewallet_page.php" class="btn btn-danger">Return to E-Wallet</a>
                </div>
            </form>
            
        </main>
      
    <?php
        include "footer.php";
        ?>
    </body>
   
    </html>
