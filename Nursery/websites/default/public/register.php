<?php
session_start();
require 'db.php';
require 'public_header.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['firstname']; 
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate parent ID
    $result_parents = $pdo->query("SELECT * FROM parents ORDER BY id DESC");
    $row_parents = $result_parents->fetchObject();
    $count_parents = $result_parents->rowCount();
    if ($count_parents <= 0) {
        $parentid = "prt1";
    } else {
        $parentid = "prt" . ($row_parents->id + 1);
    }

    // Insert parent bio data into 'parents' table
    $insert_parent = $pdo->prepare("INSERT INTO parents (firstname, lastname, email, phone, parentid) VALUES (:firstname, :lastname, :email, :phone, :parentid)");
    $insert_parent->execute([
        'firstname' => $first_name,
        'lastname' => $last_name,
        'email' => $email,
        'phone' => $phone,
        'parentid' => $parentid
    ]);

    // Insert parent login details into 'users' table
    $insert_users = $pdo->prepare("INSERT INTO users (username, password, user_type, userid) VALUES (:username, :password, :user_type, :userid)");
    $insert_users->execute([
        'username' => $username,
        'password' => $hashedPassword,
        'user_type' => 'parent',
        'userid' => $parentid
    ]);

    echo 'User ' . htmlspecialchars($first_name) . ' is successfully registered as a parent. <a href="login.php">Log in here</a>.';
} else {
    // HTML form moved inside PHP for easier management of the 'require' calls
    include('templates/register_form.html.php'); 
}

require 'footer.php';
?>
