<?php
// Include the database connection file
include ("../db.php");
// Check if an ID was provided in the POST request
if (isset($_POST['id'])) {

  // Retrieve the teacher ID from the POST request
  $id = $_POST['id'];

  // Prepare a query to fetch the teacher details based on the provided ID
  $stmt = $pdo->prepare("select * from teachers where id=:id");

  // Execute the query with the teacher ID as a parameter
  $stmt->execute(['id' => $id]);

  // Fetch the teacher details 
  $row = $stmt->fetch();
  // Encode the teacher details into JSON format and return the response

  echo json_encode($row);
}
?>