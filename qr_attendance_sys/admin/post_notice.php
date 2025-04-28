<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_sys";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle notice posting
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notice_title = isset($_POST['notice_title']) ? $_POST['notice_title'] : '';
    $notice_desc = isset($_POST['notice_desc']) ? $_POST['notice_desc'] : '';

    if (!empty($notice_title) && !empty($notice_desc)) {
        $stmt = $conn->prepare("INSERT INTO notices (title, description, posted_on) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $notice_title, $notice_desc);
        if ($stmt->execute()) {
            $message = "Notice posted successfully.";
        } else {
            $message = "Error posting notice: " . $stmt->error;
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Notice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #A5B5BF;
            /* Background color as per your preference */
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

        /* Align buttons side by side */
        .button-group {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .button-group a {
            margin-left: 10px;
            /* Space between buttons */
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
            padding: 10px 15px;
            border-radius: 4px;
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
    <div class="container">
        <h2>Post Notice</h2>

        <?php if (isset($message)): ?>
            <div class="success-message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="post_notice.php">
            <input type="text" name="notice_title" placeholder="Notice Title" required>
            <textarea name="notice_desc" placeholder="Notice Description" rows="4" required></textarea>
            <div class="button-group">
                <input type="submit" value="Post Notice">
                <a href="manage_attendance.php">Back to Manage Attendance</a>
            </div>
        </form>
    </div>
</body>

</html>

<?php
$conn->close();
?>