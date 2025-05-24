<!-- student form -->
<div class="modal fade" id="student">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Student Admission</i></h2>
			</div>
			<div class="modal-body">
				<form method="POST" enctype="multipart/form-data" autocomplete="off">
					<div class="row">
						<div class="col-lg-6">
							<label for="name">Student Name</label>
							<input type="text" name="full_name" required class="form-control"
								placeholder="Enter Full Name">
						</div>
						<div class="col-lg-6">
							<label for="admissionNumber">Admission Number</label>
							<input type="text" name="admission_number" placeholder="Enter Admission No" required
								class="form-control">
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="row">
						<div class="col-lg-6">
							<label for="rollNumber">Roll Number</label>
							<input type="text" name="roll_number" required placeholder="Enter Roll Number"
								class="form-control">
						</div>
						<div class="col-lg-6">
							<label for="image">Student Image</label>
							<input type="file" name="image_name" accept="image/*" class="form-control">
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="row">
						<div class="col-lg-6">
							<label for="academicYearSelect">Academic Year*</label>
							<select id="academicYearSelect" name="academic_year" required class="form-control">
								<option value="">Select Year</option>
								<!-- Populate with years dynamically if possible -->
								<option value='<?php echo date("Y"); ?>'><?php echo date("Y"); ?></option>
								<?php
								for ($t = 0; $t <= 3; $t++) {
									$cy = date("y") + $t;
									echo "<option value='20" . $cy . "'>20" . $cy . "</option>";
								}
								?>
							</select>
						</div>
						<div class="col-lg-6">
							<label for="admissionDate">Admission Date</label>
							<input type="date" name="admission_date" required class="form-control">
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="row">
						<div class="col-lg-6">
							<label for="class">Class*</label>
							<select name="class_name" required class="form-control">
								<option value="">--SELECT--</option>
								<!-- Populate with class options -->
								<?php
								$result_class = $pdo->query("select * from classes order by name asc");
								$count_class = $result_class->rowCount();
								$row_class = $result_class->fetchObject();
								if ($count_class > 0) {
									do {
										echo "<option value='" . $row_class->id . "'>" . $row_class->name . "</option>";
									} while ($row_class = $result_class->fetchObject());
								} else {
									echo "No class found.. please add one";
								}
								?>
							</select>
						</div>
						<div class="col-lg-6">
							<label for="section">Section*</label>
							<select name="section" required class="form-control">
								<option value="">Select</option>
								<option value="Section 1">Section A</option>
								<option value="Section 2">Section B</option>
							</select>
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="form-group">
						<label>Parent</label>
						<select class="form-control" name="parentid">
							<option>--SELECT--</option>
							<?php
							$result_parent = $pdo->query("select * from parents order by firstname asc");
							$count_parent = $result_parent->rowCount();
							$row_parent = $result_parent->fetchObject();
							if ($count_parent > 0) {
								do {
									echo "<option value='" . $row_parent->parentid . "'>" . $row_parent->firstname . " " . $row_parent->lastname . "</option>";
								} while ($row_parent = $result_parent->fetchObject());
							} else {
								echo "No parent found.. please add one";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" name="submit_student" value="Admit Student"
							class="btn btn-block btn-info"><i class="fas fa-database">Admit Student</i></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- teacher form-->
<div class="modal fade" id="myteacher">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Add Teacher</i></h2>
			</div>
			<div class="modal-body">
				<form enctype="multipart/form-data" method="POST" autocomplete="off">
					<div class="row">
						<div class="col-lg-6">
							<label for="firstname">First Name</label>
							<input type="text" name="firstname" class="form-control" placeholder="Enter First Name"
								required>
						</div>
						<div class="col-lg-6">
							<label for="lastname">Last Name</label>
							<input type="text" name="lastname" class="form-control" placeholder="Enter Last Name"
								required>
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="row">
						<div class="col-lg-6">
							<label for="email">Email</label>
							<input type="email" name="email" class="form-control" placeholder="Enter Email" required>
						</div>
						<div class="col-lg-6">
							<label>Phone</label>
							<input type="text" name="phone" class="form-control" placeholder="Enter Phone" required>
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="row">
						<div class="col-lg-6">
							<label>Assign Subjects</label>
							<?php
							echo "
<dl class='dropdown' style='margin-bottom:0px !important;'> 
<dt><a >
<input type='text' name='subject' class='multiSelodisp form-control' placeholder='Selected subjects'>
<input type='hidden'  id='text' class=' form-control' placeholder='Search Subjects'>
</a>
<p class='multiSel'></p>  
</dt>
<dd>
<div class='mutliSelect'>
<ul style='height:100px;overflow-y:scroll;'>";
							$result_subjects = $pdo->query("select * from subjects order by subject_name asc");
							$row_subjects = $result_subjects->fetchObject();
							$count_subjects = $result_subjects->rowCount();
							if ($count_subjects > 0) {
								$r = 1;
								do {
									echo "
<li><input class='check' type='checkbox' id='" . $r++ . "' value=" . $row_subjects->id . " />" . $row_subjects->subject_name . "</li>";
								} while ($row_subjects = $result_subjects->fetchObject());
							} else {
								echo "<option value=''>There are no subjects in this list.</option>";
							}
							echo "</ul>
</div></dd></dl>";
							?>
						</div>
						<div class="col-lg-6">
							<label for="class_id">Assign Class</label>
							<select name="class_id" class="form-control" required>
								<option value="">Select Class</option>
								<?php
								// Retrieve class options from the database
								$result_classes = $pdo->query("SELECT * FROM classes ORDER BY name ASC");
								// Corrected variable name: $result_classes instead of $result_class
								while ($row_class = $result_classes->fetchObject()) {
									echo "<option value='" . $row_class->id . "'>" . $row_class->name . "</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="row">
						<div class="col-lg-6">
							<label>Teacher Image</label>
							<input type="file" name="image" class="form-control" accept="image/*" required>
						</div>
						<div class="col-lg-6">
							<label for="section">Section*</label>
							<select name="section" class="form-control" required>
								<option value="">Select</option>
								<option value="Section 1">Section A</option>
								<option value="Section 2">Section B</option>
							</select>
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="row">
						<div class="col-lg-6">
							<label for="username">Username</label>
							<input type="text" name="username" class="form-control" placeholder="Enter Username"
								required>
						</div>
						<div class="col-lg-6">
							<label for="password">Password</label>
							<input type="text" name="password" class="form-control" placeholder="Enter Password"
								required>
						</div>
					</div>
					<div class="col-lg-12"><br></div>
					<div class="form-group">
						<label>User Role</label>
						<select class="form-control" name="teacher_role">
							<option>--SELECT--</option>
							<?php
							$result_role = $pdo->query("select * from roles where status=1 order by role_name asc");
							$count_role = $result_role->rowCount();
							$row_role = $result_role->fetchObject();
							if ($count_role > 0) {
								do {
									echo "<option value='" . $row_role->role_name . "'>" . $row_role->role_name . "</option>";
								}
								while ($row_role = $result_role->fetchObject());
							} else {
								echo "No roles found.. please add one";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" name="submit_teacher" class="btn btn-sm btn-info btn-block"><i
								class="fas fa-database"></i> Add Teacher</button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- Role form-->
<div class="modal fade" id="roles">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-white"><i class="fas fa-plus">Add Role</i></h2>
			</div>
			<div class="modal-body">
				<form method="POST" autocomplete="off">
					<div class="form-group">
						<label for="role_name">Role Name</label>
						<input type="text" class="form-control" name="role_name" placeholder="Enter role name" required>
					</div>
					<div class="form-group">
						<button type="submit" name="submit_roles" class="btn btn-sm btn-block btn-info"><i
								class="fas fa-database">Add Role</i></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- subject form-->
<div class="modal fade" id="subject">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-center text-white"><i class="fas fa-plus"> Add Subject</i></h2>
			</div>
			<div class="modal-body">
				<form method="POST" autocomplete="off">
					<div class="form-group">
						<label for="subject">Subject Name</label>
						<input type="text" class="form-control" name="subject_name" placeholder="Enter Subject Name"
							required>
					</div>
					<div class="form-group">
						<label for="subject_code">Subject Code</label>
						<input type="text" class="form-control" name="subject_code" placeholder="Enter Subject Code"
							required>
					</div>
					<div class="form-group">
						<button type="submit" name="submit_subject" class="btn btn-sm btn-block btn-info"><i
								class="fas fa-database">Add Subject</i></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- parent form-->
<div class="modal fade" id="parent">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Add Parent</i></h2>
			</div>
			<div class="modal-body">
				<form method="POST" autocomplete="off">
					<div class="form-group">
						<label for="first_name">First Name</label>
						<input type="text" class="form-control" name="first_name" placeholder="Enter First Name"
							required>
					</div>

					<div class="form-group">
						<label for="last_name">Last Name</label>
						<input type="text" class="form-control" name="last_name" placeholder="Enter Last Name" required>
					</div>
					<div class="form-group">
						<label for="phone">Phone Number</label>
						<input type="text" class="form-control" name="phone" placeholder="Enter Mobile Number" required>
					</div>

					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" class="form-control" name="email" placeholder="example@mail.com...."
							required>
					</div>

					<div class="form-group">
						<label for="username">Username</label>
						<input type="text" class="form-control" name="username" placeholder="Enter Username" required>
					</div>

					<div class="form-group">
						<label for="password">Password</label>
						<input type="text" class="form-control" name="password" placeholder="Enter Password" required>
					</div>
					<div class="form-group">
						<button type="submit" name="submit_parent" class="btn btn-sm btn-block btn-info"><i
								class="fas fa-database">Add Parent</i></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- class form-->
<div class="modal fade" id="class_form">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Add Class</i></h2>
			</div>
			<div class="modal-body">
				<form method="POST" autocomplete="off">
					<div class="form-group">
						<label for="name">Class Name</label>
						<input type="text" name="name" class="form-control" placeholder="Enter Class Name" required>
					</div>
					<div class="form-group">
						<label for="section">Section*</label>
						<select id="section" name="section" class="form-control" required>
							<option value="">Select</option>
							<option value="Section 1">Section A</option>
							<option value="Section 2">Section B</option>
						</select>
					</div>
					<div class="form-group">
						<label for="teacher">Teacher</label>
						<select class="form-control" name="teacher">
							<option>--SELECT--</option>
							<?php
							$result_teacher = $pdo->query("select * from teachers order by firstname asc");
							$count_teacher = $result_teacher->rowCount();
							$row_teacher = $result_teacher->fetchObject();
							if ($count_teacher > 0) {
								do {
									echo "<option value='" . $row_teacher->teacherid . "'>" . $row_teacher->firstname . " " . $row_teacher->lastname . "</option>";
								} while ($row_teacher = $result_teacher->fetchObject());
							} else {
								echo "No teacher found.. please add one";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" name="submit_class" class="btn btn-sm btn-info btn-block"><i
								class="fas fa-database"> Add Class</i></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- message review form -->
<div class="modal fade" id="message_review">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-center text-white"><i class="fas fa-edit">Send Reply</i></h2>
			</div>
			<div class="modal-body">
				<form method="POST" autocomplete="off">
					<input type="hidden" name="reply_status" value="3">
					<input type='hidden' id='messageid' name="message_id">
					<div class="form-group">
						<label for="name">Message Reply</label>
						<textarea class="form-control" name="reply_message" required></textarea>
					</div>

					<div class="form-group">
						<button type="submit" name="submit_reply" class="btn btn-sm btn-info btn-block"><i
								class="fas fa-database"> Send Reply</i></button>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- view message reply -->
<div class="modal fade" id="view_comment">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h2 class="modal-title text-center text-white"><i class="fas fa-edit">Message Reply</i></h2>
			</div>
			<div class="modal-body">
				<p id="message_reply"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" style="float: right"><i
						class="fas fa-expand-arrows-alt"></i> Close</button>
			</div>
		</div>
	</div>
</div>