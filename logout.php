<?php
session_start();
logout();
function logout() {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}
?>