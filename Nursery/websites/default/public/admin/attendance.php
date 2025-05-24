<?php
// Start output buffering and session management
ob_start();
session_start();
// Include database connection and header files
require_once '../db.php';
require_once '../header.php';
// Retrieve the current user's ID from session
$userid = $_SESSION['user_id'];
// check if the user is logged in as a admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
	// Redirect to login page if not logged in or not an admin
	header('Location: ../login.php');
	exit;
}
?>
<div id="wrapper">
	<!-- Include the sidebar menu -->
	<?php include ('sidemenu.php'); ?>
	<div id="main-content">
		<div class="card card-default">
			<div class="card-header d-flex p-0">
				<h4 class="card-title text-black p-3 text-bold">GENERATE | ATTENDANCE RECORDS</h4>
			</div>
			<div class="card-body">
				<!-- Attendance records form -->
				<form onsubmit="redirect(event)">
					<div class="row">
						<div class="col-lg-3">
							<label class='form-label'>Class</label>
							<select class="form-control" id="classid">
								<option value="">--SELECT--</option>
								<?php
								// Fetch all classes from the database and populate the dropdown
								$result_classes = $pdo->query("select * from classes order by name asc");
								$count_classes = $result_classes->rowCount();
								$row_classes = $result_classes->fetchObject();
								// Check if there are any classes and loop through the results
								
								if ($count_classes > 0) {
									do {
										echo "<option value='" . $row_classes->id . "'>" . $row_classes->name . "</option>";
									} while ($row_classes = $result_classes->fetchObject());
								}
								?>
							</select>
						</div>
						<div class="col-lg-3">
							<label class='form-label'>Subject</label>
							<select class="form-control" id="subjectid">
								<option value="">--SELECT--</option>
								<?php
								// Fetch all subjects from the database and populate the dropdown
								$result_subject = $pdo->query("select * from subjects order by subject_name asc");
								$count_subject = $result_subject->rowCount();
								$row_subject = $result_subject->fetchObject();
								// Check if there are any subjects and loop through the results
								if ($count_subject > 0) {
									do {
										echo "<option value='" . $row_subject->id . "'>" . $row_subject->subject_name . "</option>";
									} while ($row_subject = $result_subject->fetchObject());
								}
								?>
							</select>
						</div>
						<div class="col-lg-2">
							<label class='form-label'>Date From</label>
							<input type="date" id="start_date" class="form-control">
						</div>
						<div class="col-lg-2">
							<label class='form-label'>Date To</label>
							<input type="date" id="end_date" class="form-control">
						</div>
						<div class="col-lg-2">
							<label><br></label>
							<!-- Submit button -->
							<input type="submit" class="btn btn-sm btn-primary" value="VIEW ATTENDANCE"
								style="width: 100%;">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Include jQuery and Bootstrap JavaScript files -->
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
	// Function to redirect to the attendance report page with selected filters

	function redirect() {
		// Retrieve the selected values from the form

		var value1 = document.getElementById('classid').value;
		var value2 = document.getElementById('subjectid').value;
		var value3 = document.getElementById('start_date').value;
		var value4 = document.getElementById('end_date').value;
		// Prevent the default form submission
		event.preventDefault();
		window.location.href = "view_attendanceReports.php?class=" + value1 + "&subject=" + value2 + "&sdate=" + value3 + "&edate=" + value4;
	}
	//
</script>
</body>

</html>