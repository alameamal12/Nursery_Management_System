<?php
// Include the database connection file
include ("../db.php");
// Check if the 'id' parameter is set in the POST request
if (isset($_POST['id'])) {
  // Retrieve the 'id' parameter value
  $id = $_POST['id'];
  // Prepare a SQL statement to fetch the message details by 'id'

  $stmt = $pdo->prepare("select * from messages where id=:id");
  // Execute the statement with the 'id' parameter
  $stmt->execute(['id' => $id]);
  //Fetch the row containing the message details
  $row = $stmt->fetch();
  // Return the message details in JSON format
  echo json_encode($row);
}
?>