<?php
// Start output buffering and session management
ob_start();
session_start();
// Include the database connection and header files
require '../db.php';
require_once '../header.php';
// Check if the user is logged in and is an admin
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
        <div class="container-fluid">
            <div id="accordion">
                <!-- Card to add a new event -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><a data-toggle="collapse" data-parent="#accordion" href="#task"
                                class="btn btn-sm btn-primary" style="width: 100%;">ADD EVENT</a></h4>
                    </div>
                    <div id="task" class="panel-collapse collapse in">
                        <div class="card-body">
                            <!-- Form to submit a new event -->
                            <form method="post" autocomplete="off" enctype="multipart/form-data">
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <label>Event Name</label>
                                        <input type='text' class='form-control' name='eventname'
                                            placeholder="Event Name" required>
                                    </div>
                                    <div class='col-md-6'>
                                        <label>Event Date</label>
                                        <input type='date' class='form-control' name='eventdate' required>
                                    </div>
                                </div>
                                <div class='col-lg-12'><br></div>
                                <div class="row">
                                    <div class='col-lg-6'>
                                        <label>Attach Category</label>
                                        <select name="category" class="form-control">
                                            <option>Category</option>
                                            <option value="Admission">Admission</option>
                                            <option value="Academics">Academics</option>
                                            <option value="Sports">Sports</option>
                                            <option value="Pastoral">Pastoral</option>
                                        </select>
                                    </div>
                                    <div class='col-lg-6'>
                                        <label>Event Image</label>
                                        <input type="file" name="event_image" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12"><br></div>
                                <div class="form-group">
                                    <textarea placeholder="Details" name="details" class="form-control"
                                        rows="5"></textarea>
                                </div>
                                <div class="form-group">
                                    <input type='submit' name='submit_event' class='btn btn-sm btn-success form-control'
                                        value="Submit">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Card to display upcoming events -->
                <div class="card">
                    <div class="card-header bg-dark">
                        <h3 class="card-title text-center text-light"><b>UPCOMING EVENTS</b></h3>
                    </div>
                    <div class="card-body">
                        <?php
                        // Handle event submission
                        if (isset($_POST['submit_event'])) {
                            $category = $_POST['category'];
                            $eventname = $_POST['eventname'];
                            $eventdate = $_POST['eventdate'];
                            $details = addslashes($_POST['details']);
                            // Check if an event image was uploaded
                            if (!empty($_FILES['event_image']['tmp_name'])) {
                                // Generate a unique filename
                                $filename = str_replace(' ', '_', date('i') . "" . date('s') . $_FILES['event_image']['name']);
                                // destination of the file on the server
                                $destination = '../uploads/' . $filename;
                                // get the file extension
                                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                // the physical file on a temporary uploads directory on the server
                                $file = $_FILES['event_image']['tmp_name'];
                                $size = $_FILES['event_image']['size'];
                                if (!in_array($extension, ['pdf', 'docx', 'xls', 'png', 'jpg', 'jpeg', 'gif', 'xlsx', 'doc', 'odt', 'txt', 'ods'])) {
                                    echo "You file extension must be .pdf, .xls, .png, .jpg, .jpeg, .gif, .xlsx, .doc, .odt, .txt, .ods or .docx";
                                } elseif ($_FILES['event_image']['size'] > 1000000000) { // file shouldn't be larger than 1GB
                                    echo "File too large!";
                                } else {
                                    // Insert event data into the `events` table
                                    $insert_events = $pdo->query("insert into events (category,eventname,eventdate,event_image,fsize,details) values ('$category','$eventname','$eventdate','$filename','$size','$details')");
                                    if ($insert_events) {
                                        echo "<div class='alert alert-success'>Event Set Successfully</div>";
                                    } else {
                                        echo "<div class='alert alert-danger'>Event Not Added</div>";
                                    }
                                    // Move the uploaded (temporary) file to the specified destination
                                    if (move_uploaded_file($file, $destination)) {
                                        echo "<div class='alert alert-success'>Image Uploaded.......</div>";
                                    }
                                }
                            }
                        }
                        // Handle event update
                        if (isset($_POST['update_events'])) {
                            $eventname = $_POST['eventname'];
                            $category = $_POST['category'];
                            $details = addslashes($_POST['details']);
                            $eventid = $_POST['eventid'];
                            $eventdate = $_POST['eventdate'];
                            //handle image update
                            if (!empty($_FILES['eventimage']['tmp_name'])) {
                                $filename = str_replace(' ', '_', date('i') . "" . date('s') . $_FILES['eventimage']['name']);
                                // destination of the file on the server
                                $destination = '../uploads/' . $filename;
                                // get the file extension
                                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                // the physical file on a temporary uploads directory on the server
                                $file = $_FILES['eventimage']['tmp_name'];
                                $size = $_FILES['eventimage']['size'];
                                // Check if the file extension is allowed
                                if (!in_array($extension, ['pdf', 'docx', 'xls', 'png', 'jpg', 'jpeg', 'gif', 'xlsx', 'doc', 'odt', 'txt', 'ods'])) {
                                    echo "You file extension must be .pdf, .xls, .png, .jpg, .jpeg, .gif, .xlsx, .doc, .odt, .txt, .ods or .docx";
                                } elseif ($_FILES['eventimage']['size'] > 1000000000) { // file shouldn't be larger than 1GB
                                    echo "File too large!";
                                } else {
                                    // Check if the file extension is allowed
                                    $update_teacher = $pdo->query(" UPDATE events SET event_image='$filename', fsize='$size' WHERE id='$eventid'");

                                    // move the uploaded (temporary) file to the specified destination
                        
                                    if (move_uploaded_file($file, $destination)) {
                                        echo "<div class='alert alert-success'>Image Updated</div>";
                                    }
                                }
                            }
                            // Update the event details in the `events` table
                            $update_events = $pdo->query("UPDATE events SET eventname='$eventname',category='$category',details='$details', eventdate='$eventdate' where id='$eventid'");
                            if ($update_events) {
                                echo "<div Class='alert alert-success'>Success event details updated</div>";
                            }
                        }
                        //archive events by changing status instead of deleting it completely
                        if (isset($_POST['delete_event'])) {
                            $status = '0';
                            $eventid = $_POST['eventid'];
                            // Update the event status to '0' (archived)
                            $archive_events = $pdo->query("UPDATE events SET status='$status' WHERE id='$eventid'");
                            if ($archive_events) {
                                echo "<div class='alert alert-warning'>Event has been deleted</div>";
                            }
                        }
                        ?>
                        <input type='hidden' id='no' value='0'>
                        <!-- Table to display the list of upcoming events -->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Image</th>
                                    <th>Event Name</th>
                                    <th>Happening Date</th>
                                    <th>Category</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <?php
                            // Fetch active events from the database
                            $result_events = $pdo->query("select * from events where status=1 order by eventdate asc");
                            $row_events = $result_events->fetchObject();
                            $count_events = $result_events->rowCount();
                            if ($count_events > 0) {
                                $z = 1;
                                do {
                                    // Default event image if none is provided
                                    $image = (!empty($row_events->event_image)) ? $row_events->event_image : '../uploads/avatar.png';
                                    echo "<tr>
<td>" . $z++ . "</td>
<td><img src='../uploads/" . $image . "' alt='Add Images' style='width:50px;max-height:50px;height: expression(this.height > 50 ? 50: true);min-height:50px;height: expression(this.height < 50 ? 50: true);' class='img-circle'></td>
<td>" . $row_events->eventname . "</td>
<td style='color:blue;font-weight:bold;'>" . $row_events->eventdate . "</td>
<td>" . $row_events->category . "</td>
<td>"; ?>
                                    <!-- Form to archive (delete) an event -->
                                    <form method='post' onsubmit="return delete_checker('Event','Deleted');">
                                        <?php echo "
<input type='hidden' name='eventid' value=" . $row_events->id . ">
<button type='submit' name='delete_event' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
<td><button onClick='show_dis(" . $row_events->id . ")' class='btn btn-sm btn-warning'>Edit</button></td></tr> ";
                                        echo "<tr>
<td colspan='7' style='border-top:none; border-bottom:1px dashed black;'><b>Abouts : </b><br><span style='color:maroon;'>" . $row_events->details . "</span></td>
</tr>
<tr id='dis_edit" . $row_events->id . "' style='border-bottom: 2px solid red;border-top: 2px solid red;display:none;'><form method='post' enctype='multipart/form-data'>
<input type='hidden' name='eventid' value='" . $row_events->id . "'>  
<td colspan='7'><div class='row'>
<div class='col-lg-6'>Event Name : 
<input type='text' name='eventname' class='form-control' value='" . $row_events->eventname . "'></div>
<div class='col-lg-6'>Category :
<input type='text' name='category' class='form-control' value='" . $row_events->category . "'>
</div></div>
<div class='row'>
<div class='col-lg-6'>Image : 
<input type='file' name='eventimage' class='form-control'></div>
<div class='col-lg-6'>Date :
<input type='date' name='eventdate' class='form-control' value='" . $row_events->eventdate . "'>
</div></div>
<div class='row'>
<div class='col-lg-12'>Details :
<input type='text' class='form-control' name='details' value='" . $row_events->details . "' required></div></div>
<div class='col-lg-12'><br></br>
<div class='form-group'><input type='submit' name='update_events' class='btn btn-sm btn-success form-control' value='Update'></div></td></form></tr>";
                                } while ($row_events = $result_events->fetchObject());
                            } else {
                                // Display a message if no events are found
                                echo "<tr><td colspan='20' align='center'>There is No data here. </td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Include jQuery and Bootstrap JavaScript files -->
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
    // Toggle the edit form
    function show_dis(ai) { $("#dis_edit" + ai).toggle(500); }
    function display_row(ai) { $("#rows" + ai).toggle(); }
    // Set the value of a hidden field
    function get_no(ai) { document.getElementById("no").value = ai; }
</script>
<script>
    // Confirm before deleting an event
    function delete_checker(names, act) {
        var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");
        if (confirmer == false) { return false; }
    }
</script>
</body>

</html>