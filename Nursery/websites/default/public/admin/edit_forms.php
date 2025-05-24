<!-- Subject edit Form -->
<div class="modal fade" id="subject_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Update Subject</i></h2>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" id="subjectid" name="subjectid">
                    <div class="form-group">
                        <label for="subject">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                    </div>
                    <div class="form-group">
                        <label for="subject_code">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update_subject" class="btn btn-sm btn-block btn-warning"><i
                                class="fas fa-edit">&nbsp&nbspUpdate Subject</i></button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat pull-left" data-dismiss="modal"><i
                        class="fas fa-expand-arrows-alt"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Parents edit Form -->
<div class="modal fade" id="parent_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Update Parent</i></h2>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" id="parentid" name="parentid">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" required>
                    </div>

                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" name="phone" id="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update_parent" class="btn btn-sm btn-block btn-warning">Update
                            Parent</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat pull-left" data-dismiss="modal"><i
                        class="fas fa-expand-arrows-alt"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Teacher edit Form -->
<div class="modal fade" id="teacher_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Update Teacher</i></h2>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" id="teacherid" name="teacherid">
                    <input type="hidden" id="teachid" name="teachid">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="temail" class="form-control" id="temail" required>
                    </div>
                    <div class='form-group'>
                        <label>Phone</label>
                        <input type="text" name="tphone" class="form-control" id="tphone" required>
                    </div>
                    <div class="form-group">
                        <label>Assign Subjects</label>
                        <?php
                        echo "
<dl class='dropdown' style='margin-bottom:0px !important;'> 
<dt><a >
<input type='text' name='attached_subject' id='attached_subject' class='multiSelodisp form-control' placeholder='Selected subjects' required>
<input type='hidden'  id='text' class=' form-control' placeholder='Search Subjects'>
</a>
<p class='multiSel'></p>  
</dt>
<dd>
<div class='mutliSelect'>
<ul style='height:100px;overflow-y:scroll;'>";
                        $result_subjects = $pdo->query("select * from subjects order by subject_name asc");
                        $row_subjects = $result_subjects->fetchObject();
                        $count_subjects = $result_subjects->rowCount();
                        if ($count_subjects > 0) {
                            $r = 1;
                            do {
                                echo "
<li><input class='check' type='checkbox' id='" . $r++ . "' value=" . $row_subjects->id . " />" . $row_subjects->subject_name . "</li>";
                            } while ($row_subjects = $result_subjects->fetchObject());
                        } else {
                            echo "<option value=''>There are no subjects in this list.</option>";
                        }
                        echo "</ul>
</div></dd></dl>";
                        ?>
                    </div>
                    <div class="form-group">
                        <label>User Role</label>
                        <select class="form-control" name="teacher_roleUpdate" required>
                            <?php
                            $result_role = $pdo->query("select * from roles where status=1 order by role_name asc");
                            $count_role = $result_role->rowCount();
                            $row_role = $result_role->fetchObject();
                            if ($count_role > 0) {
                                do {
                                    echo "<option value='" . $row_role->role_name . "'>" . $row_role->role_name . "</option>";
                                }
                                while ($row_role = $result_role->fetchObject());
                            } else {
                                echo "No role found.. please add one";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Teacher Image</label>
                        <input type="file" name="teacher_image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label for="section">Section*</label>
                        <select name="teacher_section" class="form-control" required>
                            <option value="Section 1">Section A</option>
                            <option value="Section 2">Section B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update_teacher" class="btn btn-sm btn-warning btn-block"><i
                                class="fas fa-database"> Update Teacher</i></button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat pull-left" data-dismiss="modal"><i
                        class="fas fa-expand-arrows-alt"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- class edit Form -->
<div class="modal fade" id="class_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Update Class</i></h2>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" id="classid" name="classid">
                    <div class="form-group">
                        <label>Class Name</label>
                        <input type="text" name="class_name" class="form-control" id="class_name" required>
                    </div>
                    <div class="form-group">
                        <label for="section">Section*</label>
                        <select id="class_section" name="class_section" class="form-control" required>
                            <option value="Section 1">Section A</option>
                            <option value="Section 2">Section B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="teacher">Teacher</label>
                        <select class="form-control" name="class_teacher" required>
                            <?php
                            $result_teacher = $pdo->query("select * from teachers order by firstname asc");
                            $count_teacher = $result_teacher->rowCount();
                            $row_teacher = $result_teacher->fetchObject();
                            if ($count_teacher > 0) {
                                do {
                                    echo "<option value='" . $row_teacher->teacherid . "'>" . $row_teacher->firstname . " " . $row_teacher->lastname . "</option>";
                                } while ($row_teacher = $result_teacher->fetchObject());
                            } else {
                                echo "No teacher found.. please add one";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update_class" class="btn btn-sm btn-warning btn-block"><i
                                class="fas fa-database"> Update Class</i></button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat pull-left" data-dismiss="modal"><i
                        class="fas fa-expand-arrows-alt"></i> Close</button>
            </div>
        </div>
    </div>
</div>
<!-- student edit Form -->
<div class="modal fade" id="student_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h2 class="modal-title text-center text-white"><i class="fas fa-edit"> Update Student</i></h2>
            </div>
            <div class="modal-body">
                <form method="POST" autocomplete="off">
                    <input type="hidden" id="studentid" name="studentid">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" name="full_name" class="form-control" id="full_name" required>
                    </div>
                    <div class="form-group">
                        <label>Admission Number</label>
                        <input type="text" name="admission_number" class="form-control" id="admission_number" required>
                    </div>
                    <div class="form-group">
                        <label>Roll Number</label>
                        <input type="text" name="roll_number" class="form-control" id="roll_number" required>
                    </div>
                    <div class="form-group">
                        <label for="academicYearSelect">Academic Year*</label>
                        <select name="student_year" required class="form-control">
                            <!-- Populate with years dynamically if possible -->
                            <option value='<?php echo date("Y"); ?>'><?php echo date("Y"); ?></option>
                            <?php
                            for ($t = 0; $t <= 3; $t++) {
                                $cy = date("y") + $t;
                                echo "<option value='20" . $cy . "'>20" . $cy . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="admissionDate">Admission Date</label>
                        <input type="date" name="student_admission" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="class">Class*</label>
                        <select name="student_class" required class="form-control">
                            <!-- Populate with class options -->
                            <?php
                            $result_class = $pdo->query("select * from classes order by name asc");
                            $count_class = $result_class->rowCount();
                            $row_class = $result_class->fetchObject();
                            if ($count_class > 0) {
                                do {
                                    echo "<option value='" . $row_class->id . "'>" . $row_class->name . "</option>";
                                } while ($row_class = $result_class->fetchObject());
                            } else {
                                echo "No class found.. please add one";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="section">Section*</label>
                        <select name="student_section" required class="form-control">
                            <option value="Section 1">Section A</option>
                            <option value="Section 2">Section B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Parent</label>
                        <select class="form-control" name="student_parent">
                            <?php
                            $result_parent = $pdo->query("select * from parents order by firstname asc");
                            $count_parent = $result_parent->rowCount();
                            $row_parent = $result_parent->fetchObject();
                            if ($count_parent > 0) {
                                do {
                                    echo "<option value='" . $row_parent->parentid . "'>" . $row_parent->firstname . " " . $row_parent->lastname . "</option>";
                                } while ($row_parent = $result_parent->fetchObject());
                            } else {
                                echo "No parent found.. please add one";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update_student" class="btn btn-sm btn-warning btn-block"><i
                                class="fas fa-database"></i> Update Student</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat pull-left" data-dismiss="modal"><i
                        class="fas fa-expand-arrows-alt"></i> Close</button>
            </div>
        </div>
    </div>
</div>