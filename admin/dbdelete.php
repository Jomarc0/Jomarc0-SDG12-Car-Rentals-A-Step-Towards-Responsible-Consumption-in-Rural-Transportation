<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); 
    exit();
}

require_once 'RentedCarManager.php'; 

$rentedCarManager = new AdminRentedCar();

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    if ($rentedCarManager->deleteUserandRentedCar($userId)) {
        header("Location: admin_dashboard.php"); 
        exit();
    } else {
        echo "Error deleting record.";
    }
} else {
    // Redirect if no ID is provided
    header("Location: admin_dashboard.php");
    exit();
}