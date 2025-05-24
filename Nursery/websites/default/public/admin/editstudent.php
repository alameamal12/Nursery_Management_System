<?php
// Include the database connection file
include ("../db.php");

// Check if an ID was provided in the POST request
if (isset($_POST['id'])) {

  // Retrieve the student ID from the POST request
  $id = $_POST['id'];

  // Prepare a query to fetch the student details based on the provided ID
  $stmt = $pdo->prepare("select * from students where id=:id");
  $stmt->execute(['id' => $id]);
  //fetch the student details
  $row = $stmt->fetch();

  // Encode the student details into JSON format and return the response
  echo json_encode($row);
}
?>