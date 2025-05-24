<?php
// Start output buffering and initialize session
ob_start();
session_start();
// Include database connection and header files
require_once '../db.php';
require_once '../header.php';

// Check if the user is logged in as a parent
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'parent') {
    header('Location: ../login.php'); // Redirect to login page if not logged in or not a teacher
    exit;
}
?>
<div id="wrapper">
    <!-- Include the parent sidebar menu -->
    <?php include ('parentsidemenu.php'); ?>
    <div id="main-content">
        <div class="card card-default">
            <div class="card-header">
                <h4 class="card-title" style="color: #000000;font-weight: bold; ">Parent Inbox</h4>
            </div>
            <div class="card-body">
                <!-- Table to display parent inbox messages -->
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
                            // Retrieve parent ID from session
                            $userid = $_SESSION['user_id'];
                            // Fetch all messages sent to the parent
                            $result_messages = $pdo->query("select * from messages where parentid='$userid' order by id desc");
                            $count_messages = $result_messages->rowCount();
                            $row_messages = $result_messages->fetchObject();
                            // Check if any messages are available
                            if ($count_messages > 0) {
                                $no = 1;
                                do {
                                    // Fetch the teacher details who sent the message
                                    $result_teacher = $pdo->query("select * from teachers where teacherid='$row_messages->teacherid'");
                                    $count_teacher = $result_teacher->rowCount();
                                    $row_teacher = $result_teacher->fetchObject();
                                    // Determine message read status and background color
                                    if ($row_messages->status == 2) {
                                        $ffcolor = "style='background-color:blue;color:white;'";
                                    } elseif ($row_messages->status == 3) {
                                        $ffcolor = "style='background-color:green;color:white;'";
                                    } else {
                                        $ffcolor = "";
                                    }
                                    // Determine message read status label
                                    if ($row_messages->status == 1) {
                                        $stat = "Not viewed";
                                    } elseif ($row_messages->status == 2) {
                                        $stat = "Seen";
                                    } else {
                                        $stat = "";
                                    }
                                    // Display each message in a table row
                                    echo "<tr " . $ffcolor . ">
<td>" . $no++ . "</td>
<td style='width:40%; min-width:500px;white-space:normal;'>" . $row_messages->message . "</td>
<td>";
                                    // Display the teacher's name and phone number
                                    if ($count_teacher > 0) {
                                        echo $row_teacher->firstname . " " . $row_teacher->lastname . "<br><span>[" . $row_teacher->phone . "]</span>";
                                    }
                                    echo "</td>
<td>" . date('Y-m-d H:i:s', strtotime($row_messages->sent_at)) . "</td>
<td>" . $stat . "<br>";
                                    // Check if there are any message reviews available for the message
                                    $result_review = $pdo->query("select * from message_review where messageid='$row_messages->messageid'");
                                    $count_review = $result_review->rowCount();
                                    // Provide a button to view the message review if available
                        
                                    if ($count_review > 0 && $row_messages->status = 3) {
                                        echo "<a type='button' data-toggle='modal' data-target='#view_comment' class='btn btn-sm btn-warning viewCom' data-id='" . $row_messages->messageid . "'><i class='fas fa-eye'></i></a>";
                                    }
                                    echo " </td>
</tr>";
                                } while ($row_messages = $result_messages->fetchObject());
                            } else {
                                // Display a message if no messages are available
                                echo "<tr class='text-center'><td colspan='5'>No previous messages attached on this chat</td></tr>";
                            }
                        } catch (PDOException $e) {
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
<?php include ("../admin/forms.php"); ?>
<script>
    $(function () {
        // // Handle click event to view submitted comment
        $(document).on('click', '.viewCom', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            getRow(id);
        });

    });
    // Fetch and display the submitted comment using AJAX
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