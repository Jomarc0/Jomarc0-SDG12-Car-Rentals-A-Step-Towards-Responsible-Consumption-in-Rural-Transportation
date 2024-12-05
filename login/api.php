<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'gmailAPI.php';
require_once 'dbSignUp.php';

// Create instances of the GoogleLogin and UserRegistration classes
$googleLogin = new GoogleLogin(); 
$userRegistration = new UserRegistration(); 

// Check if the 'code' parameter is present in the URL
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    try {
        // Authenticate and get the token
        $token = $googleLogin->authenticate($code); 
        
        // Check if the token is valid
        if (isset($token['access_token'])) {
            // Get user info from Google
            $userinfo = $googleLogin->getUserInfo(); 
            
            // Check if the user is already registered
            if ($userRegistration->isUserRegistered($userinfo->email)) {
                session_start();
                $_SESSION['loggedIn'] = true;
                $_SESSION['user_id'] = $user['user_id']; //session the user_id
                $_SESSION['email'] = $user['email'];
                header('Location:' .'../main/index.php'); 
                exit;
            } else {
                // Register the user using the information retrieved from Google
                $message = $userRegistration->registerFromGoogle($userinfo->given_name, $userinfo->family_name,$userinfo->email); 
                
                // Redirect to the index page after successful registration
                header('Location:' .'verify.php'); 
                exit;
            }
        } else {
            throw new Exception("Invalid token format: " . json_encode($token));
        }
    } catch (Exception $e) {
        // Display error message
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
} else {
    // Log an error if no code was received
    error_log("No code received.");
    echo "No authentication code received.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login</title>
</head>
<body>
    <h1>Google Login</h1>
    <p>Please wait while we process your login...</p>
</body>
</html>