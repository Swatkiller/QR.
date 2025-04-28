<?php
session_start(); // Start the session
require "./db_connection.php";
require "../header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $address = $_POST['address'];
    $fathers_name = $_POST['fathers_name'];
    $mothers_name = $_POST['mothers_name'];
    $grade = $_POST['grade'];
    $section = $_POST['section'];
    $gender = isset($_POST['gender']) ? $_POST['gender'] : NULL;
    $dob = isset($_POST['dob']) ? $_POST['dob'] : NULL;
    $mobile_no = $_POST['mobile_no'];
    $email = $_POST['email'];

    // Check for duplicate email
    $checkEmailStmt = $conn->prepare("SELECT * FROM student_details WHERE email = ?");
    if (!$checkEmailStmt) {
        die("Prepare failed: " . $conn->error);
    }
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists, store data in session
        $_SESSION['form_data'] = $_POST; // Store the entire form data
        echo "<script>alert('This email address is already registered. Please use a different email.'); window.location.href='../registration.php';</script>";
        exit();
    }

    // Handle image upload
    if ($_FILES['profile_image']['name']) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "<script>alert('Error uploading file.'); window.location.href='../registration.php';</script>";
            exit();
        }
    } else {
        $image_path = NULL;
    }

    $section = ucwords($section); // capitalize section input

    // Insert into student_details table
    $stmt = $conn->prepare("INSERT INTO student_details (first_name, last_name, address, image, fathers_name, mothers_name, grade, section, gender, dob, mobileno, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssss", $first_name, $last_name, $address, $image_path, $fathers_name, $mothers_name, $grade, $section, $gender, $dob, $mobile_no, $email);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;

        // Generate QR code
        $command = escapeshellcmd("python3 \"./generate_qr.py\" $last_id " . escapeshellarg($grade) . " " . escapeshellarg($section));
        $output = shell_exec($command . " 2>&1");

        $log_data = "Command: $command\nOutput: $output\n";
        file_put_contents(__DIR__ . '/qrcodes/log.txt', $log_data, FILE_APPEND);

        $qr_code_file_name = 'student_' . $last_id . '.png';
        $qr_code_location = __DIR__ . '/qrcodes/' . $qr_code_file_name;

        // Check for QR code file
        if (!file_exists($qr_code_location)) {
            echo "<script>alert('QR code file not found at: $qr_code_location'); window.location.href = '../registration.php';</script>";
            exit();
        }

        // Update student_details with QR code location
        $stmt2 = $conn->prepare("UPDATE student_details SET qr_code = ? WHERE sid = ?");
        if (!$stmt2) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt2->bind_param("si", $qr_code_file_name, $last_id);

        if ($stmt2->execute()) {
            if (strpos($output, 'QR code generated and saved at') !== false) {
                header("Location: ../reg_success.php?id=$last_id&full_name=" . urlencode($first_name . ' ' . $last_name));
                exit();
            } else {
                echo "<script>alert('Error generating QR code. See log for details.'); window.location.href = '../registration.php';</script>";
            }
        } else {
            echo "<script>alert('Error: " . $stmt2->error . "'); window.location.href = '../registration.php';</script>";
        }

        $stmt2->close();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = '../registration.php';</script>";
    }

    $stmt->close();
    $conn->close();
}

// Clear session data after it's used in registration.php
if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    unset($_SESSION['form_data']); // Clear session data
} else {
    $form_data = []; // Empty array if no data
}
?>
