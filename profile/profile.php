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
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../header/header.css">
</head>
<body>
    <header class="header">
        <?php include('../header/header.php'); ?>  <!--including my header -->
    </header>

    <?php include('sidebar.php');?>  <!--including my sidebar -->
    
    <div class="container">
        <div class="main-content" id="main-content">
            <div class="profile-header">
                <div class="profile-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="profile-info">
                    <div class="name-section"> <!--display the name of the user using this htmlspecialchars -->
                        <div class="display-field last-name"><h4>Last Name</h4><?php echo htmlspecialchars($user['last_name']); ?></div>
                        <div class="display-field first-name"><h4>First Name</h4><?php echo htmlspecialchars($user['first_name']); ?></div>
                    </div>
                    <div class="age-gender-section">
                        <?php
                        $dob = new DateTime($user['dob']); //y is from the date interval year 
                        $age = $dob->diff(new DateTime())->y; // calculate age by getting the current time minus the birthday
                        ?>       
                        <div class="display-field"><h4>Age</h4><?php echo $age; ?></div>
                        <div class="display-field"><h4>Gender</h4><?php echo htmlspecialchars($user['gender']); ?></div>
                    </div>  <!-- display all the info of the user -->
                    <div class="display-field full-width"><h4>Date of Birth</h4><?php echo htmlspecialchars($user['dob']); ?></div>
                    <div class="display-field full-width"><h4>Address</h4><?php echo htmlspecialchars($user['address']); ?></div>
                    <div class="display-field full-width"><h4>Phone Number</h4><?php echo htmlspecialchars($user['phone_number']); ?></div>
                    <div class="display-field full-width"><h4>Email Address</h4><?php echo htmlspecialchars($user['email']); ?></div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>