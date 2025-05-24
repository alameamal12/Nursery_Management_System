<?php
// Start output buffering and initialize session
ob_start();
session_start();
// Include database connection and header files
require_once '../db.php';
require_once '../header.php';

// Retrieve user ID from session
$userid = $_SESSION['user_id'];

// check if the user is logged in as an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php'); // Redirect to login page if not logged in as an admin
    exit;
}
?>
<?php
// Retrieve GET parameters for class, subject, start date, and end date
$class = $_GET['class'];
$subject = $_GET['subject'];
$sdate = $_GET['sdate'];
$edate = $_GET['edate'];
?>
<div id="wrapper">
    <!-- Include the sidebar menu -->
    <?php include ('sidemenu.php'); ?>
    <div id="main-content">
        <div class="card card-default">
            <div class="card-header d-flex p-0">
                <h4 class="card-title text-black p-3 text-bold">GENERATED | ATTENDANCE</h4>
            </div>
            <div class="card-body">
                <!-- Table to display generated attendance records -->
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Student Name</th>
                            <th>Present</th>
                            <th>Late</th>
                            <th>Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Prepare SQL query to fetch attendance records based on the selected class, subject, and date range
                        $result_atten = $pdo->prepare("select * from attendance where subject=:subid and class=:memid and (atten_date>=:sd and atten_date<=:ed)");
                        $result_atten->bindParam(":memid", $class);
                        $result_atten->bindParam(":subid", $subject);
                        $result_atten->bindParam(":sd", $sdate);
                        $result_atten->bindParam(":ed", $edate);
                        $result_atten->execute();

                        // Fetch the attendance records
                        $count_atten = $result_atten->rowCount();
                        $row_atten = $result_atten->fetchObject();
                        // Check if there are attendance records available
                        if ($count_atten > 0) {
                            $no = 1;
                            do {
                                // Retrieve student details for each attendance record
                        
                                $result_student = $pdo->query("select * from students where id='$row_atten->studentid'");
                                $count_student = $result_student->rowCount();
                                $row_student = $result_student->fetchObject();

                                // Count the number of times the student was present for the specified subject
                        
                                $result_present = $pdo->query("select * from attendance where studentid='$row_student->id' and atten_status='P' and subject='$subject'");
                                $count_present = $result_present->rowCount();
                                // Ensure that student records exist before continuing
                                if ($count_student > 0) {
                                    // Count the number of times the student was late for the specified subject
                                    $result_late = $pdo->query("select * from attendance where studentid='$row_student->id' and (atten_status='L' or atten_status='AM') and subject='$subject'");

                                    $count_late = $result_late->rowCount();

                                    // Count the number of times the student was absent for the specified subject
                                    $result_absent = $pdo->query("select * from attendance where studentid='$row_student->id' and atten_status='A' and subject='$subject'");
                                    $count_absent = $result_absent->rowCount();
                                    // Display the student's attendance data in the table
                                    echo "
<tr>
<td>" . $no++ . "</td>
<td>" . $row_student->name . "</td>
<td><a href='student_attendance.php?basin=" . $row_student->id . "&subid=" . $subject . "&status=P'>" . $count_present . "</a></td>
<td><a href='student_attendance.php?basin=" . $row_student->id . "&subid=" . $subject . "&status=L'>" . $count_late . "</a></td>
<td><a href='student_attendance.php?basin=" . $row_student->id . "&subid=" . $subject . "&status=A'>" . $count_absent . "</a></td>
</tr>";
                                }
                            } while ($row_atten = $result_atten->fetchObject());
                        } else {
                            // Display a message if no attendance records are available for the selected subject
                        
                            echo "<tr><td colspan='5' class='text-center'>No attendance records for this subject</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Include jQuery and Bootstrap JavaScript files -->
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>

</html>