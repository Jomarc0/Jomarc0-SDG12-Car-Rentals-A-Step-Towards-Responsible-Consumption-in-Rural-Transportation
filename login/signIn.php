<?php
require_once 'dbSignIn.php'; // Require login database
require_once 'gmailAPI.php'; // Include the GoogleLogin class

$errorMessage = '';
$googleLogin = new GoogleLogin();
$googleUrl = $googleLogin->getAuthUrl();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = new UserLogin(); // UserLogin class
    $loginMessage = $user->login($email, $password);

    if ($loginMessage) { // Debugging/error message
        $errorMessage = $loginMessage;
        header("Location: signIn.php");
        exit;
    }
}
?>

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/signin.css"> 
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="branding">
                <h1>QuickWheels</h1>
            </div>
            <button class="back-btn" onclick="window.location.href='../main/index.php'">Back to website →</button>
        </div>
        <div class="right-panel">
            <h2>Log in</h2>
            <?php if (isset($errorMessage)): ?>
                <p style="color: red;"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <input type="email" name="email" id="email" placeholder="Email or Phone Number" required>
                <div class="password-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <button class="create-account">Log in</button>
            </form>
            <p>Don't have an account? <a href="../login/signUp.php">Sign up</a></p>
            <p>Or log in with</p>
            <div class="social-buttons">
                <a href="<?= $googleUrl ?>" class="google-btn"><i class="fab fa-google"></i></a>
            </div>
            <p class="recover"><a href="../ResetPassword/forgetpassword.php">Recover Password</a></p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>



