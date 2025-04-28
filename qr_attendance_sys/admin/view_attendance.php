<?php
// Database connection details
$servername = "localhost";
$username = "root"; // replace with your database username
$password = ""; // replace with your database password
$dbname = "attendance_sys"; // replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the student ID (sid) from the URL
$sid = isset($_GET['sid']) ? $_GET['sid'] : '';

// Fetch student name and total present days
if (!empty($sid)) {
    // Fetch student name
    $sql_student = "SELECT first_name, last_name FROM student_details WHERE sid = ?";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("s", $sid);
    $stmt_student->execute();
    $result_student = $stmt_student->get_result();

    if ($result_student->num_rows > 0) {
        $student = $result_student->fetch_assoc();
        $student_name = $student['first_name'] . ' ' . $student['last_name'];

        // Fetch total present days
        $sql_attendance = "SELECT COUNT(*) as total_present FROM attendance WHERE sid = ? AND status = 'Present'";
        $stmt_attendance = $conn->prepare($sql_attendance);
        $stmt_attendance->bind_param("s", $sid);
        $stmt_attendance->execute();
        $result_attendance = $stmt_attendance->get_result();
        $attendance_data = $result_attendance->fetch_assoc();
        $total_present = $attendance_data['total_present'];

        // Fetch attendance records
        $sql_records = "SELECT date, status FROM attendance WHERE sid = ?";
        $stmt_records = $conn->prepare($sql_records);
        $stmt_records->bind_param("s", $sid);
        $stmt_records->execute();
        $result_records = $stmt_records->get_result();
    } else {
        echo "No student found with the provided ID.";
        exit();
    }
} else {
    echo "Invalid student ID.";
    exit();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #a5b5bf;
        }

        .attendance-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            margin-top: 50px;
            /* Spacing from the top */
        }
    </style>
</head>

<body>
    <div class="container attendance-container">
        <h1 class="text-center">Attendance for <?php echo htmlspecialchars($student_name); ?></h1>
        <p><strong>Student ID:</strong> <?php echo htmlspecialchars($sid); ?></p>
        <p><strong>Total Present Days:</strong> <?php echo $total_present; ?></p>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_records->num_rows > 0): ?>
                    <?php while ($row = $result_records->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No attendance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="manage_students.php" class="btn btn-primary">Back to Manage Students</a>
    </div>
</body>

</html>