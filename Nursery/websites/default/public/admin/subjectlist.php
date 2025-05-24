<?php
// Page to view the added subjects in the table and to edit, delete, and add a subject
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
                    <h4 class="card-title p-3"> MANAGE | SUBJECTS</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Handle form submission for adding a new subject
                    if (isset($_POST['submit_subject'])) {
                        // Retrieve form data
                        $subject_name = $_POST['subject_name'];
                        $subject_code = $_POST['subject_code'];
                        // Insert the new subject into the `subjects` table
                        $submit_subject = $pdo->prepare("insert into subjects(subject_name, subject_code) values('$subject_name', '$subject_code')");
                        $submit_subject->execute();

                        // Display a success or error message based on the query result
                        if ($submit_subject) {
                            echo "<div class='alert alert-success'>Success.....subject added</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Subject Not Added</div>";
                        }
                    }
                    //archive subject by status instead of deleting it completely
                    if (isset($_POST['delete_subject'])) {
                        $status = '0';
                        $subjectid = $_POST['subjectid'];
                        // Update the subject status to '0' (archived)
                    
                        $archive_subject = $pdo->query("UPDATE subjects SET status='$status' WHERE id='$subjectid'");
                        if ($archive_subject) {
                            echo "<div class='alert alert-warning'>Subject has been archived</div>";
                        }
                    }
                    //update subject details
                    if (isset($_POST['update_subject'])) {
                        // Retrieve form data
                        $subjectid = $_POST['subjectid'];
                        $subject_name = $_POST['subject_name'];
                        $subject_code = $_POST['subject_code'];
                        // Update subject details in the `subjects` table
                        $update_subject = $pdo->query("UPDATE subjects SET subject_name='$subject_name', subject_code='$subject_code' WHERE id='$subjectid'");
                        // Display a success message if updated successfully
                        if ($update_subject) {
                            echo "<div class='alert alert-success'>Subject details updated</div>";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Form to filter subjects based on selection -->
                            <form method='post' autocomplete="off">
                                <select class="form-control" name="filter_subject" onchange="this.form.submit();">
                                    <option>--FILTER--</option>
                                    <?php
                                    // Fetch all active subjects from the database
                                    $result_sub = $pdo->query("select * from subjects where status=1 order by subject_name asc");
                                    $count_sub = $result_sub->rowCount();
                                    $row_sub = $result_sub->fetchObject();

                                    // Check if there are any subjects and loop through the results
                                    if ($count_sub > 0) {
                                        do {
                                            echo "<option value='" . $row_sub->id . "'>" . $row_sub->subject_name . "</option>";
                                        }
                                        while ($row_sub = $result_sub->fetchObject());
                                    } else {
                                        // Display a message if no subjects are registered
                                        echo "No subjects registered...";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-3"></div>
                        <div class="col-lg-3">
                            <!-- Button to open the modal for adding a new subject -->
                            <a class="btn btn-sm btn-success btn-block" data-toggle='modal' data-target='#subject'><i
                                    class="fas fa-plus"></i> Add Subject</a>
                        </div>
                    </div>
                    <div class="col-lg-12"><br></div>
                    <div class="table-responsive">
                        <!-- Table to display the list of subjects -->
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">No</th>
                                    <th>Subject Name</th>
                                    <th>Subject Code</th>
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //filtering check based on onchange event
                                if (isset($_POST["filter_subject"])) {
                                    $filter_subject = $_POST["filter_subject"];
                                    if (!empty($_POST["filter_subject"])) {
                                        $prod = "and id='$filter_subject'";
                                    }
                                } else {
                                    $prod = "";
                                }
                                // Retrieve records from `subjects` table using a do-while loop
                                $result_subject = $pdo->query("select * from subjects where status = 1 $prod");
                                $count_subject = $result_subject->rowCount();
                                $row_subject = $result_subject->fetchObject();
                                // Check if there are any subjects and loop through the results
                                
                                if ($count_subject > 0) {
                                    $no = 1;
                                    do {
                                        echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_subject->subject_name . "</td>
<td>" . $row_subject->subject_code . "</td>
<td><a href='#subject_edit' data-toggle='modal' class='btn btn-primary btn-sm btn-flat subjct' data-id='" . $row_subject->id . "'><i class='fas fa-edit'>&nbsp&nbspEdit</i></a></td>
<td>"; ?>
                                        <!-- Form to archive (delete) a subject -->
                                        <form method='post' onsubmit="return delete_checker('Subject','Deleted');">
                                            <?php echo "
<input type='hidden' name='subjectid' value=" . $row_subject->id . ">
<button type='submit' name='delete_subject' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
</tr>";
                                    } while ($row_subject = $result_subject->fetchObject());
                                } else {
                                    // Display a message if no subjects are found
                                    echo "<tr class='text-center'><td colspan='5'>No subject registered</td></tr>";
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
                    // Confirm before deleting a subject
                    function delete_checker(names, act) {
                        var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");
                        if (confirmer == false) { return false; }
                    }
                </script>
                <script>
                    // Fetch subject data for editing
                    $(function () {
                        $(document).on('click', '.subjct', function (e) {
                            e.preventDefault();
                            var id = $(this).data('id');
                            getRow(id);
                        });

                    });
                    // AJAX request to fetch the subject data for the edit form
                    function getRow(id) {
                        $.ajax({
                            type: 'POST',
                            url: 'editsubject.php',
                            data: { id: id },
                            dataType: 'json',
                            success: function (response) {
                                $('#subject_name').val(response.subject_name);
                                $('#subject_code').val(response.subject_code);
                                $('#subjectid').val(response.id);
                            }
                        });
                    }
                </script>
            </div>
             <!-- Include forms for adding and editing subjects -->
            <?php include ("forms.php"); ?>
            <?php include ("edit_forms.php"); ?>
        </div>
    </div>
</body>

</html>