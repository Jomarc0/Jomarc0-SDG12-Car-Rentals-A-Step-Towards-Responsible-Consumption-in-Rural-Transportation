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
    <link rel="stylesheet" href="../css/signup.css"> 
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