<?php
require_once '../Mailer/UserMailer.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 

    $otpService = new SendEmail(); 

    if ($otpService->sendOTP($email)) { 
        header("Location: verifyreset.php?email=" . urlencode($email));        // got to OTP verification page with get email in http
        exit; 
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
    <link rel="stylesheet" href="../css/forgetpassword.css">

</head>
<body>
    
    <div class="container" id="signIn">
    <h2>Forget Password</h2>
    <p>Enter your email address </p><br><br>
        <form action="" method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i> <!-- input email where the verification will send-->
                <input type="email" name="email" id="email" placeholder="Email" required>
                
            </div>
            <button type="submit">Reset</button>
        </form>
    </div>
</body>
</html>