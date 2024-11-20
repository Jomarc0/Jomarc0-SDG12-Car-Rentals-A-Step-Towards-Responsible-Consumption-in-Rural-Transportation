<?php
require_once 'dbverify.php'; //require the verify 

$message = ""; //put message as null 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect input from the 6 fields
    $verification_code = $_POST["opt1"] . $_POST["opt2"] . $_POST["opt3"] . $_POST["opt4"] . $_POST["opt5"] . $_POST["opt6"]; //ippost yung 6 na opt or input ng user
    
    if (strlen($verification_code) !== 6) { //check if the length is not equal to 6 but the same data type
        $message = "Verification code must be 6 digits.";
    } else {
        $verification = new Verification(); // verification class
        $message = $verification->verifyAccount($verification_code); //input the verification code
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/verify.css">
</head>
<body>
    <div class="container" id="verificationCode">
        <h1 class="form-title">Verify Your Account</h1>
        <form action="" method="post" autocomplete="off">
            <div class="fields-input">
                <input type="number" name="opt1" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt2" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt3" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt4" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt5" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt6" class="otp-field" placeholder="0" required onpaste="false">
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