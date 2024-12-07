<?php
require_once __DIR__ . '/../dbcon/dbcon.php'; 
require_once __DIR__ . '/resetpassword.php'; 

// get the email and verification code in the http
$email = isset($_GET['email']) ? $_GET['email'] : '';
$code = isset($_GET['verification_code']) ? $_GET['verification_code'] : '';

$message = ""; // message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $newPassword = $_POST['newPassword'];
    $newConfirmPassword = $_POST['newConfirmPassword'];

    if ($newPassword !== $newConfirmPassword) { //check if password match
        $message = "Passwords do not match.";
    } else {
        $passwordReset = new ResetPassword(); // call passwordreset class from resetpassword
        $message = $passwordReset->resetPassword($email, $code, $newPassword, $newConfirmPassword); // resetpassword function parameter
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
    <link rel="stylesheet" href="../css/changepassword.css">

</head>
<body>
    <div class="container" id="signIn">
    <h2>Reset Password</h2> <br>
    <p>Your new password must be different from previous passwords</p>
    <p>Password should be at least 8 characters</p><br>
    <form action="" method="post"> 
        <div class="input-group"><!-- put the emaul and veriication code hidden para  masama sa form -->
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>"> 
            <input type="hidden" name="verification_code" value="<?php echo htmlspecialchars($code); ?>">   
        </div>
        <div class="input-group">
            <input type="password" name="newPassword" id="password" placeholder="Enter New password" required>
        </div>
        <div class="input-group">
            <input type="password" name="newConfirmPassword"  id='newConfirmPassword' placeholder="Confirm password" required>
        </div>
        <button type="submit" name="changePass">Change Password</button>
    </form>
    </div>
    <?php if ($message): //if has an error or successful?> 
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
</body>
</html>