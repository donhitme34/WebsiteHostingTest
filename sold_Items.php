<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header('Location: login.php');
    exit();
}

?>
    <?php 
    include "header.php"
    ?>
    <body>
        <?php
        include "nav.php";
     ?> 
        <main class="container">
            <h1>Sold Items</h1>
             <div class="container">
            <div class="row">
                <div class="col m-auto">
                    <div class="card mt-5">
                        <table class="table table-bordered">
                            <tr>
                                <td> No. </td>
                                <td> Item Name </td>
                                <td> Buyer ID</td>
                                <td> Amount Earned </td>
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
          
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname'], 3306);
            global $count;
            $userID = $_SESSION['UID'];
            if ($stmt = $conn->prepare("SELECT * FROM Ecomm.transaction JOIN product ON itemID = idproduct where sellerID = ? and itemID = idproduct ORDER BY dateCreated DESC")) {
                $stmt->bind_param("i", $userID);
                
            if ($stmt->execute()) {
                
                $count = 1;
                $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            // display results ?>
          <tr>
           
            <td><?php echo $count; ?></td>
            <td><?php echo $row["itemname"]; ?></td>
            <td><?php echo $row["userID"]; ?> </td>
            <td>$<?php echo number_format($row["total_amt"] * 0.8, 2); ?> </td>
            
            <td>
            <?php
             if ($row["itemReceived"] == 0) {
                       echo 'Item not Received';
                       $count=$count+1;
                      ?>
                    
                    <?php
                   }
                   else {
                       echo 'Item Received';
         
                       $count=$count+1;
                   }
           
            ?>
            </td>
   
          </tr>
              
                <?php
             }
             } else {
            echo "Error! Please refresh and try again! ";
            }
            $stmt->close();
            } else {
            echo "Error! Please refresh and try again!";
}
           

        }
        
    ?>
          
       
         
         
   
</html>
