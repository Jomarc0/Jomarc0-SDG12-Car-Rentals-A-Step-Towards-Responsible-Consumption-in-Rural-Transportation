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
    <style>
    body {
    font-family: Arial, sans-serif;
    background-color: #1a1a1a; /* Dark background color */
    color: #fff; /* White text color */
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh; /* Full height */
    margin: 0; /* Remove default margin */
}

.container {
    background-color: #2a2a2a; /* Background color for form */
    padding: 20px; /* Padding around the form */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Shadow effect */
    width: 500px; /* Fixed width for the form */
    text-align: center; /* Center text */
}

.form-title {
    font-size: 1.5rem; /* Title font size */
    margin-bottom: 20px; /* Space below title */
}

.fields-input {
    display: flex; /* Use flexbox for layout */
    justify-content: space-between; /* Space between input fields */
    margin-bottom: 20px; /* Space below input fields */
}

.otp-field {
    width: 40px; /* Fixed width for each input */
    height: 40px; /* Fixed height for each input */
    background-color: #3e3e3e; /* Darker background for input fields */
    border: 1px solid #4b4e69; /* Border color */
    border-radius: 5px; /* Rounded corners */
    color: #ffffff; /* White text color */
    font-size: 1.2rem; /* Larger font size */
    text-align: center; /* Center text in input */
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Transition effects */
}

.otp-field:focus {
    border-color: gold; /* Change border color on focus */
    box-shadow: 0 0 5px gold; /* Shadow effect on focus */
    outline: none; /* Remove default outline */
}

.btn {
    background-color: gold; /* Button background color */
    color: #000; /* Button text color */
    border: none; /* Remove border */
    border-radius: 5px; /* Rounded corners */
    padding: 10px 15px; /* Padding for button */
    font-size: 1rem; /* Button font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease, transform 0.2s ease; /* Transition effects */
}

.btn:hover {
    background-color: #d0b8ff; /* Change background color on hover */
    transform: scale(1.05); /* Slightly enlarge on hover */
}

.message {
    margin-top: 15px; /* Space above message */
    font-size: 0.9rem; /* Smaller font size for message */
    color: red; /* Red color for error messages */
}
    </style>
</head>
<body>
    <div class="container" id="verificationCode">
        <h1 class="form-title">Verify Your Account</h1>
        <p>Enter 6-digit code sent to your registered email address.</p>
        <form action="" method="post" autocomplete="off">
            <div class="fields-input">
                <input type="number" name="opt1" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt2" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt3" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt4" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt5" class="otp-field" placeholder="0" required onpaste="false">
                <input type="number" name="opt6" class="otp-field" placeholder="0" required onpaste="false">
            </div>
            <input type="submit" class="btn" value="Verify" name="verify" id="verifyButton">
        </form>
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>
    <script src="../js/verify.js"></script>
</body>
</html>