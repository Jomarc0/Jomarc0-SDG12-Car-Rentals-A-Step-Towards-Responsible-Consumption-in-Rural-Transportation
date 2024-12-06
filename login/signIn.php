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
    <!-- <link rel="stylesheet" href="../css/login.css"> -->
    <style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background: #1a1a1a; /* Changed to match the background color */
  color: #fff; /* White text color */
  line-height: 1.6;
}

/* Container Styles */
.container {
  display: flex;
  width: 800px;
  height: 500px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
  border-radius: 10px;
  overflow: hidden;
}

/* Left Panel */
.left-panel {
  background: url('../pictures/bg.webp') no-repeat center center; 
  background-size: cover;
  width: 40%;
  padding: 20px;
  text-align: center;
  position: relative;
}

.left-panel .branding h1 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: gold; /* Changed to gold */
}

.left-panel .back-btn {
  background: none;
  color: #fff; /* White text color */
  border: none;
  font-size: 0.9rem;
  cursor: pointer;
  transition: color 0.3s ease;
}

.left-panel .back-btn:hover {
  color: gold; /* Changed hover color to gold */
}

.left-panel .image-text h2 {
  margin-top: 80px;
  font-size: 1.5rem;
  line-height: 1.5;
}

/* Right Panel */
.right-panel {
  background-color: #2a2a2a; /* Changed to match the overall theme */
  width: 60%;
  padding: 40px;
}

.right-panel h2 {
  font-size: 1.8rem;
  margin-bottom: 70px;
}

.right-panel p {
  font-size: 0.9rem;
  margin-bottom: 20px;
}

.right-panel a {
  color: gold; /* Changed link color to gold */
  text-decoration: none;
  transition: color 0.3s ease;
}

.right-panel a:hover {
  color: #d0b8ff; /* Changed hover color */
}

/* Input Fields */
input {
  background-color: #3e3e3e; /* Darker background for input fields */
  border: 1px solid #4b4e69;
  border-radius: 5px;
  padding: 10px;
  color: #ffffff; /* White text color */
  width: 100%;
  margin-bottom: 15px;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

input:focus {
  border-color: gold; /* Changed focus border color to gold */
  box-shadow: 0 0 5px gold; /* Changed focus shadow color to gold */
  outline: none;
}

.password-group {
  display: flex;
  align-items: center;
}

.password-group input {
  flex: 1;
}

.password-group .show-password {
  background: none;
  border: none;
  cursor: pointer;
  color: gold; /* Changed show password button color to gold */
  font-size: 1rem;
  margin-left: 10px;
}

.password-group .show-password:hover {
  color: #d0b8ff; /* Changed hover color */
}

/* Buttons */
button {
  border: none;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.create-account {
  background-color: gold; /* Changed button color to gold */
  color: #000; /* Black text color */
  border-radius: 5px;
  padding: 10px;
  width: 100%;
  font-size: 1rem;
  margin-top: 10px;
  margin-bottom: 20px;
}

.create-account:hover {
  background-color: #8f6ac8; /* Retained original hover color */
  transform: scale(1.02);
}

.google-btn, .apple-btn {
  flex: 1;
  background-color: #3e3e3e; /* Darker background for social buttons */
  border-radius: 5px;
  padding: 10px;
  color: #ffffff; /* White text color */
  font-size: 0.9rem;
   margin: 5px;
  transition: background-color 0.3s ease;
}

.google-btn:hover {
  background-color: #db4437; /* Retained original hover color */
}

.apple-btn:hover {
  background-color: #000000; /* Retained original hover color */
}

/* Checkbox */
label {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
  font-size: 0.8rem;
}

label input {
  margin-right: 10px;
}

/* Links Hover */
.right-panel a {
  transition: all 0.3s ease-in-out;
}

.right-panel a:hover {
  text-decoration: underline;
}
.recover{
  margin-top: 10px;
}
    </style>
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



