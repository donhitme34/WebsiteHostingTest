
<!DOCTYPE html>
<html lang="en">
<?php 
    include 'header.php';
    if (isset($_SESSION['user']) && $_SESSION["user"] == true) {
        global $email,$address, $wallet;
        $config = parse_ini_file('../private/db-config.ini');
        $conn = new mysqli($config['servername'], $config['username'],
                $config['password'], $config['dbname']);
        // Check connection
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            $success = false;
        } 
        else {
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
                $fname = $row["fname"];
                $lname = $row["lname"];
                $email = $row['emailaddress'];
                $wallet = $row['walletCredit'];
                $address = $row['homeAddress'];
            } 
            else {
                $errorMsg = "Email not found or password doesn't match...";
                $success = false;
            }
            $stmt->close();
        }
        $conn->close();
        $_SESSION['address']= $address;
    }   
    else{
        header("Location: login.php"); // redirect to login page if user is not logged in
        exit();
    }
?>
<body class="animsition">
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <script>
        function toggleEditProfile() {
            const displayFields = document.querySelectorAll(".editable-field");
            const inputFields = document.querySelectorAll(".editable-input");
            const editButton = document.getElementById("editButton");
            const saveButton = document.getElementById("saveButton");

            displayFields.forEach(field => field.classList.toggle("d-none"));
            inputFields.forEach(input => input.classList.toggle("d-none"));
            editButton.classList.toggle("d-none");
            saveButton.classList.toggle("d-none");
        }
        function showEditPasswordForm() {
            document.getElementById("editPasswordForm").style.display = "block";
        }

        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <!-- Header -->
    <?php 
        include 'nav.php';
    ?>
        <main class='container'>
            <?php
                if (isset($_GET['password_updated']) && $_GET['password_updated'] == 1) {
                    echo "<div class='alert alert-success'>Password updated successfully.</div>";
                } elseif (isset($_GET['error_message'])) {
                    echo "<div class='alert alert-danger'>" . htmlspecialchars(urldecode($_GET['error_message'])) . "</div>";
                }
            ?>
        <h1>Profile page</h1>
        <form id="profileUpdateForm" method="POST" action="update_profile.php">
            <div id="profileInfo">
                <p>First Name: <span class="editable-field"><?= $_SESSION['fname'] ?></span><input type="text" name="fname" aria-label="Your first name" pattern="[a-zA-Z][a-zA-Z ]+" class="editable-input d-none" value="<?= $_SESSION['fname'] ?>"></p>
                <p>Last Name: <span class="editable-field"><?= $_SESSION['lname'] ?></span><input type="text" name="lname" aria-label="Your last name" pattern="[a-zA-Z][a-zA-Z ]+" class="editable-input d-none" value="<?= $_SESSION['lname'] ?>"></p>
                <p>Email: <span><?= $email ?></span></p>
                <p>Username: <span><?= $_SESSION['username'] ?></span></p>
                <p>Wallet Credit remaining: <span><?= $wallet ?></span></p>
                <p>Address: <span class="editable-field"><?= $address ?></span><input type="text" name="address" aria-label="Your address" pattern="[a-zA-Z][a-zA-Z ]+" class="editable-input d-none" value="<?= $address ?>"></p>
            </div>
        </form>
        <div id="editPasswordForm" class="container" style="display: none;">
            <h2>Edit Password</h2>
            <form method="POST" action="change_password.php">
                <div class="mb-3 position-relative">
                    <label for="currentPassword" class="form-label">Current Password</label>
                    <input type="password" name="currentPassword" class="form-control password-input" id="currentPassword" required minlength="8">
                    <i class="bi bi-eye-fill eye-icon" onclick="togglePasswordVisibility('currentPassword')"></i>
                </div>
                <div class="mb-3 position-relative">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" name="newPassword" class="form-control password-input" id="newPassword" required minlength="8">
                    <i class="bi bi-eye-fill eye-icon" onclick="togglePasswordVisibility('newPassword')"></i>
                </div>
                <div class="mb-3 position-relative">
                    <label for="confirmNewPassword" class="form-label">Confirm New Password</label>
                    <input type="password" name="confirmNewPassword" class="form-control password-input" id="confirmNewPassword" required minlength="8">
                    <i class="bi bi-eye-fill eye-icon" onclick="togglePasswordVisibility('confirmNewPassword')"></i>
                </div>
                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
        

        <br>
        <div class="container">
            <div class="row">
                <div class="col">
                    <button id="editButton" class="btn btn-primary" onclick="toggleEditProfile()">Edit Profile</button>
                    <a href="#" class="btn btn-info" onclick="showEditPasswordForm()">Change password</a>
                    <button id="saveButton" class="btn btn-success d-none" form="profileUpdateForm" type="submit" name="submit">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">Delete Account</button>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        </div>
        
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title" id="deleteConfirmModalLabel">Confirm Deletion</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete your account?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <form method="POST" action="delete_account.php">
                            <button type="submit" name="submit" class="btn btn-danger">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </main>
        <br>
    <?php 
        include 'footer.php';
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        var onloadCallback = function() {
            alert("grecaptcha is ready!");
        };
        
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>