<?php
ob_start();
session_start();
require '../db.php';
require_once '../header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
    header('Location: ../login.php');
    exit;
}

$user = null;

// Get current user details
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE userid = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Could not fetch user details: " . $e->getMessage());
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user details
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, password = :password WHERE userid = :user_id");
        $stmt->execute([
            'username' => $_POST['username'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT), // Hash the new password
            'user_id' => $_SESSION['user_id']
        ]);
        // Redirect to the same page with a success message
        header('Location: profile.php?success=1');
        exit;
    } catch (PDOException $e) {
        // Handle any errors during the database update
        die("Could not update user details: " . $e->getMessage());
    }
}
?>

<div id="wrapper">
    <!-- Include the parent-specific sidebar menu -->
    <?php include ('parentsidemenu.php'); ?>
    <div id="main-content">
        <div class="container">
            <div class="form-section">
                <h2>Update Profile</h2>
                <!-- Form to update profile details -->
                <form id="profileForm" action="profile.php" method="post">
                    <div class="form-field">
                        <label for="username">Username:</label>
                        <!-- Display the current username in the input field -->
                        <input type="text" id="username" name="username"
                            value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>
                    <div class="form-field">
                        <label for="password">Password:</label>
                        <!-- Input field for the new password -->
                        <input type="password" id="password" name="password" required>
                    </div>
                    <!-- Button to submit the profile update form -->
                    <button type="submit" class="submit-btn">Update Profile</button>
                </form>
                <!-- Display a success message if the profile was updated -->
                <?php if (isset($_GET['success'])): ?>
                    <p class="success-message">Profile updated successfully!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>

</html>