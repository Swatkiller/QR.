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

// Fetch all students with their QR codes
$sql = "SELECT sid, first_name, last_name, qr_code FROM student_details";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View QR Codes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" class="image/x-icon" href="./images/logo.png">
    <?php include './header.php' ?>
    <style>
        body {
            background-color: #a5b5bf;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .back-button {
            margin-bottom: 20px;
            display: block;
            text-align: center;
            max-width: 15%;
            margin-top: 50px;
            margin-left: 50px;
        }
    </style>
</head>

<body>
    <div class="brand-container">
        <a class="logo-brand" href="./dashboard.php">
            <img class="logo" src="./images/logo.png" alt="logo" />
        </a>
    </div>
    <div class="container">
        <h1 class="text-center">Students QR Codes</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>QR Code</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['sid']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                            <td>
                                <?php if (!empty($row['qr_code'])): ?>
                                    <a href="./backend/show_qr.php?id=<?php echo htmlspecialchars($row['sid']); ?>"
                                        class="btn btn-primary">View QR Code</a>
                                <?php else: ?>
                                    No QR Code
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <a href="manage_students.php" class="btn btn-primary">Back to Manage Students</a>
    </div>
</body>

</html>

<?php
// Close connection
$conn->close();
?>