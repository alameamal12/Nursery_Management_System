<?php
//parent dashboard page
ob_start();
session_start(); // Start the session
require_once '../db.php';
require_once '../header.php';
?>
<body>
<div id="wrapper">
<?php include('parentsidemenu.php'); ?>
<main id="main-content">
<!-- navbar starts-->
<?php include("../navbar.php"); ?>
<!-- Nav bar ends -->
<section id="tiles">
<div class="tile"><a href="parent_dashboard.php"><i class="fas fa-chalkboard"></i> Home</a></div>
<div class="tile"><a href="message.php"><i class="fas fa-user-graduate"></i> Message</a></div>
<div class="tile"><a href="history.php"><i class="fas fa-chalkboard-teacher"></i> History</a></div>
<div class="tile"><a href="profile.php"><i class="fas fa-chalkboard-teacher"></i> Profile</a></div>
<div class="tile"><a href="view_notS.php?memid=parent"><i class="fas fa-bullhorn"></i> Noticeboard</a></div>
</section>
</main>
</div>
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
