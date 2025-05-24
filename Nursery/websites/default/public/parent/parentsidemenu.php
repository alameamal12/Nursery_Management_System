<?php
// Parent side bar page
require '../header.php';
?>
<div id="sidebar">
<div id="logo">
<img src="../images/logo.jpg" alt="Your Logo" class="sidebar-logo">
</div>

<nav class='mt-2'>
<ul class='nav nav-pills nav-sidebar flex-column' data-widget='treeview' role='menu' data-accordion='false'>
<li class='nav-item'><a class='nav-link active' href="parent_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
<li class='nav-item mt-0'><a class='nav-link' href="message.php"><i class="fas fa-user-graduate"></i> Message</a></li>
<li class='nav-item mt-0'><a class='nav-link' href="history.php"><i class="fas fa-chalkboard"></i> History</a></li>
<li class='nav-item mt-0'><a class='nav-link' href="profile.php"><i class="fas fa-chalkboard"></i> Profile</a></li>
<li class='nav-item mt-0'><a class='nav-link' href="view_notS.php?memid=parent"><i class="fas fa-bullhorn"></i> Noticeboard</a></li>





</ul>
</nav>
</div>
