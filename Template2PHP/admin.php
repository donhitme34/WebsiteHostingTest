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
            
            include_once "dbconnect.php";
       ?>
        <div role="main" id="main-content" class="container allContent-section py-5">
            <h2 class="center"><strong>Dashboard</strong></h2>
            <br>
            <div class="row">
                <div class="col-sm-2">
                    <div class="dashboard center">
                        <i class="fa fa-users mb-2" style="font-size: 70px;"></i>
                        <h3 style="color:white;"> Users </h3>
                        <h4 style="color:white;">
                            <?php
                                $sql = "SELECT * FROM User where isAdmin=0";
                                $result = $conn-> query($sql);
                                $count = 0;
                                if($result-> num_rows > 0){
                                    while($row=$result-> fetch_assoc()){
                                        $count++;
                                    }
                                }
                                echo $count;
                            ?>
                        </h4>
                    </div>
                </div>
                
                <div class="col-sm-2">
                    <div class="dashboard center">
                        <i class="fa fa-th-large mb-2" style="font-size: 70px;"></i>
                        <h3 style="color:white;"> Categories </h3>
                        <h4 style="color:white;">
                            <?php
                                $sql = "SELECT * FROM category";
                                $result = $conn-> query($sql);
                                $count = 0;
                                if($result-> num_rows > 0){
                                    while($row=$result-> fetch_assoc()){
                                        $count++;
                                    }
                                }
                                echo $count;
                            ?>
                        </h4>
                    </div>
                </div>
                
                <div class="col-sm-2">
                    <div class="dashboard center">
                        <i class="fa fa-th mb-2" style="font-size: 70px;"></i>
                        <h3 style="color:white;"> Products </h3>
                        <h4 style="color:white;">
                            <?php
                                $sql = "SELECT * FROM product";
                                $result = $conn-> query($sql);
                                $count = 0;
                                if($result-> num_rows > 0){
                                    while($row=$result-> fetch_assoc()){
                                        $count++;
                                    }
                                }
                                echo $count;
                            ?>
                        </h4>
                    </div>
                </div>
                
                <div class="col-sm-2">
                    <div class="dashboard center">
                        <i class="fa fa-list mb-2" style="font-size: 70px;"></i>
                        <h3 style="color:white;"> Transactions </h3>
                        <h4 style="color:white;">
                            <?php
                                $sql = "SELECT * FROM transaction,User where Userid = idUser";
                                $result = $conn-> query($sql);
                                $count = 0;
                                if($result-> num_rows > 0){
                                    while($row=$result-> fetch_assoc()){
                                        $count++;
                                    }
                                }
                                echo $count;
                            ?>
                        </h4>
                    </div>
                </div>
                
                <div class="col-sm-2">
                    <div class="dashboard center">
                        <i class="fa fa-ticket" style="font-size: 70px;"></i>
                        <h3 style="color:white;"> Coupons </h3>
                        <h4 style="color:white;">
                            <?php
                                $sql = "SELECT * FROM coupon";
                                $result = $conn-> query($sql);
                                $count = 0;
                                if($result-> num_rows > 0){
                                    while($row=$result-> fetch_assoc()){
                                        $count++;
                                    }
                                }
                                echo $count;
                            ?>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
            
        <?php
            if (isset($_GET['category']) && $_GET['category'] == "success") {
                echo '<script> alert("Category Successfully Added")</script>';
            }else if (isset($_GET['category']) && $_GET['category'] == "error") {
                echo '<script> alert("Adding Unsuccess")</script>';
            }
            if (isset($_GET['size']) && $_GET['size'] == "success") {
                echo '<script> alert("Size Successfully Added")</script>';
            }else if (isset($_GET['size']) && $_GET['size'] == "error") {
                echo '<script> alert("Adding Unsuccess")</script>';
            }
            if (isset($_GET['variation']) && $_GET['variation'] == "success") {
                echo '<script> alert("Variation Successfully Added")</script>';
            }else if (isset($_GET['variation']) && $_GET['variation'] == "error") {
                echo '<script> alert("Adding Unsuccess")</script>';
            }
        ?>
        
        <?php include "footer.php";?>
        
        
    </body>  
    
</html>