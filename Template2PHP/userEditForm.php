
<div role="user_form" class="container p-5">

<h4>Edit User Detail</h4>
<?php
    include_once "./dbconnect.php";
	$ID=$_POST['record'];
	$qry=mysqli_query($conn, "SELECT * FROM User WHERE idUser='$ID'");
	$numberOfRow=mysqli_num_rows($qry);
	if($numberOfRow>0){
		while($row1=mysqli_fetch_array($qry)){
      $userID=$row1["idUser"];
?>
<form id="update-Items" onsubmit="updateUsers()" enctype='multipart/form-data'>
    <div class="form-group">
      <input type="text" class="form-control" id="idUser" value="<?=$row1['idUser']?>" hidden>
    </div>
    <div class="form-group">
      <input type="text" class="form-control" id="Username" value="<?=$row1['Username']?>" hidden>
    </div>
    <div class="form-group">
      <label for="firstname">First Name:</label>
      <input type="text" class="form-control" id="firstname" pattern="^[a-zA-Z\s]{1,50}$" value="<?=$row1['firstname']?>">
    </div>
    <div class="form-group">
      <label for="lastname">Last Name:</label>
      <input type="text" class="form-control" id="lastname" pattern="^[a-zA-Z\s]{1,50}$" value="<?=$row1['lastname']?>">
    </div>
    <div class="form-group">
      <label for="emailaddress">Email:</label>
      <input type="email" class="form-control" id="emailaddress" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" value="<?=$row1['emailaddress']?>">
    </div>
    <div class="form-group">
      <label for="homeAddress">Home Address:</label>
      <input type="text" class="form-control" id="homeAddress" pattern="^[a-zA-Z0-9\s\,\#]{1,100}$" value="<?=$row1['homeAddress']?>">
    </div>
    
    <div class="form-group">
      <button type="submit" style="height:40px" class="btn btn-primary">Update User</button>
    </div>
    <?php
    		}
    	}
    ?>
  </form>

    </div>