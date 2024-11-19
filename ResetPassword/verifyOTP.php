<!-- verify_otp.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="../login/login.css">
</head>
<body>
    <h2>Verify OTP</h2>
    <form action="resetpassword.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="text" name="verification_code" placeholder="Enter OTP" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <button type="submit" name="changePass" >Change Password</button>
    </form>
</body>
</html>