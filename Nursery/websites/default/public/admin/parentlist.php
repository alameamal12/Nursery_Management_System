<?php
// Page to view the added parents in the table and to edit, delete, and view parent details
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
                    <h4 class="card-title p-3"> MANAGE | PARENTS</h4>
                </div>
                <div class="card-body">
                    <?php
                    //submit parents records: Bio data in parents table and login details in users table
                    if (isset($_POST['submit_parent'])) {
                        // Retrieve form data
                        $first_name = $_POST['first_name'];
                        $last_name = $_POST['last_name'];
                        $email = $_POST['email'];
                        $phone = $_POST['phone'];
                        $username = $_POST['username'];
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        //create an automatic parentid
                        $result_parents = $pdo->query("select * from parents order by id desc");
                        $row_parents = $result_parents->fetchObject();
                        $count_parents = $result_parents->rowCount();
                        if ($count_parents <= 0) {
                            $parentid = "prt1";
                        } else {
                            $parentid = "prt" . ($row_parents->id + 1);
                        }
                        //insert parent bio data into parents table
                        $insert_parent = $pdo->prepare("insert into parents(firstname,lastname,email,phone,parentid) values('$first_name','$last_name','$email','$phone','$parentid')");
                        $insert_parent->execute();
                        //insert parent login details into users table
                        $insert_users = $pdo->prepare("insert into users(username,password,user_type,userid) values('$username','$password','parent','$parentid')");
                        $insert_users->execute();
                        // Display a success or error message based on the query result
                        if ($insert_parent && $insert_users) {
                            echo "<div class='alert alert-success'>Success.....parent added</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Parent Not Added</div>";
                        }
                    }
                    //archive parents by status instead of deleting it completely
                    if (isset($_POST['delete_parent'])) {
                        $status = '0';
                        $parentid = $_POST['parentid'];
                        // Update the parent status to '0' (archived)
                        $archive_parent = $pdo->query("UPDATE parents SET status='$status' WHERE id='$parentid'");
                        // Display a warning message if archived successfully
                        if ($archive_parent) {
                            echo "<div class='alert alert-warning'>Parent has been archived</div>";
                        }
                    }
                    //update parents details
                    if (isset($_POST['update_parent'])) {
                        // Retrieve form data
                        $parentid = $_POST['parentid'];
                        $first_name = $_POST['first_name'];
                        $last_name = $_POST['last_name'];
                        $phone = $_POST['phone'];
                        $email = $_POST['email'];

                        // Update parent details in the `parents` table
                        $update_parent = $pdo->query("UPDATE parents SET firstname='$first_name', lastname='$last_name', email='$email', phone='$phone' WHERE id='$parentid'");

                        // Display a success message if updated successfully
                        if ($update_parent) {
                            echo "<div class='alert alert-success'>parent details updated</div>";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-9">
                            <!-- Form to filter parents based on selection -->
                            <form method='post' autocomplete="off">
                                <select class="form-control" name="filter_parent" onchange="this.form.submit();">
                                    <option>--FILTER--</option>
                                    <?php
                                    // Fetch all active parents from the database
                                    $result_parent = $pdo->query("select * from parents where status=1 order by firstname asc");
                                    $count_parent = $result_parent->rowCount();
                                    $row_parent = $result_parent->fetchObject();
                                    // Check if there are any parents and loop through the results
                                    if ($count_parent > 0) {
                                        do {
                                            echo "<option value='" . $row_parent->id . "'>" . $row_parent->firstname . " " . $row_parent->lastname . "</option>";
                                        }
                                        while ($row_parent = $result_parent->fetchObject());
                                    } else {
                                        // Display a message if no parents are registered
                                        echo "No parent registered...";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-3">
                            <!-- Button to open the modal for adding a new parent -->
                            <a class="btn btn-sm btn-success btn-block" data-toggle='modal' data-target='#parent'>+ Add
                                Parent</a>
                        </div>
                    </div>
                    <div class="col-lg-12"><br></div>
                    <div class="table-responsive">
                        <!-- Table to display the list of parents -->
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">No</th>
                                    <th>Full Name</th>
                                    <th>Contact</th>
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //filtering check based on onchange event
                                if (isset($_POST["filter_parent"])) {
                                    $filter_parent = $_POST["filter_parent"];
                                    if (!empty($_POST["filter_parent"])) {
                                        $prod = "and id='$filter_parent'";
                                    }
                                } else {
                                    $prod = "";
                                }
                                // Fetch all active parents based on filter criteria
                                $result_parents = $pdo->query("select * from parents where status=1 $prod order by firstname asc");
                                $count_parents = $result_parents->rowCount();
                                $row_parents = $result_parents->fetchObject();
                                // Check if there are any parents and loop through the results
                                if ($count_parents > 0) {
                                    $no = 1;
                                    do {
                                        echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_parents->firstname . " " . $row_parents->lastname . "</td>
<td>" . $row_parents->phone . "<br><span style='color:maroon;font-weight:bold;'>[" . $row_parents->email . "]</span></td>
<td><a href='#parent_edit' data-toggle='modal' class='btn btn-primary btn-sm btn-flat paro' data-id='" . $row_parents->id . "'><i class='fas fa-edit'>&nbsp&nbspEdit</i></a></td>
<td>"; ?>
                                        <!-- Form to archive (delete) a parent -->
                                        <form method='post' onsubmit="return delete_checker('Parent','Deleted');">
                                            <?php echo "
<input type='hidden' name='parentid' value=" . $row_parents->id . ">
<button type='submit' name='delete_parent' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
</tr>";
                                    } while ($row_parents = $result_parents->fetchObject());
                                } else {
                                    // Display a message if no parents are found
                                    echo "<tr class='text-center'><td colspan='5'>No parent registered...please add one</td></tr>";
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
                    // Confirm before deleting a parent
                    function delete_checker(names, act) {
                        var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");
                        if (confirmer == false) { return false; }
                    }
                </script>
                <script>
                    // Fetch parent data for editing
                    $(function () {
                        $(document).on('click', '.paro', function (e) {
                            e.preventDefault();
                            var id = $(this).data('id');
                            getRow(id);
                        });

                    });
                    // AJAX request to fetch the parent data for the edit form
                    function getRow(id) {
                        $.ajax({
                            type: 'POST',
                            url: 'editparent.php',
                            data: { id: id },
                            dataType: 'json',
                            success: function (response) {
                                $('#first_name').val(response.firstname);
                                $('#last_name').val(response.lastname);
                                $('#phone').val(response.phone);
                                $('#email').val(response.email);
                                $('#parentid').val(response.id);
                            }
                        });
                    }
                </script>
            </div>
            <!-- Include forms for adding and editing parents -->
            <?php include ("forms.php"); ?>
            <?php include ("edit_forms.php"); ?>
        </div>
    </div>
</body>

</html>