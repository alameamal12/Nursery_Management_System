<?php
session_start(); // Start the session 

// Unset specific session variables first
unset($_SESSION['user_id']);

// Destroy all session data
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit;
?>