<?php
// admin dashboard page
ob_start();
session_start(); // Start the session
require '../db.php';
require '../header.php';
?>

<body>
    <div id="wrapper">
        <?php include ('sidemenu.php'); ?>
        <main id="main-content">
            <?php include ("../navbar.php"); ?>
            <section id="tiles">

                <div class="tile"><a href="classlist.php"><i class="fas fa-chalkboard"></i> Class</a></div>
                <div class="tile"><a href="studentlist.php"><i class="fas fa-user-graduate"></i> Students</a></div>
                <div class="tile"><a href="teacherslist.php"><i class="fas fa-chalkboard-teacher"></i> Teachers</a>
                </div>
                <div class="tile"><a href="parentlist.php"><i class="fas fa-users"></i> Parents</a></div>
                <div class="tile"><a href="subjectlist.php"><i class="fas fa-book"></i> Subject</a></div>
                <div class="tile"><a href="noticeboard.php"><i class="fas fa-bullhorn"></i> Noticeboard</a></div>
                <div class="tile"><a href="inbox.php"><i class="fas fa-envelope"></i> Inbox</a></div>
                <div class="tile"><a href="rolelist.php"><i class="fas fa-cogs"></i>User Roles</a></div>
                <div class="tile"><a href="inquiries.php"><i class="fas fa-phone"></i>Inquiries</a></div>
                <div class="tile"><a href="attendance.php"><i class="fas fa-edit"></i>Attendance</a></div>
                <div class="tile"><a href="events.php"><i class="fas fa-calendar"></i>Events</a></div>
            </section>
        </main>
    </div>
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</body>

</html>