<?php
    require_once __DIR__ . '/../dbcon/dbcon.php'; 
    require_once __DIR__ . '/resetpassword.php'; // require resetpasswod

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $email = $_POST['email'];
        $verification_code = $_POST['verification_code'];
        $newPassword = $_POST['newPassword'];
        $newConfirmPassword = $_POST['newConfirmPassword'];


        $passwordReset = new PasswordReset(); //call passwordreset class from resetpassword
        $message = $passwordReset->resetPassword($email, $verification_code, $newPassword, $newConfirmPassword); //resetpassword function parameter
        echo "<p>$message</p>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <h2>Verify OTP</h2>
    <form action="" method="post"> 
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="text" name="verification_code" placeholder="Enter OTP" required>
        <input type="password" name="newPassword" placeholder="Enter new password" required>
        <input type="password" name="newConfirmPassword" placeholder="Enter new confirm password" required>
        <button type="submit" name="changePass">Change Password</button>
    </form>
</body>
</html>