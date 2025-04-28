<?php
// Your database connection code remains the same
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_sys";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission to toggle attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sid = isset($_POST['sid']) ? intval($_POST['sid']) : null;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'present') {
        if ($sid === null) {
            die("Error: SID cannot be null.");
        }

        // Check if attendance has already been marked for this student today
        $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM attendance WHERE sid = ? AND date = CURDATE() AND status = 'Present'");
        $stmt->bind_param("i", $sid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            $message = "Attendance already marked for today.";
        } else {
            // Mark attendance manually
            $stmt = $conn->prepare("INSERT INTO attendance (sid, date, status, recorded_at) VALUES (?, CURDATE(), 'Present', NOW())");
            $stmt->bind_param("i", $sid);
            if ($stmt->execute()) {
                $message = "Attendance marked as Present.";
            } else {
                $message = "Error marking attendance: " . $stmt->error;
            }
        }
    }

    if ($action === 'holiday') {
        // Mark all students as Holiday
        $stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM attendance WHERE date = CURDATE() AND status = 'Holiday'");
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $row_check = $result_check->fetch_assoc();

        if ($row_check['count'] > 0) {
            $message = "Holiday already marked for today.";
        } else {
            // Insert holiday for all students
            $stmt = $conn->prepare("INSERT INTO attendance (sid, date, status, recorded_at) SELECT sid, CURDATE(), 'Holiday', NOW() FROM student_details");
            if ($stmt->execute()) {
                $message = "All students marked as Holiday for today.";
            } else {
                $message = "Error marking holiday: " . $stmt->error;
            }
        }
    }

    if ($action === 'undo_holiday') {
        // Undo the holiday by resetting the attendance status for all students
        $stmt_check = $conn->prepare("SELECT * FROM attendance WHERE date = CURDATE() AND status = 'Holiday'");
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // There are records with 'Holiday' status, so let's delete them
            $stmt = $conn->prepare("DELETE FROM attendance WHERE date = CURDATE() AND status = 'Holiday'");
            if ($stmt->execute()) {
                $message = "Holiday status undone for all students.";
            } else {
                $message = "Error undoing holiday: " . $stmt->error;
            }
        } else {
            $message = "No holiday status found for today.";
        }
    }
}

// Search students by sid or name remains the same
$search_keyword = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT student_details.sid, 
                 CONCAT(student_details.first_name, ' ', student_details.last_name) AS name, 
                 student_details.grade, 
                 student_details.section 
          FROM student_details 
          WHERE student_details.sid LIKE ? OR CONCAT(student_details.first_name, ' ', student_details.last_name) LIKE ? 
          ORDER BY student_details.sid DESC LIMIT 5";
$search_param = "%$search_keyword%";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Attendance</title>
    <link rel="icon" class="image/x-icon" href="./images/logo.png">
    <?php include './header.php' ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #A5B5BF;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #2980b9;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input[type="text"],
        textarea {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        input[type="submit"],
        button {
            background-color: #2980b9;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: #1c598a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2980b9;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #2980b9;
            border-radius: 4px;
            margin-bottom: 30px;
            margin-left: -20px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #1c598a;
        }

        input[type="hidden"] {
            display: none;
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
        <h2>Manage Attendance</h2>

        <?php if (isset($message)): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Search Form -->
        <form method="GET" action="manage_attendance.php" style="margin-bottom: 20px; text-align: center;">
            <input type="text" name="search" placeholder="Search by Student ID or Name"
                value="<?php echo htmlspecialchars($search_keyword); ?>" required>
            <button type="submit"
                style="background-color: #2980b9; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Search</button>
        </form>

        <!-- Display Last 5 Registered Students -->
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Grade</th>
                    <th>Section</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['sid']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['grade']; ?></td>
                        <td><?php echo $row['section']; ?></td>
                        <td>
                            <form method="POST" action="manage_attendance.php" style="display:inline;">
                                <input type="hidden" name="sid" value="<?php echo $row['sid']; ?>">
                                <input type="submit" name="action" value="Present"
                                    style="background-color: #27ae60; color: white; padding: 5px; border: none; border-radius: 4px;">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No students found.</p>
        <?php endif; ?>

        <!-- Mark Holiday Form -->
        <div style="text-align: center; margin-right: 20px;margin-bottom: 20px;">
            <form method="POST" action="manage_attendance.php">
                <input type="hidden" name="action" value="holiday">
                <input type="submit" value="Mark All as Holiday"
                    style="background-color: #e74c3c; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer;">
            </form>
        </div>

        <!-- Redirect to Post Notice Page -->
        <div style="text-align: center;">
            <a href="post_notice.php"
                style="background-color: #2980b9; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;">Go
                to Post Notice</a>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>