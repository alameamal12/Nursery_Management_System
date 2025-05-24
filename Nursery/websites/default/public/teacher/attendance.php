<?php
// Start the output buffering and session
ob_start();
session_start();
// Include database and header files
require_once '../db.php';
require_once '../header.php';
// Retrieve the user ID from the session
$userid = $_SESSION['user_id'];
// check if the user is logged in as a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'teacher') {
	header('Location: ../login.php'); // Redirect to login page if not logged in as a teacher
	exit;
}
?>
<div id="wrapper">
	<!-- Include the teacher sidebar menu -->
	<?php include ('teachersidemenu.php'); ?>
	<?php $classid = $_GET['basin']; ?>
	<div id="main-content">
		<div class="card card-default">
			<div class="card-header d-flex p-0">
				<h4 class="card-title text-black p-3">SUBJECT | ATTENDANCE</h4>
				<ul class="nav nav-pills ml-auto p-2">
					<li class="nav-item active"><a class="btn btn-sm btn-success" data-toggle="tab" href="#tab_1">Take
							Attendance</a></li>&nbsp&nbsp
					<li class="nav-item"><a class="btn btn-sm btn-primary" data-toggle="tab" href="#tab_2">View Today's
							Attendance</a></li>
				</ul>
			</div>
			<div class="card-body">
				<input type='hidden' id='classid' value='<?php echo $classid; ?>'>
				<!-- Form to select a subject for attendance -->
				<input type='hidden' id='no' value='0'>
				<!-- selecting subject for attendance -->
				<?php if (isset($_POST["toshow"])) {
					$toshow = $_POST["toshow"];
					// Retrieve subject details
					$result_sub = $pdo->query("select * from subjects where id='$toshow'");
					$row_sub = $result_sub->fetchObject();
					$result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='subject'");
					$count_toshow = $result_toshow->rowCount();
					if ($count_toshow <= 0) {
						$insert_toshow = $pdo->query("insert into toshow (item2,item3,item4,typez) values('$userid','$toshow','$row_sub->subject_name','subject')");
					} else {
						// Update the pre-set subject if already pre-set
						$update_toshow = $pdo->query("update toshow set item3='$toshow',item4='$row_sub->subject_name' where item1='toshow' and typez='subject' and item2='$userid'");
					}
				}
				//record attendance for present students
				if (isset($_POST['submit_sub_present'])) {
					$studentid = $_POST['studentid'];
					$class = $_POST['class'];
					$pres_stat = $_POST['pres_stat'];
					$atten_date = date("Y-m-d");
					//// Retrieve pre-set subject details
					$result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='subject'");
					$count_toshow = $result_toshow->rowCount();
					$row_toshow = $result_toshow->fetchObject();
					if ($count_toshow > 0) {
						// Insert attendance record
						$insert_attendance = $pdo->query("insert into attendance(studentid,class,teacherid,atten_date,atten_status,subject) values('$studentid','$class','$userid','$atten_date','$pres_stat','$row_toshow->item3')");
						if ($insert_attendance) {
							echo "<div class='alert alert-success'>Attendance Recorded</div>";
						} else {
							echo "<div class='alert alert-danger'>Attendance Not Recorded</div>";
						}
					}
				}
				//Record attendance for absent students
				if (isset($_POST['submit_sub_absent'])) {
					$studentid = $_POST['studentid'];
					$class = $_POST['class'];
					$abs_stat = $_POST['abs_stat'];
					$atten_date = date("Y-m-d");
					$absent_reason = addslashes($_POST['absent_reason']);
					// Retrieve pre-set subject details
					$result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='subject'");
					$count_toshow = $result_toshow->rowCount();
					$row_toshow = $result_toshow->fetchObject();
					if ($count_toshow > 0) {
						// Insert attendance record
						$insert_attendance = $pdo->query("insert into attendance(studentid,class,teacherid,atten_date,atten_status,comment,subject) values('$studentid','$class','$userid','$atten_date','$abs_stat','$absent_reason','$row_toshow->item3')");
						if ($insert_attendance) {
							echo "<div class='alert alert-success'>Attendance Recorded</div>";
						} else {
							echo "<div class='alert alert-danger'>Attendance Not Recorded</div>";
						}
					}
				}
				// Record attendance for latecomers
				if (isset($_POST['submit_sub_late'])) {
					$studentid = $_POST['studentid'];
					$class = $_POST['class'];
					$late_stat = $_POST['late_stat'];
					$mins_late = $_POST['mins_late'];
					$atten_date = date("Y-m-d");
					$late_reason = addslashes($_POST['late_reason']);
					$mystatus = 1;
					// Retrieve pre-set subject details
					$result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='subject'");
					$count_toshow = $result_toshow->rowCount();
					$row_toshow = $result_toshow->fetchObject();
					if ($count_toshow > 0) {
						// Insert attendance record
						$insert_attendance = $pdo->query("insert into attendance(studentid,class,teacherid,atten_date,atten_status,comment,mins_late,subject) values('$studentid','$class','$userid','$atten_date','$late_stat','$late_reason','$mins_late','$row_toshow->item3')");
						if ($insert_attendance) {
							echo "<div class='alert alert-success'>Attendance Recorded</div>";
						} else {
							echo "<div class='alert alert-danger'>Attendance Not Recorded</div>";
						}
					}
				}
				//update attendance record
				if (isset($_POST['update_attendance'])) {
					$attenid = $_POST['attenid'];
					$comment = addslashes($_POST['comment']);
					$mins_late = $_POST['mins_late'];
					$attendance_status = $_POST['attendance_status'];
					if (!empty($attenid) && !empty($attendance_status)) {
						$update_atten = $pdo->query("update attendance set comment='$comment', mins_late='$mins_late', atten_status='$attendance_status' where id='$attenid'");
						if ($update_atten) {
							echo "<div class='alert alert-warning'>Attendance Updated Successfully</div>";
						} else {
							echo "<div class='alert alert-danger'>Attendance Updated Failed, Call IT Department</div>";
						}
					}
				}
				?>
				<div class="tab-content">
					<!-- Tab 1: Take Attendance -->
					<div class="tab-pane active" id="tab_1">
						<div class="row">
							<div class="col-lg-12">
								<label>Pre-Set Subject For Attendance</label>
								<!-- Form to select a pre-set subject -->
								<form method='post'>
									<select class='form-control' name='toshow' onchange="this.form.submit()">
										<?php
										// Retrieve pre-set subject details
										$result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='subject'");
										$row_toshow = $result_toshow->fetchObject();
										$count_toshow = $result_toshow->rowCount();
										if ($count_toshow > 0) {
											echo "<option value='" . $row_toshow->item2 . "'>Attendance For : " . $row_toshow->item4 . "</option>";
										} else {
											echo "<option value=''>--SELECT--</option>";
										}
										//populate options values if no subject preset
										$result_teacher = $pdo->query("select * from teachers where teacherid='$userid'");
										$count_teacher = $result_teacher->rowCount();
										$row_teacher = $result_teacher->fetchObject();
										if ($count_teacher > 0) {
											//explode assigned subjects from teacher data
											$attached_subject = explode(",", $row_teacher->assigned_subject);
											for ($a = 0; $a <= 50; $a++) {
												if (isset($attached_subject[$a]) and !empty($attached_subject[$a])) {
													$assigned = $attached_subject[$a];
													$select_subject = $pdo->query("select * from subjects where id='$assigned'");
													$row_subject = $select_subject->fetchObject();
													echo "<option value='" . $row_subject->id . "'>" . $row_subject->subject_name . "</option>";
												}
											}
										}
										?>
									</select>
								</form>
							</div>
						</div>
						<div class="col-lg-12"><br></div>
						<!-- Table to display the list of students and attendance actions -->

						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>No</th>
									<th>Name</th>
									<th colspan="3">Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
								// Get today's date
								$mydate = date("Y-m-d");
								// Retrieve pre-set subject details
								$result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='subject'");
								$row_toshow = $result_toshow->fetchObject();
								$count_toshow = $result_toshow->rowCount();
								// Retrieve list of students for the specified class
								$result_students = $pdo->prepare("select * from students where class=:memid and status=1 order by name asc");
								$result_students->bindParam(":memid", $classid);
								$result_students->execute();
								$count_students = $result_students->rowCount();
								$row_students = $result_students->fetchObject();
								if ($count_students > 0 && $count_toshow > 0) {
									$m = 1;
									do {
										// Check if attendance is already recorded for today
										$result_attendance = $pdo->query("select * from attendance where atten_date='$mydate' and studentid='$row_students->id' and class='$row_students->class' and subject='$row_toshow->item3'");
										$count_attendance = $result_attendance->rowCount();
										$row_attendance = $result_attendance->fetchObject();
										if ($count_attendance <= 0) {
											echo "
<tr>
<td>" . $m++ . "</td>
<td>";
											if ($count_students > 0) {
												echo $row_students->name;
											}
											echo "</td>
<td>
<form method='POST'>
<input type='hidden' name='studentid' value='";
											if ($count_students > 0) {
												echo $row_students->id;
											}
											echo "'>
<input type='hidden' name='class' value='";
											if ($count_students > 0) {
												echo $row_students->class;
											}
											echo "'>
<input type='hidden' name='pres_stat' value='P'>
<button class='btn btn-sm btn-success' type='submit' name='submit_sub_present'>Present</button>
</form></td>
<td><button onClick='show_2(" . $row_students->id . ")' class='btn btn-sm btn-warning'>Late</button></td>
<td><button onClick='show_dis(" . $row_students->id . ")' class='btn btn-sm btn-danger'>Absent</button></td>
</tr> "; ?>
											<!-- Late attendance form -->
											<tr id='late<?php echo $row_students->id; ?>'
												onClick="get_no('<?php echo $row_students->id; ?>')"
												style='border-bottom: 2px solid red;border-top: 2px solid red;display:none;'>
												<?php echo "<form  method='POST'>
<input type='hidden' name='studentid' value='" . $row_students->id . "'>
<input type='hidden' name='class' value='" . $row_students->class . "'>
<input type='hidden' name='late_stat' value='L'>
<td colspan='6'><div class='row'>
<div class='col-lg-2'>Minutes  Late :
<input type='number' class='form-control' data-type='number' name='mins_late'>
</div>
<div class='col-lg-8'>Reason :
<input type='text' class='form-control' name='late_reason' placeholder='Enter Reason'></div>         
<div class='col-lg-2'><br><input type='submit' style='float:right;' name='submit_sub_late' class='btn btn-sm btn-success form-control' value='submit'></div></div></td></form></tr>"; ?>
												<!-- Absent attendance form -->
											<tr id='absent<?php echo $row_students->id; ?>'
												onClick="get_no('<?php echo $row_students->id; ?>')"
												style='border-bottom: 2px solid red;border-top: 2px solid red;display:none;'>
												<?php echo "<form method='POST'>
<input type='hidden' name='studentid' value='" . $row_students->id . "'>
<input type='hidden' name='class' value='" . $row_students->class . "'>
<input type='hidden' name='abs_stat' value='A'>
<td colspan='6'>
<div class='row'>
<div class='col-lg-10'><input type='text' class='form-control' name='absent_reason' placeholder='Reason For The Absence If Any?'></div>
<div class='col-lg-2'><input type='submit' name='submit_sub_absent' class='btn btn-sm btn-success' value='Submit'></div></diV></td></form>
</tr>";
										}
									} while ($row_students = $result_students->fetchObject());
								} else {
									echo "<tr><td colspan='5'>No Students Registered To The Selected Class Group, Contact Your IT Department</td></tr>";
								}
								?>
							</tbody>
						</table>
					</div>
					<!-- Tab 2: View Today's Attendance -->
					<div class="tab-pane" id="tab_2">
						<table class="table table-striped table-hover table-bordered">
							<thead>
								<tr>
									<th>No.</th>
									<th>Student Name</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$mydate = date("Y-m-d");
								//Retrieve pre-set subject details
								$result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='subject'");
								$row_toshow = $result_toshow->fetchObject();
								$count_toshow = $result_toshow->rowCount();
								// Retrieve today's attendance for the specified class and subject
								$result_atten = $pdo->prepare("select * from attendance where atten_date='$mydate' and subject='$row_toshow->item3' and class=:memid order by id asc");
								$result_atten->bindParam(":memid", $classid);
								$result_atten->execute();
								$count_atten = $result_atten->rowCount();
								$row_atten = $result_atten->fetchObject();
								if ($count_atten > 0) {
									$no = 1;
									do {
										// Retrieve student details
										$result_student = $pdo->query("select * from students where id='$row_atten->studentid'");
										$count_student = $result_student->rowCount();
										$row_student = $result_student->fetchObject();
										// Determine the attendance status
										if ($row_atten->atten_status == "P") {
											$status = "Present";
										} elseif ($row_atten->atten_status == "L") {
											$status = "Late" . "<br>" . $row_atten->comment;
										} elseif ($row_atten->atten_status == "A") {
											$status = "Absent" . "<br>" . $row_atten->comment;
										} elseif ($row_atten->atten_status == "AM") {
											$status = "Moved Out After Attendance" . "<br>" . $row_atten->comment;
										}
										echo "
<tr>
<td>" . $no++ . "</td>
<td>" . $row_student->name . "</td>
<td>" . $status . "</td>
<td>
<button onClick='show_atten(" . $row_atten->id . ")' class='btn btn-sm btn-info'>Edit</button>
</td>
</tr><tr></tr>"; ?>
										<!-- Form to edit attendance -->
										<tr id='attendata<?php echo $row_atten->id; ?>'
											onClick="get_no('<?php echo $row_atten->id; ?>')"
											style='border-bottom: 2px solid red;border-top: 2px solid red;display:none;'>
											<?php echo "<form  method='POST'>
<input type='hidden' name='attenid' value='" . $row_atten->id . "'>
<td colspan='4'>
<div class='row'>
<div class='col-lg-3'>
<label>Status</label>
<select name='attendance_status' class='form-control'>
<option value='" . $row_atten->atten_status . "'>" . $status . "</option>
<option value='P'>Present</option>
<option value='L'>Late</option>
<option value='AM'>Moved Out After Attendance</option>
<option value='A'>Absent</option>
</select>
</div>
<div class='col-lg-2'>
<label class='form-label'>Mins Wasted</label>
<input type='text' name='mins_late'  value='" . $row_atten->mins_late . "' class='form-control'>
</div>
<div class='col-lg-7'>
<label class='form-label'>Comment</label>
<input type='text' name='comment' class='form-control' value='" . $row_atten->comment . "'>
</div></div>
<div class='col-lg-12'><br></div>    
<div class='form-group'>
<input type='submit' name='update_attendance' class='btn btn-sm btn-primary btn-block' value='Update'></div></div></td></form></tr>";
									} while ($row_atten = $result_atten->fetchObject());
								} else {
									echo "<tr><td colspan='4' class='text-center'>No student rollcalled today. Take rollcall and check again</td></tr>";
								}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<script src="../js/jquery.js"></script>
		<script src="../js/bootstrap.min.js"></script>
		<script>
			function display_row(ai) { $("#rows" + ai).toggle(); }
			function show_dis(ai) { $("#absent" + ai).toggle(); }
			function show_2(ai) { $("#late" + ai).toggle(); }
			function get_no(ai) { document.getElementById("no").value = ai; }
			function show_atten(ai) { $("#attendata" + ai).toggle(); }
		</script>
		</body>

		</html>