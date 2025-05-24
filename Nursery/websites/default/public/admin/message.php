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
        <div class="container">
            <div class="form-section card-body col-lg-8">
                <?php
                // Handle form submission for sending a message to a teacher
                if (isset($_POST['send_message'])) {
                    // Retrieve form data
                    $teacherid = $_POST['teacherid'];
                    $teacher_message = addslashes($_POST['teacher_message']);
                    $sent_date = date("Y-m-d H:i:s");
                    $admin = $_SESSION['user_id'];
                    $cat = "admin_teacher";

                    // Check if the teacher and message fields are not empty
                    if (!empty($teacherid) && !empty($teacher_message)) {
                        //create an automatic message id
                        $result_messages = $pdo->query("select * from messages order by id desc");
                        $row_messages = $result_messages->fetchObject();
                        $count_messages = $result_messages->rowCount();
                        // Determine the new message ID
                        if ($count_messages <= 0) {
                            $messageid = "mgs1";
                        } else {
                            $messageid = "msg" . ($row_messages->id + 1);
                        }
                        // Insert the new message into the `messages` table
                        $insert_message = $pdo->query("insert into messages(teacherid,parentid,message,sent_at,messageid,cat) values('$teacherid','$admin','$teacher_message','$sent_date','$messageid','$cat')");
                        // Display a success or error message based on the query result
                        if ($insert_message) {
                            echo "<div class='alert alert-info'>Message successfuly sent, open your inbox to see teacher reply</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Message not sent, try again</div>";
                        }
                    } else {
                        // Display a warning message if any field is missing
                        echo "<div class='alert alert-warning'>Fill all fields</div>";
                    }
                }

                ?>
                <h2>Send Message to Teacher</h2>
                <!-- Form to send a message to a teacher -->
                <form method="post" autocomplete="off">
                    <div class="form-field">
                        <label for="teacherid">Select Teacher:</label>
                        <select name="teacherid" required>
                            <option value="">Select Teacher</option>
                            <?php
                            try {
                                // Fetch all active teachers from the database
                                $result_teachers = $pdo->query("select * from teachers where status=1 order by firstname asc");
                                $count_teachers = $result_teachers->rowCount();
                                $row_teachers = $result_teachers->fetchObject();
                                // Check if there are any teachers and loop through the results
                                if ($count_teachers > 0) {
                                    do {
                                        echo "<option value='" . $row_teachers->teacherid . "'>" . $row_teachers->firstname . " " . $row_teachers->lastname . "</option>";
                                    } while ($row_teachers = $result_teachers->fetchObject());
                                } else {
                                    // Display a message if no teachers are registered
                                    echo "No teachers registered, contact IT....";
                                }
                            } catch (PDOException $e) {
                                // Display an error message if the teachers couldn't be fetched
                                die("No teacher found: " . $e->getMessage());
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-field">
                        <label for="message">Message:</label>
                        <textarea name="teacher_message" required style="width: 100%;"></textarea>
                    </div>

                    <!-- Submit button to send the message -->
                    <button type="submit" name="send_message" class="submit-btn">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>