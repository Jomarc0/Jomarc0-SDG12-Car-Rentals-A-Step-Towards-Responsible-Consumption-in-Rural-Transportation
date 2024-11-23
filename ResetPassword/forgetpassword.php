<?php
require_once 'OTPmailer.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 

    $otpService = new OTPService(); 

    if ($otpService->sendOTP($email)) { 
        // Redirect to the OTP verification page with the email as a query parameter
        header("Location: verifyOTP.php?email=" . urlencode($email));
        exit; // Make sure to exit after redirecting
    } else {
        echo "Failed to send OTP. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Password Reset</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    
    <div class="container" id="signIn">
    <h2>Forget Password</h2>
    <p>Enter your email address </p><br><br>
        <form action="" method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <button type="submit">Reset</button>
        </form>
    </div>
</body>
</html>