<?php
// Start output buffering and session management
ob_start();
session_start();

// Include the database connection and header files
require_once '../db.php';
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
        <!-- Button to navigate to the message page -->
        <div class="col-lg-12">
            <div class="col-lg-3"><a href="message.php" class="btn btn-sm btn-primary" style="width: 100%;">SEND
                    MESSAGE</a></div>
        </div>
        <!-- Card to display the admin inbox -->
        <div class="col-lg-12"><br></div>
        <div class="card card-default">
            <div class="card-header">
                <h4 class="card-title" style="color: #000000;font-weight: bold; ">Admin Inbox</h4>
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Message</th>
                            <th>Teacher Name</th>
                            <th>Sent At</th>
                            <th>Read Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // Retrieve the user ID from the session
                            $userid = $_SESSION['user_id'];

                            // Fetch messages sent to the current user (admin)
                            $result_messages = $pdo->query("select * from messages where parentid='$userid' order by id desc");
                            $count_messages = $result_messages->rowCount();
                            $row_messages = $result_messages->fetchObject();
                            // Check if there are any messages and loop through the results
                            if ($count_messages > 0) {
                                $no = 1;
                                do {
                                    // Fetch the teacher who sent the message
                                    $result_teacher = $pdo->query("select * from teachers where teacherid='$row_messages->teacherid'");
                                    $count_teacher = $result_teacher->rowCount();
                                    $row_teacher = $result_teacher->fetchObject();
                                    // Determine the message's read status and apply appropriate color styling
                                    if ($row_messages->status == 2) {
                                        $ffcolor = "style='background-color:blue;color:white;'";
                                    } elseif ($row_messages->status == 3) {
                                        $ffcolor = "style='background-color:green;color:white;'";
                                    } else {
                                        $ffcolor = "";
                                    }
                                    //Convert the message status to readable text
                                    if ($row_messages->status == 1) {
                                        $stat = "Not viewed";
                                    } elseif ($row_messages->status == 2) {
                                        $stat = "Seen";
                                    } else {
                                        $stat = "";
                                    }
                                    // Output the message row
                                    echo "<tr " . $ffcolor . ">
<td>" . $no++ . "</td>
<td style='width:40%; min-width:500px;white-space:normal;'>" . $row_messages->message . "</td>
<td>";
                                    if ($count_teacher > 0) {
                                        echo $row_teacher->firstname . " " . $row_teacher->lastname . "<br><span>[" . $row_teacher->phone . "]</span>";
                                    }
                                    echo "</td>
<td>" . date('Y-m-d H:i:s', strtotime($row_messages->sent_at)) . "</td>
<td>" . $stat . "<br>";
                                    // Fetch message reviews (comments) if any
                                    $result_review = $pdo->query("select * from message_review where messageid='$row_messages->messageid'");
                                    $count_review = $result_review->rowCount();
                                    if ($count_review > 0 && $row_messages->status = 3) {
                                        echo "<a type='button' data-toggle='modal' data-target='#view_comment' class='btn btn-sm btn-warning viewCom' data-id='" . $row_messages->messageid . "'><i class='fas fa-eye'></i></a>";
                                    }
                                    echo " </td>
</tr>";
                                } while ($row_messages = $result_messages->fetchObject());
                            } else {
                                // Display a message if no messages are found
                                echo "<tr class='text-center'><td colspan='5'>No previous messages attached on this chat</td></tr>";
                            }
                        } catch (PDOException $e) {
                            // Display an error message if the messages could not be fetched
                            die("Could not fetch messages: " . $e->getMessage());
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
<!-- Include forms for additional functionalities -->
<?php include ("../admin/forms.php"); ?>
<script>
    // jQuery function to handle viewing message comments
    $(function () {
        // Triggered when the view comment button is clicked
        $(document).on('click', '.viewCom', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            getRow(id);
        });

    });
    // Fetch the message reply (comments) via AJAX and display it in the modal
    function getRow(id) {
        $.ajax({
            type: 'POST',
            url: '../teacher/viewMessageReply.php',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                $('#message_reply').html(response.message_review);
            }
        });
    }
</script>
</body>

</html>