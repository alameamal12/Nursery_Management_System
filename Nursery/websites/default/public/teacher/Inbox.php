<?php
ob_start();
session_start();
// Include the database connection and header files
require_once '../db.php';
require_once '../header.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'teacher') {
    header('Location: ../login.php'); // Redirect to login page if not logged in as a teacher
    exit;
}
// Retrieve the teacher's user ID
$userid = $_SESSION['user_id'];
?>
<div id="wrapper">
    <?php include ('teachersidemenu.php'); ?>
    <div id="main-content">
        <div class="card card-default">
            <div class="card-header">
                <h4 class="card-title" style="color: #000000;font-weight: bold; ">Teacher Inbox</h4>
            </div>
            <div class="card-body">
                <?php
                //// Update message status to 'viewed'
                if (isset($_POST['submit_status'])) {
                    $status = $_POST['status'];
                    $autoid = $_POST['autoid'];
                    // Ensure both status and autoid are not empty
                    if (!empty($status) && !empty($autoid)) {
                        $update_messages = $pdo->query("UPDATE messages SET status='$status' WHERE id='$autoid'");
                    }
                }
                //submit message reply
                if (isset($_POST['submit_reply'])) {
                    $reply_status = $_POST['reply_status'];
                    $reply_message = addslashes($_POST['reply_message']);
                    $message_id = $_POST['message_id'];
                    $commenterid = $_SESSION['user_id'];
                    // Ensure required fields are not empty
                    if (!empty($message_id) && !empty($reply_message) && !empty($commenterid)) {
                        $insert_message_review = $pdo->query("insert into message_review(messageid,message_review,userid) values('$message_id','$reply_message','$commenterid')");
                        if ($insert_message_review) {
                            $update_messages = $pdo->query("UPDATE messages SET status='$reply_status' WHERE messageid='$message_id'");
                            echo "<div class='alert alert-success'>Message reply sent.......</div>";
                        }
                    }
                }
                //pre set value to view messages 
                if (isset($_POST["toshow"])) {
                    $toshow = $_POST["toshow"];
                    // Determine display group based on the selected value
                    if ($toshow == "teacher_parent") {
                        $display = "Parent";
                    } elseif ($toshow == "admin_teacher") {
                        $display = "Admin";
                    } else {
                        $display = "";
                    }
                    // Retrieve the current group for message filtering
                    $result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='message'");
                    $count_toshow = $result_toshow->rowCount();
                    if ($count_toshow <= 0) {
                        $insert_toshow = $pdo->query("insert into toshow (item2,item3,item4,typez) values('$userid','$toshow','$display','message')");
                    } else {
                        $update_toshow = $pdo->query("update toshow set item3='$toshow',item4='$display' where item1='toshow' and typez='message' and item2='$userid'");
                    }
                }
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <label>Pre-set Group </label>
                        <form method='post'>
                            <select class='form-control' name='toshow' onchange="this.form.submit()">
                                <?php
                                $result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='message'");
                                $row_toshow = $result_toshow->fetchObject();
                                $count_toshow = $result_toshow->rowCount();
                                if ($count_toshow > 0) {
                                    echo "<option value='" . $row_toshow->item2 . "'>View Messages For : " . $row_toshow->item4 . "</option>";
                                } else {
                                    echo "<option value=''>--SELECT--</option>";
                                } ?>
                                <option value="teacher_parent">Parent</option>
                                <option value="admin_teacher">Admin</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="col-lg-12"><br></div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Message</th>
                            <th>Sender Name</th>
                            <th>Sent At</th>
                            <th>Read Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // Retrieve messages based on the preset group
                            $userid = $_SESSION['user_id'];
                            $result_toshow = $pdo->query("select * from toshow where item1='toshow' and item2='$userid' and typez='message'");
                            $row_toshow = $result_toshow->fetchObject();
                            $count_toshow = $result_toshow->rowCount();
                            $result_messages = $pdo->query("select * from messages where teacherid='$userid' and cat='$row_toshow->item3' order by id desc");
                            $count_messages = $result_messages->rowCount();
                            $row_messages = $result_messages->fetchObject();
                            if ($count_messages > 0) {
                                $no = 1;
                                do {
                                    // Retrieve parent details who sent the message
                                    $result_parents = $pdo->query("select * from parents where parentid='$row_messages->parentid'");
                                    $count_parents = $result_parents->rowCount();
                                    $row_parents = $result_parents->fetchObject();
                                    //message message read status and color changes
                                    if ($row_messages->status == 2) {
                                        $ffcolor = "style='background-color:blue;color:white;'";
                                    } elseif ($row_messages->status == 3) {
                                        $ffcolor = "style='background-color:green;color:white;'";
                                    } else {
                                        $ffcolor = "";
                                    }
                                    echo "<form method='post'>
<input type='hidden' name='messageid' value='" . $row_messages->messageid . "'>
<input type='hidden' name='autoid' value='" . $row_messages->id . "'>";
                                    if ($row_messages->status == 1) {
                                        echo "<input type='hidden' name='status' value='2'>";
                                        $actbutton = "<br><button type='submit' class='btn btn-sm btn-success' name='submit_status'><i class='fas fa-check-circle'></i></button></form>";
                                    }
                                    // Display message details in table row
                                    echo "<tr " . $ffcolor . ">
<td>" . $no++ . "</td>
<td style='width:40%; min-width:500px;white-space:normal;'>" . $row_messages->message . "</td>
<td>";
                                    if ($count_parents > 0) {
                                        echo $row_parents->firstname . " " . $row_parents->lastname . "<br><span>[" . $row_parents->phone . "]</span>";
                                    } elseif ($row_messages->parentid == "admin") {
                                        echo "Admin";
                                    }
                                    echo "</td>
<td>" . date('Y-m-d H:i:s', strtotime($row_messages->sent_at)) . "</td>
<td class='td'>";
                                    if ($row_messages->status == 1) {
                                        echo $actbutton;
                                    }
                                    echo "<br>";
                                    // Retrieve message reviews and display buttons accordingly
                                    $result_review = $pdo->query("select * from message_review where userid='$userid' and messageid='$row_messages->messageid'");
                                    $count_review = $result_review->rowCount();
                                    if ($count_review <= 0 && $row_messages->status == 2) {
                                        echo "<a type='button' data-toggle='modal' data-target='#message_review' class='btn btn-sm btn-primary sendReply' data-id='" . $row_messages->id . "'><i class='fas fa-edit'></i></a>";
                                    } elseif ($count_review > 0) {
                                        echo "<a type='button' data-toggle='modal' data-target='#view_comment' class='btn btn-sm btn-warning viewCom' data-id='" . $row_messages->messageid . "'><i class='fas fa-eye'></i></a>";
                                    }
                                    echo " </td>
</tr>";
                                } while ($row_messages = $result_messages->fetchObject());
                            } else {
                                echo "<tr><td colspan='5'>No messages attached to this inbox</td></tr>";
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
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<?php include ("../admin/forms.php"); ?>
<script>
    $(function () {
        //fetch message id
        $(document).on('click', '.sendReply', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            getRow(id);
        });
        //view submitted comment
        $(document).on('click', '.viewCom', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            getRow1(id);
        });

    });

    //fetch message id
    function getRow(id) {
        $.ajax({
            type: 'POST',
            url: 'fetchMessage.php',
            data: { id: id },
            dataType: 'json',
            success: function (response) {
                $('#messageid').val(response.messageid);
            }
        });
    }
    //view submitted comment
    function getRow1(id) {
        $.ajax({
            type: 'POST',
            url: 'viewMessageReply.php',
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