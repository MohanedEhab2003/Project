<?php


session_start();
session_destroy();

// Clear remember me cookie if exists
if (isset($_COOKIE['remember_email'])) {
    setcookie("remember_email", "", time() - 3600, "/");
}

// Redirect to login page
header("Location: ../login.php");
die();
?>