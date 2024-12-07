<?php
require_once 'UserProfile.php'; 

$userAccount = new UserAccount(); 


$user = $userAccount->getUserData(); // get all user data base on id
$isVerified = $userAccount->isVerified(); // if the user is verified

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) { //form in uloatinf profile pic
    $uploadMessage = $userAccount->uploadProfilePicture($_FILES['profile_picture']);
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
    <link rel="stylesheet" href="../css/profiles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="header">
        <?php include('../header/header.php'); ?>  <!-- Including header -->
    </div>
    <?php include('../sidebar/sidebar.php');?>  <!-- Including sidebar -->

    <div class="container">
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
                        <div class="upload-icon">  <!-- cam icon overlay -->
                            <i class="fas fa-camera"></i>
                        </div>
                    </label>
                    <!-- hidden file input for uploading profile picture -->
                    <input type="file" id="profile-picture-upload" name="profile_picture" style="display: none;" onchange="this.form.submit();" />
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>" />
                </form>
            </div>
            <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>

            
            <?php //verification 
            if ($isVerified) {
                echo "<a href='idverification.php' class='verification-button verified'><i class='fas fa-check-circle'></i> Verified</a>";
            } else {
                echo "<a href='idverification.php' class='verification-button not-verified'><i class='fas fa-times-circle'></i> Not Verified</a>";
            }
            ?>
        </div>

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
