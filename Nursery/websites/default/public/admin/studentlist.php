<?php
//page to view the added students in the table and to edit,delete and add a student
session_start();
// Include the header and database connection files
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
                    <h4 class="card-title p-3"> MANAGE | STUDENTS</h4>
                </div>
                <div class="card-body">
                    <?php
                    //submit teacher records: Bio data in parents table and login details in users table
                    if (isset($_POST['submit_student'])) {
                        // Retrieve form data
                        $full_name = $_POST['full_name'];
                        $admission_number = $_POST['admission_number'];
                        $roll_number = $_POST['roll_number'];
                        $academic_year = $_POST['academic_year'];
                        $admission_date = $_POST['admission_date'];
                        $class_name = $_POST['class_name'];
                        $parentid = $_POST['parentid'];
                        $section = $_POST['section'];
                        //create an automatic studentid
                        $result_students = $pdo->query("select * from students order by id desc");
                        $row_students = $result_students->fetchObject();
                        $count_students = $result_students->rowCount();
                        if ($count_students <= 0) {
                            $studentid = "st1";
                        } else {
                            $studentid = "st" . ($row_students->id + 1);
                        }
                        //// Handle image upload
                        if (!empty($_FILES['image_name']['tmp_name'])) {
                            // Create a unique filename for the uploaded image
                            $filename = str_replace(' ', '_', date('i') . "" . date('s') . $_FILES['image_name']['name']);
                            // destination of the file on the server
                            $destination = '../uploads/' . $filename;
                            // get the file extension
                            $extension = pathinfo($filename, PATHINFO_EXTENSION);
                            // the physical file on a temporary uploads directory on the server
                            $file = $_FILES['image_name']['tmp_name'];
                            $size = $_FILES['image_name']['size'];
                            // Check file extension and size
                            if (!in_array($extension, ['pdf', 'docx', 'xls', 'png', 'jpg', 'jpeg', 'gif', 'xlsx', 'doc', 'odt', 'txt', 'ods'])) {
                                echo "You file extension must be .pdf, .xls, .png, .jpg, .jpeg, .gif, .xlsx, .doc, .odt, .txt, .ods or .docx";
                            } elseif ($_FILES['image_name']['size'] > 1000000000) { // file shouldn't be larger than 1Megabyte
                                echo "File too large!";
                            } else {
                                //insert student bio data into parents table
                                $insert_student = $pdo->prepare("insert into students(admission_number, roll_number,name,image_name,fsize,academic_year,admissionDate,class,section,parentid,studentid) values('$admission_number','$roll_number','$full_name','$filename','$size','$academic_year','$admission_date','$class_name','$section','$parentid','$studentid')");
                                $insert_student->execute();
                                if ($insert_student) {
                                    echo "<div class='alert alert-success'>Success.....student added</div>";
                                } else {
                                    echo "<div class='alert alert-danger'>Student Not Added</div>";
                                }
                                // Move the uploaded file to the specified destination
                                if (move_uploaded_file($file, $destination)) {
                                    echo "<div class='alert alert-success'>Image Uploaded.......</div>";
                                }
                            }
                        }
                    }

                    //archive students by status instead of deleting it completely
                    if (isset($_POST['delete_student'])) {
                        $status = '0';
                        $studentid = $_POST['studentid'];
                        // Update the student status to '0' (archived)
                        $archive_student = $pdo->query("UPDATE students SET status='$status' WHERE id='$studentid'");
                        if ($archive_student) {
                            echo "<div class='alert alert-warning'>Student has been archived</div>";
                        }
                    }
                    //update student details
                    if (isset($_POST['update_student'])) {
                        // Retrieve form data
                        $studentid = $_POST['studentid'];
                        $full_name = $_POST['full_name'];
                        $admission_number = $_POST['admission_number'];
                        $roll_number = $_POST['roll_number'];
                        $student_year = $_POST['student_year'];
                        $student_admission = $_POST['student_admission'];
                        $student_class = $_POST['student_class'];
                        $student_section = $_POST['student_section'];
                        $student_parent = $_POST['student_parent'];

                        // Update student details in the `students` table
                        $update_student = $pdo->query("UPDATE students SET name='$full_name', admission_number='$admission_number', roll_number='$roll_number',academic_year='$student_year',admissionDate='$student_admission',class='$student_class',section='$student_section',parentid='$student_parent' WHERE id='$studentid'");
                        if ($update_student) {
                            echo "<div class='alert alert-success'>student details updated</div>";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-9">
                            <!-- Form to filter students based on selection -->
                            <form method='post' autocomplete="off">
                                <select class="form-control" name="filter_student" onchange="this.form.submit();">
                                    <option>--FILTER--</option>
                                    <?php
                                    // Fetch all active students from the database
                                    $result_student = $pdo->query("select * from students where status=1 order by name asc");
                                    $count_student = $result_student->rowCount();
                                    $row_student = $result_student->fetchObject();
                                    // Check if there are any students and loop through the results
                                    if ($count_student > 0) {
                                        do {
                                            echo "<option value='" . $row_student->id . "'>" . $row_student->name . "</option>";
                                        }
                                        while ($row_student = $result_student->fetchObject());
                                    } else {
                                        // Display a message if no students are registered
                                        echo "No students registered";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-3">
                            <!-- Button to open the modal for adding a new student -->
                            <a class="btn btn-sm btn-success btn-block" data-toggle='modal' data-target='#student'>+ Add
                                Student</a>
                        </div>
                    </div>
                    <div class="col-lg-12"><br></div>
                    <div class="table-responsive">
                        <!-- Table to display the list of students -->
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">No</th>
                                    <th>Name</th>
                                    <th>Photo</th>
                                    <th>Academic Year</th>
                                    <th>Admission Date</th>
                                    <th>Class</th>
                                    <th>Section</th>
                                    <th>Parent</th>
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //filtering check based on onchange event
                                if (isset($_POST["filter_student"])) {
                                    $filter_student = $_POST["filter_student"];
                                    if (!empty($_POST["filter_student"])) {
                                        $prod = "and id='$filter_student'";
                                    }
                                } else {
                                    $prod = "";
                                }
                                // Fetch all active students based on filter criteria
                                $result_students = $pdo->query("select * from students where status=1 $prod order by name asc");
                                $count_students = $result_students->rowCount();
                                $row_students = $result_students->fetchObject();
                                // Check if there are any students and loop through the results
                                if ($count_students > 0) {
                                    $no = 1;
                                    do {
                                        // Fetch class and parent details for each student
                                        $result_class = $pdo->query("select * from classes where id='$row_students->class'");
                                        $count_class = $result_class->rowCount();
                                        $row_class = $result_class->fetchObject();
                                        $result_parent = $pdo->query("select * from parents where parentid='$row_students->parentid'");
                                        $count_parent = $result_parent->rowCount();
                                        $row_parent = $result_parent->fetchObject();
                                        // Set a default image if none is provided
                                        $image = (!empty($row_students->image_name)) ? $row_students->image_name : '../uploads/avatar.png';
                                        echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_students->name . "</td>
<td><img src='../uploads/" . $image . "' alt='Add Images' style='width:50px;max-height:50px;height: expression(this.height > 50 ? 50: true);min-height:50px;height: expression(this.height < 50 ? 50: true);' class='img-circle'></td>
<td>" . $row_students->academic_year . "</td>
<td>" . $row_students->admissionDate . "</td>
<td>";
                                        if ($count_class > 0) {
                                            echo $row_class->name;
                                        }
                                        echo "</td>
<td>" . $row_class->section . "</td>
<td>";
                                        if ($count_parent > 0) {
                                            echo $row_parent->firstname . " " . $row_parent->lastname . "<br>" . $row_parent->phone . "<br><span style='color:maroon;font-weight:bold;'>" . $row_parent->email . "</span>";
                                        }
                                        echo "</td>
<td><a href='#student_edit' data-toggle='modal' class='btn btn-primary btn-sm btn-flat studs' data-id='" . $row_students->id . "'><i class='fas fa-edit'>&nbsp&nbspEdit</i></a></td>
<td>"; ?>
                                        <!-- Form to archive (delete) a student -->
                                        <form method='post' onsubmit="return delete_checker('Student','Deleted');">
                                            <?php echo "
<input type='hidden' name='studentid' value=" . $row_students->id . ">
<button type='submit' name='delete_student' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
</tr>";
                                    } while ($row_students = $result_students->fetchObject());
                                } else {
                                    // Display a message if no students are found
                                    echo "<tr class='text-center'><td colspan='10'>No students registered in this class</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Include jQuery and Bootstrap JavaScript files -->
            <script src="../js/jquery.js"></script>
            <script src="../js/bootstrap.min.js"></script>
            <script>
                // Confirm before deleting a student
                function delete_checker(names, act) {
                    var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");
                    if (confirmer == false) { return false; }
                }
            </script>
            <script>
                $(function () {
                    // Fetch student data for editing
                    $(document).on('click', '.studs', function (e) {
                        e.preventDefault();
                        var id = $(this).data('id');
                        getRow(id);
                    });

                });
                // AJAX request to fetch the student data for the edit form
                function getRow(id) {
                    $.ajax({
                        type: 'POST',
                        url: 'editstudent.php',
                        data: { id: id },
                        dataType: 'json',
                        success: function (response) {
                            $('#full_name').val(response.name);
                            $('#roll_number').val(response.roll_number);
                            $('#admission_number').val(response.admission_number);
                            $('#studentid').val(response.id);
                        }
                    });
                }
            </script>
        </div>
        <!-- Include forms for adding and editing students -->
        <?php include ("forms.php"); ?>
        <?php include ("edit_forms.php"); ?>
    </div>
    </div>
</body>

</html>