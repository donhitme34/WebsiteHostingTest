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
    <div role="main" id="main-content" class="allContent-section">
        <h3 class="center"><strong>Category Items</strong></h3>
        <br>
      <table class="table ">
        <thead>
          <tr>
            <th class="text-center">No.</th>
            <th class="text-center">Category Name</th>
            <th class="text-center" colspan="1">Action</th>
          </tr>
        </thead>
        <?php
          include_once "./dbconnect.php";
          $sql="SELECT * from category ORDER BY idcategory";
          $result=$conn-> query($sql);
          $count=1;
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["Name"]?></td>   
          <!-- <td><button class="btn btn-primary" >Edit</button></td> -->
          </tr>
          <?php
                $count=$count+1;
              }
            }
          ?>
      </table>

      <!-- Trigger the modal with a button -->
      <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add Category
      </button>

      <!-- Modal -->
      <div class="modal fade" id="myModal" role="dialog" name="dialog">
        <div role="modal-dialog" class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">New Category Item</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form  enctype='multipart/form-data' action="./addCatController.php" method="POST">
                <div class="form-group">
                  <label for="c_name">Category Name:</label>
                  <input id="c_name" type="text" aria-label="c_name" class="form-control" name="c_name" required pattern="^[A-Za-z\s]+$" title="Category name can only contain alphabets and spaces">
                  <small class="form-text text-muted">Category must only contain letters and spaces.</small>

                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Category</button>
                </div>
              </form>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
            </div>
          </div>

        </div>
      </div>


    </div>
    <?php include "footer.php";?>
</body>
</html>