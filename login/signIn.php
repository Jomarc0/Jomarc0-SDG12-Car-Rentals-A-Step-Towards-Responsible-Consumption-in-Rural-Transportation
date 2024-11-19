<?php
require_once 'dbSignIn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = new User();
    $loginMessage = $user->login($email, $password);

    // if ($loginMessage) {
    //     echo $loginMessage;
    //     header("Location: signIn.php");
    //     exit;
    // }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

        <div class="container" id="signIn">
            <h1 class="form-title">Sign In</h1>
            <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email or Phone Number" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <p class="recover">
                <a href="../ResetPassword/forgetpassword.php">Recover Password</a>
            </p>
            <input type="submit" class="btn" value="Sign In" name="signIn">
            </form>
            <p class="or">
            ----------or--------
            </p>
            <div class="links">
            <p>Don't have account yet?</p>
            <button id="signUpButton"><a href="signUp.php">Sign Up</a></button>
            </div>
        </div>
        <script src="script.js"></script>
    </body>
    </html>