<?php
require './backend/db_connection.php'; 

// Get the email from the request
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'];

// Prepare a SQL statement to check for duplicates
$sql = "SELECT * FROM student_details WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if the email exists
$response = ['exists' => $result->num_rows > 0];
echo json_encode($response);

$stmt->close();
$conn->close();
?>
