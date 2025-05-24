<?php
// Include the database connection file
include ("../db.php");
// Check if an ID was provided in the POST request
if (isset($_POST['id'])) {
  // Retrieve the parent ID from the POST request
  $id = $_POST['id'];
  // Prepare a query to fetch the parent details based on the provided ID

  $stmt = $pdo->prepare("select * from parents where id=:id");
  $stmt->execute(['id' => $id]);
  $row = $stmt->fetch();
  // Encode the parent details into JSON format and return the response

  echo json_encode($row);
}
?>