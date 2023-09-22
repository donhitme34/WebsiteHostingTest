<!DOCTYPE html>
<html lang="en">
    <?php
    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);
// Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        $stmt = $conn->prepare("SELECT * FROM Ecomm.review WHERE
            idproduct=1");
        // Bind & execute the query statement:
        $stmt->execute();
//        $row = mysqli_fetch_array($stmt);
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $title = $row["title"];
                $rating = $row["rating"];
                $text = $row["text"];
            }
//            $row = $result->fetch_assoc();
        }
        $stmt->close();
    }
    $conn->close();
    ?>
    <?php
    include 'header.php';
    ?>
    <body class="animsition">

        <!-- Header -->
        <?php
        include 'nav.php';
        ?>

        <!-- Title Page -->
        <section class="bg-title-page p-t-40 p-b-50 flex-col-c-m" style="background-image: url(images/heading-pages-05.jpg);">
            <h2 class="l-text2 t-center">
                Blog
            </h2>
        </section>

        <!-- Cart -->
        <section class="cart bgwhite p-t-70 p-b-100">
            <div class="container">
                <!-- Cart item -->

                <div class="container-table-cart pos-relative">
                    <div class="wrap-table-shopping-cart bgwhite">
                        <table class="table-shopping-cart">

                            <tr class="table-head">
                                <th class="column-1"></th>
                                <th class="column-2">Title</th>
                                <th class="column-3">Rating</th>
                                <th class="column-4 p-l-70">Text</th>
                            </tr>
                            <?php
                            foreach ($result as $row) {
                                ?>
                                <tr class="table-row">
                                    <td class="column-1">
                                    </td>
                                    <td class="column-2"><?php echo $row["title"]; ?></td>
                                    <td class="column-3"><?php echo $row["rating"]; ?></td>
                                    <td class="column-4"><?php echo $row["text"]; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
                <form class="leave-comment" method="post" >
                <h4 class="m-text25 p-b-14">
                    Leave a Review
                </h4>

                <div class="bo12 of-hidden size19 m-b-20">
                    <input class="sizefull s-text7 p-l-18 p-r-18" type="text" name="title" placeholder="Title *">
                </div>

                <div>
                    <label for="rating">Choose a rating:</label>
                    <select name="rating" id="rating" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>

                <textarea class="dis-block s-text7 size18 bo12 p-l-18 p-r-18 p-t-13 m-b-20" name="text" placeholder="Comment..."></textarea>

                <div class="w-size24">
                    <button name="submit" type="submit" class="flex-c-m size1 bg1 bo-rad-20 hov1 s-text1 trans-0-4">
                        Post Comment
                    </button>
                </div>
            </form>
            </div>
        </section>

        <?php
        $email = $errorMsg = "";
        $success = true;
        $buttonName = "";
        if(isset($_POST['submit'])){
            saveMemberToDB();
        }
        /*
         * Helper function to write the member data to the DB
         */
        function saveMemberToDB() {
            global $title, $rating, $text, $errorMsg, $success;
            $title = ($_POST["title"]);
            $rating = ($_POST["rating"]);
            $text = ($_POST["text"]);
            // Create database connection.
            $config = parse_ini_file('../private/db-config.ini');
            $conn = new mysqli($config['servername'], $config['username'],
                    $config['password'], $config['dbname']);
            // Check connection
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
            // Prepare the statement:
                $stmt = $conn->prepare("INSERT INTO Ecomm.review (title, rating,
                    text) VALUES (?, ?, ?) WHERE idproduct = 1");
            // Bind & execute the query statement:
                $stmt->bind_param("ssss", $title, $rating, $text);
                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                }
                $stmt->close();
            }
            $conn->close();
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
    </body>
</html>
