<?php
// Start output buffering and initialize session
ob_start();
session_start();
// Include database connection and header files
require_once '../db.php';
require_once '../header.php';

// Check if the user is logged in as a parent
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
    header('Location: ../login.php'); // Redirect to login page if not logged in as a parent
    exit;
}
// Retrieve the user ID from the session
$userid = $_SESSION['user_id'];
?>
<div id="wrapper">
    <!-- Include the parent sidebar menu -->
    <?php include ('parentsidemenu.php'); ?>
    <div id="main-content">
        <div class="card card-default">
            <div class="card-header d-flex p-0">
                <h4 class="card-title p-3" style="color: #000000;font-weight: bold; ">STUDENT | PROFILE</h4>
                <ul class="nav nav-pills ml-auto p-2">
                    <!-- Tab navigation buttons -->
                    <li class="nav-item active"><a class="btn btn-sm btn-success" data-toggle="tab"
                            href="#tab_1">Student Bio Data</a></li>&nbsp&nbsp
                    <li class="nav-item"><a class="btn btn-sm btn-primary" data-toggle="tab"
                            href="#tab_2">Attendance</a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Tab one: Student Bio Data -->
                    <div class="tab-pane active" id="tab_1">
                        <!-- Table to display student bio data -->
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">No</th>
                                    <th>Name</th>
                                    <th>Academic Year</th>
                                    <th>Admission Date</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Prepare and execute SQL query to fetch students attached to the parent
                                $result_students = $pdo->prepare("select * from students where parentid=:memid");
                                $result_students->bindParam(":memid", $userid);
                                $result_students->execute();
                                $count_students = $result_students->rowCount();
                                $row_students = $result_students->fetchObject();
                                // Check if any students are attached to the parent
                                if ($count_students > 0) {
                                    $no = 1;
                                    do {
                                        // Retrieve the class details for the current student
                                        $result_class = $pdo->query("select * from classes where id='$row_students->class'");
                                        $count_class = $result_class->rowCount();
                                        $row_class = $result_class->fetchObject();
                                        // Display each student's bio data in a table row
                                        echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_students->name . "</td>
<td>" . $row_students->academic_year . "</td>
<td>" . $row_students->admissionDate . "</td>
<td>";
                                        if ($count_class > 0) {
                                            echo $row_class->name;
                                        }
                                        echo "</td>
<td>" . $row_class->section . "</td>
</tr>";
                                    } while ($row_students = $result_students->fetchObject());
                                } else {
                                    // Display a message if no students are attached to the parent
                                    echo "<tr class='text-center'><td colspan='5'>No students attached to this parent</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Tab two: Attendance -->
                    <div class="tab-pane" id="tab_2">
                        <!-- Table to display attendance details -->
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
                                // Retrieve the students attached to the parent
                                $result_student = $pdo->query("select * from students where parentid='$userid'");
                                $count_student = $result_student->rowCount();
                                $row_student = $result_student->fetchObject();
                                // Check if there are any students available
                                if ($count_student > 0) {
                                    $no = 1;
                                    do {
                                        // Retrieve the count of attendance statuses for the student
                                        $result_present = $pdo->query("select * from attendance where studentid='$row_student->id' and atten_status='P'");
                                        $count_present = $result_present->rowCount();
                                        $result_late = $pdo->query("select * from attendance where studentid='$row_student->id' and (atten_status='L' or atten_status='AM')");
                                        $count_late = $result_late->rowCount();
                                        $result_absent = $pdo->query("select * from attendance where studentid='$row_student->id' and atten_status='A'");
                                        $count_absent = $result_absent->rowCount();
                                        // Display each student's attendance record in a table row
                                        echo "
<tr>
<td>" . $no++ . "</td>
<td>" . $row_student->name . "</td>
<td><a href='student_attendance.php?basin=" . $row_student->id . "&status=P'>" . $count_present . "</a></td>
<td><a href='student_attendance.php?basin=" . $row_student->id . "&status=L'>" . $count_late . "</a></td>
<td><a href='student_attendance.php?basin=" . $row_student->id . "&status=A'>" . $count_absent . "</a></td>
</tr>";
                                    } while ($row_student = $result_student->fetchObject());
                                } else {
                                    // Display a message if no attendance records are available
                                    echo "<tr><td colspan='5' class='text-center'>No attendance records for this parent</td></tr>";
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Include jQuery and Bootstrap JavaScript files -->
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    </body>

    </html>