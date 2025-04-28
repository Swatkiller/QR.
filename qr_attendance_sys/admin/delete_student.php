<?php
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

// Get the student ID (sid) from the URL
$sid = isset($_GET['sid']) ? $_GET['sid'] : '';

if (!empty($sid)) {
    // Build the absolute QR code file path
    $qr_code_file = __DIR__ . "/backend/qrcodes/student_" . $sid . ".png"; // Use absolute path

    // Debug the file path
    echo "QR Code File Path: " . $qr_code_file . "<br>";

    // Check if the file exists
    if (file_exists($qr_code_file)) {
        // Attempt to delete the file
        if (unlink($qr_code_file)) {
            // QR code file successfully deleted
            echo "QR code deleted successfully.<br>";
        } else {
            // Debug why file deletion failed
            $error = error_get_last();
            echo "Error deleting QR code file: " . $error['message'] . "<br>";
        }
    } else {
        echo "QR code file does not exist.<br>";
    }

    // Prepare and execute the DELETE statement for the student
    $sql = "DELETE FROM student_details WHERE sid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $sid);

    if ($stmt->execute()) {
        // Redirect back to the student list with a success message
        header("Location: manage_students.php?message=Student%20and%20QR%20code%20deleted%20successfully");
        exit();
    } else {
        echo "Error deleting student: " . $conn->error;
    }
} else {
    echo "Invalid student ID.";
}

$conn->close();
?>