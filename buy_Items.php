<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: login.php');
    exit();
}

?>
<html lang="en">
    <?php 
    include "header.php"
    ?>
    <body>
        <?php
        include "nav.php";
     ?> 
        <main class="container">
            <h1>Purchased Items</h1>
             <div class="container">
            <div class="row">
                <div class="col m-auto">
                    <div class="card mt-5">
                        <table class="table table-bordered">
                            <tr>
                                <td> No. </td>
                                <td> Item Name </td>
                                <td> Payment Type </td>
                                <td> Quantity </td>
                                <td> Total Price </td>
                                <td> Seller ID </td>
                                <td> Item Received</td>
                            </tr>
                            <?php
                            retrieveItems();
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
        function retrieveItems(){
            $username = $_SESSION['username']; 
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname'], 3306);
            global $count;
            $userID = $_SESSION['UID'];
            
            if ($stmt = $conn->prepare("SELECT * FROM Ecomm.transaction JOIN product ON itemID = idproduct where userID = ? and itemID = idproduct ORDER BY dateCreated DESC")) {
                $stmt->bind_param("i", $userID);
                
            if ($stmt->execute()) {
                
                $count = 1;
                $result = $stmt->get_result();
                
            while ($row = $result->fetch_assoc()) {
            // display results ?>
          <tr>
            
              
            <td><?php echo $count; ?></td>
            
            <td><?php echo $row["itemname"]; ?></td>
            <td><?php echo $row["paymentType"]; ?> </td>
            <td><?php echo $row["quantity"]; ?> </td>
            <td>$<?php echo number_format($row["total_amt"], 2); ?> </td>
            <td><?php echo $row["sellerID"]; ?> </td>
            
           
            
            <td>
                <form method="post">
                    <input type="hidden" name="data" value="updateSeller">
                   <?php 
                   if ($row["itemReceived"] == 0) {
                       global $transactionID;
                        $transactionID = $row["idtransaction"];
                        
            
                      ?>
                    <button type="submit" name=received class="btn btn-primary" style="height:30px" >Receive Item</button>
                    <?php
                   
                   }
                   else {
                       ?>
                      Item Received
                       
                       <?php
                       $count=$count+1;
                   }
                   ?>
                    
                </form></td>
          </tr>
          
              
                <?php
             }
             } else {
            echo "Error! Please refresh and try again!";
            }
            $stmt->close();
            
            if (isset($_POST['received'])){
            $stmt2 = $conn->prepare("UPDATE Ecomm.transaction SET itemreceived = 1 WHERE idtransaction = ?");
            $stmt2 ->bind_param("i", $transactionID);
            $stmt2 ->execute();
            $stmt2-> close();
            // Reload the page to show the updated itemReceived status
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
            } else {
            
            
            }
            
}
     
        }
        
    ?>
          
        
</html>