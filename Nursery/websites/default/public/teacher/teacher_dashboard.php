<?php
//teacher dashboard page
ob_start(); // Start output buffering
session_start(); // Start the session

// Include database connection and header files
require_once '../db.php';
require_once '../header.php';
?>

<body>
<div id="wrapper">
<?php include('teachersidemenu.php'); ?> 

<main id="main-content">
<?php include("../navbar.php"); ?>
<!-- Section containing clickable tiles linking to various teacher features -->
<section id="tiles">
<div class="tile"><a href="teacher_dashboard.php"><i class="fas fa-chalkboard"></i> Dashboard</a></div>
<div class="tile"><a href="Inbox.php"><i class="fas fa-user-graduate"></i> Inbox</a></div>
<div class="tile"><a href="view_notS.php?memid=teacher"><i class="fas fa-bullhorn"></i>Noticeboard</a></div>
<div class="tile"><a href="profile.php"><i class="fas fa-chalkboard"></i> Profile</a></div>
<div class="tile"><a href="open_marksheet.php"><i class="fas fa-edit"></i>Marks</a></div>
<div class="tile"><a href="teacher_classes.php"><i class="fas fa-user-check"></i> Classes</a></div>


</section>
</main>
</div>
<!-- Include JavaScript files for jQuery and Bootstrap -->
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
