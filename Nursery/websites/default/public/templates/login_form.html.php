<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Little Learners</title>
<link rel="stylesheet" href="js/admin_styles.css">
</head>
<body>

<div class="form-container">
    <form class="form-box" action="login.php" method="POST">
        <h2 class="form-title">Login</h2>
        <div class="form-icon-container">
            <img src="images/users-icon.jpg" alt="User Icon" class="form-icon">
        </div>
        <div class="input-group">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="user_type" required>
                <option value="">Select User Type</option>
                <option value="parent">Parent</option>
                <option value="admin">Admin</option>
                <option value="teacher">Teacher</option>
            </select>
            <button type="submit" value="submit" class="form-button" name="submit">Login</button>
            <?php if (isset($error_message)): ?>
                <p><?php echo $error_message; ?></p>
            <?php endif; ?>
            <a href="../register.php" class="form-title">No yet a member, Click to register</a>
        </div>
    </form>
</div>

</body>
</html>