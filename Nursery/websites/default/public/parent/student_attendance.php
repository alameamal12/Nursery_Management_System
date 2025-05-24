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
		<?php
		// Retrieve student ID and attendance status from the URL parameters
		$memid = $_GET['basin'];
		$status = $_GET['status'];
		?>
		<div class="card card-default">
			<div class="card-header d-flex p-0">
				<h4 class="card-title p-3" style="color: #000000;font-weight: bold; ">ATTENDANCE | DETAILS</h4>
			</div>
			<div class="card-body">
				<!-- Table to display attendance details -->
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr class="text-center text-light bg-success">
						<tr>
							<th>No.</th>
							<th>Subject</th>
							<th>Teacher</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//Prepare and execute the SQL query to fetch attendance details for a specific student and status
						$result_subAttendance = $pdo->prepare("select * from attendance where studentid=:memid and atten_status=:stat");
						$result_subAttendance->bindParam(":memid", $memid);
						$result_subAttendance->bindParam(":stat", $status);
						$result_subAttendance->execute();

						// Fetch the count of attendance records and the first record
						$count_subAttendance = $result_subAttendance->rowCount();
						$row_subAttendance = $result_subAttendance->fetchObject();
						// Check if there are attendance records available
						if ($count_subAttendance > 0) {
							$no = 1;
							do {
								// Retrieve the subject details for the current attendance record
								$result_subject = $pdo->query("select * from subjects where id='$row_subAttendance->subject'");
								$row_subject = $result_subject->fetchObject();
								$count_subject = $result_subject->rowCount();
								// Retrieve the teacher details for the current attendance record
								$result_teachers = $pdo->query("select * from teachers where teacherid='$row_subAttendance->teacherid'");
								$row_teacher = $result_teachers->fetchObject();
								$count_teacher = $result_teachers->rowCount();
								// Display each attendance record in a table row
								echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_subject->subject_name . "</td>
<td>" . $row_teacher->firstname . " " . $row_teacher->lastname . "<br><span style='color:maroon;'>[" . $row_teacher->phone . "]</span></td>
<td>" . $row_subAttendance->atten_date . "</td>
</tr>";
							} while ($row_subAttendance = $result_subAttendance->fetchObject());
						} else {
							// Display a message if no attendance records are available for the student
							echo "<tr><td colspan='4' class='text-center'>No Subject Attendance Recorded For This Student</td></tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- Include jQuery and Bootstrap JavaScript files -->
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	</body>

	</html>