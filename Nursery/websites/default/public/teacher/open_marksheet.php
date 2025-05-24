<?php
// Start the session and include necessary files
ob_start();
session_start();
require_once '../db.php';
require_once '../header.php';

// Check if the user is logged in and is a teacher, otherwise redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'teacher') {
	header('Location: ../login.php'); // Redirect to login page if not logged in or not a teacher
	exit;
}
?>
<div id="wrapper">
	<?php include ('teachersidemenu.php'); ?>
	<div id="main-content">
		<div class="card card-default">
			<div class="card-header d-flex p-0">
				<!-- Page Title -->
				<h4 class="card-title p-3" style="color: #000000;font-weight: bold; ">MANAGE | MARKS</h4>
				<ul class="nav nav-pills ml-auto p-2">
					<!-- Navigation Tabs -->
					<li class="nav-item active"><a class="btn btn-sm btn-success" data-toggle="tab" href="#tab_1">Open
							Marks Sheet</a></li>&nbsp&nbsp
					<li class="nav-item"><a class="btn btn-sm btn-primary" data-toggle="tab" href="#tab_2">View
							Marks</a></li>
				</ul>
			</div>
			<div class="card-body">
				<div class="tab-content">
					<!-- Tab 1: Open Marks Sheet -->
					<div class="tab-pane active" id="tab_1">
						<form onsubmit="redirect(event)">
							<div class="row">
								<!-- Class Selection -->
								<div class="col-lg-3">
									<label class='form-label'>Class</label>
									<select class="form-control" name="classid" id="classid">
										<option value="">--SELECT--</option>
										<?php
										// Fetch classes from the database
										$result_classes = $pdo->query("select * from classes order by name asc");
										$count_classes = $result_classes->rowCount();
										$row_classes = $result_classes->fetchObject();
										if ($count_classes > 0) {
											do {
												echo "<option value='" . $row_classes->id . "'>" . $row_classes->name . "</option>";
											} while ($row_classes = $result_classes->fetchObject());
										} else {
											echo "No Class Registered In The System";
										}
										?>
									</select>
								</div>
								<!-- Subject Selection -->
								<div class="col-lg-3">
									<label class='form-label'>Subject</label>
									<select class="form-control" name="subject" id="subject">
										<option value="">--SELECT--</option>
										<?php
										// Fetch subjects from the database
										$result_subject = $pdo->query("select * from subjects order by subject_name asc");
										$count_subject = $result_subject->rowCount();
										$row_subject = $result_subject->fetchObject();
										if ($count_subject > 0) {
											do {
												echo "<option value='" . $row_subject->id . "'>" . $row_subject->subject_name . "</option>";
											} while ($row_subject = $result_subject->fetchObject());
										} else {
											echo "No Subjects Added";
										}
										?>
									</select>
								</div>
								<!-- Exam Series Selection -->
								<div class="col-lg-3">
									<label class='form-label'>Exam Serie</label>
									<select class="form-control" name="exam_serie" id="series">
										<option value="">--SELECT--</option>
										<option value="MID">Mid Term Exams</option>
										<option value="EOT">End Of Term Exams</option>
									</select>
								</div>
								<div class="col-lg-3">
									<label><br></label>
									<!-- Submit Button -->
									<input type="submit" id="submit_ls" class="btn btn-sm btn-info"
										value="OPEN MARKS SHEET" style="width: 100%;">
								</div>
							</div>
						</form>
					</div>
					<!-- Tab 2: View Marks -->
					<div class="tab-pane" id="tab_2">
						<form onsubmit="redirect2(event)">
							<div class="row">
								<!-- Class Selection -->

								<div class="col-lg-5">
									<label class='form-label'>Class</label>
									<select class="form-control" name="streamid" id="streamid">
										<option value="">--SELECT--</option>
										<?php
										// Fetch classes from the database
										$result_classes = $pdo->query("select * from classes order by name asc");
										$count_classes = $result_classes->rowCount();
										$row_classes = $result_classes->fetchObject();
										if ($count_classes > 0) {
											do {
												echo "<option value='" . $row_classes->id . "'>" . $row_classes->name . "</option>";
											} while ($row_classes = $result_classes->fetchObject());
										} else {
											echo "No classes registered";
										}
										?>
									</select>
								</div>
								<!-- Exam Series Selection -->
								<div class="col-lg-5">
									<label class='form-label'>Exam Serie</label>
									<select class="form-control" name="exam_serie" id="exam_serie">
										<option value="">--SELECT--</option>
										<option value="MID">Mid Term Exams</option>
										<option value="EOT">End Of Term Exams</option>
									</select>
								</div>
								<!-- Submit Button -->
								<div class="col-lg-2">
									<div class="col-lg-12"><br></div>
									<input type="submit" class="btn btn-sm btn-primary" value="VIEW MARKS"
										style="width: 100%;">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="../js/jquery.js"></script>
	<script src="../js/bootstrap.min.js"></script>
	<script>
		//redirect js fuction to create a php get link based on selected values from the form
		function redirect() {
			var value1 = document.getElementById('classid').value;
			var value2 = document.getElementById('subject').value;
			var value3 = document.getElementById('series').value;
			event.preventDefault();
			window.location.href = "mark_sheet.php?basin=" + value1 + "&memid=" + value2 + "&kaps=" + value3;
		}
		//view entered marks
		function redirect2() {
			var value1 = document.getElementById('streamid').value;
			var value2 = document.getElementById('exam_serie').value;
			event.preventDefault();
			window.location.href = "manage_marks.php?basin=" + value1 + "&memid=" + value2;
		}
	</script>
	</body>

	</html>