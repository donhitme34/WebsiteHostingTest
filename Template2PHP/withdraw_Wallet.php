<!DOCTYPE html>
<html lang="en">
<?php
session_start();
//if not logged in, will redirect to login.phps
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
            <h1>Withdrawal From E-Wallet</h1>
            <form action="withdrawProcessor.php" method="post">
                <div class="form-group">
                    <div class="col-xs-3">
                    <label for="withdraw_amt">Enter Amount to Withdraw:</label>
                    <input class="form-control" type="number" id="withdraw_amt"
                           name="withdraw_amt" step="0.01" min="0.10" max="1000.00" required placeholder="Enter Amount to Withdraw (SGD):">
                </div>
                </div>
                    <br>
                <div class="form-group">
                    <button class="btn btn-dark"  type="submit">Submit</button>
                    <a href="ewallet_page.php" class="btn btn-danger">Return to E-Wallet</a>
                </div>
            </form>
            
            
        </main>
      <?php
        include "footer.php";
        ?>

    </body>
    </html>
