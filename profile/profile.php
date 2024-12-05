<?php
require_once __DIR__ . '/dbprofile.php'; // Include the UserProfile class

$userProfile = new UserProfile(); // Call the UserProfile class
$user = $userProfile->getUserData(); // Fetch user data
$isVerified = $userProfile->isVerified(); // Check if the user is verified

// Handle file upload if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $uploadMessage = $userProfile->uploadProfilePicture($_FILES['profile_picture']);
        header("Location: profile.php?success=" . urlencode($uploadMessage));
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
/* General body styles */
body {
    background: linear-gradient(135deg, #1a1a1a, #2a2a2a); /* Dark gradient background */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    color: #e0e0e0; /* Light text color */
}

/* Profile container */
.container {
    max-width: 800px;
    margin: 50px auto;
    background: #2c2c2c; /* Dark background for container */
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    padding: 20px;
}

/* Profile header */
.profile-header {
    text-align: center;
    padding: 20px;
    background: #4f79bc; /* Keep this color for header */
    color: white;
    border-radius: 12px 12px 0 0;
}

.profile-header img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid white;
    margin-bottom: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.profile-header h2 {
    margin: 10px 0;
    font-size: 22px;
    letter-spacing: 0.5px;
}

/* Verification link styles */
.verification-button {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none; /* Remove underline */
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    margin-top: 15px;
    text-transform: uppercase;
}

.verification-button.verified {
    background-color: #28a745; /* Green for verified */
    color: white;
}

.verification-button.not-verified {
    background-color: #dc3545; /* Red for not verified */
    color: white;
}

.verification-button:hover {
    opacity: 0.9;
    transform: scale(1.05); /* Add a slight zoom effect */
}

/* Profile icon container */
.profile-icon {
    position: relative;
    display: inline-block; 
    cursor: pointer;
    margin-bottom: 15px;
}

/* Profile picture or placeholder */
.profile-icon img  {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background-color: #ddd;
    display: block;
    object-fit: cover;
    border: 4px solid #007bff;
    margin: 0 auto;
}

/* Upload icon without color (no background and color) */
.upload-icon i {
    position: absolute;
    bottom: 0;
    right: 5px;
    color: #ddd;
    font-size: 18px;  /* Icon size */
    cursor: pointer;
    transition: transform 0.3s ease-in-out;
    color: inherit; /* Inherit the color from the image */
}

/* Hover effect for upload icon */
.upload-icon:hover {
    transform: scale(1.2);
}

/* Hidden file input */
#profile-picture-upload {
    display: none;
}

.profile-info {
    padding: 20px;
}

.profile-info h3 {
    font-size: 20px;
    color: #4f79bc; /* Keep this color for section headers */
    margin-bottom: 15px;
    border-bottom: 2px solid #4f79bc; /* Change border color to match header */
    padding-bottom: 5px;
}

.info-item {
    margin-bottom: 15px;
    padding: 10px;
    background: #3c3c3c; /* Darker background for info items */
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.info-item h4 {
    margin: 0;
    font-size: 16px;
    color: #4f79bc; /* Keep this color for item titles */
}

.info-item p {
    margin: 5px 0 0;
    font-size: 14px;
    color: #ddd; /* Light color for text in info items */
}

/* Footer styles */
.footer {
    text-align: center;
    margin: 20px auto;
    font-size: 14px;
    color: #aaa; /* Light color for footer text */
}
    </style>
</head>
<body>
    <div class="header">
        <?php include('../header/header.php'); ?>  <!-- Including header -->
    </div>
    <?php include('sidebar.php');?>  <!-- Including sidebar -->

    <div class="container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-icon">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="profile-picture-upload" class="upload-label">
                        <?php
                        $profilePicture = htmlspecialchars($user['profile_picture'] ?? '');
                        if ($profilePicture) {
                            echo "<img src='$profilePicture' alt='Profile Picture'>";
                        } else {
                            echo "<i class='fas fa-user-circle fa-7x'></i>";
                        }
                        ?>
                        <!-- Upload icon overlay -->
                        <div class="upload-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                    </label>
                    <!-- Hidden file input for uploading profile picture -->
                    <input type="file" id="profile-picture-upload" name="profile_picture" style="display: none;" />
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>" />
                </form>
            </div>
            <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>

            <!-- Verification link -->
            <?php
            if ($isVerified) {
                echo "<a href='idverification.php' class='verification-button verified'><i class='fas fa-check-circle'></i> Verified</a>";
            } else {
                echo "<a href='idverification.php' class='verification-button not-verified'><i class='fas fa-times-circle'></i> Not Verified</a>";
            }
            ?>
        </div>

        <!-- Profile Details -->
        <div class="profile-info">
            <h3>Personal Information</h3>
            <div class="info-item">
                <h4>Date of Birth</h4>
                <p><?php echo htmlspecialchars($user['dob'] ?? 'N/A'); ?></p>
            </div>
            <div class="info-item">
                <h4>Age</h4>
                <?php
                $dob = new DateTime($user['dob'] ?? '0000-00-00');
                $age = $dob->diff(new DateTime())->y;
                ?>
                <p><?php echo $age; ?></p>
            </div>
            <div class="info-item">
                <h4>Gender</h4>
                <p><?php echo htmlspecialchars($user['gender'] ?? 'N/A'); ?></p>
            </div>
            <div class="info-item">
                <h4>Address</h4>
                <p><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></p>
            </div>
            <div class="info-item">
                <h4>Phone Number</h4>
                <p><?php echo htmlspecialchars($user['phone_number'] ?? 'N/A'); ?></p>
            </div>
            <div class="info-item">
                <h4>Email Address</h4>
                <p><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </div>
    <?php endif; ?>
</body>
</html>
