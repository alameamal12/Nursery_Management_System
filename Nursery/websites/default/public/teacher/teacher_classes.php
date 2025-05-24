<?php
// Start output buffering and session
ob_start();
session_start();
// Include the database connection and header files
require_once '../db.php';
require_once '../header.php';

// check if the user is logged in as a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'teacher') {
    header('Location: ../login.php'); // Redirect to login page if not logged in as a teacher
    exit;
}
?>
<div id="wrapper">
    <?php include ('teachersidemenu.php'); ?>
    <div id="main-content">
        <div class="card card-default">
            <div class="card-header">
                <h4 class="card-title">MANAGE | CLASSES</h4>
            </div>
            <div class="card-body">
                <!-- Table to display the classes assigned to the current teacher -->
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Class</th>
                            <th>Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Retrieve the current user (teacher) ID from the session
                        $userid = $_SESSION['user_id'];
                        // Fetch the classes assigned to the current teacher
                        $result_streams = $pdo->query("select * from classes where teacher='$userid'");
                        $count_streams = $result_streams->rowCount();
                        $row_streams = $result_streams->fetchObject();
                        // Check if any classes are assigned to the teacher
                        if ($count_streams > 0) {
                            $no = 1;
                            // Initialize the counter for the "No" column
                            do {
                                // Count the number of active students in the current class
                                $result_students = $pdo->query("select * from students where class='$row_streams->id' and status=1");
                                $count_students = $result_students->rowCount();
                                // Display each class with its associated number of students and an action button for attendance
                                echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_streams->name . "</td>
<td>" . $count_students . "</td>
<td><a href='attendance.php?basin=" . $row_streams->id . "' class='btn btn-success btn-sm btn-flat'><i class='fas fa-check-circle'>Take Attendance</i></a></td>
</tr>";
                            } while ($row_streams = $result_streams->fetchObject());
                        } else {
                            // Display a message if no classes are assigned to this teacher
                            echo "<tr><td colspan='4'>No classes assigned to this teacher</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</body>

</html>