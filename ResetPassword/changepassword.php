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
    <link rel="stylesheet" href="../css/login.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a1a; /* Dark background */
            color: #fff; /* White text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full height */
            margin: 0; /* Remove default margin */
        }

        .container {
            background-color: #2a2a2a; /* Form background */
            padding: 20px; /* Padding around the form */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Shadow effect */
            width: 400px; /* Fixed width for the form */
            text-align: center; /* Center text */
        }

        h2 {
            margin-bottom: 20px; /* Space below title */
        }

        .input-group {
            position: relative; /* Positioning for icon */
            margin-bottom: 20px; /* Space below input */
        }

        .input-group i {
            position: absolute; /* Position icon */
            left: 10px; /* Positioning */
            top: 50%; /* Center vertically */
            transform: translateY(-50%); /* Center icon */
            color: #888; /* Icon color */
        }

        .input-group input {
            width: 100%; /* Full width */
            padding: 10px 10px 10px 40px; /* Padding for input */
            border: 1px solid #4b4e69; /* Border color */
            border-radius: 5px; /* Rounded corners */
            background-color: #3e3e3e; /* Input background */
            color: #ffffff; /* Input text color */
            font-size: 1rem; /* Font size */
            transition: border-color 0.3s; /* Transition for border color */
        }

        .input-group input:focus {
            border-color: gold; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        button {
            background-color: gold; /* Button background color */
            color: #000; /* Button text color */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners */
            padding: 10px 15px; /* Padding for button */
            font-size: 1rem; /* Button font size */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Transition for background color */
        }

        button:hover {
            background-color: #d0b8ff; /* Change background color on hover */
        }

        .message {
            margin-top: 15px; /* Space above message */
            font-size: 0.9rem; /* Smaller font size for message */
            color: red; /* Red color for error messages */
        }
    </style>
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
    <?php if ($message): //if has an error or successful?> 
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
</body>
</html>