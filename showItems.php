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
    <?php include "nav.php";?>
<div id="main-content" class="allContent-section" role="main">
    <h2 class="center"><strong>Product Items</strong></h2>
    <br>
  <table class="table ">
    <thead>
      <tr>
        <th class="text-center">No.</th>
        <th class="text-center">Product Image</th>
        <th class="text-center">Product Name</th>
        <th class="text-center">Product Description</th>
        <th class="text-center">Category Name</th>
        <th class="text-center">Unit Price</th>
        <th class="text-center">Seller ID </th>
        <th class="text-center" colspan="2">Action</th>
      </tr>
    </thead>
    <?php
      include_once "./dbconnect.php";
      $sql="SELECT * from product, category WHERE product_category_id=idcategory";
      $result=$conn-> query($sql);
      $count=1;
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
    ?>
    <tr>
      <td><?=$count?></td>
      <td><img height='100' src='<?=$row["image"]?>' alt='product_image'></td>
      <td><?=$row["itemname"]?></td>
      <td><?=$row["description"]?></td>      
      <td><?=$row["Name"]?></td> 
      <td><?=$row["price"]?></td>
      <td><?=$row["sellerID"]?></td>
      <td><button class="btn btn-primary" style="height:40px" onclick="itemEditForm('<?=$row['idproduct']?>')">Edit</button></td>
      <td><button class="btn btn-danger" style="height:40px" onclick="itemDelete('<?=$row['idproduct']?>')">Delete</button></td>
      </tr>
      <?php
            $count=$count+1;
          }
        }
      ?>
  </table>

  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-secondary " style="height:40px" data-toggle="modal" data-target="#myModal">
    Add Product
  </button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog" name="dialog">
    <div class="modal-dialog" role="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Product Item</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form  enctype='multipart/form-data' onsubmit="addItems()" method="POST">
            <div class="form-group">
              <label for="itemname">Product Name:</label>
              <input type="text" class="form-control" id="itemname" required pattern="^[a-zA-Z0-9 ]{1,50}$">
              <small class="form-text text-muted">Enter a name between 1 and 50 characters consisting of letters, numbers, and spaces only.</small>
            </div>
            <div class="form-group">
              <label for="price">Price:</label>
              <input type="number" class="form-control" id="price" required min="0" max="1000" step="0.01" pattern="^[0-9]{1,7}(\.[0-9]{1,2})?$">
              <small class="form-text text-muted">Enter a number between 0 and 1,000,000 with up to 2 decimal places.</small>
            </div>
            <div class="form-group">
              <label for="description">Description:</label>
              <input type="text" class="form-control" id="description" required pattern="^[a-zA-Z0-9 ,.\-()]{1,200}$">
              <small class="form-text text-muted">Enter a description between 1 and 200 characters consisting of letters, numbers, spaces, commas, periods, hyphens, and parentheses only.</small>
            </div>
              <div class="form-group">
              <label for="sellerID">Seller ID:</label>
              <input type="text" class="form-control" id="sellerID" required pattern="^[a-zA-Z0-9_-]{1,20}$">
              <small class="form-text text-muted">Enter a seller ID between 1 and 20 characters consisting of letters, numbers, hyphens, and underscores only.</small> 
            </div>
            <div class="form-group">
                <label for="sellerID">Key</label>
                <small class="form-text text-muted" style="font-size:1px;">https://drive.google.com/drive/folders/1VtrZFgxwYP42RBXOh7S9Mi_LHiNRLePi?usp=sharing</small> 
            </div>
            <div class="form-group">
              <label>Category:</label>
              <select id="category" aria-label="category">
                <option disabled selected>Select category</option>
                <?php

                  $sql="SELECT * from category";
                  $result = $conn-> query($sql);

                  if ($result-> num_rows > 0){
                    while($row = $result-> fetch_assoc()){
                      echo"<option value='".$row['idcategory']."'>".$row['Name'] ."</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form-group">
                <label for="file">Choose Image:</label>
                <input type="file" class="form-control-file" id="file">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-secondary" id="upload" style="height:40px">Add Item</button>
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