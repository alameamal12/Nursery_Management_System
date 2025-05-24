<?php
session_start();
// Include header and database connection files
require '../header.php';
require '../db.php';
?>
</head>

<body>
	<div id="contentWrapper">
		<!-- Include the sidebar menu -->
		<?php include ('sidemenu.php'); ?>
		<div class="container-fluid">
			<div class="card card-info" style="margin-top: 30px;">
				<div class="card-header d-flex p-0">
					<h4 class="card-title p-3">MANAGE | COMUNICATION</h4>
				</div>
				<div class="card-body">
					<?php
					// Handle form submission for sending a new message
					if (isset($_POST['submit_message'])) {
						// Retrieve form data
						$message_title = $_POST['message_title'];
						$message_date = $_POST['message_date'];
						$group = $_POST['group'];
						$details = addslashes($_POST['details']);
						// Insert the new message into the `notifications` table
						$insert_notifications = $pdo->query("insert into notifications(title,not_date,not_group,details) values('$message_title','$message_date','$group','$details')");
						// Display a success or error message based on the query result
						if ($insert_notifications) {
							echo "<div class='alert alert-success'>Success.....notification sent</div>";
						} else {
							echo "<div class='alert alert-danger'>Notification not sent</div>";
						}
					}
					?>
					<div id="accordion">
						<!-- Card to create a new notification -->
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"><a data-toggle="collapse" data-parent="#accordion" href="#task"
										class="btn btn-sm btn-primary" style="width: 100%;">CREATE NEW NOTIFICATION</a>
								</h4>
							</div>
							<div id="task" class="panel-collapse collapse in">
								<div class="card-body">
									<!-- Form to create a new notification -->
									<form method="post" autocomplete="off">
										<div class='row'>
											<div class='col-md-4'>
												<label>Message Title</label>
												<input type='text' class='form-control' name='message_title'
													placeholder="Message Title" required>
											</div>
											<div class='col-md-4'>
												<label>Message Date</label>
												<input type='date' class='form-control' name='message_date' required>
											</div>
											<div class='col-md-4'>
												<label>Intended Group</label>
												<select class="form-control" name="group" required>
													<option>--SELECT--</option>
													<?php
													// Fetch all active roles from the database
													$result_role = $pdo->query("select * from roles where status=1 order by role_name asc");
													$count_role = $result_role->rowCount();
													$row_role = $result_role->fetchObject();
													// Check if there are any roles and loop through the results
													if ($count_role > 0) {
														do {
															echo "<option value='" . $row_role->role_name . "'>" . $row_role->role_name . "</option>";
														}
														while ($row_role = $result_role->fetchObject());
													} else {
														// Display a message if no roles are registered
														echo "No roles registered...";
													}
													?>
												</select>
											</div>
										</div>
										<div class='col-lg-12'><br></div>
										<div class="col-lg-12"><br></div>
										<div class="form-group">
											<label>Message Body</label>
											<textarea placeholder="Details" name="details" class="form-control" rows="5"
												required></textarea>
										</div>
										<!-- Submit button to create the notification -->
										<div class="form-group">
											<input type='submit' name='submit_message'
												class='btn btn-sm btn-success form-control' value="Submit">
										</div>
									</form>
								</div>
							</div>
						</div>
						<!-- Card to display the message inbox -->
						<div class="card">
							<div class="card-header bg-dark">
								<h3 class="card-title text-center text-light"><b>MESSAGE INBOX</b></h3>
							</div>
							<div class="card-body">

								<input type='hidden' id='no' value='0'>
								<!-- Table to display the list of notifications -->
								<table class="table table-striped table-bordered">
									<thead>
										<tr>
											<th>Abouts</th>
											<th>Group</th>
										</tr>
									</thead>
									<tbody>
										<?php
										// Fetch all notifications from the database
										$result_nots = $pdo->query("select * from notifications order by id desc");
										$row_nots = $result_nots->fetchObject();
										$count_nots = $result_nots->rowCount();
										// Check if there are any notifications and loop through the results
										if ($count_nots > 0) {
											do {
												echo "<tr>
<td><span style='color:maroon'>" . $row_nots->title . "</span><br><span style='color:blue;'>" . $row_nots->not_date . "</span><br></td>
<td>" . $row_nots->not_group . "</td>
</tr>
<tr>
<td colspan='4' style='border-top:none; border-bottom:1px dashed yellow;'><b>Notes : </b><br>" . $row_nots->details . "</td>
</tr>";
											} while ($row_nots = $result_nots->fetchObject());
										} else {
											// Display a message if no notifications are found
											echo "<tr><td colspan='10' align='center'>There is no data as yet.</td></tr>";
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

			</div>
		</div>
	</div>
</body>

</html>