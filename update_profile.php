<?php ob_start(); // Start output buffering

session_start();

$redirectToProfile = false;

if (isset($_POST['submit'])) {
    $lname = empty($_POST["lname"]) ? $_SESSION['lname'] : $_POST["lname"];
    $fname = empty($_POST["fname"]) ? $_SESSION['fname'] : $_POST["fname"];
    $address = empty($_POST["address"]) ? $_SESSION['address'] : $_POST['address'];

    $config = parse_ini_file('../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else {
        $sql = "UPDATE Ecomm.User SET firstname = '$fname', lastname = '$lname', homeAddress = '$address' WHERE idUser = " . $_SESSION['UID'];
        if ($conn->multi_query($sql)) {
            $redirectToProfile = true; // Set the flag for redirection

            // Loop through all result sets
            do {
                $result = $conn->store_result();

                // Check if the current result set has rows and is not the result of an UPDATE, DELETE, etc.
                if ($result && $result->num_rows > 0) {
                    $redirectToProfile = false; // Reset the flag if there are extra result sets (potential injection)

                    while($row = $result->fetch_assoc()) {
                        foreach($row as $columnName => $value) {
                            echo $columnName . ": " . $value . " - ";
                        }
                        echo "<br>";
                    }
                    $result->free();
                }

            } while ($conn->more_results() && $conn->next_result());
        } else {
            $errorMsg = "Execute failed: (" . $conn->errno . ") " . $conn->error;
            $success = false;
        }
    }
    $conn->close();
}

$_SESSION['fname'] = $fname;
$_SESSION['lname'] = $lname;

// If update was successful and no additional query results were detected
if ($redirectToProfile) {
    header('Location: profile.php');
    exit; // Ensure no further code is executed
}

ob_end_flush(); // End output buffering and send output
?>
