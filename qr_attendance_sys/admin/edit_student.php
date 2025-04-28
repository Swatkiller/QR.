<?php
// Start the session
session_start();

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

$sid = isset($_GET['sid']) ? trim($_GET['sid']) : '';

if (empty($sid)) {
    echo "Invalid student ID.";
    exit();
}

$sql = "SELECT * FROM student_details WHERE sid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $sid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No student found with the provided ID: " . htmlspecialchars($sid);
    exit();
}

$student = $result->fetch_assoc();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #a5b5bf;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1 {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Edit Student</h1>

        <form action="update_student.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="sid" value="<?php echo htmlspecialchars($student['sid']); ?>">

            <div class="row">
                <div class="col-md-6">
                    <label for="first_name">First Name:</label>
                    <input type="text" class="form-control" name="first_name"
                        value="<?php echo htmlspecialchars($student['first_name']); ?>" required>

                    <label for="last_name">Last Name:</label>
                    <input type="text" class="form-control" name="last_name"
                        value="<?php echo htmlspecialchars($student['last_name']); ?>" required>

                    <label for="fathers_name">Father's Name:</label>
                    <input type="text" class="form-control" name="fathers_name"
                        value="<?php echo htmlspecialchars($student['fathers_name']); ?>">

                    <label for="mothers_name">Mother's Name:</label>
                    <input type="text" class="form-control" name="mothers_name"
                        value="<?php echo htmlspecialchars($student['mothers_name']); ?>">

                    <label for="dob">Date of Birth:</label>
                    <input type="date" class="form-control" name="dob"
                        value="<?php echo htmlspecialchars($student['dob']); ?>" required>

                    <label for="gender">Gender:</label>
                    <select class="form-control" name="gender">
                        <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female
                        </option>
                        <option value="Other" <?php echo ($student['gender'] == 'Other') ? 'selected' : ''; ?>>Other
                        </option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="grade">Grade:</label>
                    <input type="text" class="form-control" name="grade"
                        value="<?php echo htmlspecialchars($student['grade']); ?>" required>

                    <label for="section">Section:</label>
                    <input type="text" class="form-control" name="section"
                        value="<?php echo htmlspecialchars($student['section']); ?>" required>

                    <label for="mobileno">Mobile No:</label>
                    <input type="text" class="form-control" name="mobileno"
                        value="<?php echo htmlspecialchars($student['mobileno']); ?>" required>

                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email"
                        value="<?php echo htmlspecialchars($student['email']); ?>" required>

                    <label for="image">Student Image:</label>
                    <input type="file" class="form-control" name="image" accept="image/*">

                    <label for="qr_code">QR Code:</label>
                    <input type="text" class="form-control" name="qr_code"
                        value="<?php echo htmlspecialchars($student['qr_code']); ?> " readonly>
                </div>
            </div>

            <input type="submit" class="btn btn-primary" value="Update">
            <button type="button" class="btn btn-secondary"
                onclick="window.location.href='manage_students.php'">Cancel</button>

        </form>
    </div>
</body>

</html>