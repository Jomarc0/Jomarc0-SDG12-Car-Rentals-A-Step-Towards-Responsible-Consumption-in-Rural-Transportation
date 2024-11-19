<?php
// Include the Database class and RentalCancellation class
require_once __DIR__ . '/../dbcon/dbcon.php'; // Adjust the path as necessary
require_once 'cancel_rent.php'; // Adjust the path as necessary

// Instantiate the RentalCancellation class
$rentalCancellation = new CancelRent();

// Check if the form was submitted
if (isset($_POST['cancel-btn'])) {
    // Get the rent_id from the POST request
    $rent_id = $_POST['rent_id'];
    
    // Call the cancelRental method
    $message = $rentalCancellation->cancelRental($rent_id);
    echo $message; // Display the message
}
?>