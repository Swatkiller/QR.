<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_sys";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$students = [];

$sql = "SELECT sid, first_name, last_name, grade, section FROM student_details";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch students into an array
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

$conn->close();

// Check if there's a message in the URL
$message = isset($_GET['message']) ? $_GET['message'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" class="image/x-icon" href="./images/logo.png">
    <?php include './header.php' ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #a5b5bf;
        }

        .container {
            width: 90%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .logo-brand img {
            margin-bottom: 30px;
            margin-left: -20px;
            max-width: 150px;
            border-radius: 10px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            width: 200px;
            margin: auto;
        }

        .btn-edit,
        .btn-delete {
            padding: 6px 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            color: white;
            margin: 0 2px;
        }

        .btn-edit {
            background-color: #4CAF50;
        }

        .btn-edit:hover {
            background-color: #45a049;
        }

        .btn-delete {
            background-color: #f44336;
            margin-left: 15px;
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .back-button {
            margin-bottom: 20px;
            display: block;
            text-align: center;
            max-width: 15%;
        }
    </style>
</head>

<body>

    <div class="brand-container">
        <a class="logo-brand" href="./dashboard.php">
            <img class="logo" src="./images/logo.png" alt="logo" />
        </a>
    </div>

    <h1>Student List</h1>
    <div class="container">

        <?php if (!empty($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>


        <?php if (count($students) > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['sid']); ?></td>
                            <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($student['grade']); ?></td>
                            <td><?php echo htmlspecialchars($student['section']); ?></td>
                            <td>
                                <div class="btn-container">
                                    <a class="btn btn-warning"
                                        href="view_attendance.php?sid=<?php echo $student['sid']; ?>">Attendance</a>
                                    <a class="btn btn-primary"
                                        href="edit_student.php?sid=<?php echo $student['sid']; ?>">Edit</a>
                                    <a class="btn btn-danger" href="delete_student.php?sid=<?php echo $student['sid']; ?>"
                                        onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No students found in the database.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>