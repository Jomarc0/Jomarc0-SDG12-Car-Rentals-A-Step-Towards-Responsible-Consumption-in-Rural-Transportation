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
    <style>  
        

        header {
            background-color: #121212;
            padding: .2rem; /* Reduced padding */
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        header h1{
            margin-left: 50px;
            font-weight: bold;
            font-size: 36px;
        }

        .nav{
            background-color: #121212;
            padding: .3rem; /* Reduced padding */
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }
        .nav {
            display: flex;
            justify-content: space-between; /* Space between left and right sections */
            align-items: center;
            width: 100%; /* Full width */
        }

        .left-nav {
            display: flex;
            gap: 1rem; /* Adjusted gap */
            align-items: center;
            margin-left: 30px;
        }

        .right-nav {
            display: flex;
            align-items: center; /* Center items vertically */
        }
        
        .nav a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease, transform 0.3s ease; /* Transition for link color */
        }
        
        .nav a:hover {
            color: #f7b531; /* Change color on hover */
            transform: scale(1.1); /* Slightly enlarge on hover */
        }

        .login-button, .logout-button {
            display: flex;
            align-items: center; /* Center icon vertically */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .login-button {
            background-color: #181b26; /* Changed to #181b26 */
            color: #D5DFF2; /* Changed text color to #D5DFF2 for contrast */
        }

        .login-button:hover {
            background-color: #9FA7BF; /* Changed to #9FA7BF */
        }

        .logout-button {
            background-color: #f14c39; /* This can remain as is or be changed */
            color: #fff;
            margin-left: 1rem; /* Space between buttons */
        }

        .logout-button:hover {
            background-color: #f14c39; /* This can remain as is or be changed */
        }

    </style>
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