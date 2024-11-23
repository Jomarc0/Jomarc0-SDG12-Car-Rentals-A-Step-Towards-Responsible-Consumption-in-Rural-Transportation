<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Car Rental</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="header.css">
</head>

<body>
    <header>
        <h1>Jay Car Rental</h1>
    </header>
    <nav>
        <a href="index.php" class="nav-button">Home</a>
        <a href="profile.php" class="nav-button">Profile</a>
        <a href="reservation.php" class="nav-button">Reservations</a>
        <a href="guide.php" class=" nav-button">Guides</a>
        <a href="location.php" class="nav-button">Service Area</a> 
        <a href="vehicles.php" class="nav-button">Vehicles</a>
        <a href="about.php" class="nav-button">About</a>
        
        <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true): ?>
            <a href="../login_signup/signIn.php" class="login-button">Sign In</a>
        <?php else: ?>
            <a href="../login_signup/logout.php" class="logout-button">Log Out</a> 
        <?php endif; ?>
    </nav>
</body>
</html>