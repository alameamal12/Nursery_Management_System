<?php
// page to view the added classes in the table and to edit, delete, and add a class
session_start();
require '../header.php';
require '../db.php'; // Ensure this is the correct path to your database connection script
?>
<style>
  .dropdown {}

  .dropdown dd,
  .dropdown dt {
    margin: 0px;
    padding: 0px;
  }

  .multiselo {
    color: #000;
  }

  .dropdown ul {
    margin: -1px 0 0 0;
  }

  .dropdown dd {
    position: relative;
  }

  .dropdown a,
  .dropdown a:visited {
    color: #fff;
    text-decoration: none;
    outline: none;
    font-size: 12px;
  }

  .dropdown dt a {

    overflow: hidden;

  }

  .dropdown dt a span,
  .multiSel span {
    cursor: pointer;
    display: inline-block;
    padding: 0 3px 2px 0;
  }

  .dropdown dd ul {
    background-color: #4F6877;
    border: 0;
    color: #fff;
    display: none;
    left: 0px;
    padding: 2px 15px 2px 5px;
    position: absolute;
    top: 2px;
    width: 200px;
    list-style: none;
    height: 240px;
    overflow: auto;
    z-index: 99;
  }


  .dropdown span.value {
    display: none;
  }

  .dropdown dd ul li a {
    padding: 5px;
    display: block;
  }

  .dropdown dd ul li a:hover {
    background-color: #fff;
  }
</style>
</head>

<body>
  <div id="contentWrapper">
    <?php include ('sidemenu.php'); ?>
    <div class="container-fluid">
      <div class="card card-info" style="margin-top: 30px;">
        <div class="card-header d-flex p-0">
          <h4 class="card-title p-3"> MANAGE | TEACHERS</h4>
        </div>
        <div class="card-body">
          <?php
          //submit teacher records; Bio data in parents table and login details in users table
          if (isset($_POST['submit_teacher'])) {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $username = $_POST['username'];
            $subject = $_POST['subject'];
            $teacher_role = $_POST['teacher_role'];
            $section = $_POST['section'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $class_id = $_POST['class_id']; // Retrieve selected class ID from the form
          
            //create an automatic parentid
            $result_teachers = $pdo->query("select * from teachers order by id desc");
            $row_teachers = $result_teachers->fetchObject();
            $count_teachers = $result_teachers->rowCount();
            if ($count_teachers <= 0) {
              $teacherid = "tr1";
            } else {
              $teacherid = "tr" . ($row_teachers->id + 1);
            }
            //inserting image
            if (!empty($_FILES['image']['tmp_name'])) {
              $filename = str_replace(' ', '_', date('i') . "" . date('s') . $_FILES['image']['name']);
              // destination of the file on the server
              $destination = '../uploads/' . $filename;
              // get the file extension
              $extension = pathinfo($filename, PATHINFO_EXTENSION);
              // the physical file on a temporary uploads directory on the server
              $file = $_FILES['image']['tmp_name'];
              $size = $_FILES['image']['size'];
              if (!in_array($extension, ['pdf', 'docx', 'xls', 'png', 'jpg', 'jpeg', 'gif', 'xlsx', 'doc', 'odt', 'txt', 'ods'])) {
                echo "You file extension must be .pdf, .xls, .png, .jpg, .jpeg, .gif, .xlsx, .doc, .odt, .txt, .ods or .docx";
              } elseif ($_FILES['image']['size'] > 1000000000) { // file shouldn't be larger than 1Megabyte
                echo "File too large!";
              } else {
                //insert parent bio data into parents table
                $insert_teacher = $pdo->prepare("insert into teachers(firstname,lastname,email,phone,assigned_subject,image_link,fsize,section,teacherid,class_id) values('$firstname','$lastname','$email','$phone','$subject','$filename','$size','$section','$teacherid','$class_id')");
                $insert_teacher->execute();
                //insert parent login details into users table
                $insert_users = $pdo->prepare("insert into users(username,password,user_type,userid) values('$username','$password','$teacher_role','$teacherid')");
                $insert_users->execute();
                if ($insert_teacher && $insert_users) {
                  echo "<div class='alert alert-success'>Success.....teacher added</div>";
                } else {
                  echo "<div class='alert alert-danger'>Teacher Not Added</div>";
                }
                if (move_uploaded_file($file, $destination)) {
                  echo "<div class='alert alert-success'>Image Uploaded.......</div>";
                }
              }
            }
          }

          //archive teachers by status instead of deleting it completely
          if (isset($_POST['delete_teacher'])) {
            $status = '0';
            $teacherid = $_POST['teacherid'];
            $archive_teacher = $pdo->query("UPDATE teachers SET status='$status' WHERE id='$teacherid'");
            if ($archive_teacher) {
              echo "<div class='alert alert-warning'>Teacher has been archived</div>";
            }
          }
          //update teachers
          if (isset($_POST['update_teacher'])) {
            $teacherid = $_POST['teacherid'];
            $teachid = $_POST['teachid'];
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $phone = $_POST['tphone'];
            $email = $_POST['temail'];
            $attached_subject = $_POST['attached_subject'];
            $teacher_role = $_POST['teacher_roleUpdate'];
            $teacher_section = $_POST['teacher_section'];

            //handle image update
            if (!empty($_FILES['teacher_image']['tmp_name'])) {
              $filename = str_replace(' ', '_', date('i') . "" . date('s') . $_FILES['teacher_image']['name']);
              // destination of the file on the server
              $destination = '../uploads/' . $filename;
              // get the file extension
              $extension = pathinfo($filename, PATHINFO_EXTENSION);
              // the physical file on a temporary uploads directory on the server
              $file = $_FILES['teacher_image']['tmp_name'];
              $size = $_FILES['teacher_image']['size'];
              if (!in_array($extension, ['pdf', 'docx', 'xls', 'png', 'jpg', 'jpeg', 'gif', 'xlsx', 'doc', 'odt', 'txt', 'ods'])) {
                echo "You file extension must be .pdf, .xls, .png, .jpg, .jpeg, .gif, .xlsx, .doc, .odt, .txt, .ods or .docx";
              } elseif ($_FILES['teacher_image']['size'] > 1000000000) { // file shouldn't be larger than 1Megabyte
                echo "File too large!";
              } else {
                // move the uploaded (temporary) file to the specified destination
                $update_teacher = $pdo->query(" UPDATE teachers SET image_link='$filename', fsize='$size' WHERE id='$teacherid'");
                if (move_uploaded_file($file, $destination)) {
                  echo "<div class='alert alert-success'>Image Updated</div>";
                }
              }
            }
            //update teacher details
            $update_teacher = $pdo->prepare("UPDATE teachers SET firstname='$firstname', lastname='$lastname', email='$email', phone='$phone', assigned_subject='$attached_subject',section='$teacher_section' WHERE id='$teacherid'");
            $update_teacher->execute();
            //update users table
            $update_users = $pdo->prepare("UPDATE users SET user_type='$teacher_role' WHERE userid='$teachid'");
            $update_users->execute();
            if ($update_teacher) {
              echo "<div class='alert alert-success'>teacher details updated</div>";
            }
          }
          ?>
          <div class="row">
            <div class="col-lg-9">
              <form method='post' autocomplete="off">
                <select class="form-control" name="filter_teacher" onchange="this.form.submit();">
                  <option>--FILTER--</option>
                  <?php
                  $result_teacher = $pdo->query("select * from teachers where status=1 order by firstname asc");
                  $count_teacher = $result_teacher->rowCount();
                  $row_teacher = $result_teacher->fetchObject();
                  if ($count_teacher > 0) {
                    do {
                      echo "<option value='" . $row_teacher->id . "'>" . $row_teacher->firstname . " " . $row_parent->teacher . "</option>";
                    }
                    while ($row_teacher = $result_teacher->fetchObject());
                  } else {
                    echo "No teachers registered...please add one";
                  }
                  ?>
                </select>
              </form>
            </div>

            <div class="col-lg-3">
              <a class="btn btn-sm btn-success btn-block" data-toggle='modal' data-target='#myteacher'>+ Add Teacher</a>
            </div>
          </div>
          <div class="col-lg-12"><br></div>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr class="text-center">
                  <th>No</th>
                  <th>Name</th>
                  <th>Contact</th>
                  <th>Assigned Subjects</th>
                  <th>Image</th>
                  <th>Section</th>
                  <th>Class</th>
                  <th colspan="2">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                //filtering check based on onchange event
                if (isset($_POST["filter_teacher"])) {
                  $filter_teacher = $_POST["filter_teacher"];
                  if (!empty($_POST["filter_teacher"])) {
                    $prod = "and id='$filter_teacher'";
                  }
                } else {
                  $prod = "";
                }
                $result_teachers = $pdo->query("select * from teachers where status=1 $prod order by firstname asc");
                $count_teachers = $result_teachers->rowCount();
                $row_teachers = $result_teachers->fetchObject();
                if ($count_teachers > 0) {
                  $no = 1;
                  do {
                    $image = (!empty($row_teachers->image_link)) ? $row_teachers->image_link : '../uploads/avatar.png';
                    echo "<tr>
<td>" . $no++ . "</td>
<td>" . $row_teachers->firstname . " " . $row_teachers->lastname . "</td>
<td>" . $row_teachers->phone . "<br><span style='color:maroon;font-weight:bold;'>[" . $row_teachers->email . "]</span></td>
<td>";
                    $attached_subject = explode(",", $row_teachers->assigned_subject);
                    for ($a = 0; $a <= 50; $a++) {
                      if (isset($attached_subject[$a]) and !empty($attached_subject[$a])) {
                        $assigned = $attached_subject[$a];
                        $select_subject = $pdo->query("select * from subjects where id='$assigned'");
                        $row_subject = $select_subject->fetchObject();
                        echo ($a + 1) . ". " . $row_subject->subject_name . "<br>";
                      }
                    }
                    echo "</td>
<td><img src='../uploads/" . $image . "' alt='Add Images' style='width:50px;max-height:50px;height: expression(this.height > 50 ? 50: true);min-height:50px;height: expression(this.height < 50 ? 50: true);' class='img-circle'></td>
<td>" . $row_teachers->section . "</td>
<td>" . $row_teachers->class_id . "</td> <!-- Display Class ID -->
<td><a href='#teacher_edit' data-toggle='modal' class='btn btn-primary btn-sm btn-flat teach' data-id='" . $row_teachers->id . "'><i class='fas fa-edit'>&nbsp&nbspEdit</i></a></td>
<td>"; ?>
                    <form method='post' onsubmit="return delete_checker('Teacher','Deleted');">
                      <?php echo "
<input type='hidden' name='teacherid' value=" . $row_teachers->id . ">
<button type='submit' name='delete_teacher' class='btn btn-sm btn-danger'><i class='fas fa-trash'>&nbsp&nbspDelete</i></button>
</form>
</td>
</tr>";
                  } while ($row_teachers = $result_teachers->fetchObject());
                } else {
                  echo "<tr class='text-center'><td colspan='9'>No teachers registered....please add one</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script>
          function delete_checker(names, act) {
            var confirmer = confirm(names + " Will  Be " + act + " Click Ok; To Confirm ");
            if (confirmer == false) { return false; }
          }
        </script>
        <script>
          $(function () {
            $(document).on('click', '.teach', function (e) {
              e.preventDefault();
              var id = $(this).data('id');
              getRow(id);
            });

          });
          function getRow(id) {
            $.ajax({
              type: 'POST',
              url: 'editteacher.php',
              data: { id: id },
              dataType: 'json',
              success: function (response) {
                $('#firstname').val(response.firstname);
                $('#lastname').val(response.lastname);
                $('#tphone').val(response.phone);
                $('#temail').val(response.email);
                $('#teacherid').val(response.id);
                $('#attached_subject').val(response.subject);
                $('#teachid').val(response.teacherid);
              }
            });
          }
        </script>
      </div>
      <?php include ("forms.php"); ?>
      <?php include ("edit_forms.php"); ?>
      <script type="text/javascript">
        $(".dropdown dt a").on('click', function () {
          $(".dropdown dd ul").slideToggle('fast');
        });

        $(".dropdown dd ul li a").on('click', function () {
          $(".dropdown dd ul").hide();
        });

        function getSelectedValue(id) {
          return $("#" + id).find("dt a span.value").html();
        }

        $(document).bind('click', function (e) {
          var $clicked = $(e.target);
          if (!$clicked.parents().hasClass("dropdown")) $(".dropdown dd ul").hide();
        });
        $('.check').click(function () {
          $(".multiSelodisp").val('');
          $(".check").each(function () {
            if ($(this).prop('checked')) {

              $(".multiSelodisp").val($(".multiSelodisp").val() + $(this).val() + ",");
            };
          });




        });
      </script>
    </div>
  </div>
</body>

</html>