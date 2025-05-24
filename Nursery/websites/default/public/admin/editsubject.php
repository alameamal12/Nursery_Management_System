<?php
// Include the database connection file
include ("../db.php");

// Check if an ID was provided in the POST request
if (isset($_POST['id'])) {

  // Retrieve the subject ID from the POST request
  $id = $_POST['id'];

  // Prepare a query to fetch the subject details based on the provided ID
  $stmt = $pdo->prepare("select * from subjects where id=:id");
  $stmt->execute(['id' => $id]);

  // Fetch the subject details 
  $row = $stmt->fetch();

  // Encode the subjecr details into JSON format and return the response
  echo json_encode($row);
}
?>