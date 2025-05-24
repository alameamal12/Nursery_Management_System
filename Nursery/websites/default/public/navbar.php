<nav class=' navbar navbar-expand navbar-dark navbar-light border-bottom'>
    <!-- Left navbar links -->
    <ul class='navbar-nav'>
        <li class='nav-item d-none d-sm-inline-block'>
            <a href='parent_dashboard.php' class='nav-link' style="color:#ffffff;font-weight: bold;">
                <h4>Welcome To
                    <?php echo "<span style='text-transform:capitalize;'>" . $_SESSION['user_type'] . "</span>"; ?>
                    Dashboard</h4>
            </a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class='navbar-nav ml-auto'>
        <!-- Notifications -->
        <?php if ($_SESSION['user_type'] == "parent" || $_SESSION['user_type'] == "teacher") { ?>
            <li class='nav-item dropdown'>
                <a class='nav-link' data-toggle='dropdown' href='#'>
                    <i class='far fa-bell fa-2x'></i>
                    <?php
                    $login_user = $_SESSION['user_type'];
                    $result_nots = $pdo->query("select * from notifications where not_group='$login_user' and status='1'");
                    $row_nots = $result_nots->fetchObject();
                    $count_nots = $result_nots->rowCount();
                    ?>
                    <span class='badge badge-danger navbar-badge'><?php echo $count_nots; ?></span>
                </a>
                <div class='dropdown-menu dropdown-menu-lg dropdown-menu-right'>
                    <a href='#' class='dropdown-item'>
                        <div class='media'>
                            <div class='media-body'>
                                <center style='font-size:18px;' class='text-lg bg-info text-bold'>
                                    <?php echo "<span style='text-transform:uppercase;'>" . $_SESSION['user_type'] . "</span>"; ?>
                                    NOTIFICATIONS</center>
                            </div>
                        </div>
                    </a>
                    <a href='#' class='dropdown-item'>
                        <!-- Message Start -->
                        <div class='media'>
                            <div class='media-body' style='height:200px;overflow-y:scroll;'>
                                <ul class='list-group'>
                                    <?php
                                    if ($count_nots > 0) {
                                        $no = 1;
                                        do {
                                            echo "
<li class='list-group-item'><b>" . $no++ . "." . $row_nots->title . "</b><br><span style='color:maroon;'>" . $row_nots->not_date . "</span></li>";
                                        } while ($row_nots = $result_nots->fetchObject());
                                    } else {
                                        echo "<li class='list-group-item'>No notifications yet.</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </a>
                    <div class='dropdown-divider'></div>
                    <a href='view_nots.php?memid=<?php echo $_SESSION['user_type']; ?>'
                        class='btn btn-sm btn-block btn-warning'>See All Notifications</a>
                </div>
            </li>
            <!--Messages Menu -->
            <li class='nav-item dropdown'>
                <a class='nav-link' data-toggle='dropdown' href='#'>
                    <i class='far fa-envelope fa-2x'></i>
                    <?php
                    $login_user = $_SESSION['user_type'];
                    $userid = $_SESSION['user_id'];
                    if ($login_user == "parent") {
                        $returnValue = " where parentid='$userid'";
                    } elseif ($login_user == "teacher") {
                        $returnValue = " where teacherid='$userid'";
                    } else {
                        $returnValue = "";
                    }
                    $result_message = $pdo->query("select * from messages $returnValue and status='1'");
                    $row_message = $result_message->fetchObject();
                    $count_message = $result_message->rowCount();
                    ?>
                    <span class='badge badge-success navbar-badge'><?php echo $count_message; ?></span>
                </a>
                <div class='dropdown-menu dropdown-menu-lg dropdown-menu-right'>
                    <a href='#' class='dropdown-item'>
                        <div class='media'>
                            <div class='media-body'>
                                <center style='font-size:18px;' class='text-lg bg-primary text-bold'>
                                    <?php echo "<span style='text-transform:uppercase;'>" . $_SESSION['user_type'] . "</span>"; ?>
                                    MESSAGES</center>
                            </div>
                        </div>
                    </a>
                    <a href='#' class='dropdown-item'>
                        <!-- Message Start -->
                        <div class='media'>
                            <div class='media-body' style='height:200px;overflow-y:scroll;'>
                                <ul class='list-group'>
                                    <?php

                                    if ($count_message > 0) {
                                        $no = 1;
                                        do {
                                            echo "
<li class='list-group-item'><span>" . $no++ . "." . substr_replace($row_message->message, 0, 20) . "...</span></li>";
                                        } while ($row_message = $result_message->fetchObject());
                                    } else {
                                        echo "<li class='list-group-item'>No notifications yet.</li>";
                                    }

                                    ?>
                                </ul>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class='dropdown-divider'></div>
                    <?php
                    $login_user = $_SESSION['user_type'];
                    if ($login_user == "parent") {
                        $viewPage = "history.php";
                    } elseif ($login_user == "teacher") {
                        $viewPage = "inbox.php";
                    } else {
                        $returnValue = "";
                    }
                    ?>
                    <a href='<?php echo $viewPage; ?>' class='btn btn-sm btn-block btn-success'>See All Messages</a>
                </div>
            </li>

            <!-- profile -->
            <li class='nav-item dropdown'>
                <a class='nav-link' data-toggle='dropdown' href='#'>
                    <i class='far fa-user fa-2x'></i></a>
                <div class='dropdown-menu dropdown-menu-lg dropdown-menu-right'>
                    <?php if ($_SESSION['user_type'] == "parent") { ?>
                        <a href='student_profile.php' class='dropdown-item dropdown-footer '>View Students</a>
                    <?php } ?>
                    <div class='dropdown-divider'></div>
                    <a href='profile.php' class='dropdown-item dropdown-footer '>Update Profile</a>
                </div>
            </li>
        <?php } ?>
        <!-- Logout -->
        <li class='nav-item'>
            <a href="../logout.php" id="logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </li>
    </ul>
</nav>