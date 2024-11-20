<?php
require_once __DIR__ . 'OTPmailer.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 

    $otpService = new OTPService(); 

    if ($otpService->sendOTP($email)) { 
        echo "OTP has been sent to your email address.";
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
    <h2>Request Password Reset</h2>
    <form action="" method="post"> <!--form action -->
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>
        <button type="submit">Send OTP</button>
    </form>
</body>
</html>