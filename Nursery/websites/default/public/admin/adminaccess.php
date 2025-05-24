<?php
if (!isset($_SESSION['admin_loggedin'])) {
    // If this session variable is not set, the user is not an admin
    header("Location: login.php");
    exit; // Prevent further execution of the script
}
?>