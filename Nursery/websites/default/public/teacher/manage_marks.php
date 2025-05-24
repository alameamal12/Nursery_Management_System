<?php
// Start the session
session_start();
// Include required files
require '../header.php';
require '../db.php';
// Import PHPMailer classes into the global namespace
use phpmailer\phpmailer\PHPMailer;
use phpmailer\phpmailer\Exception;

// Load PHPMailer files
require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
// Get the user ID from the session
$userid = $_SESSION['user_id'];
?>
</head>

<body>
    <!-- Get class and exam series from URL parameters -->
    <?php
    $streamid = $_GET['basin'];
    $serie = $_GET['memid'];
    // Fetch class details
    $result_group = $pdo->prepare("select * from classes where id=:memid");
    $result_group->bindParam(":memid", $streamid);
    $result_group->execute();
    $row_group = $result_group->fetchObject();
    $count_group = $result_group->rowCount();
    ?>
    <div id="wrapper">
        <?php include ('teachersidemenu.php'); ?>
        <div class="container-fluid">
            <div class="card card-default" style="margin-top: 30px;">
                <div class="card-header d-flex p-0">
                    <h4 class="card-title text-black p-3"><?php if ($count_group > 0) {
                        echo $title = $row_group->name;
                    } ?>
                        RESULTS</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Send performance summary email to parents
                    if (isset($_POST['send_studentPerformance'])) {
                        $studid = $_POST['studid'];
                        $ex_serie = $_POST['ex_serie'];
                        $stud_class = $_POST['stud_class'];
                        //get student marks
                        $result_student = $pdo->query("select * from students where id='$studid'");
                        $row_student = $result_student->fetchObject();
                        //student name
                        $names = $row_student->name;
                        //fetch parent
                        $resultmail = $pdo->query("select * from parents where parentid='$row_student->parentid'");
                        $rowmail = $resultmail->fetchObject();
                        //parent email
                        $to = $rowmail->email;
                        //parent names
                        $receiver = $rowmail->firstname;
                        $receivername2 = $rowmail->lastname;
                        //fetch student marks
                        $result_mark = $pdo->query("select * from student_marks where studentid='$studid' and classid='$stud_class' and serie_id='$ex_serie'");
                        $count_mark = $result_mark->rowCount();
                        $row_mark = $result_mark->fetchObject();
                        //mail body
                        $body = "
<span style='font-size:15px;text-transform:capitalize;'>" . $names . " Peformance Summary Report.</span>
<p>";
                        $body .= "<table width='100%' style='font-size:15px;'>
<tr><th style='height:50px;'>Subject</th><th>Test</th><th>Exam</th><th>Total</th><th>Grade</th></tr>";
                        if ($count_mark > 0) {
                            do {
                                //fetch subject details
                                $result_subject = $pdo->query("select * from subjects where id='$row_mark->subjectid'");
                                $count_subject = $result_subject->rowCount();
                                $row_subject = $result_subject->fetchObject();
                                $body .= "
<tr><td style='height:50px;'>" . $row_subject->subject_name . "</td><td>" . $row_mark->course_work . "</td><td>" . $row_mark->end_mark . "</td><td>" . $row_mark->total_mark . "</td><td>" . $row_mark->grade . "</td></tr>";
                            } while ($row_mark = $result_mark->fetchObject());
                        }
                        $body .= "</table>

<p></p><p> Thank you for using Little Nursery Managment System<br> Click to Visit System : <a href='#' style='font-size:16px;'>Little Nursery Management System</a>";
                        // Email footer
                        $footer = "<span style='font-size:13px;'>This email is meant for " . $receiver . " " . $receivername2 . "
<p style='color:#242582'>Sent by <a href='#' style='color:#242582'>Little Learner Nursery</a></p>
<p style='color:#242582'>+44 7423 234934 | <a href='mailto:' style='color:#242582'>amal.alame12@gmail.com</a> | <a href='#' style='color:#242582'>www.littlenursery.com</a><br></p>
";
                        //variables
                        $subject = "LITTLE NURSERY PERFORMANCE SUMMARY";
                        //Load php mailer
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
                            $mail->addAddress($to);
                            $mail->addReplyTo('alameamal12@gmail.com');

                            //Content
                            $mail->isHTML(true);
                            $mail->Subject = $subject;
                            $mail->Body = $body;

                            $mail->send();

                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo;
                        }
                    }
                    ?>
                    <table class="table table-strped table-bordered">
                        <thead>
                            <tr class="text-center bg-black text-light" style="text-transform: uppercase;">
                                <th>NO.</th>
                                <th>STUDENT NAME</th>
                                <?php
                                //creating dyming table columns names for distinct subjects
                                $result_marks = $pdo->query("select distinct(subjectid) from student_marks where classid='$streamid' and serie_id='$serie'");
                                $count_marks = $result_marks->rowCount();
                                $row_marks = $result_marks->fetchObject();
                                if ($count_marks > 0) {
                                    do {
                                        $result_subject = $pdo->query("select * from subjects where id='$row_marks->subjectid'");
                                        $count_subject = $result_subject->rowCount();
                                        $row_subject = $result_subject->fetchObject();
                                        if ($count_subject > 0) {
                                            do {
                                                echo "<th>" . $row_subject->subject_name . "</th>";
                                            } while ($row_subject = $result_subject->fetchObject());
                                        }
                                    } while ($row_marks = $result_marks->fetchObject());
                                }
                                ?>
                                <th>Total</th>
                                <th>Average</th>
                                <th>Overoll Grade</th>
                                <th colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            //Getting marks from student marks for distinct student
                            $result_studMarks = $pdo->prepare("select distinct(studentid) from student_marks where classid=:memid and serie_id=:sels");
                            $result_studMarks->bindParam(":memid", $streamid);
                            $result_studMarks->bindParam(":sels", $serie);
                            $result_studMarks->execute();
                            $row_studMarks = $result_studMarks->fetchObject();
                            $count_studMarks = $result_studMarks->rowCount();
                            if ($count_studMarks > 0) {
                                $m = 1;
                                do {
                                    //fetch student names in the table body
                                    $result_students = $pdo->query("select * from students where id='$row_studMarks->studentid' order by name asc");
                                    $count_students = $result_students->rowCount();
                                    $row_students = $result_students->fetchObject();
                                    echo "<tr class='text-center'>
<td>" . $m++ . "</td>";
                                    //attach marks to each individual dynamic subject column created in the table header
                                    if ($count_students) {
                                        echo "<td>" . $row_students->name;
                                    }
                                    $result_mark = $pdo->query("select distinct(subjectid) from student_marks where studentid='$row_studMarks->studentid' or classid='$streamid'");
                                    $count_mark = $result_mark->rowCount();
                                    $row_mark = $result_mark->fetchObject();
                                    // Compute student total marks in all subjects using an array
                                    if ($count_mark > 0) {
                                        do {
                                            $result_sub = $pdo->query("select * from subjects where id='$row_mark->subjectid' group by subject_name order by subject_name asc");
                                            $count_sub = $result_sub->rowCount();
                                            $row_sub = $result_sub->fetchObject();
                                            if ($count_sub > 0) {
                                                //compute student total marks in all subjects using an array
                                                $sums = array();
                                                do {
                                                    $result_performance = $pdo->query("select * from student_marks where subjectid='$row_sub->id' and studentid='$row_students->id'");
                                                    $count_performance = $result_performance->rowCount();
                                                    $row_performance = $result_performance->fetchObject();
                                                    echo "<td>";
                                                    if ($count_performance > 0) {
                                                        echo $row_performance->course_work . " | " . $row_performance->end_mark . " <br><span style='color:maroon;'>" . $row_performance->total_mark . "</span>";
                                                    } else {
                                                        echo "-";
                                                    }
                                                    echo "</td>";
                                                } while ($row_sub = $result_sub->fetchObject());
                                            }
                                        } while ($row_mark = $result_mark->fetchObject());
                                    }
                                    //fill subject marks into an arrray and use them to compute student overall total mark in all subjects
                                    $result_total_marks = $pdo->query("select sum(total_mark) from student_marks where studentid='$row_students->id' and serie_id='$serie'");
                                    $row_total_marks = $result_total_marks->fetch();
                                    array_push($sums, $row_total_marks[0]);
                                    $result_total = $pdo->query("select * from student_marks where studentid='$row_students->id'");
                                    $count_total = $result_total->rowCount();
                                    $average = round((array_sum($sums) / $count_total));
                                    //attach grade to final computed student mark ffrom all subjects
                                    if ($average >= 90 and $average <= 100) {
                                        $grade = "A*";
                                    } elseif ($average >= 80 and $average <= 89) {
                                        $grade = "A";
                                    } elseif ($average >= 70 and $average <= 79) {
                                        $grade = "B";
                                    } elseif ($average >= 60 and $average <= 69) {
                                        $grade = "C";
                                    } elseif ($average >= 50 and $average <= 59) {
                                        $grade = "D";
                                    } elseif ($average >= 40 and $average <= 49) {
                                        $grade = "E";
                                    } elseif ($average >= 30 and $average <= 39) {
                                        $grade = "F";
                                    } elseif ($average >= 0 and $average <= 29) {
                                        $grade = "U";
                                    }
                                    // Display student performance summary in the table
                                    echo "
<td><span style='color:#000000;font-weight:bold;font-size:16px;'>" . number_format(array_sum($sums)) . "</span></td>
<td><span style='color:maroon;font-weight:bold;font-size:16px;'>" . $average . "</span></td>
<td><span style='color:blue;font-weight:bold;font-size:16px;'>" . $grade . "</span></td>
<td>
<form method='POST'> 
<input type='hidden' name='studid' value='" . $row_students->id . "'>
<input type='hidden' name='stud_class' value='" . $row_students->class . "'>
<input type='hidden' name='ex_serie' value='" . $serie . "'>
<button type='submit' name='send_studentPerformance' class='btn btn-sm btn-success'><i class='fas fa-envelope'></i></button>
</form>
</td>
</tr>";
                                } while ($row_studMarks = $result_studMarks->fetchObject());
                            } else {
                                echo "<tr><td colspan='10'>No marks added for this class</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/jquery.js"></script>
    <script src="/js/bootstrap.min.js"></script>

    </div>
    </div>
    </div>
</body>

</html>