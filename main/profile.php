<?php
session_start();
// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'login_system';

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
// Test connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'] ?? null;

// Fetch user data
$query = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($query);


if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found";
    exit;
}
?>

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Profile Layout</title> <link rel="stylesheet" href="profile.css"> 
</head> 
<body> 
    <header><?php include('header.php');?></header>
    <div class="container"> 
        <?php include('sidebar.php'); ?> 
        <div class="main-content" id="main-content">

        <div class="profile-header">
            <div class="profile-info">
                <div class="name-section">
                    <div class="display-field last-name"><?php echo htmlspecialchars($user['last_name']); ?></div>
                    <div class="display-field first-name"><?php echo htmlspecialchars($user['first_name']); ?></div>
                </div>
                <div class="age-gender-section">
                    <?php
                    $dob = new DateTime($user['dob']);
                    $age = $dob->diff(new DateTime())->y;//To echo the age 
                    ?>
                    <div class="display-field"><?php echo $age; ?></div>
                    <div class="display-field"><?php echo htmlspecialchars($user['gender']); ?></div>
                </div>
                <div class="display-field full-width"><?php echo htmlspecialchars($user['dob']); ?></div>
                <div class="display-field full-width"><?php echo htmlspecialchars($user['address']); ?></div>
                <div class="display-field full-width"><?php echo htmlspecialchars($user['phone_number']); ?></div>
                <div class="display-field full-width"><?php echo htmlspecialchars($user['email']); ?></div>
            </div>
        </div>
        </div> 
    </div>

</body> 
</html>