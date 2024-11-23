<?php
require_once __DIR__ . '/resetpassword.php'; // require resetpassword

$message = ""; // Initialize message variable

// Check if the email is set in the GET parameters
$email = isset($_GET['email']) ? $_GET['email'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect input from the 6 OTP fields
    $verification_code = $_POST["opt1"] . $_POST["opt2"] . $_POST["opt3"] . $_POST["opt4"] . $_POST["opt5"] . $_POST["opt6"];
    
    if (strlen($verification_code) !== 6) {
        $message = "Verification code must be 6 digits.";
    } else {
        $resetPassword = new ResetPassword(); // Use the ResetPassword class
        $verification_result = $resetPassword->verifyOTP($email, $verification_code); // Verify the OTP
        
        if ($verification_result === true) {
            // Redirect to the password reset page with the email and verification code
            header("Location: changepassword.php?email=" . urlencode($email) . "&verification_code=" . urlencode($verification_code));
            exit; // Make sure to exit after redirecting
        } else {
            $message = $verification_result; // Capture any error message from the verification
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/verify.css">
</head>
<body>
    <div class="container" id="verificationCode">
        <h1 class="form-title">Verify Your Account</h1>
        <form action="" method="post" autocomplete="off">
            <div class="fields-input">
                <input type="number" name="opt1" class="otp-field" placeholder="0" required>
                <input type="number" name="opt2" class="otp-field" placeholder="0" required>
                <input type="number" name="opt3" class="otp-field" placeholder="0" required>
                <input type="number" name="opt4" class="otp-field" placeholder="0" required>
                <input type="number" name="opt5" class="otp-field" placeholder="0" required>
                <input type="number" name="opt6" class="otp-field" placeholder="0" required>
            </div>
            <input type="submit" class="btn" value="Verify ME" name="verify" id="verifyButton">
        </form>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
    <script src="../js/verify.js"></script>
</body>
</html>