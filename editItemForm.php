
<div class="container p-5" role="edit_form">

<h4 class="center">Edit Product Detail</h4>
<?php
    include_once "./dbconnect.php";
	$ID=$_POST['record'];
	$qry=mysqli_query($conn, "SELECT * FROM product WHERE idproduct='$ID'");
	$numberOfRow=mysqli_num_rows($qry);
	if($numberOfRow>0){
		while($row1=mysqli_fetch_array($qry)){
                    $catID=$row1["product_category_id"];
?>
    <form id="update-Items" onsubmit="updateItems()" enctype='multipart/form-data'>
    <div class="form-group">
      <input type="text" class="form-control" id="idproduct" value="<?=$row1['idproduct']?>" hidden>
    </div>
    <div class="form-group">
      <label for="itemname">Product Name:</label>
      <input type="text" class="form-control" id="itemname" pattern="^[a-zA-Z0-9 ]{1,50}$" value="<?=$row1['itemname']?>">
        <small class="form-text text-muted">Enter a name between 1 and 50 characters consisting of letters, numbers, and spaces only.</small>
    </div>
    <div class="form-group">
      <label for="description">Product Description:</label>
      <input type="text" class="form-control" id="description" value="<?=$row1['description']?>" pattern="^[a-zA-Z0-9 ,.\-()]{1,200}$">
              <small class="form-text text-muted">Enter a description between 1 and 200 characters consisting of letters, numbers, spaces, commas, periods, hyphens, and parentheses only.</small>
    </div>
    <div class="form-group">
      <label for="price">Unit Price:</label>
      <input type="number" class="form-control" id="price" pattern="^[a-zA-Z0-9_-]{1,20}$" value="<?=$row1['price']?>">
      <small class="form-text text-muted">Enter a number between 0 and 1,000,000 with up to 2 decimal places.</small>
    </div>
    <div class="form-group">
      <label for="sellerID">Seller ID:</label>
      <input type="text" class="form-control" id="sellerID" value="<?=$row1['sellerID']?>" pattern="^[a-zA-Z0-9_-]{1,20}$">
      <small class="form-text text-muted">Enter a seller ID between 1 and 20 characters consisting of letters, numbers, hyphens, and underscores only.</small> 
    </div>
    <div class="form-group">
      <label>Category:</label>
      <select id="category" aria-label="category">
        <?php
          $sql="SELECT * from category WHERE idcategory='$catID'";
          $result = $conn-> query($sql);
          if ($result-> num_rows > 0){
            while($row = $result-> fetch_assoc()){
              echo"<option value='". $row['idcategory'] ."'>" .$row['Name'] ."</option>";
            }
          }
        ?>
        <?php
          $sql="SELECT * from category WHERE idcategory!='$catID'";
          $result = $conn-> query($sql);
          if ($result-> num_rows > 0){
            while($row = $result-> fetch_assoc()){
              echo"<option value='". $row['idcategory'] ."'>" .$row['Name'] ."</option>";
            }
          }
        ?>
      </select>
    </div>
      <div class="form-group">
         <img width='200px' height='150px' src='<?=$row1["image"]?>' alt="product_img">
         <div>
            <label for="file">Choose Image:</label>
            <input type="text" id="existingImage" class="form-control" value="<?=$row1['image']?>" hidden>
            <input type="file" id="newImage" value="" aria-label="newImage">
         </div>
    </div>
    <div class="form-group">
      <button type="submit" style="height:40px;color: black;" class="btn btn-primary">Update Item</button>
    </div>
    <?php
    		}
    	}
    ?>
  </form>

  </div>