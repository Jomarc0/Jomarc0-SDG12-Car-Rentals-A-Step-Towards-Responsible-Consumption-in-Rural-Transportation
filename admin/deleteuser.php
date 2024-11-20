<?php
session_start();

require_once 'dbadmin.php'; 

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); 
    exit();
}
$rentedCarManager = new AdminRentedCar();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    if ($rentedCarManager->deleteUser($userId) ) {
        header("Location: adminHome.php"); 
        exit();
    } else {
        echo "Error deleting record.";
    }
} else {
    // Redirect if no ID is provided
    header("Location: admin_dashboard.php");
    exit();
}