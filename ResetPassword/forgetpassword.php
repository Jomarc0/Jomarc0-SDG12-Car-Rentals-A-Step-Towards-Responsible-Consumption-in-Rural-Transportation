<?php
require_once 'OTPmailer.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; 

    $otpService = new OTPService(); 

    if ($otpService->sendOTP($email)) { 
        // Redirect to the OTP verification page with the email as a query parameter
        header("Location: verifyOTP.php?email=" . urlencode($email));
        exit; // Make sure to exit after redirecting
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
    <h2>Forget Password</h2>
    <p>Enter your email address </p><br><br>
        <form action="" method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                
            </div>
            <button type="submit">Reset</button>
        </form>
    </div>
</body>
</html>