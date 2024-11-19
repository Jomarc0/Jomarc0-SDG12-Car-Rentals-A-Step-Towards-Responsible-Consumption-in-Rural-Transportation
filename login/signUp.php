<?php 
require_once 'dbSignUp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["fName"];
    $last_name = $_POST["lName"];
    $address = $_POST["address"];
    $gender = $_POST["gender"];
    $dob = $_POST["dob"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phone"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];

    $userRegistration = new UserRegistration();
    $registrationMessage = $userRegistration->register($first_name, $last_name, $address, $gender, $dob, $email, $phoneNumber, $password, $confirmPassword);

    if ($registrationMessage) {
        echo $registrationMessage;
        if (strpos($registrationMessage, 'successful') !== false) {
            header("Location: verify.php");
            exit;
        }
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

        <div class="container" id="signup">
        <h1 class="form-title">Register</h1>
        <form action="" method="post">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required>
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-phone"></i>
                <input type="phone" name="phone" id="phone" placeholder="Phone Number" required pattern="\d{11}" maxlength="11" minlength="11">
                <label for="phone">Phone Number</label>
            </div>
            <div class="input-group">
                <i class="fas fa-home"></i>
                <input type="text" name="address" id="address" placeholder="House No/Street/Brgy/City" required>
                <label for="address">House No/Street/Brgy/City</label>
            </div>
            <div class="input-group">
                <i class="fas fa-venus-mars"></i>
                <input type="text" name="gender" id="gender" placeholder="Gender (e.g., Male, Female)" required>
                <label for="gender">Gender</label>
            </div>
            <div class="input-group">
                <i class="fas fa-calendar-alt"></i>
                <input type="date" name="dob" id="dob" required>
                <label for="dob">Date of Birth</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password"  required>
                <label for="confirmPassword">Confirm Password</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
            </form> 
        <p class="or">
            ----------or--------
        </p>
        <div class="links">
            <p>Already Have Account?</p>
            <button id="signInButton"><a href="signIn.php">Sign In</a></button>
        </div>
        </div >
        <script src="script.js"></script>
</body>
</html>