<?php
// Include the database connection file
include ("../db.php");
// Check if the 'id' parameter is set in the POST request
if (isset($_POST['id'])) {
  // Get the 'id' value from the POST data
  $id = $_POST['id'];
  // Prepare SQL statement to select data based on 'id'
  $stmt = $pdo->prepare("select * from message_review where messageid=:id");
  // Execute the prepared statement with the 'id' parameter
  $stmt->execute(['id' => $id]);
  // Fetch the row from the result set
  $row = $stmt->fetch();
  // Encode the row as JSON and echo it
  echo json_encode($row);
}
?>