<?php
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
        <h1>QuickWheels</h1>
    </header>
    <nav>
        <a href="../main/index.php" class="nav-button">Home</a>
        <a href="../profile/profile.php" class="nav-button">Profile</a>
        <a href="../main/reservation.php" class="nav-button">Reservations</a>
        <a href="../main/guide.php" class=" nav-button">Guides</a>
        <a href="../main/location.php" class="nav-button">Service Area</a> 
        <a href="../main/vehicles.php" class="nav-button">Vehicles</a>
        <a href="../main/about.php" class="nav-button">About</a>
        
        <?php if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true): ?>
            <a href="../login/signIn.php" class="login-button">Sign In</a>
        <?php else: ?>
            <a href="../main/logout.php" class="logout-button">Log Out</a> 
        <?php endif; ?>
    </nav>
</body>
</html>