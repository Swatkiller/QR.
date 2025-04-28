<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" class="image/x-icon" href="./images/logo.png">
    <?php include './header.php'?>
</head>

<body>

<div class="brand-container">
    <a class="logo-brand" href="./dashboard.php">
        <img class="logo" src="./images/logo.png" alt="logo" />
    </a>
</div>

<div class="attendance_topic">
    <h1>QR-BASED ATTENDANCE SYSTEM</h1>
</div>

<div class="image-container container">
    <div class="row">
        <div class="col-md-3 student-registration-container hover-effect">
            <a href="./registration.php" class="btn0-container">
                <img src="images/registration.jpeg" alt="Student Registration" class="admin_img">
                <button class="btn0">Register Student</button>
            </a>
        </div>

        <div class="col-md-3 attendance-container hover-effect">
            <a href="./manage_attendance.php" class="btn0-container">
                <img src="images/attendance.png" alt="Attendance" class="admin_img">
                <button class="btn0">Manage Attendance</button>
            </a>
        </div>

        <div class="col-md-3 manage-students hover-effect">
            <a href="./manage_students.php" class="btn0-container">
                <img src="images/manage_students.png" alt="Manage Students" class="admin_img">
                <button class="btn0">Manage Student</button>
            </a>
        </div>

        <div class="col-md-3 qr-container hover-effect">
            <a href="./view_qr.php" class="btn0-container">
                <img src="images/qrscanner1.jpg" alt="QR Scanner Test" class="admin_img">
                <button class="btn0">View QR-Codes </button>
        </div>
    </div>
</div>

<?php include "footer.php"?>

</body>
</html>
