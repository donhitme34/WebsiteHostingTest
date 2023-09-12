<!DOCTYPE html>
<html lang="en">
    <main>
        <?php include 'header.php' ?>
        <!-- Header -->
        <?php
        include 'nav.php';
        ?>

        <script defer src="js/ajaxWork.js"></script>
        <script defer src="js/admin.js"></script>
        <?php
        session_start();
        include_once "./dbconnect.php";
        $ID = $_POST['record'];
        $idproduct = $_GET["idproduct"];
        $qry = mysqli_query($conn, "SELECT * FROM product WHERE idproduct=$idproduct");
        $numberOfRow = mysqli_num_rows($qry);
        if ($numberOfRow > 0) {
            while ($row1 = mysqli_fetch_array($qry)) {
                if (!empty($_GET["action"])) {
                    switch ($_GET["action"]) {
                        case "add":
                            if (!isset($_SESSION['UID'])) {
                                echo "<script> location.href='http://35.213.170.131/project/login.php'; </script>";
                                exit;
                            } else {
                                $productByCode = $conn->query("SELECT * FROM product WHERE idproduct=" . $_GET["idproduct"] . "");
                                if ($productByCode) {
                                    $product = $productByCode->fetch_assoc();
                                    $itemArray = array($product["idproduct"] => array
                                            ('itemname' => $row1['itemname'],
                                            'idproduct' => $row1['idproduct'],
                                            'quantity' => $_POST["quantity"],
                                            'price' => $product["price"],
                                            'image' => $product["image"]));

                                    if (!empty($_SESSION["cart_item"])) {
                                        ?>
                                        <script>
                                            alert("Sorry! We only allow 1 item per transaction at a time.");
                                        </script>
                                        <?php
                                    } else {
                                        ?>
                                        <script>
                                            alert("Item added to cart!");
                                        </script>
                                        <?php
                                        $_SESSION["cart_item"] = $itemArray;
                                    }
                                } else {
                                    echo "Error: " . $conn->error;
                                }
//                    }
                            }
                            break;
                        case "empty":
                            unset($_SESSION["cart_item"]);
                            break;
                    }
                }
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                ?>

                <form method="post" action="product-detail.php?action=add&idproduct=<?= $row1['idproduct'] ?>">
                    <!-- Product Detail -->
                    <div class="container bgwhite p-t-35 p-b-80">
                        <div class="flex-w flex-sb">
                            <div class="w-size13 p-t-30 respon5">
                                <div class="wrap-slick3 flex-sb flex-w">
                                    <div class="wrap-slick3-dots"></div>
                                    <div class="wrap-pic-w">
                                        <img src="<?= $row1['image'] ?>" alt="IMG-PRODUCT"> 
                                    </div>
                                </div>
                            </div>

                            <div class="w-size14 p-t-30 respon5">
                                <h1 class="product-detail-name m-text16 p-b-13">
                                    <?= $row1['itemname'] ?>
                                </h1>

                                <h2 class="m-text17">
                                    $ <?= $row1['price'] ?>
                                </h2>
                                <?php
                                $sellerID = $row1['sellerID'];
                                if (!empty($sellerID) && !empty($_SESSION['UID'])) {
                                    if ($sellerID != $_SESSION['UID']) {
                                        ?>
                                        <div class="flex-r-m flex-w p-t-10">
                                            <div class="w-size16 flex-m flex-w">
                                                <div class="flex-w bo5 of-hidden m-r-22 m-t-10 m-b-10">
                                                    <button class="btn-num-product-down color1 flex-c-m size7 bg8 eff2" name="minus" aria-label="minus">
                                                        <i class="fs-12 fa fa-minus" aria-hidden="true"></i>
                                                    </button>

                                                    <input class="size8 m-text18 t-center num-product" type="number" name="quantity" value="1" aria-label="quantity">

                                                    <button class="btn-num-product-up color1 flex-c-m size7 bg8 eff2" name="plus" aria-label="plus">
                                                        <i class="fs-12 fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </div>

                                                <div class="btn-addcart-product-detail size9 trans-0-4 m-t-10 m-b-10">
                                                    <!-- Button -->
                                                    <button class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
                                                        Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="wrap-dropdown-content bo6 p-t-15 p-b-14 active-dropdown-content">
                                    <h3 class="js-toggle-dropdown-content flex-sb-m cs-pointer m-text19 color0-hov trans-0-4">
                                        Description
                                        <i class="down-mark fs-12 color1 fa fa-minus dis-none" aria-hidden="true"></i>
                                        <i class="up-mark fs-12 color1 fa fa-plus" aria-hidden="true"></i>
                                    </h3>

                                    <div class="dropdown-content dis-none p-t-15 p-b-23">
                                        <p style="color:black">
                                            <?= $row1['description'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>				
                    </div>
                </form>
                <?php
                $sellerID = $row1['sellerID'];
                if (!empty($sellerID) && !empty($_SESSION['UID'])) {
                    if ($sellerID == $_SESSION['UID']) {
                        ?>
                        <div class="flex-r-m flex-w p-t-10">
                            <div class="w-size16 flex-m flex-w">
                                <form method="post" action="userEditItem.php?idproduct=<?= $row1['idproduct'] ?>">
                                    <div class="btn-addcart-product-detail size9 trans-0-4 m-t-10 m-b-10">

                                        <button class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
                                                Edit
                                            </button>

                                    </div>
                                </form>

                                <form method="post" action="delete_product.php?idproduct=<?= $row1['idproduct'] ?>">
                                    <div style="padding-left: 10px" class="btn-addcart-product-detail size9 trans-0-4 m-t-10 m-b-10">
                                        <button class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
                                                Delete
                                            </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
        }
        ?>


        <!-- Footer -->
        <?php
        include 'footer.php';
        ?>



        <!-- Back to top -->
        <div class="btn-back-to-top bg0-hov" id="myBtn">
            <span class="symbol-btn-back-to-top">
                <i class="fa fa-angle-double-up" aria-hidden="true"></i>
            </span>
        </div>

        <!--===============================================================================================-->
        <script src="js/main.js"></script>


    </main>
</body>
</html>
