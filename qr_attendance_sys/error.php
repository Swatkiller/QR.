<?php
$message = isset($_GET['message']) ? $_GET['message'] : 'An error occurred.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #e74c3c;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 30px;
            text-align: center;
        }
        a {
            text-decoration: none;
            color: white;
            background-color: red;
            padding: 10px 20px;
            border-radius: 5px;
            margin-right: 55px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #3b3b6d;
            transform: translateY(-2px);
        }
        
        .container {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Error</h1>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="index.php">Go Back</a>
    </div>
</body>
</html>
