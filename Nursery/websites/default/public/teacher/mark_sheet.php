<?php
// Start the session
session_start();
// Include required files
require '../header.php';
require '../db.php';
// Get the current user ID from the session
$userid = $_SESSION['user_id'];
?>
</head>

<body>
    <div id="wrapper">
        <?php include ('teachersidemenu.php'); ?>
        <!-- Manage Get Links From Marks Entry Opening Form -->
        <?php
        $classid = $_GET['basin'];
        $subjectid = $_GET['memid'];
        $serie = $_GET['kaps'];
        //fetch class details using get link and prepared statement to manage sql injections
        $result_group = $pdo->prepare("select * from classes where id=:memid");
        $result_group->bindParam(":memid", $classid);
        $result_group->execute();
        $row_group = $result_group->fetchObject();
        $count_group = $result_group->rowCount();
        if ($count_group > 0) {
            $title = $row_group->name;
        }
        //fetch subject details using get link and prepared statement to manage sql injections
        $result_sub = $pdo->prepare("select * from subjects where id=:memid");
        $result_sub->bindParam(":memid", $subjectid);
        $result_sub->execute();
        $row_sub = $result_sub->fetchObject();
        $count_sub = $result_sub->rowCount();
        if ($count_sub > 0) {
            $get_sub = $row_sub->subject_name;
        }
        ?>
        <div class="container-fluid">
            <div class="card card-default" style="margin-top: 30px;">
                <div class="card-header d-flex p-0">
                    <h4 class="card-title text-black p-3">
                        <?php echo "<span style='text-transform:uppercase;text-Shadow:all;'>" . $title . "</span>"; ?> |
                        <?php echo "<span style='text-transform:uppercase;text-Shadow:all;'>" . $get_sub . "</span>"; ?>
                        MARK
                        SHEET
                    </h4>
                    <ul class="nav nav-pills ml-auto p-2">
                        <!-- Form to submit/update marks -->
                        <form method="POST" enctype="multipart/form-data">
                            <li class="nav-item active"><button class="btn btn-primary btn-sm" type="submit"
                                    name="submit_marks">Save/Update Marks</button></li>
                    </ul>
                </div>
                <div class="card-body">
                    <?php
                    //submit marks
                    if (isset($_POST['submit_marks'])) {
                        $subject = $_POST['subject'];
                        $classid = $_POST['classid'];
                        $serie = $_POST['serie'];
                        $mydate = date("Y-m-d");
                        $count = count($_POST['studentid']);
                        if (is_array($_POST['studentid'])) {
                            for ($i = 0; $i < $count; $i++) {
                                $course_work = $_POST['course_work'][$i];
                                $eot = $_POST['eot'][$i];
                                $comment = addslashes($_POST['comment'][$i]);
                                $studentid = $_POST['studentid'][$i];
                                if (!empty($course_work) && !empty($eot) && !empty($subject) && !empty($serie) && !empty($studentid)) {
                                    // Calculate the average mark
                                    $average = round($course_work + $eot);
                                    //grade student based on their average
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
                                    //checking if marks for a student in a examination serie exist
//if they exist just update results, otherwise make a new entry
                                    $result_marks = $pdo->query("select * from student_marks where studentid='$studentid' and subjectid='$subjectid' and classid='$classid' and serie_id='$serie'");
                                    $count_marks = $result_marks->rowCount();
                                    if ($count_marks <= 0) {
                                        $update_marks = $pdo->query("insert into student_marks (studentid,course_work,subjectid,classid,serie_id,teacherid,date_added,grade,comment,total_mark,end_mark) values('$studentid','$course_work','$subjectid','$classid','$serie','$userid','$mydate','$grade','$comment','$average','$eot')");
                                    } else {
                                        $update_marks = $pdo->query("update student_marks set course_work='$course_work', end_mark='$eot', comment='$comment',total_mark='$average',grade='$grade' where studentid='$studentid' and serie_id='$serie' and subjectid='$subject'");
                                    }
                                }
                            }
                            if ($update_marks) {
                                echo "<div class='alert alert-success'>Saved</div>";
                            } else {
                                echo "";
                            }
                        }
                    }
                    ?>
                    <!-- Table to display and input marks -->
                    <table class="table table-strped table-bordered">
                        <thead>
                            <tr class="text-center bg-black text-light" style="text-transform: uppercase;">
                                <th width="2%">No.</th>
                                <th>Student Name</th>
                                <th>Course Work<br><span>40%</span></th>
                                <th>Exam Mark<br><span>60%</span></th>
                                <th width="50%">Subject Teacher Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch students belonging to the specified class
                            $result_students = $pdo->query("select * from students where class='$classid' and status=1 order by name asc");
                            $count_students = $result_students->rowCount();
                            $row_students = $result_students->fetchObject();
                            if ($count_students > 0) {
                                $no = 1;
                                do {
                                    // Fetch student marks
                                    $result_marks = $pdo->query("select * from student_marks where studentid='$row_students->id' and subjectid='$subjectid' and serie_id='$serie'");
                                    $count_marks = $result_marks->rowCount();
                                    $row_marks = $result_marks->fetchObject();
                                    // Display student information and marks in the table
                                    echo "
<tr>
<input type='hidden' name='subject' value='" . $subjectid . "'>
<input type='hidden' name='classid' value='" . $classid . "'>
<input type='hidden' name='serie' value='" . $serie . "'>
<td>" . $no++ . "</td>
<td>" . $row_students->name . "</td>
<input type='hidden' name='studentid[]' value='" . $row_students->id . "'>
<td><input type='number' class='form-control' name='course_work[]' max='40' value='";
                                    if ($count_marks > 0) {
                                        echo $row_marks->course_work;
                                    }
                                    echo "'></td>
<td><input type='number' class='form-control' name='eot[]' max='60' value='";
                                    if ($count_marks > 0) {
                                        echo $row_marks->end_mark;
                                    }
                                    echo "'></td>
<td><textarea placeholder='Enter Subject Comment' name='comment[]' class='form-control'>";
                                    if ($count_marks > 0) {
                                        echo $row_marks->comment;
                                    }
                                    echo "</textarea></td>
</tr>
";
                                } while ($row_students = $result_students->fetchObject());
                            } else {
                                echo "<tr><td colspan='10'>No students registered in this class</td></tr>";
                            }
                            ?>
                            </form>
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