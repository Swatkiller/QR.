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

// Fetch notices
$sql = "SELECT title, description, posted_on FROM notices ORDER BY posted_on DESC";
$result = $conn->query($sql);

// Handle POST request to trigger the Python QR code scanner
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Execute the Python script to open the camera and scan the QR code
    $output = shell_exec('python3 "C:/Program Files/Ampps/www/project-II/qr_attendance_sys/scan_qr.py" 2>&1');

    // Display the output from the Python script (e.g., scanned QR code data)
    echo "<pre>$output</pre>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Notices</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            font-weight: 600;
            color: #4a4a7d;
            margin-bottom: 30px;
        }

        .notice {
            padding: 20px;
            border-bottom: 1px solid #e1e1e1;
            transition: background-color 0.3s ease;
        }

        .notice:hover {
            background-color: #f0f8ff;
        }

        .notice h2 {
            color: #4a4a7d;
            font-weight: 600;
            margin: 0 0 10px;
        }

        .notice p {
            color: #555;
            font-weight: 300;
        }

        .notice small {
            color: #888;
            font-weight: 300;
            display: block;
            margin-top: 10px;
        }

        .mark_attendance {
            display: inline-block;
            margin: 30px auto 0;
            padding: 12px 25px;
            background-color: #4a4a7d;
            color: #fff;
            font-weight: 500;
            text-transform: uppercase;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .mark_attendance:hover {
            background-color: #3b3b6d;
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 0.9em;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Latest Admin Notices</h1>

        <?php
        if ($result->num_rows > 0) {
            // Output each notice
            while ($row = $result->fetch_assoc()) {
                echo "<div class='notice'>";
                echo "<h2>" . $row["title"] . "</h2>";
                echo "<p>" . $row["description"] . "</p>";
                echo "<small>Posted on: " . $row["posted_on"] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No notices available at the moment.</p>";
        }
        $conn->close();
        ?>

        <!-- Button to trigger Python script -->
        <form method="POST" action="">
            <button type="submit" class="mark_attendance">Mark Your Attendance</button>
        </form>

        <div class="footer">
            &copy; <?php echo date('Y'); ?> Admin Panel | All Rights Reserved
        </div>
    </div>

</body>

</html>