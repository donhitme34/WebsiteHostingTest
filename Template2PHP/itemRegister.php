<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION["user"] != true) {
        header("Location: login.php");
        exit;
    }
    include 'header.php';
    ?>
    <body class="animsition">
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
        <div class="container mt-4" role='main' style='background: #ECECEC !important;'>
            <div class="row">
                <div class="col-md-8 offset-md-2 col-sm-12">
                    <div id="listNewProduct">
                        <h1>List a new product</h1>
                        <form method="POST" action="processItem.php" enctype="multipart/form-data" id="uploadForm">
                            <div class="mb-3">
                                <label for="itemname" class="form-label">Item Name</label>
                                <input type="text" name="itemname" class="form-control" id="itemname" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Item Description</label>
                                <input type="text" name="description" class="form-control" id="description" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Item Price</label>
                                <input type="number" name="price" class="form-control" id="price" required>
                            </div>
                            <div class="mb-3">
                                <label for="itemImage" class="form-label">Item Image</label>
                                <input type="file" name="itemImage" class="form-control" id="itemImage" required>
                            </div>
                            <button type="submit" name="upload" id="upload" class="btn btn-custom" style="background-color: #0056b3 !important;color: #ffffff !important;">List Item</button>
                            <br>
                        </form>
                        <br>
                    </div>
                    <br>
                </div>
            </div>
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

    </body>
</html>
