<?php
$last_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$full_name = isset($_GET['full_name']) ? $_GET['full_name'] : 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #1D1F20;
            color: #fff;
            text-align: center;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .message {
            margin-bottom: 20px;
        }
        .message h1 {
            font-size: 24px;
            margin: 0;
        }
        .message p {
            font-size: 18px;
            margin: 10px 0;
        }
        .loader {
            animation: rotate 1s infinite;
            height: 50px;
            width: 50px;
            margin-bottom: 20px;
        }
        .loader:before,
        .loader:after {
            border-radius: 50%;
            content: '';
            display: block;
            height: 20px;
            width: 20px;
        }
        .loader:before {
            animation: ball1 1s infinite;
            background-color: #cb2025;
            box-shadow: 30px 0 0 #f8b334;
            margin-bottom: 10px;
        }
        .loader:after {
            animation: ball2 1s infinite;
            background-color: #00a096;
            box-shadow: 30px 0 0 #97bf0d;
        }
        @keyframes rotate {
            0% {
                transform: rotate(0deg) scale(0.8);
            }
            50% {
                transform: rotate(360deg) scale(1.2);
            }
            100% {
                transform: rotate(720deg) scale(0.8);
            }
        }
        @keyframes ball1 {
            0% {
                box-shadow: 30px 0 0 #f8b334;
            }
            50% {
                box-shadow: 0 0 0 #f8b334;
                margin-bottom: 0;
                transform: translate(15px, 15px);
            }
            100% {
                box-shadow: 30px 0 0 #f8b334;
                margin-bottom: 10px;
            }
        }
        @keyframes ball2 {
            0% {
                box-shadow: 30px 0 0 #97bf0d;
            }
            50% {
                box-shadow: 0 0 0 #97bf0d;
                margin-top: -20px;
                transform: translate(15px, 15px);
            }
            100% {
                box-shadow: 30px 0 0 #97bf0d;
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="message">
            <h1>Registration Successful!</h1>
            <p>QR Code for '<?php echo $full_name?>' has been generated for Student_Id:  <?php echo $last_id ?>.</p>
            <p>You will be redirected to view it in <span id="countdown">5</span> seconds...</p>
            
        </div>
        <div class="loader"></div>
    </div>

    <script>
        var countdown = 5; // Countdown time in seconds
        function updateCountdown() {
            document.getElementById('countdown').innerText = countdown;
            if (countdown <= 0) {
                
                window.location.href = './backend/show_qr.php?id=<?php echo $last_id; ?>&full_name=<?php echo urlencode($full_name); ?>';

            } else {
                countdown--;
                setTimeout(updateCountdown, 1000);
            }
        }
        window.onload = function() {
            updateCountdown();
        };
    </script>
</body>
</html>
