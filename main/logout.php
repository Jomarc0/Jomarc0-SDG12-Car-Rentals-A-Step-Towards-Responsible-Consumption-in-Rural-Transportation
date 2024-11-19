<?php
session_start(); // Start the session
$_SESSION['message'] = "You have been logged out successfully.";
$_SESSION = [];

session_destroy();

header('Location: index.php');
exit();
?>