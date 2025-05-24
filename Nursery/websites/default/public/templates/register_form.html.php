<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> Register - Little Learners</title>
<link rel="stylesheet" href="js/admin_styles.css">
</head>
<body>

<div class="form-container">
  <form class="form-box" action="register.php" method="POST">
    <h2 class="form-title">Parent Register</h2>
    <div class="form-icon-container">
        <img src="images/users-icon.jpg" alt="User Icon" class="form-icon">
    </div>
    <div class="input-group">
      <input type="text" name="firstname" placeholder="First Name" required> 
      <input type="text" name="lastname" placeholder="Last Name" required> 
      <input type="text" name="phone" placeholder="Phone Number" required> 
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="form-button" name="submit">Register</button> 
      <a href="../login.php" class="form-title">Already a member, Click to login</a>

    </div>
  </form>
</div>

</body>
</html>
