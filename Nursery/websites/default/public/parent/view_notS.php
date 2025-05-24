<?php
// Start the session to maintain user authentication
session_start();
// Include header and database connection files
require '../header.php';
require '../db.php';
?>
</head>

<body>
    <div id="wrapper">
        <!-- Include the parent-specific sidebar menu -->
        <?php include ('parentsidemenu.php'); ?>
        <div class="container-fluid">
            <div class="card card-info" style="margin-top: 30px;">
                <div class="card-header d-flex p-0">
                    <h4 class="card-title p-3">MANAGE | NOTIFICATIONS</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Retrieve the notification group type from the URL parameter
                    $type = $_GET['memid'];
                    ?>
                    <!-- Table to display notifications -->
                    <table class="table table-striped">
                        <tbody>
                            <?php
                            // Query to fetch notifications for the specific group type
                            $result_nots = $pdo->query("select * from notifications where not_group='$type' order by id desc");
                            $row_nots = $result_nots->fetchObject();
                            $count_nots = $result_nots->rowCount();
                            // Check if there are notifications available
                            if ($count_nots > 0) {
                                $no = 1;
                                do {
                                    // Display each notification in a table row
                                    echo "<tr>
<td><span style='color:maroon'>" . $row_nots->title . "</span><br>" . $row_nots->not_date . "
<span style='border-top:none; border-bottom:1px dashed yellow;'>" . $row_nots->details . "</span>
</td>
</tr>";
                                } while ($row_nots = $result_nots->fetchObject());
                            } else {
                                // Display a message if there are no notifications available
                                echo "<tr><td colspan='10' align='center'>There is no data as yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Include jQuery and Bootstrap JavaScript files -->
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    </div>
    </div>
    </div>
</body>

</html>