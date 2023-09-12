<!DOCTYPE html>
<html lang="en">
    <main>
    <?php
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION["user"] != true) {
        header("Location: login.php");
        exit;
    }
    include 'header.php';
    ?>
    <body class="animsition">
        <style>
            .container-bg {
                background: #ECECEC;
            }
        </style>

        <?php
        include 'nav.php';
        ?>
        <?php
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "<div class='alert alert-success'>Item listed successfully.</div>";
        } elseif (isset($_GET['error_message'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars(urldecode($_GET['error_message'])) . "</div>";
        }
        ?>
        <?php
        include_once "./dbconnect.php";
//        $ID = $_POST['record'];
        $idproduct = $_GET["idproduct"];
        $qry = mysqli_query($conn, "SELECT * FROM product WHERE idproduct='$idproduct '");
        $numberOfRow = mysqli_num_rows($qry);
        if ($numberOfRow > 0) {
            while ($row1 = mysqli_fetch_array($qry)) {
                $catID = $row1["product_category_id"];
                ?>
       
        <div class="container mt-4">
             <h1>Item Details</h1>
                <form method="POST" action="userEditItemController.php" enctype="multipart/form-data" id="uploadForm">
                    <div class="form-group">
                        <input type="text" name="idproduct" class="form-control" id="idproduct" value="<?= $row1['idproduct'] ?>" hidden>
                    </div>
                    <div class="form-group">
                        <label for="itemname">Product Name:</label>
                        <input type="text" name="itemname" class="form-control" id="itemname" value="<?= $row1['itemname'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Product Description:</label>
                        <input type="text" name="description" class="form-control" id="description" value="<?= $row1['description'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="price">Unit Price:</label>
                        <input type="float" name="price" class="form-control" id="price" value="<?= $row1['price'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <select name="category" id="category" aria-label="select">
                            <?php
                            $sql = "SELECT * from category WHERE idcategory='$catID'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo"<option value='" . $row['idcategory'] . "'>" . $row['Name'] . "</option>";
                                }
                            }
                            ?>
                            <?php
                            $sql = "SELECT * from category WHERE idcategory!='$catID'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo"<option value='" . $row['idcategory'] . "'>" . $row['Name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <img alt="item" width='200px' height='150px' src='<?= $row1["image"] ?>'>
                        <div>
                            <label for="file">Choose Image:</label>
                            <input type="text" name="existingImage" id="existingImage" class="form-control" value="<?= $row1['image'] ?>" hidden>
                            <input type="file" id="newImage" value="" aria-label="file">
                        </div>
                    </div>
                    <div  class="form-group">
                        <button id="upload" name="upload" type="submit" style="height:40px; color: black" class="btn btn-primary">Update Item</button>
                    </div>
                    <?php
                }
            }
            ?>
        </form>
        </div>
        <?php
        include 'footer.php';
        ?>
        <script>
            document.getElementById("uploadForm").addEventListener("submit", function (e) {
                const imageInput = document.getElementById("itemImage");
                if (imageInput.files.length === 0) {
                    e.preventDefault();
                    alert("Please upload an image.");
                }
            });
        </script>
<script defer src="js/ajaxWork.js"></script>
    </body>
    </main>
</html>
