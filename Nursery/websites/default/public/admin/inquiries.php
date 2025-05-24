<?php
// Start output buffering and session management
ob_start();
session_start();
// Include the database connection and header files
require_once '../db.php';
require_once '../header.php';
// Use PHPMailer classes
use phpmailer\phpmailer\PHPMailer;
use phpmailer\phpmailer\Exception;

// Include PHPMailer files
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

// Retrieve the current user's ID from session
$userid = $_SESSION['user_id'];
// check if the user is logged in as a admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php'); // Redirect to login page if not logged in as an admin
    exit;
}
?>
<div id="wrapper">
    <!-- Include the sidebar menu -->
    <?php include ('sidemenu.php'); ?>
    <div id="main-content">
        <div class="card card-default">
            <div class="card-header d-flex p-0">
                <h4 class="card-title text-black p-3">INQUIRY | RESPONSES</h4>
            </div>
            <div class="card-body">
                <input type='hidden' id='no' value='0'>
                <?php
                //update inquiry response
                if (isset($_POST['update_inquiry'])) {
                    // Retrieve form data
                    $inquiry_id = $_POST['inquiry_id'];
                    $inquiry_email = $_POST['inquiry_email'];
                    $inquiry_name = $_POST['inquiry_name'];
                    $response_details = addslashes($_POST['response_details']);
                    $response_date = date("Y-m-d");

                    // Update the inquiry with the response details
                    if (!empty($inquiry_id) && !empty($inquiry_email) && !empty($response_details)) {
                        $update_inquiry = $pdo->query("update inquiries set response='$response_details', response_date='$response_date', responded_by='$userid' where id='$inquiry_id'");

                        // Display a success or error message based on the query result
                        if ($update_inquiry) {
                            echo "<div class='alert alert-warning'>inquiry response set successfully</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Response not sent</div>";
                        }
                    }
                    // Send the response via email using PHPMailer
                    //variables
                    $subject = "LITTLE NURSERY INQUIRY RESPONSES";
                    $mail = new PHPMailer(true);
                    try {
                        //Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'alameamal12@gmail.com';
                        $mail->Password = 'rbqc zmok odar gqdx';
                        $mail->SMTPOptions = array(
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        );
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        //Send Email
                        $mail->setFrom('alameamal12@gmail.com');

                        //Recipients
                        $mail->addAddress($inquiry_email);
                        $mail->addReplyTo('alameamal12@gmail.com');

                        //Email Content
                        $mail->isHTML(true);
                        $mail->Subject = $subject;
                        $mail->Body = $response_details;

                        // Send the email
                        $mail->send();

                    } catch (Exception $e) {
                        // Display an error message if the email couldn't be sent
                        echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
                    }

                }
                ?>
                <!-- Table displaying the list of inquiries -->
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all inquiries from the database
                        $result_inquiry = $pdo->query("select * from inquiries where status=1 order by id asc");
                        $count_inquiry = $result_inquiry->rowCount();
                        $row_inquiry = $result_inquiry->fetchObject();
                        // Check if there are any inquiries and loop through the results
                        if ($count_inquiry > 0) {
                            $no = 1;
                            do {
                                echo "
<tr>
<td>" . $no++ . "</td>
<td>" . $row_inquiry->full_name . "</td>
<td>" . $row_inquiry->email . "</td>
<td style='width:40%; min-width:500px;white-space:normal;'>" . $row_inquiry->message . "</td>
<td>
<button onClick='show_inquiry(" . $row_inquiry->id . ")' class='btn btn-sm btn-info'>Edit</button>
</td>
</tr><tr></tr>"; ?>
                                <tr id='inquirydata<?php echo $row_inquiry->id; ?>'
                                    onClick="get_no('<?php echo $row_inquiry->id; ?>')"
                                    style='border-bottom: 2px solid red;border-top: 2px solid red;display:none;'>
                                    <?php echo "<form  method='POST'>
<input type='hidden' name='inquiry_id' value='" . $row_inquiry->id . "'>
<input type='hidden' name='inquiry_email' value='" . $row_inquiry->email . "'>
<input type='hidden' name='inquiry_name' value='" . $row_inquiry->full_name . "'>
<td colspan='5'>
<div class='form-group'>
<label class='form-label'>Response</label>
<textarea type='text' name='response_details' class='form-control' placeholder='Enter Response'></textarea>
</div>    
<div class='form-group'>
<input type='submit' name='update_inquiry' class='btn btn-sm btn-primary btn-block' value='Update'></div></td></form></tr>";
                            } while ($row_inquiry = $result_inquiry->fetchObject());
                        } else {
                            // Display a message if no inquiries are found
                            echo "<tr><td colspan='5' class='text-center'>No student rollcalled today. Take rollcall and check again</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Include jQuery and Bootstrap JavaScript files -->
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
    // Toggle the inquiry response form visibility
    function show_inquiry(ai) { $("#inquirydata" + ai).toggle(); }
</script>
</body>

</html>