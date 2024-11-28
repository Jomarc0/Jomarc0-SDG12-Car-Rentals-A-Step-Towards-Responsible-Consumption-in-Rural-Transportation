<?php
require_once __DIR__ . '/dbprofile.php'; 

$userProfile = new UserProfile(); //call the user profile class from dbprofile
$user = $userProfile->getUserData(); //call the function getter

// if (!$user) { //debugging
//     echo "User not found";
//     exit;
// }
// else{
//     echo $user;
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
     <!-- <link rel="stylesheet" href="../css/profile.css"> -->
      <style>
        
        body {
    background-color: #D5DFF2; /* Light background */
    color: #333;
    margin: 0;
    padding: 0;
    font-family: sans-serif;
}

.header {
    padding: 0px;  /* Reduced padding */
    width: 100%;
    top: 0;          /* Stay at the top */
    z-index: 1000;  /* On top of other elements */
}

/* Container styles */
.container {
    width: 60%;
    margin: 150px auto;
    padding: 2px;
    background: #fff; /* Keep the container white for contrast */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

/* Main content styles */
.main-content {
    padding: 70px;
}

/* Profile header styles */
.profile-header {
    display: flex;
    flex-direction: column; /* Stack profile info vertically */
    align-items: center; /* Center profile info */
    margin-bottom: 20px;
    width: 100%; /* Ensure it takes full width */
}

.profile-icon img {
    width: 200px; 
    height: 200px; 
    border-radius: 50%; 
    margin-bottom: 50px;
    border: 4px solid #4F5576;
    border-color: #4F5576; /* Icon color */
}

.profile-info {
    width: 80%;
}

.name-section {
    display: flex;
    justify-content: space-between;
}

/* Display field styles */
.display-field {
    padding: 5px;
    border: 1px solid #4F5576; /* Border color */
    border-radius: 5px;
    margin-bottom: 5px;
    color: #181b26; /* Text color for display fields */
}

.first-name {
    width: 50%;
    text-align: center;
}

.last-name {
    width: 50%;
    text-align: center;
}

.full-width {
    width: 99%;
    text-align: center;
}

.age-gender-section {
    display: flex;
    justify-content: space-between;
    text-align: center;
}

.age-gender-section .display-field {
    width: 50%;
}
      </style>
</head>
<body>
    <div class="header">
        <?php include('../header/header.php'); ?>  <!--including my header -->
    </div>

    <?php include('sidebar.php');?>  <!--including my sidebar -->
    
    <div class="container">
        <div class="main-content" id="main-content">
        <div class="profile-header">
        <div class="profile-icon">
            
                <?php
                // Check if the profile picture exists and is a valid URL
                $profilePicture = htmlspecialchars($user['profile_picture'] ?? '');
                if ($profilePicture) {
                    echo "<img src='$profilePicture' alt='Profile Picture' style=''>";
                } else {
                    echo "No profile picture available";
                }
                ?>
            
        </div>
            <div class="profile-info">
                <div class="name-section">
                    <div class="display-field last-name"><h4>Last Name</h4><?php echo htmlspecialchars($user['last_name'] ?? ''); ?></div>
                    <div class="display-field first-name"><h4>First Name</h4><?php echo htmlspecialchars($user['first_name'] ?? ''); ?></div>
                </div>
                <div class="age-gender-section">
                    <?php
                    $dob = new DateTime($user['dob'] ?? '0000-00-00'); // Provide a default date
                    $age = $dob->diff(new DateTime())->y; // Calculate age
                    ?>
                    <div class="display-field"><h4>Age</h4><?php echo $age; ?></div>
                    <div class="display-field"><h4>Gender</h4><?php echo htmlspecialchars($user['gender'] ?? ''); ?></div>
                </div>
                <div class="display-field full-width"><h4>Date of Birth</h4><?php echo htmlspecialchars($user['dob'] ?? ''); ?></div>
                <div class="display-field full-width"><h4>Address</h4><?php echo htmlspecialchars($user['address'] ?? ''); ?></div>
                <div class="display-field full-width"><h4>Phone Number</h4><?php echo htmlspecialchars($user['phone_number'] ?? ''); ?></div>
                <div class="display-field full-width"><h4>Email Address</h4><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
            </div>
        </div>
        </div>
    </div>
    
</body>
</html>