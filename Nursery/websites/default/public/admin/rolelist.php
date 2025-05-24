<?php
// Include the header and database connection files
session_start();
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
                    <h4 class="card-title p-3"> MANAGE | ROLES</h4>
                </div>
                <div class="card-body">
                    <?php
                    // Handle form submission for adding a new role
                    if (isset($_POST['submit_roles'])) {
                        // Retrieve form data
                        $role_name = $_POST['role_name'];
                        // Insert the new role into the `roles` table
                        $submit_roles = $pdo->prepare("insert into roles(role_name) values('$role_name')");
                        $submit_roles->execute();
                        // Display a success or error message based on the query result
                        if ($submit_roles) {
                            echo "<div class='alert alert-success'>Success.....new user role added</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Role not added</div>";
                        }
                    }
                    //archive role by status instead of deleting it completely
                    if (isset($_POST['delete_role'])) {
                        $status = '0';
                        $roleid = $_POST['roleid'];
                        $archive_roles = $pdo->query("UPDATE roles SET status='$status' WHERE id='$roleid'");
                        if ($archive_roles) {
                            echo "<div class='alert alert-warning'>Role name has been archived</div>";
                        }
                    }
                    //update role details
                    if (isset($_POST['update_roles'])) {
                        // Retrieve form data
                        $roleid = $_POST['roleid'];
                        $role_name = $_POST['role_name'];
                        // Update the role name in the `roles` table
                        $update_roles = $pdo->query("UPDATE roles SET role_name='$role_name' WHERE id='$roleid'");
                        if ($update_roles) {
                            // Display a success message if updated successfully
                            echo "<div class='alert alert-success'>Role name updated successfully</div>";
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Form to filter roles based on selection -->
                            <form method='post' autocomplete="off">
                                <select class="form-control" name="filter_role" onchange="this.form.submit();">
                                    <option>--FILTER BY NAME--</option>
                                    <?php
                                    // Fetch all active roles from the database
                                    $result_role = $pdo->query("select * from roles where status=1 order by role_name asc");
                                    $count_role = $result_role->rowCount();
                                    $row_role = $result_role->fetchObject();
                                    // Check if there are any roles and loop through the results
                                    if ($count_role > 0) {
                                        do {
                                            echo "<option value='" . $row_role->id . "'>" . $row_role->role_name . "</option>";
                                        }
                                        while ($row_role = $result_role->fetchObject());
                                    } else {
                                        // Display a message if no roles are registered
                                        echo "No role registered";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                        <div class="col-lg-3"></div>
                        <div class="col-lg-3">
                            <!-- Button to open the modal for adding a new role -->
                            <a class="btn btn-sm btn-success btn-block" data-toggle='modal' data-target='#roles'><i
                                    class="fas fa-plus"></i> Add Role</a>
                        </div>
                    </div>
                    <div class="col-lg-12"><br></div>
                    <div class="table-responsive">
                        <!-- Table to display the list of roles -->
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">No</th>
                                    <th>Role Name</th>
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //filtering check based on onchange event
                                if (isset($_POST["filter_role"])) {
                                    $filter_role = $_POST["filter_role"];
                                    if (!empty($_POST["filter_role"])) {
                                        $prod = "and id='$filter_role'";
                                    }
                                } else {
                                    $prod = "";
                                }
                                //retrieving records from roles data using do while loop
                                $result_roles = $pdo->query("select * from roles where status = 1 $prod");
                                $count_roles = $result_roles->rowCount();
                                $row_roles = $result_roles->fetchObject();
                                // Check if there are any roles and loop through the results
                                if ($count_roles > 0) {
                                    $no = 1;
                                    do {
                                        echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_roles->role_name . "</td>
<td><button onClick='show_dis(" . $row_roles->id . ")' class='btn btn-sm btn-info'><i class='fas fa-edit'>&nbsp&nbspEdit</i></button></td>
<td>"; ?>
                                        <!-- Form to archive (delete) a role -->
                                        <form method='post' onsubmit="return delete_checker('User Role','Deleted');">
                                            <?php echo "
<input type='hidden' name='roleid' value=" . $row_roles->id . ">
<button type='submit' name='delete_role' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
</tr> "; ?>
                                            <tr id='edit_role<?php echo $row_roles->id; ?>'
                                                onClick="get_no('<?php echo $row_roles->id; ?>')"
                                                style='border-bottom: 2px solid red;border-top: 2px solid red;display:none;'>
                                                <?php echo "<form method='POST'>
<input type='hidden' name='roleid' value='" . $row_roles->id . "'>
<td colspan='6'>
<div class='row'>
<div class='col-lg-10'>
<input type='text' class='form-control' name='role_name' value='" . $row_roles->role_name . "'>
</div>
<div class='col-lg-2'><input type='submit' name='update_roles' class='btn btn-sm btn-success' value='Submit'></div></diV></td></form>
</tr>";
                                    } while ($row_roles = $result_roles->fetchObject());
                                } else {
                                    // Display a message if no roles are found
                                    echo "<tr class='text-center'><td colspan='5'>No roles registered...</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- Include jQuery and Bootstrap JavaScript files -->
                <script src="../js/jquery.js"></script>
                <script src="../js/bootstrap.min.js"></script>
                <script>
                    // Toggle the edit form visibility
                    function show_dis(ai) { $("#edit_role" + ai).toggle(); }
                    function get_no(ai) { document.getElementById("no").value = ai; }
                </script>
                <script>
                    // Confirm before deleting a role
                    function delete_checker(names, act) {
                        var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");
                        if (confirmer == false) { return false; }
                    }
                </script>
            </div>
            <?php include ("forms.php"); ?>
        </div>
    </div>
</body>

</html>