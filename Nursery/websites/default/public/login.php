<?php
ob_start();
session_start();
require_once 'db.php';
require_once 'public_header.php';

$error_message = ''; // Initialize error message

// Hardcoded admin credentials
$hardcoded_admin_username = 'admin123';
$hardcoded_admin_password = 'adminpass';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    // SQL to check the existence of the user with the given username, password, and user type
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND user_type = :user_type");
    $stmt->execute(['username' => $username, 'user_type' => $user_type]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // User found and password matches
        $_SESSION['user_id'] = $user['userid'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['username'] = $user['username'];
        // Redirect based on user type
        switch ($user['user_type']) {
            case 'parent':
                header('Location: parent/parent_dashboard.php');
                break;
            case 'admin':
                header('Location: admin/admin_dashboard.php');
                break;
            case 'teacher':
                header('Location: teacher/teacher_dashboard.php');
                break;
        }
        exit;
    } else {
        // If no user found in users table, check hardcoded admin credentials
        if ($user_type == 'admin' && $username == $hardcoded_admin_username && $password == $hardcoded_admin_password) {
            
            $_SESSION['user_id'] = 'admin'; 
            $_SESSION['user_type'] = 'admin';
            header('Location: admin/admin_dashboard.php');
            exit;
        } else {
            $error_message = 'Invalid Login Credentials';
        }
    }
}

include('templates/login_form.html.php');
require 'footer.php';

