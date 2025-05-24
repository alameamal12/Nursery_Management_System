<?php
// Start output buffering and session management
ob_start();
session_start();
// Include the database connection and header files
require_once '../db.php';
require_once '../header.php';
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
	header('Location: ../login.php'); // Redirect to login page if not logged in as an admin
	exit;
}
// Retrieve the current user's ID from the session
$userid = $_SESSION['user_id'];
?>
<div id="wrapper">
	<!-- Include the sidebar menu -->
	<?php include ('sidemenu.php'); ?>
	<div id="main-content">
		<?php
		// Retrieve attendance data from the URL parameters
		$memid = $_GET['basin'];
		$subject = $_GET['subid'];
		$status = $_GET['status'];
		?>
		<div class="card card-default">
			<div class="card-header d-flex p-0">
				<h4 class="card-title p-3" style="color: #000000;font-weight: bold; ">ATTENDANCE | DETAILS</h4>
			</div>
			<div class="card-body">
				<?php
				//archive teachers by status instead of deleting it completely
				if (isset($_POST['delete_attendance'])) {
					// Retrieve the attendance ID and set the status to '0' (archived)
				
					$status = '0';
					$attenid = $_POST['attenid'];
					$archive_attendance = $pdo->query("UPDATE attendance SET status='$status' WHERE id='$attenid'");
					// Display a warning message if archived successfully
					if ($archive_attendance) {
						echo "<div class='alert alert-warning'>Attendance has been archived</div>";
					}
					?>
					<script>
						// Redirect back after 200 milliseconds
						var allowed = function () { window.history.back(); }
						setTimeout(allowed, 200);
					</script>
					<?php
				}
				//update attendance details
				if (isset($_POST['update_attendance'])) {
					// Retrieve form data
					$attenid = $_POST['attenid'];
					$comment = addslashes($_POST['comment']);
					$mins_late = $_POST['mins_late'];
					$attendance_status = $_POST['attendance_status'];
					// Check if the attendance ID and status are not empty
					if (!empty($attenid) && !empty($attendance_status)) {
						// Update attendance details in the `attendance` table
						$update_atten = $pdo->query("update attendance set comment='$comment', mins_late='$mins_late', atten_status='$attendance_status' where id='$attenid'");
						// Display a success or error message based on the query result
						if ($update_atten) {
							echo "<div class='alert alert-warning'>Attendance Updated Successfully</div>";
						} else {
							echo "<div class='alert alert-danger'>Attendance Updated Failed, Call IT Department</div>";
						}
					}
					?>
					<script>
						// Redirect back after 200 milliseconds
						var allowed = function () { window.history.back(); }
						setTimeout(allowed, 200);
					</script>
					<?php
				}
				?>
				<input type='hidden' id='no' value='0'>
				<!-- Table displaying attendance details -->
				<table class="table table-striped table-bordered table-hover">
					<thead>
						<tr class="text-center text-light bg-success">
						<tr>
							<th>No.</th>
							<th>Subject</th>
							<th>Teacher</th>
							<th>Date</th>
							<th colspan="2" class="text-center">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Fetch attendance details based on the selected student, status, and subject
						$result_subAttendance = $pdo->prepare("select * from attendance where studentid=:memid and atten_status=:stat and subject=:subs and status=1");
						$result_subAttendance->bindParam(":memid", $memid);
						$result_subAttendance->bindParam(":stat", $status);
						$result_subAttendance->bindParam(":subs", $subject);
						$result_subAttendance->execute();
						$count_subAttendance = $result_subAttendance->rowCount();
						$row_subAttendance = $result_subAttendance->fetchObject();
						// Check if there are any attendance records and loop through the results
						if ($count_subAttendance > 0) {
							$no = 1;
							do {
								// Fetch the subject and teacher details
								$result_subject = $pdo->query("select * from subjects where id='$row_subAttendance->subject'");
								$row_subject = $result_subject->fetchObject();
								$count_subject = $result_subject->rowCount();
								$result_teachers = $pdo->query("select * from teachers where teacherid='$row_subAttendance->teacherid'");
								$row_teacher = $result_teachers->fetchObject();
								$count_teacher = $result_teachers->rowCount();
								echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_subject->subject_name . "</td>
<td>" . $row_teacher->firstname . " " . $row_teacher->lastname . "<br><span style='color:maroon;'>[" . $row_teacher->phone . "]</span></td>
<td>" . $row_subAttendance->atten_date . "</td>
<td>
<button onClick='show_atten(" . $row_subAttendance->id . ")' class='btn btn-sm btn-info'>Edit</button>
</td>
<td>"; ?>
								<form method='post' onsubmit="return delete_checker('Student Attendance','Deleted');">
									<?php echo "
<input type='hidden' name='attenid' value=" . $row_subAttendance->id . ">
<button type='submit' name='delete_attendance' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
</tr><tr></tr>"; ?>
									<tr id='attendata<?php echo $row_subAttendance->id; ?>'
										onClick="get_no('<?php echo $row_subAttendance->id; ?>')"
										style='border-bottom: 2px solid red;border-top: 2px solid red;display:none;'>
										<?php echo "<form  method='POST'>
<input type='hidden' name='attenid' value='" . $row_subAttendance->id . "'>
<td colspan='6'>
<div class='row'>
<div class='col-lg-3'>
<label>Status</label>
<select name='attendance_status' class='form-control'>
<option value='" . $row_subAttendance->atten_status . "'>" . $status . "</option>
<option value='P'>Present</option>
<option value='L'>Late</option>
<option value='AM'>Moved Out After Attendance</option>
<option value='A'>Absent</option>
</select>
</div>
<div class='col-lg-2'>
<label class='form-label'>Mins Wasted</label>
<input type='text' name='mins_late'  value='" . $row_subAttendance->mins_late . "' class='form-control'>
</div>
<div class='col-lg-7'>
<label class='form-label'>Comment</label>
<input type='text' name='comment' class='form-control' value='" . $row_subAttendance->comment . "'>
</div></div>
<div class='col-lg-12'><br></div>    
<div class='form-group'>
<input type='submit' name='update_attendance' class='btn btn-sm btn-primary btn-block' value='Update'></div></div></td></form></tr>";
							} while ($row_subAttendance = $result_subAttendance->fetchObject());
						} else {
							echo "<tr><td colspan='4' class='text-center'>No Subject Attendance Recorded For This Student</td></tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script>
		function get_no(ai) { document.getElementById("no").value = ai; }
		function show_atten(ai) { $("#attendata" + ai).toggle(); }
	</script>
	<script>
		function delete_checker(names, act) {
			var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");
			if (confirmer == false) { return false; }
		}
	</script>
	</body>

	</html>