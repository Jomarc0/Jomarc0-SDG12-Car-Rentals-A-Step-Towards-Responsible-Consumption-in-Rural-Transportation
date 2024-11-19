<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $admin = new Admin(); // Create an instance of the Admin class
    $errorMessage = $admin->login($username, $password);

    if ($errorMessage) {
        // Redirect with error message
        header("Location: adminLogIn.php?error=" . urlencode($errorMessage));
        exit;
    }
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
            <h1 class="form-title">Admin</h1>
            <form method="post" action="adminLogin.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="username" name="username" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign In">
            </form>
        </div>
    </body>
    </html>