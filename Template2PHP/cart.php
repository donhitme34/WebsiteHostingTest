<!DOCTYPE html>
<html lang="en">
    <?php
    include 'header.php'
    ?>
    <body class="animsition">
        <main>

            <!-- Header -->
            <?php
            include 'nav.php';
            ?>
            <?php
            session_start();
            $config = parse_ini_file('../../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);

            if ($conn->connect_error) {
                die('Connection Failed: ' . $conn->connect_error);
            }

//            ini_set('display_errors', 1);
//            error_reporting(E_ALL);

            if (!empty($_GET["action"])) {
                switch ($_GET["action"]) {
                    case "update":
                        if (!empty($_POST["quantity"])) {
                            $productByCode = $conn->query("SELECT * FROM product WHERE idproduct=" . $_GET["idproduct"] . "");
                            if ($productByCode) {
                                $product = $productByCode->fetch_assoc();
                                $itemArray = array($product["idproduct"] => array
                                        ('itemname' => $product["itemname"],
                                        'idproduct' => $product["idproduct"],
                                        'quantity' => $_POST["quantity"],
                                        'price' => $product["price"],
                                        'image' => $product["image"]));

                                if (!empty($_SESSION["cart_item"])) {
                                    if (in_array($product["idproduct"], array_keys($_SESSION["cart_item"]))) {
                                        foreach ($_SESSION["cart_item"] as $k => $v) {
                                            if ($product["idproduct"] == $k) {
                                                if ($_POST['action'] == 'minus') {
                                                    $_SESSION["cart_item"][$k]["quantity"] -= 1;
                                                    if ($_SESSION["cart_item"][$k]["quantity"] == 0) {
                                                        unset($_SESSION["cart_item"][$k]);
                                                        unset($_SESSION["cart_item"]);
                                                    }

                                                    break;
                                                } else if ($_POST['action'] == 'plus') {
                                                    $_SESSION["cart_item"][$k]["quantity"] += 1;
                                                    break;
                                                }
                                            }
                                        }
                                    } else {
                                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                                    }
                                } else {
                                    $_SESSION["cart_item"] = $itemArray;
                                    unset($_SESSION["discount"]);
                                }
                            } else {
                                echo "Error: " . $conn->error;
                            }
                        }
                        break;
                    case "remove":
                        if (!empty($_SESSION["cart_item"])) {
                            foreach ($_SESSION["cart_item"] as $k => $v) {
                                if ($_GET["idproduct"] == $k)
                                    unset($_SESSION["cart_item"][$k]);
                                if (empty($_SESSION["cart_item"]))
                                    unset($_SESSION["cart_item"]);
                            }
                        }
                        break;
                    case "empty":
                        unset($_SESSION["cart_item"]);
                        break;
                    case "coupon":
                        // Prepare the statement:
                        $coupon = $_POST['coupon'];
                        $stmt = $conn->prepare("SELECT * FROM coupon WHERE coupon_code=?");
                        // Bind & execute the query statement:
                        $stmt->bind_param("s", $coupon);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            // Note that email field is unique, so should only have
                            // one row in the result set.
                            $row = $result->fetch_assoc();
                            $discountpercent = $row["discount"];
                            $discount = $row["discount"] / 100;
                            $_SESSION["discount"] = $discount;
//                        $total = $total_price * $discount;
//                        $_SESSION["total"] = $total;
//                        $total_price -= $total;
//                        $total_price = $_SESSION['totalPrice'];
                            echo "<script>alert('Coupon code applied! Enjoy $discountpercent% off your total!');</script>";
                        } else {
                            echo "<script>alert('Code is invalid!');</script>";
                        }
//                    $total_price = $total_price * $discount;
                        $stmt->close();
                        break;
                }
            }
            ?>

            <!-- Title Page -->
            <section class="bg-title-page p-t-40 p-b-50 flex-col-c-m" style="background-image: url(images/bg.jpg);">
                <h2 class="l-text2 t-center" style="color:black">
                    Cart
                </h2>
            </section>

            <!-- Cart -->
            <section class="cart bgwhite p-t-70 p-b-100">
                <div class="container">
                    <?php
                    if (isset($_SESSION["cart_item"])) {
                        $total_quantity = 0;
                        $total_price = 0;
                        ?>


                        <h1>Your Cart</h1>
                        <!-- Cart item -->
                        <div class="container-table-cart pos-relative">
                            <div class="wrap-table-shopping-cart bgwhite">
                                <table class="table-shopping-cart">
                                    <tr class="table-head">
                                        <th style="color: white"class="column-1">Image</th>
                                        <th style="color: white" class="column-2">Product</th>
                                        <th style="color: white"class="column-3">Price</th>
                                        <th style="color: white"class="column-4 p-l-70">Quantity</th>
                                        <th style="color: white"class="column-5">Total</th>
                                    </tr>
                                    <?php
                                    foreach ($_SESSION["cart_item"] as $item) {
                                        $item_price = $item["quantity"] * $item["price"];
                                        ?>
                                        <tr class="table-row">
                                            <td class="column-1">
                                                <div class="cart-img-product b-rad-4 o-f-hidden">
                                                    <img alt="image" src="<?php echo $item["image"]; ?>"/>
                                                </div>
                                            </td>
                                            <td class="column-2"><?php echo $item["itemname"]; ?></td>
                                            <td class="column-3"><?php echo "$" . number_format($item["price"], 2); ?></td>
                                            <td class="column-4">
                                                <form method="post" action="cart.php?action=update&idproduct=<?php echo $item["idproduct"]; ?>">
                                                    <div class="flex-w bo5 of-hidden w-size17 mx-auto px-30">
                                                        <button type="submit" name="action" value="minus" class="color1 flex-c-m size7 bg8 eff2" aria-label="minus">
                                                            <i class="fs-12 fa fa-minus" aria-hidden="true"></i>
                                                        </button>

                                                        <input aria-label="quantity" class="size8 m-text18 t-center num-product" type="number" name="quantity" value="<?php echo $item["quantity"]; ?>">

                                                        <button type="submit" name="action" value="plus" class="color1 flex-c-m size7 bg8 eff2" aria-label="plus">
                                                            <i class="fs-12 fa fa-plus" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </form> 
                                                <div>
                                                    <a style="color: black" href="cart.php?action=remove&idproduct=<?php echo $item["idproduct"]; ?>" class="btnRemoveAction">Remove Item</a>
                                                </div>
                                            </td>
                                            <td class="column-5"><?php echo "$" . number_format($item_price, 2); ?></td>
                                        </tr>
                                        <?php
                                        $total_quantity += $item["quantity"];
                                        $total_price += ($item["price"] * $item["quantity"]);
                                        if (!empty($_SESSION["discount"])) {
                                            $total = $total_price * $_SESSION["discount"];
                                            $total_price -= $total;
                                        }


                                        $_SESSION['quantity'] = $item["quantity"];
                                        $_SESSION['idProduct'] = $item["idproduct"];
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <h1>
                            Your cart!
                        </h1>
                        <div  style="color: black" class="no-records">Your Cart is Empty</div>
                        <?php
                        $total_price = 0.0;
                    }
                    ?>

                    <form method="post" action="cart.php?action=coupon">
                        <div class="flex-w flex-sb-m p-t-25 p-b-25 bo8 p-l-35 p-r-60 p-lr-15-sm">
                            <div class="flex-w flex-m w-full-sm">
                                <div class="size11 bo4 m-r-10">
                                    <input id="coupon" class="sizefull s-text7 p-l-22 p-r-22" type="text" name="coupon" placeholder="Coupon Code" required>
                                </div>
                            </div>

                            <div class="size10 trans-0-4 m-t-10 m-b-10">
                                <!-- Button -->
                                <button type="submit" class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
                                    Apply Coupon
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Total -->
                    <div class="bo9 w-size18 p-l-40 p-r-40 p-t-30 p-b-38 m-t-30 m-r-0 m-l-auto p-lr-15-sm">
                        <h2 class="m-text20 p-b-24">
                            Cart Totals
                        </h2>
                        <!--
                                              
                                            <div class="flex-w flex-sb-m p-b-12">
                                                <span class="s-text18 w-size19 w-full-sm">
                                                    Subtotal:
                                                </span>
                        
                                                <span class="m-text21 w-size20 w-full-sm">
                                                    <strong>
                        <?php echo "$ " . number_format($total_price, 2); ?>
                                                    </strong>
                                                </span>
                                            </div>-->

                        <!--                      
                                            <div class="flex-w flex-sb bo10 p-t-15 p-b-20">
                                                <span class="s-text18 w-size19 w-full-sm">
                                                    Shipping:
                                                </span>
                        
                                                <div class="w-size20 w-full-sm">
                                                    <p class="s-text8 p-b-23">
                                                        There are no shipping methods available. Please double check your address, or contact us if you need any help.
                                                    </p>
                        
                                                    <span class="s-text19">
                                                        Calculate Shipping
                                                    </span>
                        
                                                    <div class="rs2-select2 rs3-select2 rs4-select2 bo4 of-hidden w-size21 m-t-8 m-b-12">
                                                        <select class="selection-2" name="country">
                                                            <option>Select a country...</option>
                                                            <option>US</option>
                                                            <option>UK</option>
                                                            <option>Japan</option>
                                                        </select>
                                                    </div>
                        
                                                    <div class="size13 bo4 m-b-12">
                                                        <input class="sizefull s-text7 p-l-15 p-r-15" type="text" name="state" placeholder="State /  country">
                                                    </div>
                        
                                                    <div class="size13 bo4 m-b-22">
                                                        <input class="sizefull s-text7 p-l-15 p-r-15" type="text" name="postcode" placeholder="Postcode / Zip">
                                                    </div>
                        
                                                    <div class="size14 trans-0-4 m-b-10">
                                                         Button 
                                                        <button class="flex-c-m sizefull bg1 bo-rad-23 hov1 s-text1 trans-0-4">
                                                            Update Totals
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>-->

                        <!--  -->
                        <div class="flex-w flex-sb-m p-t-26 p-b-30">
                            <span class="m-text22 w-size19 w-full-sm">
                                Total:
                            </span>
                            <form action="checkout_ewallet.php" method="post">       
                                <span style="color: black" class="m-text21 w-size20 w-full-sm">
                                    <?php
                                    $_SESSION['totalPrice'] = $total_price;
                                    echo "$ " . number_format($total_price, 2);
                                    ?>
                                </span>
                                <?php
                                retrievewalletCreds();
                                ?>
                                <button style="color: black" class="btn btn-primary" type="submit" > Checkout with E-Wallet </button>
                            </form>
                        </div>
                                             <form action="checkout_stripe.php" method="post">  
                            <!-- Button -->
                            <button type = "submit" class="btn btn-dark"> Checkout with Stripe </button>
                        </form>

                    </div>
                </div>
            </section>



            <!-- Footer -->
            <?php
            include 'footer.php';
            ?>
            <?php

            function retrievewalletCreds() {
                $username = $_SESSION['username'];
                $ewalletCredit = 0.0;

                // Create database connection.
                $config = parse_ini_file('../../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname'], 3306);
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    // Prepare the statement:
                    $stmt = $conn->prepare("SELECT walletCredit FROM User WHERE username=?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $stmt->bind_result($ewalletCredit);
                    // fetch result
                    $stmt->fetch();
                    echo '<br>Available E-Wallet Balance: $' . number_format($ewalletCredit, 2);
                    $_SESSION['ewalletCreds'] = $ewalletCredit;
                    if ($_SESSION['totalPrice'] > $ewalletCredit) {
                        echo '<br>You do not have enough balance! Please go Top-Up!<br>';
                    }
                    $stmt->close();
                }
                $conn->close();
            }
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
            <script>
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
            <script src="js/main.js"></script>
        </main>

    </body>
</html>
