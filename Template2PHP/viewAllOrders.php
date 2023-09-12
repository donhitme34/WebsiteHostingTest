<!DOCTYPE html>
<html lang="en">
        <?php include "header.php"?>
        <?php
            if(!isset($_SESSION['admin']) || $_SESSION["admin"] == false){
            header("Location: index.php"); //Redirect to login page if user is not an admin
            exit();
        }

        ?>
<body>
    <?php 
        include "nav.php";
    ?>
    <div id="main-content" class="allContent-section" role="main">
        <h2 class="center"><strong>Transaction Details</strong></h2>
        <br>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>No.</th>
            <th>Buyer</th>
            <th>Date</th>
            <th>Cost</th>
            <th>Quantity</th>
            <th>Payment Type</th>
            <th>Payment Status</th>
            <th>Item received</th>
            <th>Release Payment</th>
            <th>More Details</th>
         </tr>
        </thead>
         <?php
          include_once "./dbconnect.php";
          $sql="SELECT * from transaction,User where userID=idUser";
          $result=$conn-> query($sql);
          $count =1;
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
           <tr>
              <td><?=$row["idtransaction"]?></td>
              <td><?=$row["Username"]?></td>
              <td><?=$row["dateCreated"]?></td>
              <td><?=$row["total_amt"]?></td>
              <td><?=$row["quantity"]?></td>
               <?php 
                if($row["paymentType"]== "stripe"){
                            
            ?>
                <td>Stripe</td>
            <?php
                      }else if($row["paymentType"]=="ewallet"){
                          
            ?>
                <td>E-Wallet</td>
                
               <?php 
                      }
                if($row["paymentStatus"]== "paid"){
                            
            ?>
                <td>Paid</td>
            <?php
                      }else{
            ?>
                <td>Pending</td>
            <?php 
                      }
                if($row["itemReceived"]==0){
                            
            ?>
                <td><button class="btn btn-danger" style="color: black;" onclick="ChangeOrderStatus('<?=$row['idtransaction']?>')">Pending </button></td>
            <?php
                        
                }else if($row["itemReceived"]==1){
            ?>
                <td><button class="btn btn-success" style="color: black;" onclick="ChangeOrderStatus('<?=$row['idtransaction']?>')">Received</button></td>
            
            
            <?php
                }
                if($row["releasedPayment"]==0){
                            
            ?>
                <td><button class="btn btn-danger" style="color: black;" onclick="ChangeReleaseStatus('<?=$row['idtransaction']?>')">Pending </button></td>
            <?php
                        
                }else if($row["releasedPayment"]==1){
            ?>
                <td><button class="btn btn-success" style="color: black;" onclick="ChangeReleaseStatus('<?=$row['idtransaction']?>')">Released</button></td>
            <?php
                }
                ?>
            <td><a class="btn btn-primary openPopup" style="color: black;" data-href="./viewEachOrder.php?idtransaction=<?=$row['idtransaction']?>" href="javascript:void(0);">View</a></td>
            </tr>
        <?php

            }
          }
        ?>

      </table>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="viewModal" role="dialog" name="dialog">
        <div class="modal-dialog modal-lg" role="modal-dialog">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">

              <h4 class="modal-title">Order Details</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="order-view-modal modal-body">

            </div>
          </div><!--/ Modal content-->
        </div><!-- /Modal dialog-->
      </div>
    <script>
         //for view order modal  
        $(document).ready(function(){
          $('.openPopup').on('click',function(){
            var dataURL = $(this).attr('data-href');

            $('.order-view-modal').load(dataURL,function(){
              $('#viewModal').modal({show:true});
            });
          });
        });
     </script>
     
     <?php include "footer.php"?>
</body>
</html>