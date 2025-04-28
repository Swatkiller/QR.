<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login-Registration</title>
    <link rel="stylesheet" href="../static/css/login_style.css">
</head>

<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form>
                <label for="chk" aria-hidden="true">Sign Up</label>
                <input type="text" name="username" placeholder="Username" required="">
                <input type="email" name="email" placeholder="E-mail" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <button>Sign Up</button>
            </form>
        </div>
        <div class="login">
            <form>
                <label for="chk" aria-hidden="true">Log In</label>
                <input type="text" name="username" placeholder="Username" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <button>LogIn</button>
            </form>
        </div>
    </div>
</body>

</html>