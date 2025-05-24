<?php
// Start the session to access session variables
session_start();
//include database and header file
require '../header.php';
require '../db.php';
?>
</head>

<body>
    <div id="wrapper">
        <?php include ('teachersidemenu.php'); ?>
        <div class="container-fluid">
            <div class="card card-info" style="margin-top: 30px;">
                <div class="card-header d-flex p-0">
                    <!-- Add a comment for clarity -->
                    <h4 class="card-title p-3">MANAGE | NOTIFICATIONS</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Get the member ID from the URL parameter
                    $type = $_GET['memid'];
                    ?>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <?php
                            $result_nots = $pdo->query("select * from notifications where not_group='$type' order by id desc");
                            $row_nots = $result_nots->fetchObject(); // Fetch the first row of the result
                            $count_nots = $result_nots->rowCount();  //Get the count of notifications
                            if ($count_nots > 0) {
                                $no = 1;
                                do {
                                    // Check if there are notifications
                                    echo "
<tr>
<td colspan='4' style='border-top:none; border-bottom:1px dashed yellow;'><b>" . $no++ . ". Abouts : <span style='color:maroon'>" . $row_nots->title . "</span> </b><br>" . $row_nots->details . "</td>
</tr>";
                                } while ($row_nots = $result_nots->fetchObject()); // Loop through all notifications
                            } else {
                                echo "<tr><td colspan='10' align='center'>There is no data as yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    </div>
    </div>
    </div>
</body>

</html>