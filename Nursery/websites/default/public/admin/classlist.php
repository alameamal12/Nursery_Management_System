<?php
// Page to view the added classes in the table and to add, edit and delete a class
session_start();
// Include header and database connection files
require '../header.php';
require '../db.php';
?>
</head>

<body>
    <div id="contentWrapper">
        <?php include ('sidemenu.php'); ?>
        <div class="container-fluid">
            <div class="card card-info" style="margin-top: 30px;">
                <div class="card-header d-flex p-0">
                    <h4 class="card-title p-3"> MANAGE | CLASSES</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Submit class records
                    if (isset($_POST['submit_class'])) {
                        // Retrieve form data
                        $name = $_POST['name'];
                        $teacher = $_POST['teacher'];
                        $section = $_POST['section'];
                        // Insert class details into the classes table
                        $insert_class = $pdo->prepare("insert into classes(name, teacher, section) values('$name', '$teacher', '$section')");
                        $insert_class->execute();

                        // Display a success or failure message based on the query result
                        if ($insert_class) {
                            echo "<div class='alert alert-success'>Success.....class added</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Class Not Added</div>";
                        }
                    }
                    // Archive class by changing its status instead of deleting it completely
                    if (isset($_POST['delete_class'])) {
                        $status = '0';
                        $classid = $_POST['classid'];

                        // Update the class status to '0' (archived)
                        $archive_class = $pdo->query("UPDATE classes SET status='$status' WHERE id='$classid'");

                        // Display an appropriate message
                        if ($archive_class) {
                            echo "<div class='alert alert-warning'>Class has been archived</div>";
                        }
                    }

                    // Update class details
                    if (isset($_POST['update_class'])) {
                        // Retrieve form data
                        $classid = $_POST['classid'];
                        $class_name = $_POST['class_name'];
                        $class_section = $_POST['class_section'];
                        $class_teacher = $_POST['class_teacher'];
                        // Update class details in the `classes` table
                        $update_class = $pdo->query("UPDATE classes SET name='$class_name', teacher='$class_teacher', section='$class_section' WHERE id='$classid'");

                        // Display a success or failure message based on the query result
                    
                        if ($update_class) {
                            echo "<div class='alert alert-success'>class details updated</div>";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-9">
                            <!-- Form to filter classes based on the selected option -->

                            <form method='post' autocomplete="off">
                                <select class="form-control" name="filter_class" onchange="this.form.submit();">
                                    <option>--FILTER--</option>
                                    <?php
                                    // Fetch active classes from the database and populate the dropdown
                                    
                                    $result_class = $pdo->query("select * from classes where status=1 order by name asc");
                                    $count_class = $result_class->rowCount();
                                    $row_class = $result_class->fetchObject();

                                    // Check if there are any classes and loop through the results
                                    
                                    if ($count_class > 0) {
                                        do {
                                            echo "<option value='" . $row_class->id . "'>" . $row_class->name . "</option>";
                                        } while ($row_class = $result_class->fetchObject());
                                    } else {
                                        echo "No class registered..";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-3">
                            <!-- Button to open the modal to add a new class -->

                            <a class="btn btn-sm btn-success btn-block" data-toggle='modal' data-target='#class_form'>+
                                Add Class</a>
                        </div>
                    </div>
                    <div class="col-lg-12"><br></div>
                    <div class="table-responsive">
                        <!-- Table to display the list of classes -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">No</th>
                                    <th>Class Name</th>
                                    <th>Section</th>
                                    <th>Teacher</th>
                                    <th colspan="3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Filtering check based on onchange event
                                if (isset($_POST["filter_class"])) {
                                    $filter_class = $_POST["filter_class"];

                                    if (!empty($_POST["filter_class"])) {
                                        $prod = "and id='$filter_class'";
                                    }
                                } else {
                                    $prod = "";
                                }

                                // Fetch classes from the database based on the selected filter
                                
                                $result_class = $pdo->query("select * from classes where status=1 $prod order by name asc");
                                $count_class = $result_class->rowCount();
                                $row_class = $result_class->fetchObject();

                                // Check if there are any classes and loop through the results
                                

                                if ($count_class > 0) {
                                    $no = 1;
                                    do {
                                        // Fetch the teacher's details for each class
                                        $result_teacher = $pdo->query("select * from teachers where teacherid='$row_class->teacher'");
                                        $count_teacher = $result_teacher->rowCount();
                                        $row_teacher = $result_teacher->fetchObject();

                                        echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_class->name . "</td>
<td>" . $row_class->section . "</td>
<td>";
                                        // Display the teacher's name if found
                                        if ($count_teacher > 0) {
                                            echo $row_teacher->firstname . " " . $row_teacher->lastname;
                                        }
                                        echo "</td>
<td><a href='#class_edit' data-toggle='modal' class='btn btn-primary btn-sm btn-flat cla' data-id='" . $row_class->id . "'><i class='fas fa-edit'>&nbsp&nbspEdit</i></a></td>
<td>";
                                        ?>
                                        <!-- Form to archive (delete) a class -->
                                        <form method='post' onsubmit="return delete_checker('Class','Deleted');">
                                            <?php echo "
<input type='hidden' name='classid' value=" . $row_class->id . ">
<button type='submit' name='delete_class' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
</tr>";
                                    } while ($row_class = $result_class->fetchObject());
                                } else {
                                    // Display a message if no classes are found
                                    echo "<tr class='text-center'><td colspan='8'>No Classes registered</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Include jQuery and Bootstrap JavaScript files -->
                <script src="../js/jquery.js"></script>
                <script src="../js/bootstrap.min.js"></script>
                <script>
                    // Confirm before archiving (deleting) a class
                    function delete_checker(names, act) {
                        var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");

                        if (confirmer == false) {
                            return false;
                        }
                    }
                </script>
                <script>
                    // Fetch class data for editing
                    $(function () {
                        $(document).on('click', '.cla', function (e) {
                            e.preventDefault();
                            var id = $(this).data('id');
                            getRow(id);
                        });
                    });
                    // AJAX request to fetch the class data for the edit form
                    function getRow(id) {
                        $.ajax({
                            type: 'POST',
                            url: 'editclass.php',
                            data: { id: id },
                            dataType: 'json',
                            success: function (response) {
                                $('#class_name').val(response.name);
                                $('#classid').val(response.id);
                            }
                        });
                    }
                </script>
            </div>
        </div>
    </div>
    <!-- Include the forms for adding and editing classes -->
    <?php include ("forms.php"); ?>
    <?php include ("edit_forms.php"); ?>
</body>

</html>