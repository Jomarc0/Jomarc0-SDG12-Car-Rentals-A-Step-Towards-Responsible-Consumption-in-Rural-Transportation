<?php
// Suppress warnings and notices
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Car Rental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
        <header>
          <h1>QUICKWHEELS</h1>
        </header>
    <nav class="nav">
        
        <div class="left-nav">
            <a href="../main/index.php">Home</a>
            <a href="../main/reservation.php">Reservations</a>
            <a href="../main/guide.php">Guides</a>
            <a href="../main/location.php">Service Area</a> 
        </div>
        <div class="right-nav">
            <?php if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true): ?>
                <a href="../login/signIn.php" class="login-button">Sign In</a>
            <?php else: ?>
                <a href="../profile/profile.php" class="login-button"><i class="fas fa-user profile-icon"></i></a>
                <a href="../logout/logout.php" class="logout-button">Log Out</a> 
            <?php endif; ?>
        </div>
    </nav>
</body>
</html>