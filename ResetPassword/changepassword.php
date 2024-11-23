<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; 
require_once __DIR__ . '/resetpassword.php'; // require resetpassword

// Retrieve the email and verification code from the query parameters
$email = isset($_GET['email']) ? $_GET['email'] : '';
$verification_code = isset($_GET['verification_code']) ? $_GET['verification_code'] : '';

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $newPassword = $_POST['newPassword'];
    $newConfirmPassword = $_POST['newConfirmPassword'];

    // Check if the new passwords match
    if ($newPassword !== $newConfirmPassword) {
        $message = "Passwords do not match.";
    } else {
        $passwordReset = new ResetPassword(); // call passwordreset class from resetpassword
        $message = $passwordReset->resetPassword($email, $verification_code, $newPassword, $newConfirmPassword); // resetpassword function parameter
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="container" id="signIn">
    <h2>Reset Password</h2> <br>
    <p>Your new password must be different from previous passwords</p>
    <p>Password should be at least 8 characters</p><br>
    <form action="" method="post"> 
        <div class="input-group">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <input type="hidden" name="verification_code" value="<?php echo htmlspecialchars($verification_code); ?>">   
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="newPassword" id="password" placeholder="Enter New password" required>
            <label for="newPassword">New Password</label>    
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="newConfirmPassword"  id='newConfirmPassword' placeholder="Confirm password" required>
            <label for="newConfirmPassword">Confirm Password</label>
        </div>
       
        <button type="submit" name="changePass">Change Password</button>
    </form>
    </div>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
</body>
</html>