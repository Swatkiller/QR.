<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_sys";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sid = $_POST['sid'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $fathers_name = $_POST['fathers_name'];
    $mothers_name = $_POST['mothers_name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $grade = $_POST['grade'];
    $section = $_POST['section'];
    $mobileno = $_POST['mobileno'];
    $email = $_POST['email'];
    $qr_code = $_POST['qr_code'];

    // Prepare and bind
    $sql = "UPDATE student_details SET first_name=?, last_name=?, fathers_name=?, mothers_name=?, dob=?, gender=?, grade=?, section=?, mobileno=?, email=?, qr_code=? WHERE sid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssss", $first_name, $last_name, $fathers_name, $mothers_name, $dob, $gender, $grade, $section, $mobileno, $email, $qr_code, $sid);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to manage_students.php with a success message
        header("Location: manage_students.php?message=Student%20updated%20successfully");
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>