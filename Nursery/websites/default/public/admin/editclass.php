<?php
// Include the database connection file
include ("../db.php");
// Check if an ID was provided in the POST request
if (isset($_POST['id'])) {
  // Retrieve the class ID from the POST request
  $id = $_POST['id'];
  // Prepare a query to fetch the class details based on the provided ID

  $stmt = $pdo->prepare("select * from classes where id=:id");
  // Execute the query with the class ID as a parameter

  $stmt->execute(['id' => $id]);
  // Fetch the class details as an array
  $row = $stmt->fetch();
  // Encode the class details into JSON format and return the response
  echo json_encode($row);
}
?>