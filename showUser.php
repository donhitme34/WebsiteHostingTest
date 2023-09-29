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
        include"nav.php";
    ?>
<div role="main" id="main-content" class="allContent-section">
    <h2 class="center"><strong>Manage USERS</strong></h2>
    <br>
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Username</th>
                <th class="text-center">Name</th>
                <th class="text-center">Email </th>
                <th class="text-center">Home Address</th>
                <th class="text-center" colspan="2">Action </th>
            </tr>
        </thead>
        <?php
            include_once "dbconnect.php";
            $sql="SELECT * from User where isAdmin=0 ORDER BY idUser";
            $result=$conn-> query($sql);
            $count=1;
            if ($result-> num_rows > 0){
              while ($row=$result-> fetch_assoc()) {

          ?>
          <tr>
            <td><?=$count?></td>
            <td><?=$row["Username"]?></td>
            <td><?=$row["firstname"]?> <?=$row["lastname"]?></td>
            <td><?=$row["emailaddress"]?></td>
            <td><?=$row["homeAddress"]?></td>
            <td><button class="btn btn-primary" style="height:40px;color: black" onclick="userEditForm('<?=$row['idUser']?>')">Edit</button></td>
          </tr>
          <?php
                  $count=$count+1;

              }
          }
          ?>
    </table>
    
     <!-- Trigger the modal with a button -->
      <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
        Add User
      </button>

      <!-- Modal -->
      <div name="dialog" class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">New User</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form  enctype='multipart/form-data' action="./addUserController.php" method="POST">
                <div class="form-group">
                  <label for="Username">Username:</label>
                  <input id="Username" aria-required="true" aria-label="Username" type="text" class="form-control" name="Username" required pattern="^[a-zA-Z0-9_]{3,15}$">
                  <small class="form-text text-muted">Username must be 3-16 characters and can only contain letters, numbers, underscores, and hyphens.</small>
                </div>
                <div class="form-group">
                  <label for="firstname">First name:</label>
                  <input id="firstname" aria-label="firstname" type="text" class="form-control" name="firstname" pattern="^[a-zA-Z\s]{1,50}$">
                  <small class="form-text text-muted">First name must only contain letters and spaces.</small>
                </div>
                <div class="form-group">
                  <label for="lastname">Last name:</label>
                  <input id="lastname" aria-required="true" aria-label="lastname" type="text" class="form-control" name="lastname" required pattern="^[a-zA-Z\s]{1,50}$">
                  <small class="form-text text-muted">Last name must only contain letters and spaces.</small>
                </div>
                <div class="form-group">
                  <label for="emailaddress">Email:</label>
                  <input id="emailaddress" aria-required="true" aria-label="emailaddress" type="email" class="form-control" name="emailaddress" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required>
                  <small class="form-text text-muted">Email must be in a valid format.</small>
                </div>
                <div class="form-group">
                <label for="homeAddress">Home Address:</label>
                <input id="homeAddress" aria-required="true" aria-label="homeAddress" type="text" class="form-control" name="homeAddress" pattern="^[a-zA-Z0-9\s\,\#]{1,100}$" required>
                <small class="form-text text-muted">Home address can only contain letters, numbers, spaces, commas, hash symbols, and be up to 100 characters long.</small>
                </div>
                <div class="form-group">
                  <label for="password">Password:</label>
                  <input id="password" aria-required="true" aria-label="password" type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                  <button type="submit" class="btn btn-secondary" name="upload" style="height:40px;color: black">Add User</button>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px;">Close</button>
            </div>
          </div>

        </div>
      </div>
</div>
<?php include "footer.php";?>
</body> 
</html>