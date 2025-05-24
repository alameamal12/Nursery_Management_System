<?php
// Start output buffering and initialize session
ob_start();
session_start();
// Include database connection and header files
require '../db.php';
require_once '../header.php';
// Check if the user is logged in and is a parent, if not redirect to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
header('Location: ../login.php');
exit;
}
?>
<div id="wrapper">
     <!-- Include the parent sidebar menu -->
<?php include('parentsidemenu.php'); ?>
<div id="main-content">
<div class="container">
<div class="form-section card-body col-lg-8">
<?php
// Check if the form to send a message has been submitted
if (isset($_POST['send_message'])) {
// Retrieve form data
$teacherid = $_POST['teacherid'];
$parent_message=addslashes($_POST['parent_message']);
$sent_date=date("Y-m-d H:i:s");
$parentid = $_SESSION['user_id'];
$cat="teacher_parent";
// Ensure all required fields are filled
if(!empty($teacherid) && !empty($parent_message)){
//create an automatic message id
$result_messages=$pdo->query("select * from messages order by id desc"); 
$row_messages=$result_messages->fetchObject(); 
$count_messages=$result_messages->rowCount();
// Generate a new message ID
if($count_messages<=0){
$messageid="mgs1"; 
}else{$messageid="msg".($row_messages->id+1); } 
//insert the new message into the messages table
$insert_message=$pdo->query("insert into messages(teacherid,parentid,message,sent_at,messageid,cat) values('$teacherid','$parentid','$parent_message','$sent_date','$messageid','$cat')");
// Display a success or error message based on the query result

if($insert_message){echo "<div class='alert alert-info'>Message successfuly sent, open your history to see teacher reply</div>";}
else{echo "<div class='alert alert-danger'>Message not sent, try again</div>";}
// Display a warning message if required fields are not filled
}else{echo "<div class='alert alert-warning'>Fill all fields</div>";}
}

?>
<h2>Send Message to Teacher</h2>
<!-- Form to send a message to the teacher -->
<form method="post" autocomplete="off">
<div class="form-field">
<label for="teacherid">Select Teacher:</label>
<select name="teacherid" required>
<option value="">Select Teacher</option>
<?php
// Fetch the list of active teachers
try{
$result_teachers=$pdo->query("select * from teachers where status=1 order by firstname asc");
$count_teachers=$result_teachers->rowCount();
$row_teachers=$result_teachers->fetchObject();
// Check if there are any teachers and loop through the results
if($count_teachers>0){do{
echo "<option value='".$row_teachers->teacherid."'>".$row_teachers->firstname." ".$row_teachers->lastname."</option>";
}while($row_teachers=$result_teachers->fetchObject());}
// Display a message if no teachers are registered
else{ echo "No teachers registered, contact IT...."; }
}catch(PDOException $e){
die("No teacher found: ". $e->getMessage());
}
?>
</select>
</div>
<div class="form-field">
<label for="message">Message:</label>
<textarea name="parent_message" required style="width: 100%;"></textarea>
</div>

<button type="submit" name="send_message" class="submit-btn">Send Message</button>
</form>
</div>
</div>
</div>
</div>
</body>
</html>
