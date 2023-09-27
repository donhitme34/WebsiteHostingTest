<?php
if (isset($_SESSION['user']) && $_SESSION["user"] == true) {
    global $email, $address, $wallet;
    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);
    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        // Prepare the statement:
        $stmt = $conn->prepare("SELECT * FROM Ecomm.User WHERE
            idUser=?");
        // Bind & execute the query statement:
        $stmt->bind_param("i", $_SESSION['UID']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Note that email field is unique, so should only have
            // one row in the result set.
            $row = $result->fetch_assoc();
            $wallet = $row['walletCredit'];
            $ewallet = number_format($wallet, 2);
        } else {
            $errorMsg = "Email not found or password doesn't match...";
            $success = false;
        }
        $stmt->close();
    }
    $conn->close();
}

?>
<header>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand" href="index.php">
        <img src="images/icons/logo.png" alt="IMG-LOGO">
      </a>

      <!-- Toggler/collapsible Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" style='color:black' href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style='color:black' href="about.php">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" style='color:black' href="contact.php">Contact</a>
          </li>
          <?php
          if (isset($_SESSION['user']) && $_SESSION["user"] == true) {
            echo "<li class='nav-item'><a class='nav-link' style='color:black' href='ewallet_page.php'>E-Wallet: $" . $ewallet . "</a></li>";
            echo "<li class='nav-item'><a class='nav-link' style='color:black' href='itemRegister.php'>Register Items</a></li>";
            echo "<li class='nav-item'><a class='nav-link' style='color:black' href='buy_Items.php'>Bought Items</a></li>";
            echo "<li class='nav-item'><a class='nav-link' style='color:black' href='sold_Items.php'>Sold Items</a></li>";
            echo "<li class='nav-item'><a class='nav-link' style='color:black' href='profile.php'>Profile</a></li>";
            echo "<li class='nav-item'><a class='nav-link' style='color:black' href='logout.php'>Logout</a></li>";
            
          } else {
            echo "<li class='nav-item'><a class='nav-link' style='color:black' href='login.php'>Login</a></li>";
          }
          ?>
        </ul>

        <!-- Header Icon -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="cart.php" class="nav-link d-flex align-items-center">
              <img src="images/icons/icon-header-02.png" alt="ICON" class="header-icon1">
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<?php
    if(isset($_SESSION['admin']) && $_SESSION["admin"] == true ){
        include 'admin_sidebar.php';
    }
?>
</header>


