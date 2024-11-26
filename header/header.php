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
    <!-- <link rel="stylesheet" href="../css/header.css"> -->
     <style>
    header {
  background-color: #4F5576; /* Changed to #181b26 */
  color: white; /* Changed text color to #D5DFF2 for contrast */
  padding: 5px;
  text-align: center;
}

nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 5px;
}

.nav-button {
  color: #333;
  padding: 15px 15px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  text-decoration: none;
  transition: background-color 0.3s ease;
}

.nav-button:hover {
  background-color: #D5DFF2; /* Changed to #D5DFF2 */
}

.login-button {
  background-color: #181b26; /* Changed to #181b26 */
  color: #D5DFF2; /* Changed text color to #D5DFF2 for contrast */
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  text-decoration: none;
  transition: background-color 0.3s ease;
  margin-left: 40rem;
}

.login-button:hover {
  background-color: #9FA7BF; /* Changed to #9FA7BF */
}

.logout-button {
  background-color: #f14c39; /* This can remain as is or be changed */
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  text-decoration: none;
  transition: background-color 0.3s ease;
  margin-left: 40rem;
}

.logout-button:hover {
  background-color: #f14c39; /* This can remain as is or be changed */
}

main {
  padding: 2rem;
}
     </style>
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
            <a href="../logout/logout.php" class="logout-button">Log Out</a> 
        <?php endif; ?>
    </nav>
</body>
</html>