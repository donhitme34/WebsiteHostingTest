<!DOCTYPE html>
<html lang="en">
    <?php
    include 'header.php'
    ?>
    <body class="animsition">


        <!-- Header -->
        <?php
        include 'nav.php'
        ?>
        <?php
        session_start();
        global $currentCategory, $totalProducts, $itemCode;
        $currentCategory = $_GET['category'];
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);

        if ($conn->connect_error) {
            die('Connection Failed: ' . $conn->connect_error);
        }
        

        ini_set('display_errors', 1);
        error_reporting(E_ALL);

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
                                    ('itemname' => $product["itemname"],
                                    'idproduct' => $product["idproduct"],
                                    'quantity' => 1,
                                    'price' => $product["price"],
                                    'image' => $product["image"]));

                            if (!empty($_SESSION["cart_item"])) {
//                            if (in_array($product["idproduct"], array_keys($_SESSION["cart_item"]))) {
//                                foreach ($_SESSION["cart_item"] as $k => $v) {
//                                    if ($product["idproduct"] == $k) {
//                                        if (empty($_SESSION["cart_item"][$k]["quantity"])) {
//                                            $_SESSION["cart_item"][$k]["quantity"] = 0;
//                                        }
//                                        $_SESSION["cart_item"][$k]["quantity"] += 1;
//                                    }
//                                }
//                            } else {
//                                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
//                            }
//                            $_SESSION["cart_item_added"] = true;
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
        // 	return $row;
        // }
        // function generatePaginationButtons($productArray) {
        // 	for ($i = 0; $i < floor(count($productArray) / 2); $i++) {
        // 		echo "<a href=\"#\" class=\"item-pagination flex-c-m trans-0-4 active-pagination\">" . $i + 1 . "</a>";
        // 	}
        // }
        ?>
        <!-- Title Page -->
        <section class="bg-title-page p-t-50 p-b-40 flex-col-c-m" style="background-image: url(images/bg.jpg);">
            <h2 class="l-text2 t-center" style="color:black">
                <?php
                if ($currentCategory == "")
                    $currentCategory = "All Products";
                echo $currentCategory;
                ?>
            </h2>
            <p class="m-text13 t-center" style="color:black">
                New Arrivals Women Collection 2018
            </p>
        </section>
        <?php

        function generateItemCard($image,$itemName, $itemPrice, $itemCode,$sellerID) {
            if($sellerID == $_SESSION['UID']){
                echo"<div class=\"col-sm-12 col-md-6 col-lg-4 p-b-50\">" .
                "	<!-- Block2 -->" .
                "	<div class=\"block2\">" .
                "<form method=\"post\" action=\"product.php?action=add&idproduct=$itemCode\">" .
                "		<div class=\"block2-img wrap-pic-w of-hidden pos-relative block2-labelnew\">" .
                "			<img src='" . $image . "' alt=\"IMG-PRODUCT\">" .
                "			<div class=\"block2-overlay trans-0-4\">" .
                "				<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">" .
                "					<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>" .
                "					<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>" .
                "				</a>" .
                "				<div class=\"block2-btn-addcart w-size1 trans-0-4\">" .
                "					<!-- Button -->" .
                "					<button name=\"action\" type=\"submit\" class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">" .
                "						Add to Cart" .
                "					</button><br>" .
                "                                       <a href='delete_product.php?idproduct=" .  $itemCode . "' class='flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4'>" .
                "                                               Delete" .
                "                                       </a>"    .
                "				</div>" .
                "			</div>" .
                "		</div>" .
                "</form>" .
                "		<div class=\"block2-txt p-t-20\">" .
                "			<a href=\"product-detail.php?idproduct=$itemCode\" class=\"block2-name dis-block s-text3 p-b-5\">" .
                "				$itemName" .
                "			</a>" .
                "			<span class=\"block2-price m-text6 p-r-5\">" .
                "				$" . $itemPrice .
                "			</span>" .
                "		</div>" .
                "	</div>" .
                "</div>";
            }
            else{
                echo"<div class=\"col-sm-12 col-md-6 col-lg-4 p-b-50\">" .
                "	<!-- Block2 -->" .
                "	<div class=\"block2\">" .
                "<form method=\"post\" action=\"product.php?action=add&idproduct=$itemCode\">" .
                "		<div class=\"block2-img wrap-pic-w of-hidden pos-relative block2-labelnew\">" .
                "			<img src='".$image."' alt=\"IMG-PRODUCT\">" .
                "			<div class=\"block2-overlay trans-0-4\">" .
                "				<a href=\"#\" class=\"block2-btn-addwishlist hov-pointer trans-0-4\">" .
                "					<i class=\"icon-wishlist icon_heart_alt\" aria-hidden=\"true\"></i>" .
                "					<i class=\"icon-wishlist icon_heart dis-none\" aria-hidden=\"true\"></i>" .
                "				</a>" .
                "				<div class=\"block2-btn-addcart w-size1 trans-0-4\">" .
                "					<!-- Button -->" .
                "					<button name=\"action\" type=\"submit\" class=\"flex-c-m size1 bg4 bo-rad-23 hov1 s-text1 trans-0-4\">" .
                "						Add to Cart" .
                "					</button>" .                                                               
                "				</div>" .
                "			</div>" .
                "		</div>" .
                "</form>" .
                "		<div class=\"block2-txt p-t-20\">" .
                "			<a href=\"product-detail.php?idproduct=$itemCode\" class=\"block2-name dis-block s-text3 p-b-5\">" .
                "				$itemName" .
                "			</a>" .
                "			<span class=\"block2-price m-text6 p-r-5\">" .
                "				$" .$itemPrice .
                "			</span>" .
                "		</div>" .
                "	</div>" .
                "</div>";
            }
        }

// function getProducts($productCategory, $conn) {
// 	// Get information from database
// 	if ($productCategory == "all_products") {
// 		$statement = $conn->prepare("SELECT * FROM product");
// 	}
// 	else {
// 		$statement = $conn->prepare("SELECT * FROM product WHERE category = ?");
// 		$statement->bind_param("s", $productCategory);
// 	}
// 	$statement->execute();
// 	$result = $statement->get_result();
// 	$row = $result->fetch_all(MYSQLI_ASSOC);
// 	$productCount = count($row);
// 	for($i = 0; $i < $productCount; $i++) {
// 		generateItemCard($row[$i]['itemname'], $row[$i]['price']);
// 	}
// 	return $row;
// }
// function generatePaginationButtons($productArray) {
// 	for ($i = 0; $i < floor(count($productArray) / 2); $i++) {
// 		echo "<a href=\"#\" class=\"item-pagination flex-c-m trans-0-4 active-pagination\">" . $i + 1 . "</a>";
// 	}
// }
        ?>
        <!-- Title Page -->
        <section class="bg-title-page p-t-50 p-b-40 flex-col-c-m" style="background-image: url(images/bg.jpg);">
            <h2 class="l-text2 t-center" style="color:black">
                <?php
                echo $currentCategory;
                ?>
            </h2>
            <p class="m-text13 t-center" style="color:black">
                New Arrivals Women Collection 2018
            </p>
        </section>
        <?php
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            echo "<div class='alert alert-success'>Item listed successfully.</div>";
        }
        ?>

        <!-- Content page -->
        <section class="bgwhite p-t-55 p-b-65">
            <div class="container">
                <div class="col-sm-6 col-md-8 col-lg-9 p-b-50">
                    <div class="flex-sb-m flex-w p-b-35">
                        <div class="flex-w">
                            <div class="rs2-select2 bo4 of-hidden w-size12 m-t-5 m-b-5 m-r-10">
                                <select class="selection-2" name="sorting">
                                    <option>Default Sorting</option>
                                    <option>Popularity</option>
                                    <option>Price: low to high</option>
                                    <option>Price: high to low</option>
                                </select>
                            </div>

                            <div class="rs2-select2 bo4 of-hidden w-size12 m-t-5 m-b-5 m-r-10">
                                <select class="selection-2" name="sorting">
                                    <option>Price</option>
                                    <option>$0.00 - $50.00</option>
                                    <option>$50.00 - $100.00</option>
                                    <option>$100.00 - $150.00</option>
                                    <option>$150.00 - $200.00</option>
                                    <option>$200.00+</option>

                                </select>
                            </div>
                        </div>
                        <span class="s-text8 p-t-5 p-b-5">

                        </span>
                    </div>

                    <!-- Product -->
                    <div class="row">
                        <?php
                        if ($currentCategory == "All Products" || $currentCategory == "all_products") {
                            // $productArray = getProducts($currentCategory, $conn);
                            $totalProducts = $conn->query("SELECT COUNT(*) FROM product")->fetch_row()[0];
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                            $limit = 5;

                            if ($statement = $conn->prepare("SELECT * FROM product ORDER BY itemname LIMIT ?,?")) {
                                $calculatePage = ($page - 1) * $limit;
                                $statement->bind_param("ii", $calculatePage, $limit);
                                $statement->execute();
                                $result = $statement->get_result();
                                $statement->close();
                            }

                            $row = $result->fetch_all(MYSQLI_ASSOC);
                            $productCount = count($row);

                            for ($i = 0; $i < $productCount; $i++) {
                                generateItemCard($row[$i]['image'],$row[$i]['itemname'], $row[$i]['price'], $row[$i]['idproduct'], $row[$i]['sellerID']);
                                $itemCode = $row[$i]['idproduct'];
                            }
                        } else {
                            // $productArray = getProducts($currentCategory, $conn);
                            $totalProducts = $conn->query("SELECT COUNT(*) FROM product WHERE category = '$currentCategory'")->fetch_row()[0];
                            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
                            $limit = 5;

                            if ($statement = $conn->prepare("SELECT * FROM product WHERE category = ? ORDER BY itemname LIMIT ?,?")) {
                                $calculatePage = ($page - 1) * $limit;
                                $statement->bind_param("sii", $currentCategory, $calculatePage, $limit);
                                $statement->execute();
                                $result = $statement->get_result();
                                $statement->close();
                            }

                            $row = $result->fetch_all(MYSQLI_ASSOC);
                            $productCount = count($row);

                            for ($i = 0; $i < $productCount; $i++) {
                                generateItemCard($row[$i]['image'],$row[$i]['itemname'], $row[$i]['price'], $row[$i]['idproduct'], $row[$i]['sellerID']);
                                $itemCode = $row[$i]['idproduct'];
                            }
                        }
                        ?>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination flex-m flex-w p-t-26">
                        <?php if (ceil($totalProducts / $limit) > 0): ?>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="prev"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo $page - 1 ?>">Prev</a></li>
                                <?php endif; ?>

                                <?php if ($page > 3): ?>
                                    <li class="start"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=1">1</a></li>
                                    <li class="dots">...</li>
                                <?php endif; ?>

                                <?php if ($page - 2 > 0): ?><li class="page"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo $page - 2 ?>"><?php echo $page - 2 ?></a></li><?php endif; ?>
                                <?php if ($page - 1 > 0): ?><li class="page"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo $page - 1 ?>"><?php echo $page - 1 ?></a></li><?php endif; ?>

                                <li class="currentpage"><a class="item-pagination flex-c-m trans-0-4 active-pagination" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo $page ?>"><?php echo $page ?></a></li>

                                <?php if ($page + 1 < ceil($totalProducts / $limit) + 1): ?><li class="page"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo $page + 1 ?>"><?php echo $page + 1 ?></a></li><?php endif; ?>
                                <?php if ($page + 2 < ceil($totalProducts / $limit) + 1): ?><li class="page"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo $page + 2 ?>"><?php echo $page + 2 ?></a></li><?php endif; ?>

                                <?php if ($page < ceil($totalProducts / $limit) - 2): ?>
                                    <li class="dots">...</li>
                                    <li class="end"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo ceil($totalProducts / $limit) ?>"><?php echo ceil($totalProducts / $limit) ?></a></li>
                                <?php endif; ?>

                                <?php if ($page < ceil($totalProducts / $limit)): ?>
                                    <li class="next"><a class="item-pagination flex-c-m trans-0-4" href="product.php?category=<?php echo $currentCategory ?>&page=<?php echo $page + 1 ?>">Next</a></li>
                                <?php endif; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

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

<!-- Container Selection -->
<div id="dropDownSelect1"></div>
<div id="dropDownSelect2"></div>

<script type="text/javascript">
    $(".selection-1").select2({
        minimumResultsForSearch: 20,
        dropdownParent: $('#dropDownSelect1')
    });

    $(".selection-2").select2({
        minimumResultsForSearch: 20,
        dropdownParent: $('#dropDownSelect2')
    });
</script>
<!--===============================================================================================-->
<script type="text/javascript" src="vendor/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="vendor/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript">
    $('.block2-btn-addcart').each(function () {
        var nameProduct = $(this).parent().parent().parent().find('.block2-name').php();
        $(this).on('click', function () {
            swal(nameProduct, "is added to cart !", "success");
        });
    });

    $('.block2-btn-addwishlist').each(function () {
        var nameProduct = $(this).parent().parent().parent().find('.block2-name').php();
        $(this).on('click', function () {
            swal(nameProduct, "is added to wishlist !", "success");
        });
    });
</script>

<!--===============================================================================================-->
<script type="text/javascript" src="vendor/noui/nouislider.min.js"></script>
<script type="text/javascript">
    /*[ No ui ]
     ===========================================================*/
    var filterBar = document.getElementById('filter-bar');

    noUiSlider.create(filterBar, {
        start: [50, 200],
        connect: true,
        range: {
            'min': 50,
            'max': 200
        }
    });

    var skipValues = [
        document.getElementById('value-lower'),
        document.getElementById('value-upper')
    ];

    filterBar.noUiSlider.on('update', function (values, handle) {
        skipValues[handle].innerHTML = Math.round(values[handle]);
    });
</script>
<!--===============================================================================================-->

</body>
</html>
