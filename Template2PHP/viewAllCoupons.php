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
    <div id="main-content" class="allContent-section" role="main">
        <h1 class="center"><strong>ALL COUPONS</strong></h1>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Code</th>
                    <th class="text-center">Discount</th>
                    <th class="text-center">Status </th>
                    <th class="text-center" colspan="2"> Action </th>
                </tr>
            </thead>
            <?php
            include_once "dbconnect.php";
            $sql = "SELECT * from coupon";
            $result = $conn->query($sql);
            $count = 1;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $row["coupon_code"] ?></td>
                        <td><?= $row["discount"] ?></td>
                        <td><?= $row["status"] ?></td>
                        <td><button class="btn btn-primary" style="height:40px; color: black" onclick="couponEditForm('<?= $row['coupon_id'] ?>')">Edit</button></td>
                        <td><button class="btn btn-danger" style="height:40px; color: black" onclick="couponDelete('<?= $row['coupon_id'] ?>')">Delete</button></td>
                    </tr>
                    <?php
                    $count = $count + 1;
                }
            }
            ?>
        </table>

        <!-- Trigger the modal with a button -->
        <button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
            Add Coupon
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog" aria-label="modal" name="dialog">
            <div class="modal-dialog" role="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">New Coupon</h2>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form  enctype='multipart/form-data' action="./addCouponController.php" method="POST">
                            <div class="form-group">
                                <label>Coupon Code:</label>
                                <input type="text" class="form-control" name="coupon_code" pattern="^[a-zA-Z0-9_-]{1,20}$" required aria-label="code">
                                <small class="form-text text-muted">Enter a Coupon Code between 1 and 20 characters consisting of letters, numbers, hyphens, and underscores only.</small> 
                            </div>
                            <div class="form-group">
                                <label>Coupon Discount:</label>
                                <input type="text" class="form-control" name="discount" required aria-label="code" min="0" max="1000" pattern="[0-9]{1,4}">
                            </div>
                            <div class="form-group">
                                <label>Choose a status:</label>
                                <select name="status" id="status" aria-label="status">
                                    <option value="Valid">Valid</option>
                                    <option value="Invalid">Invalid</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary" name="upload" style="height:40px">Add Coupon</button>
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
    <?php include "footer.php" ?>
</body>
</html>