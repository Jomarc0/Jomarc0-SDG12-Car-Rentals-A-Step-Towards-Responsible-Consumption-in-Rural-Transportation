<?php
require_once 'dbSignUp.php'; // Include your database connection file
require_once 'gmailAPI.php'; // Include the GoogleLogin class

// Create instances of the classes
$googleLogin = new GoogleLogin(); 
$userRegistration = new UserRegistration(); 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'google') {
    header("Location: " . $googleLogin->getAuthUrl());
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["fName"];
    $last_name = $_POST["lName"];
    $address = $_POST["address"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phone"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"]; // Get all the input data

    $registrationMessage = $userRegistration->register($first_name, $last_name, $address, $gender, $dob, $email, $phoneNumber, $password, $confirmPassword); // Register the user

    if ($registrationMessage) {
        $isError = true;
        if (strpos($registrationMessage, 'successful') !== false) { // Check if registration was successful
            header("Location: verify.php"); // Redirect to verification page
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: #1a1a1a; /* Background color */
    color: #fff; /* White text color */
    line-height: 1.4; /* Reduced line height */
}

/* Container Styles */
.container {
    display: flex;
    width: 800px; /* Width of the container */
    /* Removed fixed height to allow content to dictate height */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    border-radius: 10px;
    overflow: hidden;
}

/* Left Panel */
.left-panel {
    background: url('../pictures/bg.webp') no-repeat center center; 
    background-size: cover;
    width: 40%;
    padding: 20px;
    text-align: center;
    position: relative;
}

.left-panel .branding h1 {
    font-size: 1.5rem; /* Reduced font size */
    margin-bottom: 15px; /* Reduced margin */
    color: gold; /* Changed to gold */
}

.left-panel .back-btn {
    background: none;
    color: #fff; /* White text color */
    border: none;
    font-size: 0.9rem; /* Reduced font size */
    cursor: pointer;
    transition: color 0.3s ease;
}

.left-panel .back-btn:hover {
    color: gold; /* Changed hover color to gold */
}

.left-panel .image-text h2 {
    margin-top: 60px; /* Reduced margin */
    font-size: 1.2rem; /* Reduced font size */
    line-height: 1.5;
}

/* Right Panel */
.right-panel {
    background-color: #2a2a2a; /* Background color for right panel */
    width: 60%;
    padding: 30px; /* Reduced padding */
    display: flex;
    flex-direction: column; /* Stack elements vertically */
}

.right-panel h2 {
    font-size: 1.5rem; /* Reduced font size */
    margin-bottom: 15px; /* Reduced margin */
}

.right-panel p {
    font-size: 0.8rem; /* Reduced font size */
    margin-bottom: 15px; /* Reduced margin */
}

.right-panel a {
    color: gold; /* Changed link color to gold */
    text-decoration: none;
    transition: color 0.3s ease;
}

.right-panel a:hover {
    color: #d0b8ff; /* Changed hover color */
}

/* Input Fields */
.input-group {
    display: flex;
    flex-direction: column; /* Stack label and input vertically */
    margin-bottom: 8px; /* Reduced space between input groups */
}

.input-group input,
.input-group select {
    background-color: #3e3e3e; /* Darker background for input fields */
    border: 1px solid #4b4e69;
    border-radius: 5px;
    padding: 5px; /* Reduced padding */
    color: #ffffff; /* White text color */
    width: 100%; /* Full width */
    margin-top: 5px; /* Space between label and input */
    transition: border 0.3s ease, box-shadow 0.3s ease;
    font-size: 0.8rem; /* Smaller font size for inputs */
}

.input-group input:focus,
.input-group select:focus {
    border-color: gold; /* Focus border color */
    box-shadow: 0 0 5px gold; /* Focus shadow color */
    outline: none;
}

/* Buttons */

.create-account button {
    background-color: gold; /* Changed button color to gold */
    color: #000; /* Black text color */
    border-radius: 5px;
    padding: 8px; /* Reduced padding */
    width: 100%; /* Full width */
    font-size: 0.9rem; /* Smaller font size */
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.create-account:hover {
    background-color: #d0b8ff; /* Changed hover color */
    transform: scale(1.05); /* Slightly enlarge on hover */
}
label {
    display: flex;
    align-items: center;
    font-size: 0.7rem;
}

.google-btn {
  flex: 1;
  background-color: #3e3e3e; /* Darker background for social buttons */
  border-radius: 5px;
  padding: 10px;
  color: #ffffff; /* White text color */
  font-size: 0.9rem;
   margin: 5px;
  transition: background-color 0.3s ease;
}

.google-btn:hover {
  background-color: #db4437; /* Retained original hover color */
}

.apple-btn:hover {
  background-color: #000000; /* Retained original hover color */
}
</style>

</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="branding">
                <h1>QuickWheels</h1>
            </div>
            <button class="back-btn" onclick="window.location.href='../main/index.php'">Back to website →</button>
            <div class="image-text"></div>
        </div>
        <div class="right-panel">
            <h2>Create an account</h2>
            <?php if (isset($registrationMessage)): ?>
                <h2 style="color: <?php echo $isError ? 'red' : 'green'; ?>;">
                    <?php echo htmlspecialchars($registrationMessage) .'<br>'; ?>
                </h2>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="text" name="fName" id="fName" placeholder="First Name" required>
                </div>
                <div class="input-group">
                    <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                </div>
                <div class="input-group">
                    <input type="phone" name="phone" id="phone" placeholder="Phone Number" required pattern="\d{11}" maxlength="11" minlength="11">
                </div>
                <div class="input-group">
                    <input type="text" name="address" id="address" placeholder="House No/Street/Brgy/City" required>
                </div>
                <div class="input-group">
                    <select name="gender" id="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="input-group">
                    <input type="date" name="dob" id="dob" placeholder="Date of Birth" required>
                </div>
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required>
                </div>
                <div class="create-account">
                    <button type="submit">Sign Up</button>
                </div>
            </form>
            <p>Already have an account? <a href="../login/signIn.php">Log in</a></p>
            <div class="social-buttons">
                <form id="google-signin-form" action="" method="post">
                    <input type="hidden" name="action" value="google">
                    <input type="hidden" name="code" id="google-code" value="">
                    <button type="submit" class="google-btn" ><i class="fab fa-google"></i></button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>