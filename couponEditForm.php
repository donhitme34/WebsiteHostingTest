
<div class="container p-5">

<h4>Edit Coupon Detail</h4>
<?php
    include_once "./dbconnect.php";
	$ID=$_POST['record'];
	$qry=mysqli_query($conn, "SELECT * FROM coupon WHERE coupon_id='$ID'");
	$numberOfRow=mysqli_num_rows($qry);
	if($numberOfRow>0){
		while($row1=mysqli_fetch_array($qry)){
      $coupon_id=$row1["coupon_id"];
?>
<form id="update-Items" onsubmit="updateCoupon()" enctype='multipart/form-data'>
    <div class="form-group">
      <input type="text" class="form-control" id="coupon_id" value="<?=$row1['coupon_id']?>" hidden>
    </div>
    <div class="form-group">
      <label for="firstname">Coupon Code:</label>
      <input type="text" class="form-control" id="coupon_code" pattern="^[a-zA-Z0-9_-]{1,20}$" value="<?=$row1['coupon_code']?>">
      <small class="form-text text-muted">Enter a Coupon Code between 1 and 20 characters consisting of letters, numbers, hyphens, and underscores only.</small> 
    </div>
    <div class="form-group">
      <label for="lastname">Discount:</label>
      <input type="number" class="form-control" id="discount" min="0" max="1000" pattern="[0-9]{1,4}"  value="<?=$row1['discount']?>">
    </div>
    <div class="form-group">
      <label for="status">Choose a status:</label>
                                <select name="status" id="status">
                                    <option value="Valid">Valid</option>
                                    <option value="Invalid">Invalid</option>
                                </select>
    </div>
    
    <div class="form-group">
      <button type="submit" style="height:40px" class="btn btn-primary">Update Coupon</button>
    </div>
    <?php
    		}
    	}
    ?>
  </form>

    </div>