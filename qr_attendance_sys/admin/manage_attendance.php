    <?php
    // Connect Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "attendance_sys";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Form submission handling for holiday action
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sid = isset($_POST['sid']) ? intval($_POST['sid']) : null;
        $action = $_POST['action'] ?? '';
        $today = date('Y-m-d');

        if ($action === 'Present' && $sid !== null) {
            // Delete any existing record for today
            $stmt = $conn->prepare("DELETE FROM attendance WHERE sid = ? AND date = ?");
            $stmt->bind_param("is", $sid, $today);
            $stmt->execute();

            // Insert Present
            $stmt = $conn->prepare("INSERT INTO attendance (sid, date, status, recorded_at) VALUES (?, ?, 'Present', NOW())");
            $stmt->bind_param("is", $sid, $today);
            $stmt->execute();
            $message = "Attendance marked Present.";
        }

        if ($action === 'Unpresent' && $sid !== null) {
            $stmt = $conn->prepare("DELETE FROM attendance WHERE sid = ? AND date = ? AND status = 'Present'");
            $stmt->bind_param("is", $sid, $today);
            $stmt->execute();
            $message = "Attendance marked Unpresent.";
        }

        if ($action === 'holiday') {
            // Remove all existing attendance for today
            $stmt = $conn->prepare("DELETE FROM attendance WHERE date = ?");
            $stmt->bind_param("s", $today);
            $stmt->execute();

            // Insert Holiday for all students
            $stmt = $conn->prepare("INSERT INTO attendance (sid, date, status, recorded_at) 
                                    SELECT sid, ?, 'Holiday', NOW() FROM student_details");
            $stmt->bind_param("s", $today);
            $stmt->execute();
            $message = "Holiday marked for all students.";
        }

        if ($action === 'undo_holiday') {
            // Remove holiday attendance for today
            $stmt = $conn->prepare("DELETE FROM attendance WHERE date = ? AND status = 'Holiday'");
            $stmt->bind_param("s", $today);
            $stmt->execute();
            $message = "Holiday undone.";
        }
    }

    // Search and Load Students
    $search_keyword = $_GET['search'] ?? '';
    $search_param = "%$search_keyword%";

    //<!-- SQL Query: Removed the LIMIT 5 to display all students -->
    $query = "
    SELECT sd.sid, CONCAT(sd.first_name, ' ', sd.last_name) AS name, sd.grade, sd.section,
        a.status AS attendance_status
    FROM student_details sd
    LEFT JOIN attendance a ON sd.sid = a.sid AND a.date = CURDATE()
    WHERE sd.sid LIKE ? OR CONCAT(sd.first_name, ' ', sd.last_name) LIKE ?
    ORDER BY sd.sid DESC
    ";

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
            .success-message.fade-out {
    opacity: 0;
    transition: opacity 0.5s ease;
}
.success-popup {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    padding: 12px 20px;
    border-radius: 8px;
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    font-size: 16px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.success-popup .tick {
    font-size: 20px;
}

/* Fade-out effect */
.success-popup.fade-out {
    opacity: 0;
    transition: opacity 0.5s ease;
}


        </style>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        var successPopup = document.querySelector('.success-popup');
        if (successPopup) {
            setTimeout(function() {
                successPopup.classList.add('fade-out');
            }, 2500); // Start fading after 2.5s

            setTimeout(function() {
                successPopup.style.display = 'none';
            }, 3000); // Hide fully after 3s
        }
    });

    function confirmHoliday() {
        return confirm("Are you sure you want to mark today as a Holiday for all students? It will erase today's attendance.");
    }

    function confirmUndoHoliday() {
        return confirm("Are you sure you want to undo today's Holiday?");
    }
</script>



    </head>

    <body>
    <?php if (isset($message)): ?>
    <div class="success-popup">
        <span class="tick">✔️</span>
        <?php echo $message; ?>
    </div>
<?php endif; ?>


        <div class="brand-container">
            <a class="logo-brand" href="./dashboard.php">
                <img class="logo" src="./images/logo.png" alt="logo" />
            </a>
        </div>

        <div class="container">
            <h2>Manage Attendance</h2>  

            <!-- Search Form -->
            <form method="GET" action="manage_attendance.php">
                <input type="text" name="search" placeholder="Search by Student ID or Name"
                    value="<?php echo htmlspecialchars($search_keyword); ?>" required>
                <button type="submit">Search</button>
            </form>

            <!-- Display Students -->
            <?php 
    // Check if holiday is marked for today
    $holiday_check = $conn->prepare("SELECT COUNT(*) AS count FROM attendance WHERE date = CURDATE() AND status = 'Holiday'");
    $holiday_check->execute();
    $holiday_result = $holiday_check->get_result();
    $holiday_row = $holiday_result->fetch_assoc();
    $is_holiday = ($holiday_row['count'] > 0); 
    ?>

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
                        <?php if (!$is_holiday): ?>
                            <form method="POST" action="manage_attendance.php" style="display:inline;">
                                <input type="hidden" name="sid" value="<?php echo $row['sid']; ?>">
                                <?php if ($row['attendance_status'] === 'Present'): ?>
                                    <input type="submit" name="action" value="Unpresent" style="background-color: #e74c3c;">
                                <?php else: ?>
                                    <input type="submit" name="action" value="Present" style="background-color: #27ae60;">
                                <?php endif; ?>
                            </form>
                        <?php else: ?>
                            <button disabled style="background-color: #bdc3c7; color: #fff;">Holiday</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align: center;">No students found.</p>
    <?php endif; ?>


    <!-- Mark Holiday / Undo Holiday Buttons -->
    <div style="text-align: center; margin-top: 20px;">
        <?php if (!$is_holiday): ?>
            <form method="POST" action="manage_attendance.php" style="display:inline;" onsubmit="return confirmHoliday();">
                <input type="hidden" name="action" value="holiday">
                <input type="submit" value="Mark All as Holiday"
                    style="background-color: #e74c3c; color: white; padding: 10px; border: none; border-radius: 4px;">
            </form>
        <?php else: ?>
            <form method="POST" action="manage_attendance.php" style="display:inline;" onsubmit="return confirmUndoHoliday();">
                <input type="hidden" name="action" value="undo_holiday">
                <input type="submit" value="Undo Holiday"
                    style="background-color: #f39c12; color: white; padding: 10px; border: none; border-radius: 4px;">
            </form>
        <?php endif; ?>

        <!-- Redirect to Post Notice Page -->
        <div style="text-align: center; margin-top: 20px;"> <!-- Add margin-top here to space it out -->
            <a href="post_notice.php"
                style="background-color: #2980b9; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none;">Go
                to Post Notice</a>
        </div>
    </div>


        </div>

    </body>

    </html>